<?php 

class Email implements Template {

    public function __construct($text) {

        $this->email = $text;
    }

    public function response() {

        echo('Istanziamento classe per: Email <br>');    
    }

    public function isValid($text) {

        if (!filter_var($text, FILTER_VALIDATE_EMAIL) !== false) {
            throw new CustomException('Email non valida', 'mia@stefano.com');
        } 
    }

}
