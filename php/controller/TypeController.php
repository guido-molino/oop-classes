<?php
class Base {

    public function __construct(){}
    
    public function read($type,$text) {  
        try {
            
            $this->readable($type,$text);//controllo sul payload
            $dbconn = new DbConn();      //connessione al db 
            $conn = $dbconn->connect();
            $dataStorage = new DataStorage($type,$text); //eseguo il programma
            $istance = $dataStorage->istantiateByType(); 
            $istance->isValid($text);   //validazione per istanza
            $dataStorage->store($conn); //eseguo lo store sul db
            $istance->response();       //stampo la response

        } catch (CustomException $e){

            echo $e->sendLogsException($conn);
        } catch (Exception $e){

            echo $e->getMessage();
        }
    }

    private function readable($type,$text) {

        if (empty($type && $text)) {
            throw new Exception('Invalid payload');
        }
    }
}