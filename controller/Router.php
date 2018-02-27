<?php

class Router {

    // list of all available routes, filled by Routes.php
    public $routes = [];

    // the route[x] string that matched the requested route
    public $routeMatch = '';

    // the requested uri
    public $uri = '';

    // route parameters
    public $params = [];

    // query string parameters
    public $getVars = [];

    // post parameters
    public $postVars = [];

    // both get and post vars, get
    public $vars = [];


    function __construct()
    {
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->setPostVars();
        $this->setGetVars();
    }

    public function setRoutes($routes){
        $this->routes = $routes;
        return $this;
    }

    public function getRoute($uri){

        $arrUri = explode('/', $uri);
        $arrUriLen = count($arrUri);

        $arrReturn = [];

        foreach ($this->routes as $route => $controllerMethod){

//            echo "route: " . var_dump($route);
//            echo "controllerMethod: " . var_dump($controllerMethod);
//            echo "<hr>";

            $dynamicRoute = strpos($route, ':');

            if( $dynamicRoute !== false){

                // param match
                $arrRoute = explode('/', $route);
                $arrRouteLen = count($arrRoute);

                if($arrRouteLen === $arrUriLen){

                    $buildRouteRegEx = '';
                    $cnt = 0;
                    foreach($arrRoute as $routePart){
                        $cnt++;

                        // match the array elements

                        $buildRouteRegEx .= (strpos($routePart, ':') === false ?  $routePart : '[a-zA-Z0-9-]+');

                        if($arrRouteLen > $cnt){
                            $buildRouteRegEx .= '\/';
                        }
                    }

                    $regex = $buildRouteRegEx;
                    $pregMatch = preg_match("/$regex/", $uri);
                    if($pregMatch){
                        $this->setRouteMatch($route);
                        $arrReturn = $controllerMethod;
                        break;
                    }
                }

            }else{
                // exact match
                if( $route === $uri){
                    $this->setRouteMatch($route);
                    $arrReturn = $controllerMethod;
                    break;
                }
            }

        }

        if($arrReturn === []){
            $arrReturn = ['ErrorController', 'error404'];
        }

        return $arrReturn;
    }

    private function setRouteMatch($routeMatch){
        $this->routeMatch = $routeMatch;
        $this->setParams();
    }

    // public so app can set params if necessary
    public function setParam($param, $value){
        $point = strpos($value, '?');
        if($point !== false){
            $value = substr($value, 0, $point);
        }
        $this->params[$param] = $value;
    }

    public function getParam($param){
        return ( isset($this->params[$param]) ) ? $this->params[$param] : null;
    }

    private function setParams(){
        $routeMatchArray = explode('/', $this->routeMatch);
        $uriArray = explode('/', $this->uri);
        foreach($routeMatchArray as $key => $param){
            if(strpos($param, ':') !== false){
                $param = substr($param, 1);
                $this->setParam($param, $uriArray[$key]);
//                $this->params[$param] = $uriArray[$key];
            }
        }
    }

    private function setPostVars(){
        foreach($_POST as $key => $value){
            $this->setVar($key, $value);
            $this->postVars[$key] = $value;
        }
    }

    private function setGetVars(){
        foreach($_GET as $key => $value){
            $this->setVar($key, $value);
            $this->getVars[$key] = $value;
        }
    }

    public function getVar($var){
        return isset( $this->vars[$var] ) ? $this->vars[$var] : null;
    }

    public function setVar($var, $value){
        $this->vars[$var] = $value;
    }





}