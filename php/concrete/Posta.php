<?php

class Posta implements Base {

    public function __construct($text) {

        if ($this->isValid($text)) {
            echo ('Il testo è troppo lungo');
        }
        $this->posta = $text;

    }

    public function response() {

        echo('Istanziamento classe per: Posta');

    }

    private function isValid($text) {

        return strlen($text) > 20;

    }

}