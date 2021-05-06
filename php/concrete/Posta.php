<?php namespace Posta;

use DataStorage\DataStorage;

class Posta extends DataStorage {

    public function __construct(string $textInput) {

        $this->posta = $textInput;

    }

    private function isValid($textInput) {



    }

}

?>