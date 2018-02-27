<?php

class HomeController extends ApplicationController {

    function __construct()
    {
        parent::__construct();
    }

    public function index(){
        $this->view = 'home/index';
        return $this;
    }

}