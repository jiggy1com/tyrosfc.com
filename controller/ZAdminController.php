<?php

class ZAdminController extends ApplicationController {

    public $data = "";

    function __construct()
    {
        parent::__construct();
        $this->checkIsLoggedIn();
    }

    private function checkIsLoggedIn(){
        if(!$this->isLoggedIn){
            //$this->setRedirect( $_SERVER['REQUEST_URI'] );
            //header('Location: /login');
            //die();
        }
    }
    
    private function isAdmin(){
        $this->setRedirect( $_SERVER['REQUEST_URI'] );
        if($this->isLoggedIn && $this->cookies['isAdmin'] == '1'){
            return true;
        }else{
            return false;
        }
    }

    // admin

    public function index(){
        if(!$this->isAdmin()){
            return $this->setNextRoute('/login');
        }else{
            $this->view = 'admin/index';
            return $this;
        }
    }

    // admin/schedule

    public function schedule(){
        if(!$this->isAdmin()){

            return $this->setNextRoute('/login');
        }else{
            $s = new Schedule();
            $this->rc->schedule = $s->getSchedule();
            $this->view = 'admin/schedule';
            return $this;
        }
    }

    public function game(){
        if(!$this->isAdmin()){
            return $this->setNextRoute('/login');
        }else{

            $uid = $this->router->getParam('uid');

            $s = new Schedule();
            $schedule = $s->getSchedule();
            $this->rc->game = Schedule::getGameFromSchedule($schedule, $uid);

            $m = new MySQLHelper();
            $this->rc->attendance = $m->getAttendanceByGameUID($uid);
            $this->rc->roster = $m->getActiveRoster();

            $this->view = 'admin/game';
            return $this;
        }
    }

    public function gameUpdateAttendance(){

        // force a json response
        $this->router->setVar('isAjax', 1);

        if(!$this->isAdmin()){
            return $this->setNextRoute('/login');
        }else {
            $__uid = $this->router->getParam('uid');
            $__rosterId = $this->router->getVar('rosterId');
            $__isGoing = $this->router->getVar('isGoing');

            $s = new Schedule();
            $result = $s->updateUserAttendance($__rosterId, $__uid, $__isGoing);

            if($result){

                // send email notification
                $m = new MySQLHelper();
                $user = $m->getRosterById($__rosterId);

                $schedule = $s->getSchedule();
                $game = $s->getGameFromSchedule($schedule, $__uid);

                $m = new MailManager();
                $m->sendAttendanceUpdate($game, $user[0], $__isGoing);

                $this->rc->success = true;
                $this->rc->message = 'Attendance successfully updated.';
            }else{
                $this->rc->success = false;
                $this->rc->message = $s->mysqlHelper->getError();
            }

            return $this;
        }
    }

    public function remindBySMS(){
        if(!$this->isAdmin()){
            return $this->setNextRoute('/login');
        }else{
            // force a json response
            $this->router->setVar('isAjax', 1);

            $__uid = $this->router->getParam('uid');
            $__rosterId = $this->router->getParam('rosterId');

            $m = new MySQLHelper();
            $q = $m->getRosterById($__rosterId);

            if(isset($q[0]) && !empty($q[0]->phone)){
                $__smsMessage = "Please update your attendance at http://www.tyrosfc.com/schedule/game/$__uid/attendance";
                $__num = $q[0]->phone;
                $t = new TwilioAPI();
                $t->setTo($__num)->setMessage($__smsMessage)->send();

                // TODO: THIS ASSUMES THE REQUEST WAS SUCCESSFUL, actually check the response and return accordingly
                $this->rc->success = true;
                $this->rc->message = "SMS successfully sent.";
            }else{
                $this->rc->success = false;
                $this->rc->message = "User has no number.";
            }

            $this->view = 'admin/index';
            return $this;
        }
    }

