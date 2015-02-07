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
                if ($this->add()) {
                        $this->_helper->redirector('list');
                    }
                    else {
                        $this->_helper->redirector('add');
                    }
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
                if ($this->update()) {
                        $this->_helper->redirector('list');
                    }
                    else {
                        $this->_helper->redirector('edit','problem','default',array('id'=> $this->getParam('id'), 'type' => $type ));
                    }
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

    public function detailAction()
    {   
        $type = $this->getParam('type', 'sp');
        
        if ($type == 'sp') {
            $label = 'Detail subjektivneho problemu';
        }
        else {
            $label = 'Detail objektivneho problemu';
        }      
        
        //remove unnecessary elements
        $form = new Application_Form_Problem_Detail();        
        $form->addEditButton(Zend_Registry::get('role'));
            
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            
            //redirect to edit if needed
            if(isset($formData["edit_button"])) {
                $this->_helper->redirector('edit', 'problem', 'default', array('id' => $this->getParam('id'), 'type' => $type));
                return;
            }
            
            /**
             * if back button was pressed
             */
            if(isset($formData["spat"])) {
                $this->_helper->redirector('list');
            }
        } 
        else { //zobrazujeme
            $id = $this->getParam('id', 0);
            if ($id > 0) {
                $problem = new Application_Model_DbTable_Problem();
                $problem_array = $problem->getProblem($id);
                $this->view->problem = $problem_array;

                //analyzovane problemy
                $problemAnalysis = new Application_Model_DbTable_ProblemAnalysis();
                $rows = $problemAnalysis->getProblemAnalysis('id_problem = '. $id.' and vstup = 1');
                $analysis= new Application_Model_DbTable_Analysis();
                $APs = array();
                
                 foreach ($rows as $row) {
                    $analysisRow = $analysis->getAnalysis($row->id_analyza);                        
                    $APs[] = array(
                        'id' => $row->id_analyza,
                        'name' => $analysisRow['nazov'],
                        'popis' => $row->popis);
                }
                
                //vystupne problemy
                $rows = $problemAnalysis->getProblemAnalysis('id_problem = '. $id.' and vstup = 0');
                $analysis = new Application_Model_DbTable_Analysis();
                $OPs = array();
                
                foreach ($rows as $row) {
                    $analysisRow = $analysis->getAnalysis($row->id_analyza);                        
                    $OPs[] = array(
                        'id' => $row->id_analyza,
                        'name' => $analysisRow['nazov'],
                        'popis' => $row->popis);
                }
                
                //navrhy uplne
                $problemProposal = new Application_Model_DbTable_ProblemProposal();
                $rows = $problemProposal->getProblemProposal('id_problem = '. $id.' and uplne = 1');
                $proposal = new Application_Model_DbTable_Proposal();
                $FPs = array();
                
                foreach ($rows as $row) {
                    $proposalRow = $proposal->getProposal($row->id_navrh);                        
                    $FPs[] = array(
                        'id' => $row->id_navrh,
                        'name' => $proposalRow['nazov'],
                        'popis' => $row->popis);
                }
                
                //navrhy ciastocne
                $rows = $problemProposal->getProblemProposal('id_problem = '. $id.' and uplne = 0');
                $proposal = new Application_Model_DbTable_Proposal();
                $PPs = array();
                
                foreach ($rows as $row) {
                    $proposalRow = $proposal->getProposal($row->id_navrh);                        
                    $PPs[] = array(
                        'id' => $row->id_navrh,
                        'name' => $proposalRow['nazov'],
                        'popis' => $row->popis);
                }
            }
            
            $this->view->FPs = $FPs;
            $this->view->PPs = $PPs;
            $this->view->APs = $APs;
            $this->view->OPs = $OPs;
            $this->view->type = $type;
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
    
    private function add() {
        $problem = new Application_Model_DbTable_Problem();
        try {
            $problem->addProblem($this->view->form->getValues());
            $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
            $flashMessenger->addMessage('Problém pridaný.', null,
                Strateg_MyFlashMessenger_Message::SUCCESS);
            return true;
        }
        catch (Zend_Db_Statement_Exception $ze) {
            $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
            $flashMessenger->addMessage('Zvolený názov už patrí inému problému.',
                    null, Strateg_MyFlashMessenger_Message::DANGER);
            return false;
        }
        catch (Exception $e) {
            $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
            $flashMessenger->addMessage('Iná chyba: '.$e->getMessage(), null,
                Strateg_MyFlashMessenger_Message::DANGER);
            return false;
        }
    }
    
    private function update() {
        $id = (int)$this->getParam('id');
        try {
            $problem = new Application_Model_DbTable_Problem();
            $problem->updateProblem($id, $this->view->form->getValues());
                $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
                $flashMessenger->addMessage('Problém uložený.', null, Strateg_MyFlashMessenger_Message::SUCCESS);
            return true;
        }
        catch (Zend_Db_Statement_Exception $ze) {
            $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
            $flashMessenger->addMessage('Zvolený názov už patrí inému problému.',
                    null, Strateg_MyFlashMessenger_Message::DANGER);
            return false;
        }
        catch (Exception $e) {
            $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
            $flashMessenger->addMessage('Iná chyba: '.$e->getMessage(), null,
                Strateg_MyFlashMessenger_Message::DANGER);
            return false;
        }
    }
}

