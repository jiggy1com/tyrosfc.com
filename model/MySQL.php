<?php

class MySQL extends Config {

    public $conn;
    private $queryObject; // the initial mysqli query object
    private $query; // the query to run
    private $fields;

    private $returnAs = '';

    private $all;
    private $array;
    private $assoc;
    private $object;

    private $results;
    private $error;
    private $mysqliError;

    function __construct()
    {
        $this->conn = new mysqli(self::MYSQL_HOST, self::MYSQL_USERNAME, self::MYSQL_PASSWORD, self::MYSQL_DATABASE);
    }

    private function setError(){
        $this->error = $this->conn->error;
    }

    public function getError(){
        return $this->error;
    }

    public function setQuery($query){
        $this->query = $query;
        return $this;
    }

    public function setReturnAs($returnAs){
        $this->returnAs = $returnAs;
        return $this;
    }

    public function runCreate(){
        $result = $this->conn->query($this->query);

        if(!$result){
            $this->setError();
        }

        return $result;
    }

    public function runRead(){
        $this->queryObject = $this->conn->query($this->query);

        // set the fields
        $this->setFields($this->queryObject->fetch_fields());

        // set the results object based on how you want the data returned
        if($this->returnAs === 'all'){
            $this->results = $this->queryObject->fetch_all();
        }else if($this->returnAs === 'array'){
            $this->results = $this->queryObject->fetch_array();
        }else if($this->returnAs === 'assoc'){
            $this->results = $this->queryObject->fetch_assoc();
        }else if($this->returnAs === 'object'){
            $this->results = $this->queryObject->fetch_object();
        }else{
            $this->results = $this->formatResultsAsArrayOfObjects( $this->queryObject );
        }

        return $this->results;
    }

    public function runUpdate(){

        $result = $this->conn->query($this->query);

        if(!$result){
            $this->setError();
        }

        return $result;
    }

    public function runDelete(){
        $result = $this->conn->query($this->query);

        if(!$result){
            $this->setError();
        }

        return $result;
    }

    function runQuery()
    {

        $this->queryObject = $this->conn->query($this->query);

        // set the fields
        $this->setFields($this->queryObject->fetch_fields());

        // set the results object based on how you want the data returned
        if($this->returnAs === 'all'){
            $this->results = $this->queryObject->fetch_all();
        }else if($this->returnAs === 'array'){
            $this->results = $this->queryObject->fetch_array();
        }else if($this->returnAs === 'assoc'){
            $this->results = $this->queryObject->fetch_assoc();
        }else if($this->returnAs === 'object'){
            $this->results = $this->queryObject->fetch_object();
        }else{
            $this->results = $this->formatResultsAsArrayOfObjects( $this->queryObject );
        }

        return $this->results;

    }

    private function formatResultsAsArrayOfObjects($rows){
        $fields = $this->fields;
        $arrayOfObjects = [];
        foreach($rows as $rowIdx => $row){

            $obj = new stdClass();
            foreach($fields as $idx => $field){
                $obj->{$fields[$idx]->name} = $row[$field->name];
            }

            array_push($arrayOfObjects, $obj);
        }
        return $arrayOfObjects;
    }

    private function setFields($fields){
        $this->fields = $fields;
        return $this;
    }

    private function setAll($all){
        $this->all = $all;
        return $this;
    }

    private function setArray($array){
        $this->array = $array;
        return $this;
    }

    private function setAssoc($assoc){
        $this->assoc = $assoc;
        return $this;
    }

    private function setObject($object){
        $this->object = $object;
        return $this;
    }

    function __destruct()
    {
        if( $this->conn){
            $this->conn->close();
        }
    }

}