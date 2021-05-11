<?php

class Sms implements Template {

    public function __construct($text) {

        $this->sms = $text;
    }

    public function response() {

        echo('Istanziamento classe per: SMS <br>');    
    }

    public function isValid($text) {

        if (strlen($text) > 20) {
            throw new CustomException('Testo troppo lungo');
        } 
    }

}