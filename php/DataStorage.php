<?php
require 'Base.php';
include 'concrete/Posta.php';
include 'concrete/Email.php';
include 'concrete/Sms.php';
include '../database/SendTypePdo.php';

$type = $_GET["type"];
$text = $_GET["text"];
$dataStorage = new DataStorage($type,$text);
$dataStorage->store($conn);

class DataStorage {

    protected $isValid = 1;

    public function __construct($type,$text) {
        
        $this->type = $type;
        $this->text = $text;
        $this->istantiateByType($text);
    }

    protected function istantiateByType() {

        $class = $this->type; //sms
        $istance = new $class($this->text); //istanziamento con attribute $text
        $this->isValid = $istance->isValid($this->text); //validazione
        if ($this->isValid == 1){
            $istance->response(); //response
        }
        
    }

    public function store($conn) {

        if ($this->isValid == 1){
            $store = new SendTypePdo($this->type, $this->text);
            $store->insert($conn);
        }

    }

}

