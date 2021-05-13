<?php

include '../database/DbConn.php';
include '../php/model/user/User.php';

class UserController {

    protected $conn,$istance;

    public function __construct($request) {

        $this->request = $request;
    }

    public function process() {

        //mi connetto al db
        $dbconn = new DbConn();
        $this->conn = $dbconn->connect();
        //istanzio la request
        $this->istance = new User($this->request, $this->conn);
        //in base al metodo della request  reindirizzo l'applicazione al comportamento opportuno
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':   $this->read();    break;
            case 'POST':  $this->create();  break;
            case 'PUT':   $this->update();  break;
            case 'DELETE':$this->delete();  break;
            default: echo ('invalid method');  break;
        }
    }

    protected function read() {

        //se in request è presente l'id torno l'utente, sennò una lista di utenti
        $data = $this->userOrList();       
        print_r($data);
            
    }

    protected function create() {

        $this->istance->userFormat();
        $data = new SendUserPdo($this->conn);
        $data->insert($this->istance);
    }

    protected function update() {

        $id = $this->request['id'];
        //eseguo il format dei dati in request
        $this->istance->userFormat();
        //ritiro l'utente dall'id
        $user = $this->getUserData($id);
        //inserisco a user i dati istanziati validi
        $newUserData = $this->updateUserData($user, $this->istance);
        //eseguo l'update
        $data = new SendUserPdo($this->conn);
        $data->update($newUserData);
    }

    protected function delete() {


    }

    private function userOrList() {

        if (array_key_exists('id', $this->request)) {

            return $this->getUserData($this->request['id']);

        } else {

            return $this->getUserList();
        }
    }

    public function getUserData($id) {

        // validazione dell' id 
        //$this->idValidation($id);
        $user = new SendUserPdo($this->conn);
        return ($user->select($id));
    }

    public function getUserList() {

        $list = new SendUserPdo($this->conn);
        return ($list->select());
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
