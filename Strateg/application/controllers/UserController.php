<?php

class UserController extends Strateg_Controller_Action
{

    public function init() {
        /* Initialize action controller here */  
                //add view
    }
    
    public function indexAction() {
        $this->_helper->redirector('login');
    }
    
    public function loginAction() {
       $form = new Application_Form_User_Login();
       $this->view->form = $form;  
       
       $layout = $this->_helper->layout();
       $layout->setLayout('layout_login');
        
              
       if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();            
            
            if ($form->isValid($formData)) {
             /*   if($this->_processAuth($formData)) {
                    $this->_helper->redirector('index', 'overview');
                } else {
                    Zend_Auth::getInstance()->clearIdentity();
                    $form->getElement('password')->addError('Incorrect login or password, try again !');
                }*/
                
                $this->redirect('/home/index');
            } else {
                $form->populate($formData);
            }
        }   
       
    }

    public function logoutAction() {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector('login');
    }
    
    /*protected function _getAuthAdapter() {
        $db = Zend_Db_Table::getDefaultAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable($db);

        $authAdapter->setTableName('user');
        $authAdapter->setIdentityColumn('email');
        $authAdapter->setCredentialColumn('password');
        $authAdapter->setCredentialTreatment('SHA1(CONCAT(?,password_salt))');

        return $authAdapter;
    }*/
    
   /* protected function _processAuth($formData) {
        $authAdapter = $this->_getAuthAdapter();       
        $authAdapter->setIdentity($formData['username']);
        $authAdapter->setCredential($formData['password']);
        
        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($authAdapter);
        
        if ($result->isValid()) {
            $user = $authAdapter->getResultRowObject();
            $auth->getStorage()->write($user);
            return true;
        }
        
        return false;
    }*/

}

