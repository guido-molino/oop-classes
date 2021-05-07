<?php 

class Email implements Base {

    public function __construct($text) {

        $this->email = $text;

    }

    public function response() {

        echo('Istanziamento classe per: Email <br>');
        
    }

    public function isValid($text) {

        if (!filter_var($text, FILTER_VALIDATE_EMAIL) !== false) {
            echo ('Email non valida');
        }

    }

}
