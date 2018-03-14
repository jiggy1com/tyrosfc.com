<?php

class MailManager {

    private $mailServer;
    private $mailPort;
    private $mailUsername;
    private $mailPassword;

    private $error;
    private $errorInfo;

    private $to         = []; // []
    #private $cc         = ''; // []
    #private $bcc        = ''; // []
    private $from       = '';
    private $subject    = '';
    private $textMsg    = '';
    private $htmlMsg    = '';

    function __construct($obj = null)
    {
        $this->setMailSettings();
        if($obj){
            $this->to = $obj->to;
        }
    }

    private function setMailSettings(){
        $this->mailServer   = Config::MAIL_SERVER;
        $this->mailPort     = Config::MAIL_PORT;
        $this->mailUsername = Config::MAIL_USERNAME;
        $this->mailPassword = Config::MAIL_PASSWORD;
    }


    private function setError($error){
        $this->error = $error;
    }

    # public helpers

    public function setFrom($from){
        $this->from = $from;
        return $this;
    }

    public function addTo($to){
        array_push($this->to, $to);
        return $this;
    }

    public function setSubject($subject){
        $this->subject = $subject;
        return $this;
    }

    public function setTextMessage($msg){
        $this->textMsg = $msg;
        return $this;
    }

    public function setHtmlMessage($msg){
        $this->htmlMsg = $msg;
        return $this;
    }



    # public send

    public function send(){

        $p = new PHPMailer();

        $p->isSMTP();
        $p->SMTPDebug 	= 0; // 0-Production, 1,2,3,4-Dev (less to more info)
        $p->Debugoutput = 'html';
        $p->SMTPAuth 	= true;
        # $p->SMTPSecure 	= 'ssl';
        $p->Host 		= $this->mailServer;
        $p->Port 		= $this->mailPort;
        $p->Username 	= $this->mailUsername;
        $p->Password 	= $this->mailPassword;
        $p->setFrom($this->from);
        $p->addReplyTo($this->from);
        # $p->addAddress($this->to);
        $p->Subject 	= $this->subject;
        $p->AltBody 	= $this->textMsg;
        $p->msgHTML($this->htmlMsg);

		# $p->addAttachment('/path/to/file');

        // set recipients
        foreach($this->to as $recipient){
            $p->addAddress($recipient);
        }

        $resp = $p->send();

        if($resp){
            return true;
        }else{
            $this->setError($p->ErrorInfo);
            return $p->ErrorInfo;
        }
    }

    # public message types

    public function sendForgotPassword($data){
        return $this->setFrom(Config::MAIL_USERNAME)->addTo($data->email)->setSubject('Your ' . Config::COMPANY_NAME . ' password')->setTextMessage($data->textMsg)->setHtmlMessage($data->htmlMsg)->send();
    }

    public function sendAttendanceUpdate($game, $user, $isGoing){

        $__date = $game->datetime->format('F d, Y');
        $__time = $game->datetime->format('g:i A');
        $__summary = $game->summary;
        $__location = $game->location;
        $__fieldSurface = Locations::getFieldSurface($__location);

        $__isGoingText = $isGoing == '1' ? 'GOING' : 'NOT GOING';
        $__subject = Config::COMPANY_NAME . ' Attendance Update for ' . $__date . ' at ' . $__time;

        $textMsg = "
        
        $user->firstname $user->lastname attendance is: $__isGoingText
        
        $__date at $__time
        
        $__summary at $__location ($__fieldSurface)
        
        ";

        $htmlMsg = $textMsg;
        return $this->addTo(Config::FLORIN_EMAIL)
            ->addTo(Config::MAIL_USERNAME)
            ->setFrom(Config::MAIL_USERNAME)
            ->setSubject($__subject)
            ->setTextMessage($textMsg)
            ->setHtmlMessage( preg_replace("/\r\n/", "<br>", $htmlMsg) )
            ->send();
    }

}