<?php

class LogoutController extends ApplicationController {

    function __construct()
    {
        parent::__construct();
    }

    public function index(){
        setcookie('isLoggedIn', '', time()-3600);
        setcookie('isAdmin', '', time()-3600);
        setcookie('email', '', time()-3600);
        setcookie('id', '',time()-3600);
        setcookie('data', '', time()-3600);
        setcookie('rememberMe', '', time()-3600);
//        return $this->setNextRoute('/');
        header('Location: /');
    }


}

