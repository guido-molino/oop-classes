<?php 

class Posta extends DataStorage {

    public function __construct($textInput) {

        if ($this->isValid($textInput)) {
            echo ('Il testo è troppo lungo');
        }

        $this->posta = $textInput;

    }

    protected function isValid($textInput) {

        return strlen($textInput) > 500;

    }

}
