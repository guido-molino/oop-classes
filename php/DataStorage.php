<?php
require 'Base.php';
include 'concrete/Posta.php';
include 'concrete/Email.php';
include 'concrete/Sms.php';

$type = $_GET["type"];
$text = $_GET["text"];
$dataStorage = new DataStorage($type,$text);

class DataStorage {

    public $type = null;

    public function __construct($type,$text) {
        
        $this->type = $type;
        $this->istantiateByType($text);
    }

    protected function istantiateByType($text) {

        $class = $this->type;
        $istance = new $class($text);
        return $istance;
    }

}

