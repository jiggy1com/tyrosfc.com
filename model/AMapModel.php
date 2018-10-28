<?php

class AMapModel {

    const MAP_IFRAME_PREFIX = 'https://www.google.com/maps/embed/v1/view?center=';
    const MAP_API_KEY = 'AIzaSyBXnbVhEItuMDjbPsVa-OU1lRy3PFLe_Lk';
    const MAP_ZOOM = 15;

    public $locationName;
    public $address;
    public $mapLink;

    // set from mapLink
    public $lat;
    public $lon;

    // set last
    public $iFrameSrc;


    function __construct()
    {
        $this->setLatLon();
        $this->setIframeSrc();
    }

    private function setIframeSrc(){
        $this->iFrameSrc = self::MAP_IFRAME_PREFIX .
            $this->lat . ',' . $this->lon .
            '&zoom=' . self::MAP_ZOOM .
            '&key=' . self::MAP_API_KEY;
    }

    private function setLatLon(){
        $arr = explode('@', $this->mapLink);
        $arr2 = explode(',', $arr[1]);
        $this->lat = $arr2[0];
        $this->lon = $arr2[1];
    }

}