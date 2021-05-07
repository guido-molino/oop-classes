<?php
require 'Base.php';
include 'concrete/Posta.php';
include 'concrete/Email.php';
include 'concrete/Sms.php';
include '../database/SendTypePdo.php';

$type = $_GET["type"];
$text = $_GET["text"];
$dataStorage = new DataStorage($type,$text,$conn);

class DataStorage {

    public function __construct($type,$text,$conn) {
        
        $this->type = $type;
        $this->istantiateByType($text);
        $this->conn = $conn;
    
    }

    protected function istantiateByType($text) {

        $class = $this->type; //sms
        $istance = new $class($text); //istanziamento con attribute $text
        $istance->isValid($text); //validation su $text
        $this->store($text);
        $istance->response(); //response

    }

    protected function store($text) {

        $store = new SendTypePdo($this->type, $text, $this->conn);
        $store->insert();

    }

}

