<?php

class ATTSMS {

    // api
    const OAUTH_ACCES_TOKEN = '';

    // TODO: most host to ATTAPI
    private $host = 'https://api.att.com'; // what's the sandbox ?
    private $endpoint = '/sms/v3/messaging/outbox';

    // recipient
//    private $message;
//    private $number; // recipient number
    private $data;

    // other locals
    private $http;

    function __construct()
    {
        $this->setupHttp();
        $this->data = new ATTSMSModel();
    }

    private function setupHttp(){
        $this->http = new HTTP();
        $this->http->setHeader('Content-Type', 'application/json');
        $this->http->setHeader('Accept', 'application/json');
        $this->http->setHeader('Authorization', 'Bearer {OAUTH_ACCESS_TOKEN}');
    }

    public function setMessage($msg){
        $this->data->outboundSMSRequest->message = $msg;
        return $this;
    }

    public function setAddress(){
        //        $this->data->outboundSMSRequest->address = address;

    }

    public function send(){
//        $this->http->se
    }

}