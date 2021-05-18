<?php
class SendTypePdo {

    public function __construct($conn) {

        $this->conn = $conn;
    }

    public function select($type = null) {

        if (is_null($type)) {

            $selectStatement = $this->conn->query("SELECT * FROM types ORDER BY id");
            $list = $selectStatement->fetchAll(PDO::FETCH_OBJ);
            return $list;
        } else {

            $selectStatement = $this->conn->prepare("SELECT id FROM types WHERE type=?");

            if ($selectStatement->execute([$type])) {
                $typeId = $selectStatement->fetch(PDO::FETCH_OBJ);
                return $typeId;
            } else {
                throw new Exception('Tipologia non valida');
            }
        }
    }

    public function Insert($typeId, $userId) {
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

    public function update($typeId, $userId) {
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

}
