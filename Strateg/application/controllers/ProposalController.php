<?php

class ProposalController extends Strateg_Controller_Action
{

    public function init() {
        parent::init();
    }
    
    public function indexAction() {
        $this->_helper->redirector('list');
    }
    
    public function listAction() {
        $proposals = new Application_Model_DbTable_Proposal();
        $this->view->proposals = $proposals->fetchAll();
        
        
    }
    
    public function addAction() {
        $form = new Application_Form_Proposal_Add();
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
                $proposal = new Application_Model_DbTable_Proposal();
                $proposal->addProposal($form->getValues());
                $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
                $flashMessenger->addMessage('Navrh pridany', null, Strateg_MyFlashMessenger_Message::SUCCESS);
                $this->_helper->redirector('list');
            } else {
                $form->populate($formData);
            }
        }
    }

    public function editAction()
    {
        $form = new Application_Form_Proposal_Edit();
        
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
                $proposal = new Application_Model_DbTable_Proposal();
                $proposal->updateProposal($id, $form->getValues());
                $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
                $flashMessenger->addMessage('Navrh ulozeny', null, Strateg_MyFlashMessenger_Message::SUCCESS);
                $this->_helper->redirector('list');
            } else {
                $form->populate($formData);
            }
        } else { //zobrazujeme
            $id = $this->getParam('id', 0);
            if ($id > 0) {
                $proposal = new Application_Model_DbTable_Proposal();
                $form->populate($proposal->getProposal($id));
            }
        }
    }

    public function deleteAction()
    {
        $form = new Application_Form_Proposal_Delete();
        $this->view->form = $form;
        
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            
            if(isset($formData["nie"])) {
                $this->_helper->redirector('list');
            }
            
            if ($form->isValid($formData)) {
                $id = (int)$form->getValue('id');
                $proposal = new Application_Model_DbTable_Proposal();
                $proposal->deleteProposal($id);
                $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
                $flashMessenger->addMessage('Navrh vymazany', null, Strateg_MyFlashMessenger_Message::SUCCESS);
            }
            
            $this->_helper->redirector('list');
        } else { //zobrazujeme
            $id = $this->_getParam('id', 0);
            if ($id > 0) {
                $proposal = new Application_Model_DbTable_Proposal();
                $form->populate($proposal->getProposal($id));
            }
        }
    }

}

