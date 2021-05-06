<?php 

class Sms extends DataStorage {

    public function __construct($textInput) {

        if ($this->isValid($textInput)){
            echo('Il testo è troppo lungo');
        }

        $this->sms = $textInput;

    }

    private function isValid($textInput) {

        return strlen($textInput) > 10;
        
    }

}

?>