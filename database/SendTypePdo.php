<?php
require_once 'Pdoconfig.php';

try {
    $conn = new PDO("$db_connection:host=$host;dbname=$dbname", $username, $password);
    echo "Connected to $dbname at $host successfully. <br>";
} catch (PDOException $pe) {
    die("Could not connect to the database $dbname :" . $pe->getMessage());
}


// $conn è l'oggetto istanziato alla classe pdo che effettua la connessione al db

class SendTypePdo {

    public function __construct($type,$text,$conn) {
        
        $this->type = $type;
        $this->text = $text;
        $this->created_at = $this->timestamp();
        $this->conn = $conn;
    }

    public function insert() {

        //come faccio a far arrivare $conn qua dentro?
        echo('insert');
        $data = [
            'type' => $this->type,
            'text' => $this->text,
            'created_at' => $this->created_at
        ];

        $insertStatement = $this->conn->prepare("INSERT INTO types (type, text, created_at) VALUES (:type, :text, :created_at)");

        if ($insertStatement->execute($data)) {
            echo "New record created successfully <br>";
        } else {
            echo "Unable to create record";
        }
    }

    protected function timestamp() {

        date_default_timezone_set('Europe/Rome');
        return date('Y-m-d H:i:s');
    }
    
}


