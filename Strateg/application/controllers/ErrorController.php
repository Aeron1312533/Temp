<?php

class ErrorController extends Zend_Controller_Action {
    protected $errors;
    
    public function init() {
        $this->errors = $this->_getParam('error_handler');
    }
    
    public function errorAction() {      
        $saveError = true;
        
        //if error action was called without error
        if (!$this->errors  || !$this->errors  instanceof ArrayObject) {
            $this->view->message = 'You have reached the error page';
            return;
        }
        
        switch ($this->errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found - invalid URL
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page not found';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Application error';
                break;
        }
        
        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $this->errors->exception;
        }
        
        $this->view->request   = $this->errors->request;
    }

}