    public function remindByEmail(){
        if(!$this->isAdmin()){
            return $this->setNextRoute('/login');
        }else{
            // force a json response
            $this->router->setVar('isAjax', 1);

            $__uid = $this->router->getParam('uid');
            $__rosterId = $this->router->getParam('rosterId');

            $m = new MySQLHelper();
            $user = $m->getRosterById($__rosterId)[0];

            $s = new Schedule();
            $__schedule = $s->getSchedule();
            $game = $s->getGameFromSchedule($__schedule, $__uid);

            $mailManager = new MailManager();
            $result = $mailManager->sendAttendanceReminder($game, $user);

            $this->rc->success = $result;
            $this->rc->message = $result ? 'Email successfully sent.' : 'Email was not sent.';

            return $this;

        }
    }

    // admin/roster

    public function roster(){
        if(!$this->isAdmin()){
            return $this->setNextRoute('/login');
        }else{
            $m = new MySQLHelper();
            $this->rc->roster = $m->getActiveRoster();
            $this->view = 'admin/roster';
            return $this;
        }
    }

    public function rosterSMS(){
        if(!$this->isAdmin()){
            return $this->setNextRoute('/login');
        }else{
            $this->view = 'admin/rosterSms';
            return $this;
        }
    }

    public function rosterEmail(){
        if(!$this->isAdmin()){
            return $this->setNextRoute('/login');
        }else{
            $this->view = 'admin/rosterEmail';
            return $this;
        }
    }

    // admin/sms

    public function sms(){
        if(!$this->isAdmin()){
            return $this->setNextRoute('/login');
        }else{
            $this->view = 'admin/sms';
            return $this;
        }
    }

    public function smsSend(){

        if(!$this->isAdmin()){
            return $this->setNextRoute('/login');
        }else{
            // force a json response
            $this->router->setVar('isAjax', 1);

            $arrayOfErrors = [];

            // validation
            $isValid = true;
            $__smsMessage = $this->router->getVar('smsMessage');
            $__sendTo = $this->router->getVar('sendTo');

            if($isValid && $__smsMessage === ''){
                $isValid = false;
                $this->rc->message = 'SMS message is required.';
            }

            if($isValid){

                $t = new TwilioAPI();
                $t->setMessage($__smsMessage);

                $m = new MySQLHelper();
                if($__sendTo === 'activeRoster'){
                    $roster = $m->getActiveRoster();
                }else if ($__sendTo === 'inActiveRoster'){
                    $roster = $m->getInActiveRoster();
                }else if($__sendTo === 'fullRoster'){
                    $roster = $m->getRoster();
                }else{
                    $roster = $m->getRosterById($__sendTo);
                }

                foreach($roster as $user){
                    if(!empty($user->phone)){
                        $result = $t->setTo($user->phone)->send();
                        $resultError = $result->errorMessage;
                        if(!empty($resultError)){
                            $arr = [
                                'firstname' => $user->firstname,
                                'lastname' => $user->lastname,
                                'phone' => $user->phone,
                                'error' => $resultError
                            ];
                            array_push($arrayOfErrors, $arr);
                        }
                    }
                }

                // send email notification to joe =)
                $date = new DateTime();
                $mailManager = new MailManager();
                $mailManager->addTo(Config::MAIL_USERNAME)
                    ->setFrom(Config::MAIL_USERNAME)
                    ->setSubject("sms message sent on " . $date->format('F d, Y') . " at " . $date->format('g:i A'))
                    ->setTextMessage($__smsMessage)
                    ->setHtmlMessage($__smsMessage)
                    ->send();

                $this->rc->success = true;
                $this->rc->message = 'SMS message successfully sent. If any errors occurred, they are listed below.';
                $this->rc->arrayOfErrors = $arrayOfErrors;

            }

            return $this;
        }

    }

    // admin/email

    public function email(){
        if(!$this->isAdmin()){
            return $this->setNextRoute('/login');
        }else{
            $this->view = 'admin/email';
            return $this;
        }
    }

