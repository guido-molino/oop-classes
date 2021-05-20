<?php

class SendUserdataPdo {

    public function __construct($conn) {
        
        $this->conn = $conn;
    }

    public function select($id = null) {

        if (is_null($id)) {

            $selectStatement = $this->conn->query("SELECT users.id, users.name, users.lastname, users.date_of_birth, users.age, group_concat(types.type) as type FROM users 
                                                   JOIN users_types ON users.id = users_types.users_id
                                                   JOIN types ON users_types.types_id = types.id
                                                   GROUP BY users.id");
            $list = $selectStatement->fetchAll(PDO::FETCH_OBJ);
            return $list;
        } else {

            $selectStatement = $this->conn->prepare("SELECT users.id, users.name, users.lastname, users.date_of_birth, users.age, group_concat(types.type) as type FROM users 
                                                     JOIN users_types ON users.id = users_types.users_id
                                                     JOIN types ON users_types.types_id = types.id
                                                     WHERE users.id = ?");
            $selectStatement->execute([$id]);
            $user = $selectStatement->fetch(PDO::FETCH_OBJ);
            
            if (is_null($user->id)) {
                throw new Exception('invalid ID', 500);
            }
            return $user;
        }
    }
}
