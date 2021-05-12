<?php
class SendUserPdo {

    public function __construct($conn) {
        
        $this->conn = $conn;
    }

    public function insert() {

    }

    public function select($id=null) {

        if (is_null($id)) {

            $list = $this->conn->query("SELECT * FROM users")->fetchAll();

            foreach ($list as $key=>$user) {

                var_dump('<br><br>utente '.$key. '<br><br>', $user);
            }

        }   else {

            $selectStatement = $this->conn->prepare("SELECT * FROM users WHERE id=?");
            $selectStatement->execute([$id]);
            $user = $selectStatement->fetch();
            var_dump($user);
        }

    }

    private function timestamp() {

        date_default_timezone_set('Europe/Rome');
        return date('Y-m-d H:i:s');
    }
    
}
