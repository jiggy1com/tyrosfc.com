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
        $this->routes['/schedule/game/:uid/attendance'] = ['ScheduleController', 'gameAttendance'];
        $this->routes['/schedule/game/update-game-attendance'] = ['ScheduleController', 'updateGameAttendance'];
        //$this->routes['/schedule/test'] = ['ScheduleController', 'test'];
        //$this->routes['/schedule/:id'] = ['ScheduleController', 'getScheduleById'];

        // games
//        $this->routes['/game'] = ['GameController', 'index'];
//        $this->routes['/game/test'] = ['GameController', 'test'];
//        $this->routes['/game/:id'] = ['GameController', 'index'];

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
        $this->routes['/profile/update'] = ['ProfileController', 'update'];
        $this->routes['/profile/updatePassword'] = ['ProfileController', 'updatePassword'];

        // admin
        $this->routes['/admin'] = ['ZAdminController', 'index'];
        $this->routes['/admin/roster'] = ['ZAdminController', 'roster'];
        $this->routes['/admin/roster/sms/:id'] = ['ZAdminController', 'rosterSMS'];
        $this->routes['/admin/roster/email/:id'] = ['ZAdminController', 'rosterEmail'];

        $this->routes['/admin/schedule'] = ['ZAdminController', 'schedule'];
        $this->routes['/admin/schedule/game/:uid/attendance'] = ['ZAdminController', 'game'];
        $this->routes['/admin/schedule/game/:uid/attendance/update'] = ['ZAdminController', 'gameUpdateAttendance'];
        $this->routes['/admin/schedule/game/:uid/attendance/remind/sms/:rosterId'] = ['ZAdminController', 'remindBySMS'];
        $this->routes['/admin/schedule/game/:uid/attendance/remind/email/:rosterId'] = ['ZAdminController', 'remindByEmail'];
        $this->routes['/admin/schedule/game/:uid/lineup'] = ['ZAdminController', 'lineup'];
        $this->routes['/admin/schedule/game/:uid/lineup/update'] = ['ZAdminController', 'updateLineup'];
        $this->routes['/admin/schedule/game/:uid/lineup/print'] = ['ZAdminController', 'printLineup'];



        $this->routes['/admin/email'] = ['ZAdminController', 'email'];
        $this->routes['/admin/email/send'] = ['ZAdminController', 'emailSend'];

        $this->routes['/admin/sms'] = ['ZAdminController', 'sms'];
        $this->routes['/admin/sms/send'] = ['ZAdminController', 'smsSend'];





        // admin proposed routes (need to implement)
        /*
        $this->routes['/admin/test'] = ['ZAdminController', 'test'];

        $this->routes['/admin/schedule/game/:uid'] = ['ZAdminController', 'game'];
        $this->routes['/admin/schedule/game/updateAttendance'] = ['ZAdminController', 'updateAttendance'];
        $this->routes['/admin/schedule/game/updateLineup'] = ['ZAdminController', 'updateLineup'];


        $this->routes['/admin/mail/send'] = ['ZAdminController', 'schedule'];
        $this->routes['/admin/mail/save'] = ['ZAdminController', 'schedule'];
        $this->routes['/admin/mail/update'] = ['ZAdminController', 'schedule'];
        $this->routes['/admin/mail/edit/:id'] = ['ZAdminController', 'schedule'];

        $this->routes['/admin/sms'] = ['ZAdminController', 'schedule'];
        $this->routes['/admin/sms/send'] = ['ZAdminController', 'schedule'];
        $this->routes['/admin/sms/save'] = ['ZAdminController', 'schedule'];
        $this->routes['/admin/sms/update'] = ['ZAdminController', 'schedule'];
        $this->routes['/admin/sms/edit/:id'] = ['ZAdminController', 'schedule'];

        $this->routes['/twilio/test'] = ['TwilioController', 'test'];
        */

        // testing router
//        $this->routes['/long/ass/route/:id'] = ['ScheduleController', 'test1'];
//        $this->routes['/long/:routeParam1/route/:routeParam2'] = ['ScheduleController', 'test2'];
    }

    public function getRoutes(){
        return $this->routes;
    }

}