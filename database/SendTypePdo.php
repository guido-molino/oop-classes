<?php
require_once 'Pdoconfig.php';

try {
    $conn = new PDO("$db_connection:host=$host;dbname=$dbname", $username, $password);
    echo "Connessione a $dbname tramite $host eseguita. <br>";
} catch (PDOException $pe) {
    die("Impossibile connettersi a $dbname :" . $pe->getMessage());
}


// $conn è l'oggetto istanziato alla classe pdo che effettua la connessione al db

class SendTypePdo {

    public function __construct($type,$text) {
        
        $this->type = $type;
        $this->text = $text;
        $this->created_at = $this->timestamp();
 
    }

    public function insert($conn) {

        $data = [
            'type' => $this->type,
            'text' => $this->text,
            'created_at' => $this->created_at
        ];

        $insertStatement = $conn->prepare("INSERT INTO types (type, text, created_at) VALUES (:type, :text, :created_at)");

        if ($insertStatement->execute($data)) {
            echo "Nuovi dati inseriti con successo! <br>";
        } else {
            echo "Registrazione dei dati fallita <br>";
        }
    }

    protected function timestamp() {

        date_default_timezone_set('Europe/Rome');
        return date('Y-m-d H:i:s');
    }
    
}


