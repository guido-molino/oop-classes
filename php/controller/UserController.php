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
            case 'POST':  $this->create('not yet implemented'); break;
            case 'PUT':   $this->update();  break;
            case 'DELETE':$this->delete();  break;
            default: echo ('invalid method');  break;
        }
    }

    protected function read() {

        //se nella request è presente l'id
        if ($this->userOrList() === true) {
            //ricavo i dati associati all'utente dell'id in request
            $this->istance->getUserData($this->request['id']);
            
        } else {
            //ricavo la lista degli utenti
            $this->istance->getUserList();
        }
    }

    protected function create() {

    }

    protected function update() {

    }

    protected function delete() {

    }

    private function userOrList() {

        $request = $this->request;

        if (array_key_exists('id', $request )) {

            return true;

        }   else {

            return false;
        }
    }

}
