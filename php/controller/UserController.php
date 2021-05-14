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
        
        $this->istance->fullValidation();
        $this->istance->userFormat();
        $this->service->insert($this->istance);
    }

    protected function update() {

        //controllo se i dati in request sono validi
        $this->istance->partialValidation();
        //controllo se l'utente esiste
        $this->checkIfUserExists();
        //eseguo il format dei dati in request
        $this->istance->userFormat();
        //ritiro l'utente dall'id
        $user = $this->service->select($this->request['id']);
        //inserisco a user i dati istanziati validi
        $newUserData = $this->updateUserData($user, $this->istance);
        //eseguo l'update
        $this->service->update($newUserData);
    }

    protected function delete() {

        //controllo se l'id è valido
        $this->checkIfUserExists();
        //eseguo la delete
        $this->service->delete($this->request['id']);
    }

    private function userOrList() {

        if (array_key_exists('id', $this->request)) {

            return $this->service->select($this->request['id']);

        } else {

            return $this->service->select();
        }
    }
    
    private function updateUserData($user, $requestData) {
        
        foreach ($requestData as $key => $property) {
            
            if (!$property === false && !is_array($property) && !is_object($property)) {
                
                $user->$key = $property;   
            } 
        }        
        return $user;
    }

    private function checkIfUserExists() {

        //se non è presente l' id o non è associato ad un utente torniamo "invalid id"
        if (isset($this->request['id']) === false || !$this->service->select($this->request['id'])) {
            throw new Exception('ID non valido', 400);
        }
    }
}
