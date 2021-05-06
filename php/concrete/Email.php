<?php 

class Email extends DataStorage {

    public function __construct($text) {

        if (!$this->isValid($text)) {
            echo('Email non valida');
        }

        $this->email = $text;
    }

    protected function isValid($text) {

        return filter_var($text, FILTER_VALIDATE_EMAIL) !== false;
    }

}
