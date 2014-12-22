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
        $type = $this->getParam('type', 'sp');
        if ($type == 'sp') {
            $form = new Application_Form_Problem_AddSP();
        }
        else {
            $form = new Application_Form_Problem_AddOP();        
        }
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
                $flashMessenger->addMessage('Problém pridaný.', null, Strateg_MyFlashMessenger_Message::SUCCESS);
                $this->_helper->redirector('list');
            } else {
                $form->populate($formData);
            }
        }
    }

    public function editAction()
    {
        $type = $this->getParam('type', 'sp');
        if ($type == 'sp') {
            $form = new Application_Form_Problem_EditSP();
        }
        else {
            $form = new Application_Form_Problem_EditOP();            
        }        
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
                $flashMessenger->addMessage('Problém uložený.', null, Strateg_MyFlashMessenger_Message::SUCCESS);
                $this->_helper->redirector('list');
            } else {
                $form->populate($formData);
            }
        } else { //zobrazujeme
            $id = $this->getParam('id', 0);
            if ($id > 0) {
                $problem = new Application_Model_DbTable_Problem();
                $problem_array = $problem->getProblem($id);
                $form->populate($problem_array);
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
                $pa_vazby = new Application_Model_DbTable_ProblemAnalysis();
                $pa_vazby->deletePAbyProblem($id);
                $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
                $flashMessenger->addMessage('Problém vymazaný.', null, Strateg_MyFlashMessenger_Message::SUCCESS);
            }
            
            $this->_helper->redirector('list');
        } else { //zobrazujeme
            $id = $this->_getParam('id', 0);
            if ($id > 0) {
                $problem = new Application_Model_DbTable_Problem();
                $form->populate($problem->getProblem($id));
            }
            $form->showTheRest();
        }
    }

}

