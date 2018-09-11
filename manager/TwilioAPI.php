<?php

$dir = getcwd() . '/thirdparty/twilio-api/Twilio/autoload.php';
require $dir;

class TwilioAPI {

    const ACCOUNT_SID   = 'ACde8f13d491aa452067d22b656e7082e8';
    const AUTH_TOKEN    = 'a02deab8a41126936dcb1f7cab444323';
    const FROM          = '+19253930308';

    // private
    private $twilio;
    private $sms = [];
    private $to;

    // public

    function __construct()
    {
        $this->twilio = new \Twilio\Rest\Client(self::ACCOUNT_SID, self::AUTH_TOKEN);
        $this->setupSMS();
    }

    // public methods

    public function setTo($to){
        $this->to = $to;
        return $this;
    }

    public function setMessage($msg){
        $this->sms['body'] = $msg;
        return $this;
    }

    public function send(){
        return $this->twilio->messages->create($this->to, $this->sms);
    }

    // private helpers

    private function setupSMS(){
        $this->sms['from'] = self::FROM;
        $this->sms['body'] = '';
    }

    // messages to send (move these to a helper)

}