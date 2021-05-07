<?php

class Posta implements Base {

    public function __construct($text) {

        $this->posta = $text;

    }

    public function response() {

        echo('Istanziamento classe per: Posta <br>');

    }

    public function isValid($text) {

        if (strlen($text) > 20) {
            echo ('Il testo è troppo lungo');
        }

    }

}