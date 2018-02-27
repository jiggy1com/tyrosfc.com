<?php

class LoginController extends ApplicationController {

    function __construct()
    {
        parent::__construct();
    }

    public function index(){

//        $this->rc->results = $this->mysql->setQuery("select * from roster")->runQuery();

        $this->rc->test = 'login controller' ;
        $this->view = 'login/index';
        return $this;
    }

    public function doLogin(){

        // force a json response
        $this->router->setVar('isAjax', 1);

        // query
        $email = $this->router->getVar('email');
        $password = $this->router->getVar('password');
        $this->rc->results = $this->mysql->setQuery("select id, isAdmin, email from roster where email = '$email' and password = '$password'")->runQuery();

        $this->isLoggedIn = count($this->rc->results) > 0;
        if($this->isLoggedIn){
            setcookie('isLoggedIn', 'true', time() + 3600, '/');
            setcookie('isAdmin', $this->rc->results[0]->isAdmin, time() + 3600, '/');
            setcookie('email', $this->rc->results[0]->email, time() + 3600, '/');
            // TODO: hash the password and stuff
//            setcookie('token', hash('sha1', $this->rc->results[0]->password));
        }

        // handle the login
        $this->rc->success = $this->isLoggedIn;
        $this->rc->message = $this->rc->success ? 'You have successfully logged in. One sec...' : 'Login and/or password is incorrect.';

        return $this;
    }

}

