<?php 

class Posta extends DataStorage {

    public function __construct($text) {

        if ($this->isValid($text)) {
            echo ('Il testo è troppo lungo');
        }
        $this->posta = $text;

    }

    private function isValid($text) {

        return strlen($text) > 20;

    }

}