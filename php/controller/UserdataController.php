<?php
include '../database/DbConn.php';
include '../database/services/SendUserdataPdo.php';

class UserdataController {

    protected $conn,$istance;

    public function __construct($request) {

        $this->request = $request;
    }

    public function process() {

        try {

            $dbconn = new DbConn();
            $this->conn = $dbconn->connect();
            $this->service = new SendUserdataPdo($this->conn);
            //in base al metodo della request  reindirizzo l'applicazione al comportamento opportuno
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET':   $this->read();    break;
                default: throw new Exception('Metodo non valido', 400);  break;
            }

        } catch (Exception $e) {

            echo json_encode(array(
                'status' => $e->getCode(),
                'message' => $e->getMessage()
            ));
        }
    }    

    public function read() {
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

}
