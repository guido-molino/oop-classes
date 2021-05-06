<?php
include 'concrete/Posta.php';
include 'concrete/Email.php';
include 'concrete/Sms.php';

$type = $_POST["type"];
$textInput = $_POST["textInput"];
$dataStorage = new DataStorage($type, $textInput);

class DataStorage {

    public function __construct($type,$textInput) {
        
        $this->type = $type;
        $this->istantiateByType($textInput);

    }

    public function response() {
        
        $response = 'Istanziamento classe per: '. $this->type;
        echo($response);

    }

    protected function istantiateByType($textInput) {

        $class = $this->type;
        $istance = new $class($textInput);
        return $istance;

    }

}



