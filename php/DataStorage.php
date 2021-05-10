<?php
require 'Template.php';
include 'concrete/Posta.php';
include 'concrete/Email.php';
include 'concrete/Sms.php';
include '../database/SendTypePdo.php';
include 'controller/Base.php';

$type = $_GET["type"];
$text = $_GET["text"];

if(!empty ($_GET["type"] && $_GET["text"])){

    $base = new Base();
    $base->process($type,$text,$conn);
}

class DataStorage {

    public function __construct($type,$text) {
        
        $this->type = $type;
        $this->text = $text;
    }

    public function istantiateByType() {

        $class = $this->type; //sms
        $istance = new $class($this->text); //istanziamento con attribute $text
        
        return $istance;
    }

    public function store($conn) {

        $store = new SendTypePdo($this->type, $this->text);
        $store->insert($conn);
    }

}

