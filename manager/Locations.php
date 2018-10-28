<?php

class Locations {

    const TURF = "Artificial Turf";
    const GRASS = "Grass";
    const UNKNOWN = "Unknown Playing Surface, doh!";

    public $fields = [];
    public $list = [];

    function __construct()
    {
        array_push($this->fields, 'Rancho San Ramon Community Park');
        array_push($this->fields, 'Central Park (Iron Horse Middle School)');
        array_push($this->fields, 'Sycamore Valley Park');
        array_push($this->fields, 'Tiffany Roberts');
        array_push($this->fields, 'Osage Park');
        asort($this->fields);
    }

    public static function getGoogleMapLink($location){

        $ret = "";

        if( strpos($location, ' - ') !== FALSE){
            $arrLocation = explode(' - ', $location);
            $strLocation = $arrLocation[0];
            //$strFieldNumber = $arrLocation[1];
        }else{
            $strLocation = $location;
        }

        // $pattern = "/[^0-9]/";
        // $replace = "";
        // $fieldNumber = preg_replace($pattern, $replace, $strFieldNumber);

        switch($strLocation){

            case "Rancho San Ramon Community Park":
                $ret = 'https://www.google.com/maps/place/1998+Rancho+Park+Loop,+San+Ramon,+CA+94582/@37.7494621,-121.9204398,18.03z/data=!4m5!3m4!1s0x808fedc166b8ecef:0xb6b57821f441de68!8m2!3d37.7494627!4d-121.9195772';
//                $ret = 'https://www.google.com/maps?q=1998+Rancho+Park+Loop+San+Ramon,+CA+94582&entry=gmail&source=g';
                break;

            case "Central Park (Iron Horse Middle School)":
                $ret = "https://www.google.com/maps/place/Iron+Horse+Middle+School/@37.7700232,-121.9576733,17z/data=!4m5!3m4!1s0x0:0xd8bce8adfd05a9ad!8m2!3d37.770761!4d-121.957625";
                break;

            case "Sycamore Valley Park":
                $ret = "https://www.google.com/maps/place/Sycamore+Valley+Park/@37.8084441,-121.976787,15z/data=!4m12!1m6!3m5!1s0x0:0xd39cff602dda333a!2sSycamore+Valley+Park!8m2!3d37.807448!4d-121.946245!3m4!1s0x808ff3a9e0c65cd7:0xd39cff602dda333a!8m2!3d37.807448!4d-121.946245";
                break;

            case "Tiffany Roberts":
                $ret = "https://www.google.com/maps/place/Tiffany+Roberts+Soccer+Field/@37.7511449,-121.8988322,17z/data=!3m1!4b1!4m5!3m4!1s0x808fee773728cf37:0xba8113b22e369954!8m2!3d37.7511449!4d-121.8966382";
                break;

            case "Osage Park":
                $ret = "https://www.google.com/maps/place/Osage+Park/@37.8032171,-121.9865648,17z/data=!4m12!1m6!3m5!1s0x808ff33ae37d614b:0xc9e279e6d7e2708f!2sOsage+Park!8m2!3d37.803656!4d-121.978981!3m4!1s0x808ff33ae37d614b:0xc9e279e6d7e2708f!8m2!3d37.803656!4d-121.978981";
                break;

            default:
                $ret = "";

        }

        return $ret;

    }

    public static function getFieldSurface($location){

        $ret = "";

        $arrLocation = explode(' - ', $location);
        $strLocation = $arrLocation[0];
        $strFieldNumber = $arrLocation[1];

        $pattern = "/[^0-9]/";
        $replace = "";
        $fieldNumber = preg_replace($pattern, $replace, $strFieldNumber);

        switch($strLocation){

            case "Rancho San Ramon Community Park":
                $ret = self::TURF;
                break;

            case "Central Park (Iron Horse Middle School)":
                $ret = self::GRASS;
                break;

            case "Sycamore Valley Park":

                switch($fieldNumber){
                    case 1:
                        $ret = self::GRASS;
                        break;

                    case 2:
                    case 3:
                        $ret = self::TURF;
                        break;
                    default:
                        break;
                }

                break;

            case "Tiffany Roberts":
                $ret = self::TURF;
                break;

            case "Osage Park":
                $ret = self::GRASS;
                break;

            default:
                $ret = self::UNKNOWN;

        }

        return $ret;
    }

    public static function getFieldSurfaceIcon($surface){
        return strpos($surface, 'Grass') !== FALSE ? 'grass' : 'turf';
    }

    public function getFields(){
        return $this->fields;
    }

}