<?php

class Schedule {

    const SCHEDULE_ICS = 'http://tmsdln.com/tnv1';

    const IS_GOING = "Is Going";
    const IS_NOT_GOING = 'Is Not Going';
    const UNKNOWN = 'Unknown';

    const IS_GOING_CLASS = 'text-success';
    const IS_NOT_GOING_CLASS= 'text-danger';
    const UNKNOWN_CLASS = 'text-warning';

    const BG_EVEN = 'bg-even';

    public $http = null;
    public $mysqlHelper = null;

    function __construct()
    {
        $this->http = new HTTP();
        $this->mysqlHelper = new MySQLHelper();
    }

    public static function isGoing($attendance, $uid){
        $trimmedUid = trim($uid);
        $isGoingFlag = null;
        foreach($attendance as $key => $row){
            if($row->uid === $trimmedUid){
                $isGoingFlag = $row->isGoing;
            }
        }

        if($isGoingFlag === null){
            $ret = null;
        }else if($isGoingFlag === "1"){
            $ret = true;
        }else if($isGoingFlag === "0"){
            $ret = false;
        }else{
            $ret = null;
        }

        return $ret;
    }

    /*
     * requires the boolean value returned from isGoing ^
     */
    public static function isGoingText($isGoing){
        $ret = '';
        if($isGoing === null){
            $ret = 'Set Attendance';
        }else if($isGoing === true){
            $ret = 'Going';
        }else if($isGoing === false){
            $ret = 'Not Going';
        }else{
            $ret = 'isGoingText fucked up';
        }
        return $ret;
    }

    /*
     * requires the boolean value returned from isGoing ^
     */
    public static function isGoingClass($isGoing){
        $ret = '';
        if($isGoing === null){
            $ret = 'btn-primary';
        }else if($isGoing === true){
            $ret = 'btn-success';
        }else if($isGoing === false){
            $ret = 'btn-danger';
        }else{
            $ret = 'btn-warning';
        }
        return $ret;
    }



    public static function getBackgroundClass($isCurrent){
        $ret = '';
        if($isCurrent){
            $ret = 'bg-success';
        }else{
            $ret = 'bg-light';
        }
        return $ret;
    }

    public function getSchedule()
    {
        $res = $this->http->setUrl(self::SCHEDULE_ICS)->setMethod('get')->doRequest();
        return $this->parseSchedule($res);
    }

    public function parseSchedule($schedule)
    {
        $scheduleArray = explode(chr(10), $schedule);
        $ret = [];
        $continue = false;
        $obj = new stdClass();
        foreach ($scheduleArray as $row) {

            if ($continue) {
                $rowArray = explode(':', $row);
                $key = $rowArray[0];
                $val = $rowArray[1];

                if (strpos($row, 'DTSTART') !== false) {
                    $obj->foundDTSTART = true;
                    $obj->datetime = $this->iCalToUnix($val);
                }

                $obj->{strtolower($key)} = trim($val);
            }

            if (strpos($row, 'BEGIN:VEVENT') !== false) {
                $continue = true;
                $obj = new stdClass();
            }


            if (strpos($row, 'END:VEVENT') !== false) {
                $continue = false;
                array_push($ret, $obj);
            }

        }
        return $ret;
    }

    public static function getGameFromSchedule($schedule, $uid)
    {
        $ret = null;
        foreach ($schedule as $row) {
            if ($row->uid === $uid) {
                $ret = $row;
                break;
            }
        }
        return $ret;
    }

    public static function getUserAttendanceForGame($user, $game, $attendance){
        $__rosterId = $user->id;
        $__uid = $game->uid;
        $isGoingFlag = null;
        foreach($attendance as $obj){
            if($obj->rosterId == $__rosterId && $obj->uid === $__uid){
                $isGoingFlag = $obj->isGoing;
            }
        }

        if($isGoingFlag === null){
            $ret = null;
        }else if($isGoingFlag === "1"){
            $ret = true;
        }else if($isGoingFlag === "0"){
            $ret = false;
        }else{
            $ret = null;
        }

        return $ret;
    }

    public static function setUserAttendanceForGame($rosterId, $uid, $isGoing){

    }

    public function updateUserAttendance($rosterId, $uid, $isGoing){
        $this->mysqlHelper->deleteFromAttendance($rosterId, $uid);
        return $this->mysqlHelper->insertIntoAttendance($rosterId, $uid, $isGoing);
    }

    private function iCalToUnix($icalDateTime)
    {
        // 20180520T160000Z
        $dateArray = explode('T', $icalDateTime);
        $d = $dateArray[0]; // YYYYMMDD
        $t = $dateArray[1]; // HHmmssZ

        $month = substr($d, 4, 2);
        $day = substr($d, 6, 2);
        $year = substr($d, 0, 4);

        $hours = substr($t, 0, 2);
        $minutes = substr($t, 2, 2);
        $seconds = substr($t, 4, 2);

        //$mTime = mktime($hours, $minutes, $seconds, $month, $day, $year);
        $gTime = gmmktime($hours, $minutes, $seconds, $month, $day, $year);

        $date = new DateTime();
        $date->setTimestamp($gTime);
        $date->setTimezone(new DateTimeZone('America/Los_Angeles')); // America/Los_Angeles // Etc/GMT-8

        return $date;
    }

    public static function dspIsGoingAsText($isGoing){
        $ret = '';
        switch($isGoing){
            case "0":
                $ret = self::IS_NOT_GOING;
                break;

            case "1":
                $ret = self::IS_GOING;
                break;

            default:
                $ret = self::UNKNOWN;
                break;
        }
        return $ret;
    }

    public static function dspIsGoingAsTextCSSClass($isGoing){
        $ret = '';
        switch($isGoing){
            case "0":
                $ret = self::IS_NOT_GOING_CLASS;
                break;

            case "1":
                $ret = self::IS_GOING_CLASS;
                break;

            default:
                $ret = self::UNKNOWN_CLASS;
                break;
        }
        return $ret;
    }

}