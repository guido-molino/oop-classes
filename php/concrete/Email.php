<?php 

class Email extends DataStorage {

    public function __construct($textInput) {

        if (!$this->isValid($textInput)) {
            echo('Email non valida');
        }

        $this->email = $textInput;
    }

    private function isValid($textInput) {

        return filter_var($textInput, FILTER_VALIDATE_EMAIL) !== false;
    }

}


?>