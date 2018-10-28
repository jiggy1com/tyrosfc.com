<?php

class GalleryController extends ApplicationController {

    function __construct()
    {
        parent::__construct();
    }

    public function index(){
        if (!$this->isLoggedIn) {
            $this->setRedirectToSelf();
            return $this->setNextRoute('/login');
        } else {

            $m = new GalleryManager();
            $this->rc->galleryList = $m->listGalleries();

            $this->view = 'gallery/index';
            return $this;
        }
    }

    public function gallery(){
        if (!$this->isLoggedIn) {
            $this->setRedirectToSelf();
            return $this->setNextRoute('/login');
        } else {

            $m = new GalleryManager();
            $this->rc->galleryList = $m->listImages( $this->router->getParam('gallery') );

            $this->view = 'gallery/list';
            return $this;
        }
    }

}