<?php

class ApplicationController {

    private $salt = 'velez';
    public $referrer;
    public $cookies;
    public $session;

    private $cnt = 0;

    public $routes;
    public $router;
    public $currentRoute;
    public $controller;
    public $controllerName;
    public $methodName;

    public $mysql;

    public $isLoggedIn = false;
    public $layout = 'default';
    public $view = '';
    public $nextRoute = '';
    public $redirect;

    public $rc = null;

    function __construct()
    {
        $this->referrer = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '';
        $this->isLoggedIn = isset( $_COOKIE['isLoggedIn'] ) && $_COOKIE['isLoggedIn'] === 'true' ? true : false;
        $this->cookies = $_COOKIE;
        $this->session = $_SESSION;

        $this->routes = new Routes();
        $this->router = new Router();
        $this->router->setRoutes($this->routes->getRoutes());

        $this->mysql = new MySQL();
        $this->rc = new RC();

        if($this->isLoggedIn && $_SERVER['REQUEST_URI'] !== '/logout'){
            $this->extendCookieTime();
        }
    }

    public function setNextRoute($nextRoute)
    {
        $this->currentRoute = $this->router->getRoute($nextRoute);
        return $this->runRoute();
    }

    public function runBefore(){

    }

    public function runAfter(){
        if($this->router->getVar('isAjax')){
            $this->layout = 'ajax';
        }else if($this->layout == ''){
            $this->layout = 'default';
        }
    }

    private function iterateControllerParams($params){
        foreach($params as $param => $value){
            $this->router->setParam($param, $value);
        }
    }

    public function runRoute()
    {

        $this->cnt++;

        // set the selected controller and method based on the route
        $this->controllerName    = $this->currentRoute[0];
        $this->methodName        = $this->currentRoute[1];

        // call the controller method
//        $this->controller = new $this->controllerName($this);
//        $c = $this->controller->{$this->methodName}($this);


        $this->controller = new $this->controllerName();
        $this->controller->router = $this->router;
        $c = $this->controller->{$this->methodName}();
//        $this->router = $c->router;

        // copy shit from new Controller to this

        $this->nextRoute = $c->nextRoute;


        if($this->cnt > 3){
            die();
        }


        if($this->nextRoute !== ''){

            return $this->setNextRoute($this->nextRoute);
        }else{

//            $this->router = $c->router;
            $this->redirect = $c->redirect === '' || $c->redirect === null ? $_SERVER['REQUEST_URI'] : $c->redirect;
            $this->layout = $c->layout;
            $this->view = $c->view;
            $this->rc = $c->rc; // where to store controller data outside of params, getVars, postVars
            //$this->iterateControllerParams($c->router->params);
            $this->runAfter();
            return $this;
        }

    }

    public function setRedirectToSelf(){
        $this->redirect = $_SERVER['REQUEST_URI'];
        return $this;
    }

    public function setRedirect($redirect){
        $this->redirect = $redirect;
        return $this;
    }

    public function getRedirect(){
        return $this->redirect;
    }



    public function setCookies($arrayOfCookies){

    }

    public function setCookie($cookieName, $cookieValue){

    }

    public function extendCookieTime(){
        if(!isset($this->cookies['rememberMe'])){
            foreach($this->cookies as $cookieName => $cookieValue){
                $cookieDuration = 60 * 60;
                setcookie($cookieName, $cookieValue, time() + $cookieDuration, '/');
            }
        }
    }


}