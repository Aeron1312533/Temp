<?php

class ProblemController extends Strateg_Controller_Action
{

    public function init() {
        parent::init();
    }
    
    public function indexAction() {
        $this->_helper->redirector('list');
    }
    
    public function listAction() {
        $problems = new Application_Model_DbTable_Problem();
        $this->view->problems = $problems->fetchAll();
        
        
    }
    
    public function addAction() {
        $form = new Application_Form_Problem_Add();
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            
            /**
             * if back button was pressed
             */
            if(isset($formData["spat"])) {
                $this->_helper->redirector('list');
            }
            
            if ($form->isValid($formData)) {
                $problem = new Application_Model_DbTable_Problem();
                $problem->addProblem($form->getValues());
                $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
                $flashMessenger->addMessage('Problem pridany', null, Strateg_MyFlashMessenger_Message::SUCCESS);
                $this->_helper->redirector('list');
            } else {
                $form->populate($formData);
            }
        }
    }

    public function editAction()
    {
        $form = new Application_Form_Problem_Edit();
        
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            
            /**
             * if back button was pressed
             */
            if(isset($formData["spat"])) {
                $this->_helper->redirector('list');
            }
            
            if ($form->isValid($formData)) {
                $id = (int)$this->getParam('id');
                $problem = new Application_Model_DbTable_Problem();
                $problem->updateProblem($id, $form->getValues());
                $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
                $flashMessenger->addMessage('Problem ulozeny', null, Strateg_MyFlashMessenger_Message::SUCCESS);
                $this->_helper->redirector('list');
            } else {
                $form->populate($formData);
            }
        } else { //zobrazujeme
            $id = $this->getParam('id', 0);
            if ($id > 0) {
                $problem = new Application_Model_DbTable_Problem();
                $form->populate($problem->getProblem($id));
            }
        }
    }

    public function deleteAction()
    {
        $form = new Application_Form_Problem_Delete();
        $this->view->form = $form;
        
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            
            if(isset($formData["nie"])) {
                $this->_helper->redirector('list');
            }
            
            if ($form->isValid($formData)) {
                $id = (int)$form->getValue('id');
                $problem = new Application_Model_DbTable_Problem();
                $problem->deleteProblem($id);
                $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
                $flashMessenger->addMessage('Problem vymazany', null, Strateg_MyFlashMessenger_Message::SUCCESS);
            }
            
            $this->_helper->redirector('list');
        } else { //zobrazujeme
            $id = $this->_getParam('id', 0);
            if ($id > 0) {
                $problem = new Application_Model_DbTable_Problem();
                $form->populate($problem->getProblem($id));
            }
        }
    }

}

