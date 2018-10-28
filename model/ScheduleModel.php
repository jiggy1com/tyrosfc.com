<?php

class ScheduleModel {

    // local vars
    private $rosterId;
    private $attendance;
    public $now;
    public $nowDate;
    public $nowTime;
    public $nowTimestamp;

    // public
    public $gameUid;
    public $week;

    public $datetime;
    public $datetimeTimestamp;
    public $date;
    public $time;

    public $homeTeam;
    public $awayTeam;
    public $homeClass;
    public $awayClass;

    public $location;
    public $locationMap;
    public $locationSurface;
    public $locationSurfaceIcon;

    public $isGoing;
    public $isGoingClass;
    public $isGoingText;

    public $isCurrentGame;
    public $bgClass;

    function __construct($rosterId=0, $idx=0, $data)
    {
        $this->now = new DateTime();
        $this->nowDate = $this->getFormattedDate($this->now);
        $this->nowTime = $this->getFormattedTime($this->now);
        $this->nowTimestamp = $this->getTheTimestamp($this->now);

        $m = new MySQLHelper();

        $this->setRosterId($rosterId);
        $this->setAttendance( $m->getAttendanceByRosterId( $this->rosterId ) );

        if(!empty($data)){
            $this->handleData($idx, $data);
        }
    }

    public function handleData($idx, $data){

        $__teams = explode(' vs. ', $data->summary);

        $this->homeTeam = $__teams[0];
        $this->awayTeam = $__teams[1];
        $this->homeClass = preg_replace('/[^a-zA-Z0-9]/', '', $this->homeTeam);
        $this->awayClass = preg_replace('/[^a-zA-Z0-9]/', '', $this->awayTeam);

        $this->week = $idx + 1;
        $this->gameUid = $data->uid;

        $this->a = $data->datetime->getTimestamp();
        $this->datetimeTimestamp = $data->datetime->getTimestamp();
        $this->datetime = $data->datetime;
        $this->date = $this->getFormattedDate($this->datetime);
        $this->time = $this->getFormattedTime($this->datetime);

        $this->location = $data->location;
        $this->locationMap = Locations::getGoogleMapLink(trim($data->location));
        $this->locationSurface = Locations::getFieldSurface(trim($data->location));
        $this->locationSurfaceIcon = Locations::getFieldSurfaceIcon($this->locationSurface);

        $this->isGoing = Schedule::isGoing($this->getAttendance(), $this->gameUid);
        $this->isGoingText = Schedule::isGoingText($this->isGoing);
        $this->isGoingClass = Schedule::isGoingClass($this->isGoing);

        $this->isPassGame = $this->getGameHasPassed();
        $this->isCurrentGame = $this->getIsCurrentGame();
        $this->isFutureGame = $this->getIsFutureGame();
        $this->bgClass = $this->setBgClass();
    }

    private function setRosterId($rosterId){
        $this->rosterId = $rosterId;
        return $this;
    }

    private function setAttendance($attendance){
        $this->attendance = $attendance;
        return $this;
    }

    private function setBgClass(){
        $__bgClass = '';
        if($this->getGameHasPassed()){
            $__bgClass = 'bg-light';
        }else if($this->isCurrentGame){
            $__bgClass = 'bg-info';
        }else{
            $__bgClass = 'bg-dark';
        }
        return $__bgClass;
    }


    private function getGameHasPassed(){
        return $this->nowTimestamp > $this->datetimeTimestamp;
    }

    private function getIsCurrentGame(){
        return !$this->getGameHasPassed() && !$this->getIsFutureGame();
//        $gameDayPlus7Days = $this->datetime->modify("-7 day");
//        return $this->now->getTimestamp() < $gameDayPlus7Days->getTimestamp();
    }

    private function getIsFutureGame(){
        $nowPlus7Days = $this->now->modify("+7 day");
        return $nowPlus7Days->getTimestamp() < $this->datetimeTimestamp;
    }

    private function getRosterId(){
        return $this->rosterId;
    }

    private function getAttendance(){
        return $this->attendance;
    }

    private function getFormattedDate($date){
        return $date->format("M d, Y");
    }

    private function getFormattedTime($date){
        return $date->format('g:i A');
    }

    private function getTheTimestamp($date){
        return $date->getTimestamp();
    }

    /*
    * @Deprecated
    */
    private function setIsCurrentGame(){
        $weekFromGameDay = $this->datetime->modify("-7 day");
        return $this->nowTimestamp < $this->datetimeTimestamp &&
            $this->nowTimestamp > $weekFromGameDay->getTimestamp();
    }

}