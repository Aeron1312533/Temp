<?php

class HomeController extends Strateg_Controller_Action {

    public function init() {
        /* Initialize action controller here */  
                //add view
    }
    
    public function indexAction() {
        $this->_helper->redirector('list', 'problem', 'default');
        return;
    }
}

