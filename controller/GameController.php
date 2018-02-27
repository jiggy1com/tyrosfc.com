<?php

class GameController extends ApplicationController {

    function __construct()
    {
        parent::__construct();
    }

    public function index(){

        if(!$this->isLoggedIn){
            return $this->setNextRoute('/login');
        }else{
            $this->nextRoute = '/game/test';
            $this->router->setParam('game', 'index');
            return $this;
        }

    }

    public function test(){
        $this->rc->test = 1;
        $this->router->setParam('game', 'index');
        $this->view = 'game/test';
        return $this;
    }

}

