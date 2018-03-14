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

            $s = new Schedule();
            $this->rc->schedule = $s->getSchedule();

            $query = "select * from attendance where rosterId = " . $this->cookies['id'];
            $this->rc->attendance = $this->mysql->setQuery($query)->runRead();

            $this->view = 'schedule/index';
            return $this;
        }
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


