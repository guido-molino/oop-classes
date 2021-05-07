<?php
require 'Base.php';
include 'concrete/Posta.php';
include 'concrete/Email.php';
include 'concrete/Sms.php';

$type = $_GET["type"];
$text = $_GET["text"];
$dataStorage = new DataStorage($type,$text);

class DataStorage {

    public function __construct($type,$text) {
        
        $this->type = $type;
        $this->istantiateByType($text);
    
    }

    protected function istantiateByType($text) {

        $class = $this->type; //sms
        $istance = new $class($text); 
        $istance->response();
        return $istance;
    }

}