    public function emailSend(){
        if(!$this->isAdmin()){
            return $this->setNextRoute('/login');
        }else{
            // force a json response
            $this->router->setVar('isAjax', 1);

            // validation
            $isValid = true;
            $__subject = $this->router->getVar('subject');
            $__textMessage = $this->router->getVar('textMessage');
            $__htmlMessage = $this->router->getVar('htmlMessage');
            $__sendTo = $this->router->getVar('sendTo');

            if($isValid && $__subject == ''){
                $isValid = false;
                $this->rc->message = 'Subject is required.';
            }

            if($isValid && $__textMessage == ''){
                $isValid = false;
                $this->rc->message = 'Text message is required.';
            }

            if(!$isValid){
                $this->rc->success = false;
            }

            if($isValid){

                // valid request

                $mailManager = new MailManager();
                $m = new MySQLHelper();

                if($__sendTo === 'activeRoster'){
                    $roster = $m->getActiveRoster();
                }else if ($__sendTo === 'inActiveRoster'){
                    $roster = $m->getInActiveRoster();
                }else if($__sendTo === 'fullRoster'){
                    $roster = $m->getRoster();
                }else{
                    $roster = $m->getRosterById($__sendTo);
                }

                foreach($roster as $user){
                    if($user->email !== ''){
                        $mailManager->addTo($user->email);
                    }
                }

                $mailManager->setFrom(Config::MAIL_USERNAME)
                    ->setSubject( $__subject )
                    ->setTextMessage( $__textMessage );

                if($__htmlMessage === ''){
                    $mailManager->setHtmlMessage( preg_replace('/\r\n?/', '<br>', $__textMessage) );
                }else{
                    $mailManager->setHtmlMessage( $__htmlMessage );
                }

                $msgResult = $mailManager->send();

                if($msgResult === true){
                    $this->rc->success = true;
                    $this->rc->message = 'Message successfully sent.';
                }else{
                    $this->rc->success = false;
                    $this->rc->message = $msgResult;
                }

            }

            return $this;
        }

    }

    // admin/lineup

    public function lineup(){

        if(!$this->isAdmin()){
            return $this->setNextRoute('/login');
        }else{
            // get game id
            $__uid = $this->router->getParam('uid');


            $m = new MySQLHelper();

            // who is going
            $this->rc->attendance = $m->getRosterAttendanceThatIsGoingByGameUid($__uid);
            //$this->rc->attendance = $m->getRosterAttendanceByGameUid($__uid);

            // who is starting
            $lineup = $m->getLineupByUid($__uid);
            // if no starters yet, create it
            if( count($lineup) === 0 ){
                $m->createLineup($__uid);
                $lineup = $m->getLineupByUid($__uid);
            }
            $this->rc->lineup = $lineup;

            $this->view = 'admin/lineup';
            return $this;
        }
    }

    public function printLineup(){
        if(!$this->isAdmin()){
            return $this->setNextRoute('/login');
        }else{
            $printVersion = $this->lineup();
            $printVersion->layout = 'print';
            return $printVersion;
        }
    }

    public function updateLineup(){
        if(!$this->isAdmin()){
            return $this->setNextRoute('/login');
        }else{
            // force a json response
            $this->router->setVar('isAjax', 1);

            if(!$this->isAdmin()){
                return $this->setNextRoute('/login');
            }else{

                $__uid = $this->router->getParam('uid');
                $__game = $this->router->getVar('game');

                $m = new MySQLHelper();
                $res = $m->saveLineup($__uid, $__game);
                if($res === true){
                    $this->rc->success = true;
                    $this->rc->message = 'Lineup successfully saved.';
                }else{
                    $this->rc->success = false;
                    $this->rc->message = $m->mysql->getError();
                }

                $this->view = 'admin/index';
                return $this;
            }
        }
    }

//    public function importRoster(){
//
//        $data = explode(chr(10), $this->data);
//
//        foreach($data as $row){
//            $arr = explode(',', $row);
//
//            $firstname = $arr[0];
//            $lastname = $arr[1];
//            $gender = $arr[2];
//            $address1 = $arr[3];
//            $address2 = $arr[4];
//            $city = $arr[5];
//            $state = $arr[6];
//            $zip = $arr[7];
//            $phone = $arr[8];
//            $email = $arr[9];
//
//            $query = "insert into roster (firstname, lastname, gender, address1, address2, city, state, zip, phone, email) values('$firstname', '$lastname', '$gender', '$address1', '$address2', '$city', '$state', '$zip', '$phone', '$email')";
//
//            $this->mysql->setQuery($query)->runCreate();
//
//        }
//
//        $this->view = 'admin/test';
//
//    }

}