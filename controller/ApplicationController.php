<?php

class ApplicationController {

    private $salt = 'velez';

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

    public $rc = null;

    function __construct()
    {
        $this->isLoggedIn = isset( $_COOKIE['isLoggedIn'] ) && $_COOKIE['isLoggedIn'] === 'true' ? true : false;

        $this->routes = new Routes();
        $this->router = new Router();
        $this->router->setRoutes($this->routes->getRoutes());

        $this->mysql = new MySQL();
        $this->rc = new RC();
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
        }else{
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
        $c = $this->controller->{$this->methodName}();


        // copy shit from new Controller to this

        $this->nextRoute = $c->nextRoute;


        if($this->cnt > 3){
            die();
        }


        if($this->nextRoute !== ''){

            return $this->setNextRoute($this->nextRoute);
        }else{

//            $this->isLoggedIn = $c->isLoggedIn;
            $this->router = $c->router;
            $this->layout = $c->layout;
            $this->view = $c->view;
            $this->rc = $c->rc; // where to store controller data outside of params, getVars, postVars
            $this->iterateControllerParams($c->router->params);
            $this->runAfter();
            return $this;
        }

    }

}