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
        $formData = $this->getRequest()->getPost();
        $form->populate($formData);
        
        if ($this->getRequest()->isPost()) {
            
            /**
             * if back button was pressed
             */
            if(isset($formData["spat"])) {
                $this->_helper->redirector('list');
            }
            /**
             * add input problem to this analysis
             */
            if(isset($formData['analyzedproblems']['APpridat'])) {
                    if ($formData['analyzedproblems']['APselect'] != '0') {
                        $pa_vazba = new Application_Model_DbTable_ProblemAnalysis();
                        $pa_vazba->addProblemAnalysis($formData['analyzedproblems']['APselect'],
                                $formData['editanalysis']['id'], 1);
                    }
                    else {
                        $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
                        $flashMessenger->addMessage('Prosim vyberte vstupny problem.', null, Strateg_MyFlashMessenger_Message::DANGER);
                    }
                    return;
            }
            /**
             * add output problem to this analysis
             */
            if(isset($formData['outputproblems']['OPpridat'])) {
                return;
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
                $problemAnalysis = new Application_Model_DbTable_ProblemAnalysis();
                
                $form->populate($analysis->getAnalysis($id));
                $rows = $problemAnalysis->getProblemAnalysis(null, $id);

                foreach ($rows as $row) {
                    $problem = new Application_Model_DbTable_Problem();
                    $problemRow = $problem->getProblem($row->id_problem);
                        
                    $form->addAP(array(
                        'name' => $problemRow['nazov'],
                        'id_analyza' => $id,
                        'id_problem' => $problemRow['id'],
                        'popis' => $row->popis
                    ));
                }
                $form->initAPselect($id);
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

