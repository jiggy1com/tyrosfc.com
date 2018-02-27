<?php

class ErrorController extends ApplicationController {

    function __construct()
    {
        parent::__construct();
    }

    public function error404(){
        $this->view = 'error/404';
        return $this;
    }

}