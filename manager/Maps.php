<?php

class Maps {

    public $list = [];

    function __construct()
    {
        $this->buildList();
//        asort($this->list);

    }

    private function buildList(){

        $centralPark = new CentralParkIronHorseMiddleSchool();
        array_push($this->list, $centralPark);

        $osagePark = new OsagePark();
        array_push($this->list, $osagePark);

        $ranchoSanRamon = new RanchoSanRamonCommunityPark();
        array_push($this->list, $ranchoSanRamon);

        $sycamoreValley = new SycamoreValleyPark();
        array_push($this->list, $sycamoreValley);

        $tiffanyRoberts = new TiffanyRoberts();
        array_push($this->list, $tiffanyRoberts);

    }

}