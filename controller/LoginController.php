<?php

class LoginController extends ApplicationController {

    function __construct()
    {
        parent::__construct();
    }

    public function index(){
        if($this->isLoggedIn){
            header('Location: /');
        }
        $this->rc->time = time();
        $this->view = 'login/index';
        return $this;
    }

    public function doLogin(){

        // force a json response
        $this->router->setVar('isAjax', 1);

        // query
        $email = $this->router->getVar('email');
        $password = $this->router->getVar('password');
        $this->rc->results = $this->mysql->setQuery("select id, isAdmin, email from roster where email = '$email' and password = '$password' and isActive = 1")->runQuery();

        // remember me
        $__rememberMe = $this->router->getVar('rememberMe');

        $this->isLoggedIn = count($this->rc->results) > 0;
        if($this->isLoggedIn){

            // 1 day vs 1 year =)
            $cookieDuration = $__rememberMe == 1
                ? (60 * 60 * 24 * 30 * 3) // an hour
                : (60 * 60); // approx 3 months
            //$cookieDuration = 60 * 60;

            setcookie('isLoggedIn', 'true', time() + $cookieDuration, '/');
            setcookie('id', $this->rc->results[0]->id, time() + $cookieDuration, '/');
            setcookie('isAdmin', $this->rc->results[0]->isAdmin, time() + $cookieDuration, '/');
            setcookie('email', $this->rc->results[0]->email, time() + $cookieDuration, '/');
            setcookie('data', serialize($this->rc->results[0]), time() + $cookieDuration, '/');
            if($__rememberMe == 1){
                setcookie('rememberMe', '1', time()+$cookieDuration, '/');
            }

            // TODO: hash the password and stuff
            // setcookie('token', hash('sha1', $this->rc->results[0]->password));
        }

        // handle the login
        $this->rc->success = $this->isLoggedIn;
        $this->rc->message = $this->rc->success ? 'You have successfully logged in. One sec...' : 'Login and/or password is incorrect.';

        return $this;
    }

}

