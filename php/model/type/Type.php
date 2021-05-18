<?php

include '../database/services/SendUserPdo.php';

class Type {

    public $name,$lastname,$date_of_birth,$age,$type,$type_id;

    public function __construct($request, $conn) {
        
        $this->request = $request;
        $this->conn = $conn;
    }
}
