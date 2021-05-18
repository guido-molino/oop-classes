<?php
class SendUserPdo {

    public function __construct($conn) {
        
        $this->conn = $conn;
    }

    public function insert($user) {

        $dataUser = [
            'name'          => $user->name,
            'lastname'      => $user->lastname,
            'date_of_birth' => $user->date_of_birth,
            'age'           => $user->age,
        ];
        //come faccio l'insert di type?
        $sqlUser = "INSERT INTO users (name, lastname, date_of_birth, age) 
                           VALUES (:name, :lastname, :date_of_birth, :age)";
        $insertStatement = $this->conn->prepare($sqlUser);
        //se non riesce a inserire l'utente lanciamo invalid payload
        if (!$insertStatement->execute($dataUser)) {
            throw new Exception('Invalid Payload', 500);
        }
        //prendiamo l'id dell'ultimo utente appena inserito tramite metodo PDO::lastInsertId
        $lastUserId = $this->conn->lastInsertId();
        //inseriamo id dello user e del type nella tabella pivot types_users
        $this->typeInsert($user->type_id, $lastUserId);
        //response
        echo (json_encode(array(
            "status" => 200,
            "message" => 'Utente ' . $user->name . ' ' . $user->lastname . ' creato con successo!'
        )));       

    }

    public function select($id=null) {

        if (is_null($id)) {

            $selectStatement = $this->conn->query("SELECT * FROM users ORDER BY id");
            $list = $selectStatement->fetchAll(PDO::FETCH_OBJ);
            return $list;

        }   else {

            $selectStatement = $this->conn->prepare("SELECT * FROM users WHERE id=?");
            
            if ($selectStatement->execute([$id])) {
                $user = $selectStatement->fetch(PDO::FETCH_OBJ);
                return $user;
            } else {
                throw new Exception('invalid ID', 500);
            }
        }
    }

    public function update($updatedUser) {

        $dataUser = [
            'name'          => $updatedUser->name,
            'lastname'      => $updatedUser->lastname,
            'date_of_birth' => $updatedUser->date_of_birth,
            'age'           => $updatedUser->age,
            'id'            => $updatedUser->id
        ];
        $sqlUser = "UPDATE users   
                    SET name=:name,
                        lastname=:lastname,
                        date_of_birth=:date_of_birth,
                        age=:age            
                    WHERE id=:id";
        $updateStatement = $this->conn->prepare($sqlUser);
        if (!$updateStatement->execute($dataUser)) {
            var_dump($updateStatement->execute($dataUser));
            throw new Exception('Update Failed', 500);
        }
        $this->typeInsert($updatedUser->type_id, $updatedUser->id);
        echo (json_encode(array(
            "status" => 200,
            "message" => 'Utente ' . $updatedUser->name . ' ' . $updatedUser->lastname . ' aggiornato con successo!'
        )));  
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
