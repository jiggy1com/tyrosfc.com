<?php

class ForgotPasswordController extends ApplicationController {

    function __construct()
    {
        parent::__construct();
    }

    public function index(){
        if($this->isLoggedIn){
            header('Location: /');
        }
        $this->view = 'forgot-password/index';
        return $this;
    }

    public function doForgotPassword(){

        // force a json response
        $this->router->setVar('isAjax', 1);

        // query
        $email = $this->router->getVar('email');
        $results = $this->mysql->setQuery("select email, password from roster where email = '$email'")->runQuery();

        // handle the login
        $this->rc->success = true;
        $this->rc->message = 'If your email address was found you will receive an email shortly.';

        // decide to send the forgot password email
        $found = count($results);

        if($found){
            $data = $results[0];
            $data->textMsg = 'Your password is: ' . $data->password;
            $data->htmlMsg = 'Your password is: ' . $data->password;
            $m = new MailManager();
            $resp = $m->sendForgotPassword($data);
            if($resp !== true){
                $this->rc->message = $resp;
            }
        }

        return $this;
    }

}

