<?php

class CustomException extends Exception {

    protected $email, $message;

    public function __construct($message, $email) {

        $this->message = $message;
        $this->email = $email;
    }

    public function sendEmailException(){

        
        return $this->message."<br>Invio l'errore al DB";
    }
}