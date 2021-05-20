<?php

class Posta implements Template {

    public function __construct($text) {

        $this->posta = $text;

    }

    public function response() {

        echo('Istanziamento classe per: Posta <br>');

    }

    public function isValid($text) {

        if (strlen($text) > 20) {
            throw new CustomException('Testo troppo lungo');
        }

    }

}