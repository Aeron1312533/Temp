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
            // spat:
            if (isset($formData["spat"])) {
                $this->_helper->redirector('list');
            }
            // pridat:
            if (isset($formData["pridat"])) {
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
    }

    public function editAction()
    {
        $form = new Application_Form_Analysis_Edit();        
        $this->view->form = $form;
        $id = $this->getParam('id', 0);
        if ($id > 0) {
            $analysis = new Application_Model_DbTable_Analysis();                
            $form->populate($analysis->getAnalysis($id));                
            $form->initAPselect($id);              
            $form->initOPselect($id);
            $this->showAPs($id);
            $this->showOPs($id);
        }
        $formData = $this->getRequest()->getPost();
        $form->populate($formData);
        
        if ($this->getRequest()->isPost()) {
            $this->editButtons($form, $formData);
        }
    }

    public function deleteAction()
    {
        $form = new Application_Form_Analysis_Delete();
        $this->view->form = $form;
        
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            
            if (isset($formData["ano"])) {
                if ($form->isValid($formData)) {
                    $this->delete();
                }            
            }
            $this->_helper->redirector('list');
        } 
        else { //zobrazujeme
            $id = $this->_getParam('id', 0);
            if ($id > 0) {
                $analysis = new Application_Model_DbTable_Analysis();
                $form->populate($analysis->getAnalysis($id));
                $form->showTheRest();
            }
        }
    }
    
    // true ak analyza pridana, false ak chyba
    private function add() {
        $analysis = new Application_Model_DbTable_Analysis();
        try {
            $analysis->addAnalysis($this->view->form->getValues());
            $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
            $flashMessenger->addMessage('Analýza pridaná.', null,
                Strateg_MyFlashMessenger_Message::SUCCESS);
            return true;
        }
        catch (Zend_Db_Statement_Exception $ze) {
            $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
            $flashMessenger->addMessage('Zvolený názov už patrí inej analýze.',
                    null, Strateg_MyFlashMessenger_Message::DANGER);
        }
        catch (Exception $e) {
            $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
            $flashMessenger->addMessage('Iná chyba: '.$e->getMessage(), null,
                Strateg_MyFlashMessenger_Message::DANGER);
            return false;
        }
    }
    
    private function delete() {
        $id = (int)$this->view->form->getValue('id');
        $analysis = new Application_Model_DbTable_Analysis();
        $analysis->deleteAnalysis($id);
        $pa_vazby = new Application_Model_DbTable_ProblemAnalysis();
        $pa_vazby->deletePAbyAnalysis($id);
        $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
        $flashMessenger->addMessage('Analýza vymazaná.', null, 
            Strateg_MyFlashMessenger_Message::SUCCESS);        
    }

    private function maybeAddInputPA($formData) {
        if(isset($formData['analyzedproblems']['APpridat'])) {
            if ((int)$formData['analyzedproblems']['APselect'] >= 1) {
                $this->addPA($formData['analyzedproblems']['APselect'], $formData['id'], 1);
            }
            else {
                $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
                $flashMessenger->addMessage('Prosím, vyberte vstupný problém.',
                    null, Strateg_MyFlashMessenger_Message::DANGER);
            }
            $this->_helper->redirector('edit','analysis','default',array('id'=>($this->getParam('id'))));
        }
    }
    
    private function maybeAddOutputPA($formData) {
        if(isset($formData['outputproblems']['OPpridat'])) {
            if ((int)$formData['outputproblems']['OPselect'] >= 1) {
                $this->addPA($formData['outputproblems']['OPselect'], $formData['id'], 0);
            }
            else {
                $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
                $flashMessenger->addMessage('Prosím, vyberte výstupný problém.',
                    null, Strateg_MyFlashMessenger_Message::DANGER);
                }
                $this->_helper->redirector('edit','analysis','default',array('id'=>($this->getParam('id'))));
            }
    }
    
    private function maybeDeletePA($formData) {
        $form = $this->view->form;
        $id_analyza = $form->getElement('id')->getValue();
        $aps = $form->getSubForm('analyzedproblems')->getSubForms();
        $ops = $form->getSubForm('outputproblems')->getSubForms();
        // prejdi vstupne problemy:
        foreach ($aps as $ap) {
            $name = $ap->getName();            
            if (array_key_exists('remove', $formData['analyzedproblems'][$name])) {
                $id_problem = $formData['analyzedproblems'][$name]['id_problem'];
                $this->deletePA($id_problem, $id_analyza);
            }
        }
        // prejdi vystupne problemy:
        foreach ($ops as $op) {echo 'y';
           $name = $op->getName();            
            if (array_key_exists('remove', $formData['outputproblems'][$name])) {
                $id_problem = $formData['outputproblems'][$name]['id_problem'];
                $this->deletePA($id_problem, $id_analyza);
            }
        }
    }
    
    private function addPA($id_problem, $id_analyza, $vstup) {
        $pa_vazba = new Application_Model_DbTable_ProblemAnalysis();
        $pa_vazba->addProblemAnalysis($id_problem, $id_analyza, $vstup);
        $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
        if ((int)$vstup == 1) {
            $flashMessenger->addMessage('Vstupný problém pridaný.',
                    null, Strateg_MyFlashMessenger_Message::SUCCESS);
        }
        else {
            $flashMessenger->addMessage('Výstupný problém pridaný.',
                null, Strateg_MyFlashMessenger_Message::SUCCESS);
        }
    }
    
    private function deletePA($id_problem, $id_analyza) {
        $pa_vazba = new Application_Model_DbTable_ProblemAnalysis();
        $pa_vazba->deleteProblemAnalysis($id_problem, $id_analyza);
        $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
        $flashMessenger->addMessage('Väzba vymazaná.',
            null, Strateg_MyFlashMessenger_Message::SUCCESS);
        $this->_helper->redirector('edit','analysis','default',array('id'=>$id_analyza));
    }
    
    private function update() {
        $form = $this->view->form;
        $analysis = new Application_Model_DbTable_Analysis();
        $data = array('id'=>$form->getValue('id'), 'nazov'=>$form->getValue('nazov'),
            'popis'=>$form->getValue('popis'), 'autor'=>$form->getValue('autor'));
        $analysis->updateAnalysis($form->getValue('id'),$data);
        $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
        $flashMessenger->addMessage('Analýza uložená.', null, Strateg_MyFlashMessenger_Message::SUCCESS);
        $this->_helper->redirector('edit','analysis','default',array('id'=>($this->getParam('id'))));
    }
    
    private function showAPs($id) {
        $problemAnalysis = new Application_Model_DbTable_ProblemAnalysis();
        $rows = $problemAnalysis->getProblemAnalysis('id_analyza = '. $id.' and vstup = 1');
        $problem = new Application_Model_DbTable_Problem();
        foreach ($rows as $row) {
            $problemRow = $problem->getProblem($row->id_problem);                        
            $this->view->form->addAP(array('name' => $problemRow['nazov'],'id_analyza' => $id,
                'id_problem' => $problemRow['id'],'popis' => $row->popis));
                }
    }
    
    private function showOPs($id) {
        $problemAnalysis = new Application_Model_DbTable_ProblemAnalysis();
        $rows = $problemAnalysis->getProblemAnalysis('id_analyza = '. $id.' and vstup = 0');
        $problem = new Application_Model_DbTable_Problem();
        foreach ($rows as $row) {
            $problemRow = $problem->getProblem($row->id_problem);                        
            $this->view->form->addOP(array('name' => $problemRow['nazov'],'id_analyza' => $id,
                'id_problem' => $problemRow['id'],'popis' => $row->popis));
                }
    }
    
    private function editButtons($form, $formData) {
        // tlacidlo spat:
        if(isset($formData["spat"])) {
            $this->_helper->redirector('list');
        }
        // tlacidla pridat vstupny/vystupny problem:
        $this->maybeAddInputPA($formData);
        $this->maybeAddOutputPA($formData);
        // vymazat pa-vazbu:
        $this->maybeDeletePA($formData);
        // ulozit analyzu:
        if ($form->isValid($formData)) {
            $this->update();
        } else {
            $form->populate($formData);
        } 
    }
}

