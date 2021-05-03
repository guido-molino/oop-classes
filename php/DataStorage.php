<?php namespace DataStorage;

class DataStorage {

    protected $type = null;
    protected $path = '/php/concrete/'; 

    public function __construct($type, $path, string $textInput) {
        
        $this->type = $type;
        $this->istantiateByType($path, $textInput);

    }

    public function response() {
        
        $response = 'Istanziamento classe per: '. $this->type;
        echo($response);

    }

    protected function istantiateByType($path, $textInput) {

        $class   = $path.$this->type;
        $istance = new $class($textInput);
        return $istance;

    }

}

?>