<?php

class GalleryManager {

    function __construct()
    {

    }

    public function listGalleries(){
        $arr = [];
        $galleryDir = getcwd() . '/img/gallery/';
        $list = scandir($galleryDir);
        foreach($list as $folder){
            if( is_dir($galleryDir . $folder) && $folder !== '.' && $folder !== '..'){
                $arr[] = $folder;
            }
        }
        return $arr;
    }

    public function listImages($folder){
        $cnt = 0;
        $arr = [];
        $galleryDir = getcwd() . '/img/gallery/' . $folder;
        $list = scandir($galleryDir);
        foreach($list as $file){
            if( $file !== '.' && $file !== '..'){
                $cnt++;
                $arr[] = $file;
//                if($cnt == 20){
//                    break;
//                }
            }
        }
        return $arr;
    }

}