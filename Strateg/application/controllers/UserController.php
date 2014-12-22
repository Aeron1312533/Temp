<?php

class UserController extends Strateg_Controller_Action
{    
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
                $user = new stdClass();
                $user->username = $formData['username'];
                $user->password = $formData['password'];
                
                if ($this->isAuthenticated($user)) {
                    $auth = Zend_Auth::getInstance();
                    $auth->getStorage()->write($user);
                    $this->redirect('/home/index');
                    return;
                }
              
                $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
                $flashMessenger->addMessage('Neplatne meno alebo heslo.',
                    null, Strateg_MyFlashMessenger_Message::DANGER);
                
                $this->_helper->redirector('login');
            } else {
                $form->populate($formData);
            }
        }   
       
    }

    public function logoutAction() {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector('login');
    }
    
    private function isAuthenticated(&$user) {
        $config = simplexml_load_file(APPLICATION_PATH . '/configs/users.xml');
        foreach ($config->user as $person) {                  
            if ($user->username == $person->username) {
                if ($person->password == $user->password) {
                    $user->role = (string) $person->role;
                    return true;
                } else {
                    return false;
                }
            }
        }
       
        return false;
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

