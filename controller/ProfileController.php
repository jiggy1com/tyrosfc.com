<?php

class ProfileController extends ApplicationController {

    function __construct()
    {
        parent::__construct();
    }

    public function index(){
        $this->view = 'profile/index';
        return $this;
    }

    public function edit(){

        if (!$this->isLoggedIn){
            return $this->setNextRoute('/login');
        }else{
            $this->view = 'profile/edit';
            return $this;
        }

    }

}