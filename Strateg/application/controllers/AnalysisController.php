<?php

class AnalysisController extends Strateg_Controller_Action
{

public function init() {
        parent::init();
    }
    
    public function indexAction() {
        $this->_helper->redirector('list');
    }
    
    public function listAction() {
        $analyses = new Application_Model_DbTable_Analysis();
        $this->view->analyses = $analyses->fetchAll();
        
        
    }
    
    public function addAction() {
        $form = new Application_Form_Analysis_Add();
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
                $analysis = new Application_Model_DbTable_Analysis();
                $analysis->addAnalysis($form->getValues());
                $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
                $flashMessenger->addMessage('Analyza pridana', null, Strateg_MyFlashMessenger_Message::SUCCESS);
                $this->_helper->redirector('list');
            } else {
                $form->populate($formData);
            }
        }
    }

    public function editAction()
    {
        $form = new Application_Form_Analysis_Edit();
        
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
                $analysis = new Application_Model_DbTable_Analysis();
                $analysis->updateAnalysis($id, $form->getValues());
                $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
                $flashMessenger->addMessage('Analyza ulozena', null, Strateg_MyFlashMessenger_Message::SUCCESS);
                $this->_helper->redirector('list');
            } else {
                $form->populate($formData);
            }
        } else { //zobrazujeme
            $id = $this->getParam('id', 0);
            if ($id > 0) {
                $analysis = new Application_Model_DbTable_Analysis();
                $form->populate($analysis->getAnalysis($id));
            }
        }
    }

    public function deleteAction()
    {
        $form = new Application_Form_Analysis_Delete();
        $this->view->form = $form;
        
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            
            if(isset($formData["nie"])) {
                $this->_helper->redirector('list');
            }
            
            if ($form->isValid($formData)) {
                $id = (int)$form->getValue('id');
                $analysis = new Application_Model_DbTable_Analysis();
                $analysis->deleteAnalysis($id);
                $pa_vazby = new Application_Model_DbTable_ProblemAnalysis();
                $pa_vazby->deletePAbyAnalysis($id);
                $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
                $flashMessenger->addMessage('Analyza vymazana.', null, Strateg_MyFlashMessenger_Message::SUCCESS);
            }
            
            $this->_helper->redirector('list');
        } else { //zobrazujeme
            $id = $this->_getParam('id', 0);
            if ($id > 0) {
                $analysis = new Application_Model_DbTable_Analysis();
                $form->populate($analysis->getAnalysis($id));
            }
        }
    }

}

