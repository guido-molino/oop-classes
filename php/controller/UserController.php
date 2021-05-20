<?php

include '../database/DbConn.php';
include '../php/model/user/User.php';
include '../php/exceptions/CustomException.php';

class UserController {

    protected $conn,$istance;

    public function __construct($request) {

        $this->request = $request;
    }

    public function process() {

        try {

            $dbconn = new DbConn();
            $this->conn = $dbconn->connect();
            $this->service = new SendUserPdo($this->conn);
            //istanzio la request
            $this->istance = new User($this->request, $this->conn);
            //in base al metodo della request  reindirizzo l'applicazione al comportamento opportuno
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET':   $this->read();    break;
                case 'POST':  $this->create();  break;
                case 'PUT':   $this->update();  break;
                case 'PATCH': $this->update();  break;
                case 'DELETE':$this->delete();  break;
                default: throw new Exception('Metodo non valido', 400);  break;
            }

        } catch (Exception $e) {

            echo json_encode(array(
                'status' => $e->getCode(),
                'message' => $e->getMessage()
            ));
        }
    }    

    protected function read() {
        //in base alla request torniamo una lista di utenti o un utente singolo
        $data = $this->userOrList(); 
        if ($data === false) {
            throw new Exception('ID non valido', 400);
        }      
        echo(json_encode(array(
            "status" => 200,
            "data" => $data
        )));
    }

    protected function create() {
        //validazione su tutti i parametri della request necessari per la creazione dell'utente
        $this->istance->fullValidation();
        //formattiamo i dati 
        $this->istance->userFormat();
        //ricaviamo gli id associati al type
        $this->istance->type_id = $this->setTypeId($this->istance->type);
        //inseriamo i dati nel db
        $this->service->insert($this->istance);
    }

    protected function update() {
        //controllo se i dati in request sono validi
        $this->istance->partialValidation();
        //controllo se l'utente esiste
        $this->checkIfUserExists();
        //eseguo il format dei dati in request
        $this->istance->userFormat();
        //ritiro l'utente in base all'id
        $user = $this->service->select($this->request['id']);
        //ricaviamo l'id associato al type
        $user->type = explode(',',$user->type);
        //all'oggetto user inseriamo le proprietà ricevute in request
        $updatedUser = $this->updateUserData($user, $this->istance);
        //ricaviamo gli id associati al type
        $updatedUser->type_id = $this->setTypeId($updatedUser->type);
        //se la request è in PATCH
        if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
            //non cancello le relazioni in users_types, se l'utente immette una relazione già esistente riceve errore
            foreach ($updatedUser->type_id as $key => $type_id) {
                //cerchiamo la relazione
                $relation = $this->service->userTypeSelect($updatedUser->id, $type_id);
                //se è già presente torniamo errore
                if (!$relation === false) {
                    throw new Exception('Relazione già esistente', 400);
                }
            }
            //se la realzione non esiste aggiorniamo l'utente con la relazione nuova
            $this->service->update($updatedUser);
        } else {
            //cancello le relazioni esistenti in users_types associate allo userId
            $this->service->userTypeDelete($updatedUser->id);
            //aggiorno i dati del server
            $this->service->update($updatedUser);
        }
    }

    protected function delete() {
        //controllo se l'id è valido
        $this->checkIfUserExists();
        //eseguo la delete
        $this->service->delete($this->request['id']);
    }

    private function userOrList() {
        //se in request è presente l'id
        if (array_key_exists('id', $this->request)) {
            //torniamo un utente
            return $this->service->select($this->request['id']);

        } else {
            //torniamo una lista di utenti
            return $this->service->select();
        }
    }
    
    private function updateUserData($user, $requestData) {
        //per ciascuna proprietà presente in request
        foreach ($requestData as $key => $property) {
            //controllo che la proprietà non sia stata impostata a false durante la formattazione ed escludo la property->request
            if (!$property === false || !$key == "request") {
                $user->$key = null;
                $user->$key = $property;
            }
        }
        return $user;
    }

    private function checkIfUserExists() {
        //se l'id non è presente in request torniamo "richiesta non valida"
        if (isset($this->request['id']) === false) {
            throw new Exception('Richiesta non valida', 400);
        }
        //se l' id non è associato ad un utente torniamo "invalid id"
        if (!$this->service->select($this->request['id'])) {
            throw new Exception('ID non valido', 400);
        }
    }

    private function setTypeId($types) {
        //variabile che conterrà gli id
        $type_id = array();
        //per ciascun type
        foreach ($types as $type) {
            //preleviamo l'id della tipologia associata a quella ricevuta in request
            $typeId = $this->service->typeSelect($type);
            //lo impostiamo come type_id per lo user
            array_push($type_id, $typeId->id);
        }
        return $type_id;
    }
}
