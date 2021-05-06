<?php namespace Sms;

use DataStorage\DataStorage;

class Sms extends DataStorage {

    public function __construct(string $textInput) {

        $this->sms = $textInput;

    }

    private function isValid($textInput) {

        
        
    }

}

?>