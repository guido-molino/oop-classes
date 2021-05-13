<?php

include '../database/services/SendUserPdo.php';
//include '../php/exceptions/CustomException.php';

class User {

    public $name,$lastname,$date_of_birth,$age,$send_type;

    public function __construct($request, $conn) {
        
        $this->request = $request;
        $this->conn = $conn;
    }

    public function userFormat() {
        //per ciascun parametro immesso dall'utente nella request eseguo il format, per i parametri mancanti torno false

        $this->name          = array_key_exists('name', $this->request)          == true ? ucfirst($this->request['name']) : false;
        $this->lastname      = array_key_exists('lastname', $this->request)      == true ? ucfirst($this->request['lastname']) : false;
        //date format to yyyy-mm-dd
        $this->date_of_birth = array_key_exists('date_of_birth', $this->request) == true ? date("Y-m-d", strtotime($this->request['date_of_birth'])) : false;
        //age = current date - dateofbirth
        $this->age           = array_key_exists('date_of_birth', $this->request) == true ? $this->calcAge() : false;
        $this->send_type     = array_key_exists('send_type', $this->request)     == true ? $this->request['send_type'] : false;
    }

    private function calcAge() {

        $date = new DateTime($this->date_of_birth);
        $now =  new DateTime();
        $interval = $now->diff($date);
        return $interval->y;
    }


}
