<?php

class ForgotPasswordController extends ApplicationController {

    function __construct()
    {
        parent::__construct();
    }

    public function index(){
        $this->view = 'forgot-password/index';
        return $this;
    }

    public function doForgotPassword(){

        // force a json response
        $this->router->setVar('isAjax', 1);

        // query
        $email = $this->router->getVar('email');
        $this->rc->results = $this->mysql->setQuery("select email from roster where email = '$email'")->runQuery();

        // handle the login
        $this->rc->success = true;
        $this->rc->message = 'If your email address was found you will receive an email shortly. This is not actually sending an email yet ... update this message after the email is working!';

        return $this;
    }

}

