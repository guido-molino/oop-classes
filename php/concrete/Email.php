<?php 

class Email implements Base {

    public function __construct($text) {

        if (!$this->isValid($text)) {
            echo('Email non valida');
        }
        $this->email = $text;

    }

    public function response() {

        return 'Istanziamento classe per: Email';
        
    }

    protected function isValid($text) {

        return filter_var($text, FILTER_VALIDATE_EMAIL) !== false;

    }

}
