<?php 

class Sms extends DataStorage {

    public function __construct($text) {

        if ($this->isValid($text)){
            echo('Il testo è troppo lungo');
        }

        $this->sms = $text;
        echo($this->sms);

    }

    private function isValid($text) {

        return strlen($text) > 10;
        
    }

}