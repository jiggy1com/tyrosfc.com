<?php

class ProfileController extends ApplicationController {

    function __construct()
    {
        parent::__construct();
    }

    public function index(){
        $this->view = 'profile/index';
        return $this;
    }

    public function edit(){

        if (!$this->isLoggedIn){
            return $this->setNextRoute('/login');
        }else{
            $query = "select * from roster where id = " . $this->cookies['id'];
            $this->rc->results = $this->mysql->setQuery($query)->runRead();
            $this->view = 'profile/edit';
            return $this;
        }

    }

    public function update(){

        if (!$this->isLoggedIn){
            return $this->setNextRoute('/login');
        }else{

            // force a json response
            $this->router->setVar('isAjax', 1);

            // validation
            $isValid = true;
            $__email = $this->router->getVar('email') !== null ? $this->router->getVar('email') : '';

            if($isValid){
                if($__email === ''){
                    $isValid = false;
                    $this->rc->success = false;
                    $this->rc->message = 'Your email address is required.';
                }
            }

            // update if valid
            if($isValid){
                $set = "";
                $totalPostVars = count($this->router->postVars);
                $cnt = 0;
                foreach($this->router->postVars as $key => $value){
                    $cnt++;

                    // convert phone number to just numbers
                    if($key ==='phone'){
                        $value = preg_replace('/[^0-9]/', '', $value);
                    }

                    $set .= " $key = '$value' ";
                    if($cnt < $totalPostVars){
                        $set .= ", ";
                    }
                }

                $query = "update roster set " . $set . " where id = " . $this->cookies['id'];
                $result = $this->mysql->setQuery($query)->runUpdate();

                if($result === true){
                    $this->rc->success = true;
                    $this->rc->message = 'Profile successfully updated.';
                }else{
                    $this->rc->success = false;
                    $this->rc->message = $this->mysql->getError();
                }
            }

            return $this;
        }
    }

    public function updatePassword(){
        if (!$this->isLoggedIn){
            return $this->setNextRoute('/login');
        }else{

            // force a json response
            $this->router->setVar('isAjax', 1);

            // validation
            $isValid = true;
            $query = "select * from roster where id = " . $this->cookies['id'];
            $result = $this->mysql->setQuery($query)->runRead();

            $__p = $this->router->getVar('currentPassword');
            $__np = $this->router->getVar('newPassword');
            $__cp = $this->router->getVar('confirmNewPassword');

            if( count($result) == 1){

                $user = $result[0];

                if($isValid && ($__p === null || $__np === null || $__cp === null)){
                    $isValid = false;
                    $this->rc->message = "Missing fields.";
                }

                if($isValid && $__p === ''){
                    $isValid = false;
                    $this->rc->message = "You did not enter your current password.";
                }

                if($isValid && $__np === ''){
                    $isValid = false;
                    $this->rc->message = "You did not enter a new password.";
                }

                if( $isValid && strlen($__np) < 6){
                    $isValid = false;
                    $this->rc->message = "Please use a password at least 6 characters long.";
                }

                if($isValid && $__cp === ''){
                    $isValid = false;
                    $this->rc->message = "You did not enter the confirmation password.";
                }

                if($isValid && $__np !== '' && $__cp !== '' && $__np !== $__cp){
                    $isValid = false;
                    $this->rc->message = "New password and confirm password did not match.";
                }

                if($isValid && $user->password !== $__p){
                    $isValid = false;
                    $this->rc->message = "The supplied current password does not match the password on file.";
                }

            }else{
                $isValid = false;
                $this->rc->message = "Are you sure you're logged in? Please report this issue.";
            }

            // update the password
            if($isValid){

                $query = "update roster set password = '$__np' where id = " . $this->cookies['id'];
                $result = $this->mysql->setQuery($query)->runUpdate();

                if($result === true){
                    $this->rc->success = true;
                    $this->rc->message = 'Password successfully updated.';
                }else{
                    $this->rc->success = false;
                    $this->rc->message = $this->mysql->getError();
                }
            }

            return $this;
        }
    }

}