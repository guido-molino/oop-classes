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
                default: throw new Exception('invalid method', 400);  break;
            }

        } catch (CustomException $e) {

            echo $e->sendLogsException($this->conn);
        } catch (Exception $e) {

            //creiamo una funzione per trasformare la response in JSON {$e->code:$e->message}
            echo $e->getCode()." : ".$e->getMessage();
        }
    }    

    protected function read() {

        //se in request è presente l'id torno l'utente, sennò una lista di utenti
        $data = $this->userOrList(); 
        if ($data === false) {
            throw new Exception('Invalid ID', 400);
        }      
        throw new Exception(json_encode($data), 200);       
    }

    protected function create() {
        
        $this->istance->userFormat();
        $this->service->insert($this->istance);
    }

    protected function update() {

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
}
