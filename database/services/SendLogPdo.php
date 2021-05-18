<?php
class SendLogPdo {

    public function __construct($message)
    {

        $this->message = $message;
        $this->created_at = $this->timestamp();
    }

    public function insert($conn)
    {

        $data = [
            'message' => $this->message,
            'created_at' => $this->created_at 
        ];

        $insertStatement = $conn->prepare("INSERT INTO logs (message, created_at) VALUES (:message, :created_at)");

        if (!$insertStatement->execute($data)) {
    
            echo "Registrazione dei logs fallita<br>";
        }
    }

    protected function timestamp() {

        date_default_timezone_set('Europe/Rome');
        return date('Y-m-d H:i:s');
    }
}
