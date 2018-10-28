<?php

class ScheduleController extends ApplicationController
{

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!$this->isLoggedIn) {
            $this->setRedirectToSelf();
            return $this->setNextRoute('/login');
        } else {

            $__foundIsCurrentGame = false;
            $__rosterId = $this->cookies['id'];

            $s = new Schedule();
            $__schedule = $s->getSchedule();

            $m = new MySQLHelper();
            $this->rc->attendance = $m->getAttendanceByRosterId($__rosterId);

            $this->rc->scheduleModel = [];
            foreach($__schedule as $idx => $game){
                $obj = new ScheduleModel($__rosterId, $idx, $game);
                array_push($this->rc->scheduleModel, $obj);
            }

            $this->view = 'schedule/index';
            return $this;
        }
    }

    public function remoteSchedule(){

        $curl = curl_init();

        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'http://www.diablosoccer.org/sites/dasl/schedule/205779/DASL-Division-C',
            CURLOPT_USERAGENT => 'Chrome'
        ));

        // Send the request & save response to $resp
        $resp = curl_exec($curl);

        // Close request to clear up some resources
        curl_close($curl);

        $this->rc->html = $resp;
        $this->isAjax = true;
        return $this;
    }

    public function gameAttendance()
    {

        if (!$this->isLoggedIn) {
            $this->setRedirectToSelf();
            return $this->setNextRoute('/login');
        } else {

            // schedule
            $s = new Schedule();
            $this->rc->schedule = $s->getSchedule();

            // game
            $__schedule = $this->rc->schedule;
            $__uid = $this->router->getParam('uid');
            $__id = $this->cookies['id'];
            $this->rc->game = Schedule::getGameFromSchedule($__schedule, $__uid);

            // attendance
            $query = "select * from attendance where uid = '$__uid' and rosterId = '$__id'";
            $this->rc->userAttendance = $this->mysql->setQuery($query)->runRead();

            // team attendance
            $m = new MySQLHelper();
            $this->rc->teamAttendance = $m->getRosterAttendanceByGameUid($__uid);

            // who is going
            $this->rc->attendance = $m->getRosterAttendanceThatIsGoingByGameUid($__uid);
            // who is starting
            $lineup = $m->getLineupByUid($__uid);
            // if no starters yet, create it
            if( count($lineup) === 0 ){
                $m->createLineup($__uid);
                $lineup = $m->getLineupByUid($__uid);
            }
            $this->rc->lineup = $lineup;

            $this->view = 'schedule/attendance';
            return $this;
        }
    }

    public function updateGameAttendance()
    {

        // force a json response
        $this->router->setVar('isAjax', 1);

        if (!$this->isLoggedIn) {
            return $this->setNextRoute('/login');
        } else {

            $__uid = $this->router->getVar('uid');
            $__rosterId = $this->cookies['id'];
            $__isGoing = $this->router->getVar('isGoing');

            $s = new Schedule();
            $result = $s->updateUserAttendance($__rosterId, $__uid, $__isGoing);
            $this->rc->success = $result;
            $this->rc->message = $result === true ? 'Game attendance successfully updated.' : $s->mysqlHelper->getError();

            $m = new MySQLHelper();
            $user = $m->getRosterById($__rosterId);

            $schedule = $s->getSchedule();
            $game = $s->getGameFromSchedule($schedule, $__uid);

            $m = new MailManager();
            $m->sendAttendanceUpdate($game, $user[0], $__isGoing);

            return $this;
        }

    }

    // helpers








}


