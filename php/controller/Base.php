<?php
include 'exceptions\CustomException.php';

class Base {

    public function process($type,$text,$conn) {

        try {

            $dataStorage = new DataStorage($type,$text);
            $istance = $dataStorage->istantiateByType();

            if (!$istance->isValid($text)){

                $dataStorage->store($conn);
                $istance->response();
            }
        } catch (CustomException $e){
            
            echo $e->sendEmailException();
        }
    }
}