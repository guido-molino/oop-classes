<?php
class SendUserPdo {

    public function __construct($conn) {
        
        $this->conn = $conn;
    }

    public function insert($user) {

        $data = [
            'name'          => $user->name,
            'lastname'      => $user->lastname,
            'date_of_birth' => $user->date_of_birth,
            'age'           => $user->age,
            'send_type'     => $user->send_type
        ];
        
        $sql = "INSERT INTO users (name, lastname, date_of_birth, age, send_type) 
                       VALUES (:name, :lastname, :date_of_birth, :age, :send_type)";

        $insertStatement = $this->conn->prepare($sql);

        if ($insertStatement->execute($data)) {
            echo (json_encode(array(
                "status" => 200,
                "message" => 'Utente ' . $user->name . ' ' . $user->lastname . ' creato con successo!'
            )));       
        } else {
            throw new Exception('Invalid Payload', 400);
        }
    }

    public function select($id=null) {

        if (is_null($id)) {

            $selectStatement = $this->conn->query("SELECT * FROM users ORDER BY id");
            $list = $selectStatement->fetchAll(PDO::FETCH_OBJ);
            return $list;

        }   else {

            $selectStatement = $this->conn->prepare("SELECT * FROM users WHERE id=?");
            $selectStatement->execute([$id]);
            $user = $selectStatement->fetch(PDO::FETCH_OBJ);
            return $user;
        }
    }

    public function update($newData) {

        $data = [
            'name'          => $newData->name,
            'lastname'      => $newData->lastname,
            'date_of_birth' => $newData->date_of_birth,
            'age'           => $newData->age,
            'send_type'     => $newData->send_type,
            'id'            => $newData->id
        ];

        $sql = "UPDATE users   
                SET name=:name,
                    lastname=:lastname,
                    date_of_birth=:date_of_birth,
                    age=:age,
                    send_type=:send_type               
                WHERE id=:id";

        $updateStatement = $this->conn->prepare($sql);
        
        if ($updateStatement->execute($data)) {
            echo (json_encode(array(
                "status" => 200,
                "message" => 'Utente ' . $newData->name . ' ' . $newData->lastname . ' aggiornato con successo!'
            )));       
        } else {
            throw new Exception('Aggiornamento dei dati fallito', 500);
        }

    }

    public function delete($id) {

        $deleteStatement = $this->conn->prepare("DELETE FROM users WHERE id=? ");

        if ($deleteStatement->execute([$id])) {
            echo (json_encode(array(
                "status" => 200,
                "message" => 'Utente cancellato'
            )));
        } else {
            throw new Exception('ID non valido', 200);
        }
    }
    
}
