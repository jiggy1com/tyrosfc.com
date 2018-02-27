<?php

class Routes {

    public $routes = [];

    function __construct()
    {
        $this->setRoutes();
    }

    private function setRoutes(){

        // home page
        $this->routes['/'] = ['HomeController', 'index'];

        // schedule
        $this->routes['/schedule'] = ['ScheduleController', 'index'];
        $this->routes['/schedule/test'] = ['ScheduleController', 'test'];
        $this->routes['/schedule/:id'] = ['ScheduleController', 'getScheduleById'];

        // games
        $this->routes['/game'] = ['GameController', 'index'];
        $this->routes['/game/test'] = ['GameController', 'test'];
        $this->routes['/game/:id'] = ['GameController', 'index'];

        // login
        $this->routes['/login'] = ['LoginController', 'index'];
        $this->routes['/login/doLogin'] = ['LoginController', 'doLogin'];

        // logout
        $this->routes['/logout'] = ['LogoutController', 'index'];

        // forgot password
        $this->routes['/forgot-password'] = ['ForgotPasswordController', 'index'];
        $this->routes['/forgot-password/doForgotPassword'] = ['ForgotPasswordController', 'doForgotPassword'];

        // profile
        $this->routes['/profile'] = ['ProfileController', 'index'];
        $this->routes['/profile/edit'] = ['ProfileController', 'edit'];

        // testing router
        $this->routes['/long/ass/route/:id'] = ['ScheduleController', 'test1'];
        $this->routes['/long/:routeParam1/route/:routeParam2'] = ['ScheduleController', 'test2'];
    }

    public function getRoutes(){
        return $this->routes;
    }

}