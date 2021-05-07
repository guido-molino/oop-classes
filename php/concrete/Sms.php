<?php 

class Sms implements Base {

    public function __construct($text) {

        if ($this->isValid($text)){
            echo('Il testo è troppo lungo');
        }
        $this->sms = $text;

    }

    public function response() {

        echo('Istanziamento classe per: SMS');
        
    }

    private function isValid($text) {

        return strlen($text) > 10;
        
    }

}