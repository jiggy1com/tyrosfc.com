<?php

class MapsController extends ApplicationController {

    function __construct()
    {
        parent::__construct();
    }

    public function index(){
        if (!$this->isLoggedIn) {
            $this->setRedirectToSelf();
            return $this->setNextRoute('/login');
        } else {

            $maps = new Maps();
            $this->rc->mapsList = $maps->list;

            $this->view = 'maps/index';
            return $this;
        }
    }

}