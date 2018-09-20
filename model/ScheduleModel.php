<?php

class ScheduleModel {

    // local vars
    private $rosterId;
    private $attendance;
    public $now;

    // public
    public $gameUid;
    public $week;

    public $date;
    public $time;

    public $homeTeam;
    public $awayTeam;
    public $homeClass;
    public $awayClass;

    public $location;
    public $locationMap;
    public $locationSurface;



    public $isGoing;
    public $isGoingClass;
    public $isGoingText;

    public $isCurrentGame;
    public $bgClass;

    function __construct($rosterId=0, $idx=0, $data)
    {
        $this->now = new DateTime();

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

        $this->date = $data->datetime->format("M d, Y");
        $this->time = $data->datetime->format('g:i A');

        $this->location = $data->location;
        $this->locationMap = Locations::getGoogleMapLink(trim($data->location));
        $this->locationSurface = Locations::getFieldSurface(trim($data->location));

        $this->isGoing = Schedule::isGoing($this->getAttendance(), $this->gameUid);
        $this->isGoingText = Schedule::isGoingText($this->isGoing);
        $this->isGoingClass = Schedule::isGoingClass($this->isGoing);

        $this->isCurrentGame = $this->now->getTimestamp() < $data->datetime->getTimeStamp();
        $this->bgClass = Schedule::getBackgroundClass( $this->isCurrentGame );
    }

    private function setRosterId($rosterId){
        $this->rosterId = $rosterId;
        return $this;
    }

    private function setAttendance($attendance){
        $this->attendance = $attendance;
        return $this;
    }

    private function getRosterId(){
        return $this->rosterId;
    }

    private function getAttendance(){
        return $this->attendance;
    }

}