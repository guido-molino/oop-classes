<?php

include '../database/services/SendUserPdo.php';
include '../php/exceptions/CustomException.php';

class User {

    protected $name,$lastname,$dateOfBirth,$age,$send_type;

    public function __construct($request, $conn) {
        
        $this->request = $request;
        $this->conn = $conn;
    }

    public function getUserData($id) {

        // validazione dell' id 
        //$this->idValidation($id);
        $data = new SendUserPdo($this->conn);
        $data->select($id);

    }

    public function getUserList() {

        $data = new SendUserPdo($this->conn);
        $data->select();
    }

    private function userFormat() {}


}
