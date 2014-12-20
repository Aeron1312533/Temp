<?php


class Strateg_Controller_Action extends Zend_Controller_Action {
    
    public function _construct() {
        parent::__construct();
        
    }
    
    public function preDispatch() {
        //get request information
        $resource = $this->getRequest()->getControllerName ();
        $action = $this->getRequest()->getActionName ();

        //check permissions
        $acl = Zend_Registry::get('acl');
        if (!$acl->isAllowed(Zend_Registry::get('role'), $resource, $action)){
            if (Zend_Auth::getInstance()->hasIdentity()) {
                $this->_forward("index", "home");
            } else {
                $this->_forward("login", "user");
            }
        }
        
        //init navigation
        $this->view->navigation_main = Zend_Registry::get('navigation-main');
    }
    
    public function init() {
        parent::init();
        
        $messages = $this->_helper->flashMessenger->getMessages();
        
        if (!empty($messages)) {
            $this->_helper->layout->getView()->messages = $messages;
        }
    }
    
}