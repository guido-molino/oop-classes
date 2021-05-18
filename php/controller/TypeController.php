<?php


include '../php/model/type/Type.php';
include '../database/services/SendTypePdo.php';

class TypeController {

    protected $conn, $istance;

    public function __construct($request) {

        $this->request = $request;
    }

    public function process() {

        try {

            $dbconn = new DbConn();
            $this->conn = $dbconn->connect();
            $this->service = new SendTypePdo($this->conn);
            //istanzio la request
            $this->istance = new Type($this->request, $this->conn);
            //in base al metodo della request  reindirizzo l'applicazione al comportamento opportuno
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET':$this->read(); break;
                case 'POST':$this->create();break;
                case 'PUT':$this->update();break;
                case 'DELETE':$this->delete();break;
                default:throw new Exception('Metodo non valido', 400);break;
            }
        } catch (Exception $e) {

            echo json_encode(array(
                'status' => $e->getCode(),
                'message' => $e->getMessage()
            ));
        }
    }

    protected function read() {

        if ($data === false) {
            throw new Exception('ID non valido', 400);
        }
        echo (json_encode(array(
            "status" => 200,
            "data" => $data
        )));
    }

    protected function create() {

    }

    protected function update() {

    }

    protected function delete() {

    }

    public function setTypeId() {

        //preleviamo l'id della tipologia associata a quella ricevuta in request
        $request = new SendTypePdo($this->conn);
        $typeId = $request->select($this->request['type']);
        //lo impostiamo come type_id per lo user
        $this->istance->type_id = $typeId->id;
    }
}
