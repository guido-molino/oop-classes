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

    public function fullValidation() {

        $this->validateName();
        $this->validateLastName();
        $this->validateDoB();
        $this->validateSendType();
    }

    public function partialValidation() {

        foreach ($this->request as $key => $property) {

            switch ($key) {
                case 'name':          $this->validateName();      break;
                case 'lastname':      $this->validateLastName();  break;
                case 'date_of_birth': $this->validateDoB();       break;
                case 'sent_type':     $this->validateSendType();  break;
                //default: throw new Exception($key .' non valido', 400);  break;
            }
        }

    }

    private function validateName() {

        if (!isset($this->request['name']) || !preg_match('/^\w{3,}$/', $this->request['name'])) {
            throw new Exception('nome non valido', 400);
        }
    }

    private function validateLastName() {

        if (!isset($this->request['lastname']) || !preg_match('/^\w{3,}$/', $this->request['lastname'])) {
            throw new Exception('cognome non valido', 400);
        }
    }

    private function validateDoB() {

        if (!isset($this->request['date_of_birth'])){

            throw new Exception('data di nascita non valida', 400);
        }

        $date_of_birth = new DateTime($this->request['date_of_birth']);
        $now = new DateTime();
        $now->diff($date_of_birth);

        if ( $date_of_birth > $now || $now->diff($date_of_birth)->y <= 5 ) {
            throw new Exception('data di nascita non valida', 400);
        }
    }

    private function validateSendType() {

        if (!isset($this->request['send_type'])) {
            throw new Exception('tipologia non valida', 400);
        }
        $dir = '../php/model/type/concrete';
        $typeList = scandir($dir);
        $type = ucfirst($this->request['send_type']) . '.php';

        if (!array_search($type, $typeList)) {
            throw new Exception('tipologia non valida', 400);
        }
    }

}
