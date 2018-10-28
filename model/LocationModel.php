<?php /* @deprecated in favor of AMapModel */

class LocationModel {

    public $location;
    public $locationMap;
    public $locationEmbeddedMap;
    public $locationIframeSrc;

    function __construct($location)
    {
        $this->location = $location;
        $this->locationMap = Locations::getGoogleMapLink($this->location);
        $this->locationEmbeddedMap = $this->getEmbeddedMap($this->locationMap);
        $this->locationIframeSrc = $this->getIframeMap($this->location);
    }

    public function getIframeMap($location){
        return '';
    }

    private function getEmbeddedMap($map){

        $search = 'https://www.google.com/maps?q=';
        $replace = 'https://www.google.com/maps/embed/v1/view?center=';
        $subject = $map;

        $arr = explode('@', $map);
        $arr2 = explode(',', $arr[1]);
        $lat = $arr2[0];
        $lon = $arr2[1];

//        $link = str_replace($search, $replace, $subject);

        $link = $replace . $lat . ',' . $lon;

        $link .= '&zoom=15&key=AIzaSyBXnbVhEItuMDjbPsVa-OU1lRy3PFLe_Lk';
        return $link;
    }

    // https://www.google.com/maps/embed/v1/view?center=

}