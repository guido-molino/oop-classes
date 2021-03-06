<?php

class SendUserPdo {

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
        //per ciascun type_id creiamo un field nella tabella pivot user_types
        foreach ($user->type_id as $key => $type_id) {
            $this->userTypeInsert($type_id, $lastUserId);
        }
        //response
        echo (json_encode(array(
            "status" => 200,
            "message" => 'Utente ' . $user->name . ' ' . $user->lastname . ' creato con successo!'
        )));       

    }

    public function update($updatedUser) {
        //aggiorniamo tutti i campi dell'utente selezionato per id
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
            throw new Exception('Update Failed', 500);
        }
        //per ciascun type_id eseguiamo l'insert della relazione
        foreach ($updatedUser->type_id as $key => $type_id) {
            $this->userTypeInsert($type_id, $updatedUser->id);
        }
        echo (json_encode(array(
            "status" => 200,
            "message" => 'Utente ' . $updatedUser->name . ' ' . $updatedUser->lastname . ' aggiornato con successo!'
        )));  
    }

    public function delete($id) {

        //cancelliamo l'utente selezionato per id
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
    
    /* PIVOT USERS_TYPES */ 

    private function userTypeInsert(int $typeId, int $userId) {
        //inseriamo l'id utente e l'id type nella table users_types
        $dataType = [
            'users_id'  => $userId,
            'types_id'  => $typeId
        ];
        $sqlType = "INSERT INTO users_types (users_id, types_id) 
                           VALUES (:users_id, :types_id)";
        $insertTypeStatement = $this->conn->prepare($sqlType);
        //se non riesce a scrivere il dato nel db lancia Exception 500
        if (!$insertTypeStatement->execute($dataType)) {
            throw new Exception('users_types relation error', 500);
        }
    }
    
    public function userTypeDelete(int $userId) {
        //cancelliamo tutte le relazioni gi?? associate all'utente
        $sqlType = "DELETE FROM users_types WHERE users_id = ?";
        $insertTypeStatement = $this->conn->prepare($sqlType);
        $insertTypeStatement->execute([$userId]);
    }

    public function userTypeSelect(int $userId, int $typeId) {
        $dataType = [
            'users_id'  => $userId,
            'types_id'  => $typeId
        ];
        //selezioniamo la relazione che contiene i campi users_id e types_id
        $sqlType = "SELECT * FROM users_types WHERE users_id = :users_id AND types_id = :types_id";
        $selectStatement = $this->conn->prepare($sqlType);
        if (!$selectStatement->execute($dataType)) {
            throw new Exception('users_types relation failed to select', 500);
        }
        $relation = $selectStatement->fetch(PDO::FETCH_OBJ);
        return $relation;
    }

    public function typeSelect($type) {

        //selezioniamo l'id associato ad un type
        $selectStatement = $this->conn->prepare("SELECT id FROM types WHERE type = ?");
        if (!$selectStatement->execute([$type])) {
            throw new Exception("unable to get Type ID", 500);
        }
        $typeId = $selectStatement->fetch(PDO::FETCH_OBJ);
        return $typeId;
        
    }

}
