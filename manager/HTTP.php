<?php

class HTTP {

    // TODO: these should be private
    public $url;
    public $method;
    public $data;
    public $headers;

    function __construct()
    {

    }

    function setUrl($url){
        $this->url = $url;
        return $this;
    }

    function setMethod($method){
        $this->method = $method;
        return $this;
    }

    function setData($data){
        $this->data = $data;
        return $this;
    }

    function setHeader($headerName, $headerValue){
        if( isset($this->headers[$headerName]) ){
            $this->headers[$headerName] = $headerName;
        }else{
            array_push($headerName, $headerValue);
        }
        return $this;
    }

    function doRequest(){

        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $this->url);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);

        return $output;

    }



}