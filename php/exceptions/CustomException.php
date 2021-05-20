<?php

include '../database/services/SendLogPdo.php';

class CustomException extends Exception {

    protected $message;

    public function __construct($message) {

        $this->message = $message;
    }

    public function sendLogsException($conn) {
        $this->store($conn);
        return $this->message."<br>Invio l'errore al DB";
    }

    private function store($conn) {

        $store = new SendLogPdo($this->message);
        $store->insert($conn);
    }
}