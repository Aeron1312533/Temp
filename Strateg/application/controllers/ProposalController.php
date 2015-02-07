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

    public function detailAction()
    {              
        //remove unnecessary elements
        $form = new Application_Form_Problem_Detail();
        $form->addEditButton(Zend_Registry::get('role'));
            
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            //redirect to edit if needed
            if(isset($formData["edit_button"])) {
                $this->_helper->redirector('edit', 'proposal', 'default', array('id' => $this->getParam('id')));
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
                $proposal = new Application_Model_DbTable_Proposal();
                $proposal_array = $proposal->getProposal($id);
                $this->view->proposal = $proposal_array;
                
                //problemy, ktore riesi ciastocne
                $problemProposal = new Application_Model_DbTable_ProblemProposal();
                $rows = $problemProposal->getProblemProposal('id_navrh = '. $id.' and uplne = 0');
                $problem = new Application_Model_DbTable_Problem();
                $PPs = array();
                
                 foreach ($rows as $row) {
                    $problemRow = $problem->getProblem($row->id_problem);                        
                    $PPs[] = array(
                        'typ' => $problemRow['subjektivny'] ? 'sp' : 'op',
                        'id' => $row->id_problem,
                        'name' => $problemRow['nazov'],
                        'popis' => $row->popis);
                }
                
                //problemy, ktore riesi uplne
                $rows = $problemProposal->getProblemProposal('id_navrh = '. $id.' and uplne = 1');
                $problem = new Application_Model_DbTable_Problem();
                $FPs = array();
                
                foreach ($rows as $row) {
                    $problemRow = $problem->getProblem($row->id_problem);                        
                    $FPs[] = array(
                        'typ' => $problemRow['subjektivny'] ? 'sp' : 'op',
                        'id' => $row->id_problem,
                        'name' => $problemRow['nazov'],
                        'popis' => $row->popis);
                }
            }
            
            $this->view->PPs = $PPs;
            $this->view->FPs = $FPs;
        }
    }
    
    public function editAction()
    {
        $form = new Application_Form_Proposal_Edit();
        
        $this->view->form = $form;

       /* if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            
            /**
             * if back button was pressed
             */
           /* if(isset($formData["spat"])) {
                $this->_helper->redirector('list');
            }
            
            if ($form->isValid($formData)) {
                $id = (int)$this->getParam('id');
                $proposal = new Application_Model_DbTable_Proposal();
                $proposal->updateProposal($id, $form->getValues());
                $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
                $flashMessenger->addMessage('Návrh uložený,', null, Strateg_MyFlashMessenger_Message::SUCCESS);
                $this->_helper->redirector('list');
            } else {
                $form->populate($formData);
            }
        } else { //zobrazujeme*/
        
        $id = $this->getParam('id', 0);
        if ($id > 0) {
            $proposal = new Application_Model_DbTable_Proposal();
            $form->populate($proposal->getProposal($id));
            $form->initPselect($id);              
            $this->showPs($id);
        }
        
        $formData = $this->getRequest()->getPost();
        $form->populate($formData);
        
        if ($this->getRequest()->isPost()) {
            $this->editButtons($form, $formData);
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
                $flashMessenger->addMessage('Návrh vymazaný', null, Strateg_MyFlashMessenger_Message::SUCCESS);
            }
            
            $this->_helper->redirector('list');
        } else { //zobrazujeme
            $id = $this->_getParam('id', 0);
            if ($id > 0) {
                $proposal = new Application_Model_DbTable_Proposal();
                $form->populate($proposal->getProposal($id));
                $form->showTheRest();
            }
        }
    }
    
    private function editButtons($form, $formData) {
        // tlacidlo spat:
        if(isset($formData["spat"])) {
            $this->_helper->redirector('list');
        }
        // tlacidla pridat problem:
        $this->maybeAddPP($formData);
        // vymazat pa-vazbu:
        $this->maybeDeletePP($formData);
        // ulozit zmeny na vazbe
        $this->maybeEditPP($formData);
        // ulozit navrh:
        if ($form->isValid($formData)) {
            if ($this->update()) {
                $this->_helper->redirector('list');
            }
            else {
                $this->_helper->redirector('edit','proposal','default',array('id'=> $this->getParam('id')));
            }
        } else {
            $form->populate($formData);
        } 
    }
    
    private function showPs($id) {
        $problemProposal = new Application_Model_DbTable_ProblemProposal();
        $rows = $problemProposal->getProblemProposal('id_navrh = '. $id);
        $problem = new Application_Model_DbTable_Problem();
        foreach ($rows as $row) {
            $problemRow = $problem->getProblem($row->id_problem);                        
            $this->view->form->addP(array('name' => $problemRow['nazov'],'id_navrh' => $id,
                'id_problem' => $problemRow['id'],'popis' => $row->popis, 'uplne' => $row->uplne));
                }
    }
 
    private function addPP($array_problems, $id_navrh, $uplne) {
        $pp_vazba = new Application_Model_DbTable_ProblemProposal();
        
        //pridame vybrane problemy
        foreach ($array_problems as $id_problem) {
            $pp_vazba->addProblemProposal($id_problem, $id_navrh, $uplne);
        }
        
        $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
        $flashMessenger->addMessage('Problém pridaný.',
                null, Strateg_MyFlashMessenger_Message::SUCCESS);
    }
    
    private function maybeAddPP($formData) {
        if(isset($formData['problems']['Ppridat'])) {
            if (isset($formData['problems']['Pselect'][0]) && (int)$formData['problems']['Pselect'][0] >= 1) {
                $this->addPP($formData['problems']['Pselect'], $this->getParam('id'), 0);
            }
            else {
                $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
                $flashMessenger->addMessage('Prosím, vyberte problém.',
                    null, Strateg_MyFlashMessenger_Message::DANGER);
            }
            $this->_helper->redirector('edit','proposal','default',array('id'=>($this->getParam('id'))));
        }
    }
    
    private function maybeDeletePP($formData) {
        $form = $this->view->form;
        $id_navrh = $form->getElement('id')->getValue();
        $ps = $form->getSubForm('problems')->getSubForms();
        // prejdiproblemy:
        foreach ($ps as $p) {
            $name = $p->getName();            
            if (array_key_exists('remove', $formData['problems'][$name])) {
                $id_problem = $formData['problems'][$name]['id_problem'];
                $this->deletePP($id_problem, $id_navrh);
            }
        }
    }
    
    private function deletePP($id_problem, $id_navrh) {
        $pp_vazba = new Application_Model_DbTable_ProblemProposal();
        $pp_vazba->deleteProblemProposal($id_problem, $id_navrh);
        $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
        $flashMessenger->addMessage('Väzba vymazaná.',
            null, Strateg_MyFlashMessenger_Message::SUCCESS);
        $this->_helper->redirector('edit','proposal','default',array('id'=>$id_navrh));
    }
    
     private function maybeEditPP($formData) {
        $form = $this->view->form;
        $id_navrh = $form->getElement('id')->getValue();
        $ps = $form->getSubForm('problems')->getSubForms();
        // prejdi vstupne problemy:
        foreach ($ps as $p) {
            $name = $p->getName();            
            if (array_key_exists('edit', $formData['problems'][$name])) {
                $id_problem = $formData['problems'][$name]['id_problem'];
                $popis = $formData['problems'][$name]['popis'];
                $uplne = isset($formData['problems'][$name]['uplne'][0]) ? 1 : 0;
                $this->editPP($id_problem, $id_navrh, array ('popis' => $popis, 'uplne' => $uplne));
            }
        }
    }
    
    private function editPP($id_problem, $id_navrh, array $data) {
        $pp_vazba = new Application_Model_DbTable_ProblemProposal();
        $pp_vazba->updateProblemProposal($id_problem, $id_navrh, $data);
        $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
        $flashMessenger->addMessage('Data o väzbe uložené.',
            null, Strateg_MyFlashMessenger_Message::SUCCESS);
        $this->_helper->redirector('edit','proposal','default',array('id'=>$id_navrh));        
    }

    private function add() {
        $proposal = new Application_Model_DbTable_Proposal();
        try {
            $proposal->addProposal($this->view->form->getValues());
            $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
            $flashMessenger->addMessage('Návrh pridaný.', null,
                Strateg_MyFlashMessenger_Message::SUCCESS);
            return true;
        }
        catch (Zend_Db_Statement_Exception $ze) {
            $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
            $flashMessenger->addMessage('Zvolený názov už patrí inému návrhu.',
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
        $form = $this->view->form;
        try {
            $analysis = new Application_Model_DbTable_Proposal();
            $data = array('id'=>$form->getValue('id'), 'nazov'=>$form->getValue('nazov'),
                'popis'=>$form->getValue('popis'), 'autor'=>$form->getValue('autor'));
            $analysis->updateProposal($form->getValue('id'),$data);
            $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
            $flashMessenger->addMessage('Návrh uložený.', null, Strateg_MyFlashMessenger_Message::SUCCESS);
            return true;
        }
        catch (Zend_Db_Statement_Exception $ze) {
            $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
            $flashMessenger->addMessage('Zvolený názov už patrí inému návrhu.',
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

