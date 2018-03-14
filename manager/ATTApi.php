<?php

class ATTApi {

    public $sms;

    private $http;

    function __construct()
    {
        $this->sms = new ATTSMS();

    }



}