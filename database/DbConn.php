<?php
class DbConn {
    
    public function __construct() {
        
        require 'PdoConfig.php';

        $this->db_connection = $db_connection;
        $this->host = $host;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
    }
    
    public function connect() {
        
        try {
            $conn = new PDO("$this->db_connection:host=$this->host;dbname=$this->dbname", $this->username, $this->password, array(
                PDO::ATTR_PERSISTENT => true
            ));
            echo "Connessione a $this->dbname tramite $this->host eseguita. <br>";
            return $conn;
        } catch (PDOException $pe) {
            die("Impossibile connettersi a $this->dbname :" . $pe->getMessage());
        }
    }
}