<?php namespace Email;

use DataStorage\DataStorage;

class Email extends DataStorage
{

    public function __construct(string $textInput) {

        echo('Email');
        if (!$this->isValid($textInput)) {
            throw 'Enter a valid email address';
        }
        $this->email = $textInput;
    }

    private function isValid($textInput) {

        return filter_var($textInput, FILTER_VALIDATE_EMAIL) !== false;
    }

}


?>