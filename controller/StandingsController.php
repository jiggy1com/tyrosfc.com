<?php

class StandingsController extends ApplicationController
{

    function __construct()
    {
        parent::__construct();
    }

    public function index(){
        if (!$this->isLoggedIn) {
            $this->setRedirectToSelf();
            return $this->setNextRoute('/login');
        } else {
            $this->view = 'standings/index';
            return $this;
        }
    }

}