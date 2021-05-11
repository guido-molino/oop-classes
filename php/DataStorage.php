<?php

require 'Template.php';
include 'concrete/Posta.php';
include 'concrete/Email.php';
include 'concrete/Sms.php';

class DataStorage {

    public function __construct($type,$text) {
        
        $this->type = $type;
        $this->text = $text;
    }

    public function istantiateByType() {

        $this->typeValidation();
        $class = $this->type; //sms
        $istance = new $class($this->text); //istanziamento con attribute $text
        return $istance;
    }

    public function store($conn) {

        $store = new SendTypePdo($this->type, $this->text);
        $store->insert($conn);
    }

    private function typeValidation() {

        $dir = '../php/concrete';
        $typeList = scandir($dir);
        $type = ucfirst($this->type).'.php';

        if (!array_search($type, $typeList)) {
            throw new CustomException('Tipologia non valida');
        }
    }

}

