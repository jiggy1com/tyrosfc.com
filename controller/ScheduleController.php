<?php

class ScheduleController extends ApplicationController {

    function __construct()
    {
        parent::__construct();
    }

    public function index(){
        $this->rc->test = true;
        if( !$this->isLoggedIn ){
            return $this->setNextRoute('/login');
        }else{
            $this->view = 'schedule/index';
            return $this;
        }
    }

    public function test(){
        $this->rc->test = true;
        $this->view = 'schedule/test';
        $this->router->setParam("addparam", "test");
        return $this;
    }

    public function getScheduleById(){
        $this->router->setParam("addparam", "test");
        $this->router->setParam("thisrouter", true);
        $this->view = 'schedule/id';
        return $this;
    }

    public function test1(){
        $this->view = 'schedule/test1';
        return $this;
    }

    public function test2(){
        $this->view = 'schedule/test2';
        return $this;
    }

}

//WEEK 1
//Sun 3/4	9:00 AM	Spirits (SR)	Tyros	Central Park 4

//WEEK 2
//Sun 3/11	9:00 AM	Tyros	Clockwork Orange	Sycamore 3

//WEEK 3
//Sun 3/18	9:00 AM	Inferno	Tyros	Sycamore 3

//WEEK 4
//Sun 3/25	11:00 AM	Renegades (SR)	Tyros	Central Park 4

//WEEK 5
//EASTER

//WEEK 6
//Sun 4/8	11:00 AM	FC Rehab	Tyros	Sycamore 3

//WEEK 7
//Sun 4/15	11:00 AM	Tyros	Inferno	Osage 3

//WEEK 8
//Sun 4/22	9:00 AM	Spirits (SR)	Tyros	Tiffany Roberts

//WEEK 9
//Sun 4/29	9:00 AM	Tyros	Tiburones (SR)	Sycamore 3

//WEEK 10
//Sun 5/6	9:00 AM	Sidekicks	Tyros	Osage 3

//WEEK 11
//MOTHER'S DAY

//WEEK 12
//Sun 5/20	9:00 AM	Renegades (SR)	Tyros	Central Park 4

//WEEK 13
//MEMORIAL DAY WEEKEND

//WEEK 14
//Sun 6/3	9:00 AM	Clockwork Orange	Tyros	Osage 3

//WEEK 15
//Sun 6/10	11:00 AM	Tyros	Sidekicks	Osage 3
