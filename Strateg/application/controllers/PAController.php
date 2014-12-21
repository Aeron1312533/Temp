<?php

class PAController extends Strateg_Controller_Action
{

    public function init() {
        parent::init();
    }
    
    public function indexAction() {
        $this->_helper->redirector('list');
    }
    
    public function listAction() {
        $pa_vazba = new Application_Model_DbTable_ProblemAnalysis();
        $this->view->pa_links = $pa_vazba->fetchAll();        
    }
    
    public function addAction() {
        $form = new Application_Form_ProblemAnalysis_Add();
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
                $pa_vazba = new Application_Model_DbTable_ProblemAnalysis();
                $pa_vazba->addProblemAnalysis($form->getValues());
                $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
                $flashMessenger->addMessage('PA vazba pridana', null, Strateg_MyFlashMessenger_Message::SUCCESS);
                $this->_helper->redirector('list');
            } else {
                $form->populate($formData);
            }
        }
    }

    public function editAction()
    {
        $form = new Application_Form_ProblemAnalysis_Edit();
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
                $id_problem = (int)$this->getParam('id_problem');
                $id_analyza = (int)$this->getParam('id_analyza');
                $pa_vazba = new Application_Model_DbTable_ProblemAnalysis();
                $pa_vazba->updateProblemAnalysis($id_problem, $id_analyza, $form->getValues());
                $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
                $flashMessenger->addMessage('PA vazba ulozena', null, Strateg_MyFlashMessenger_Message::SUCCESS);
                $this->_helper->redirector('list');
            } else {
                $form->populate($formData);
            }
        } else { //zobrazujeme
            $id_problem = $this->getParam('id_problem', 0);
            $id_analyza = $this->getParam('id_analyza', 0);
            if ($id_problem > 0 && $id_analyza > 0) {
                $pa_vazba = new Application_Model_DbTable_ProblemAnalysis();
                $pa_vazba_array = $pa_vazba->getProblem($id_problem, $id_analyza);
                $form->populate($pa_vazba_array);
            }
        }
    }

    public function deleteAction()
    {
        $form = new Application_Form_ProblemAnalysis_Delete();
        $this->view->form = $form;
        
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            
            if(isset($formData["nie"])) {
                $this->_helper->redirector('list');
            }
            
            if ($form->isValid($formData)) {
                $id_problem = (int)$form->getValue('id_problem');
                $id_analyza = (int)$form->getValue('id_analyza');
                $pa_vazba = new Application_Model_DbTable_ProblemAnalysis();
                $pa_vazba->deleteProblem($id_problem, $id_analyza);
                $flashMessenger = $this->_helper->getHelper('MyFlashMessenger');
                $flashMessenger->addMessage('PA vazba vymazana', null, Strateg_MyFlashMessenger_Message::SUCCESS);
            }
            
            $this->_helper->redirector('list');
        } else { //zobrazujeme
            $id_problem = $this->_getParam('id_problem', 0);
            $id_analyza = $this->_getParam('id_analyza', 0);
            if ($id_problem > 0 && $id_analyza > 0) {
                $pa_vazba = new Application_Model_DbTable_ProblemAnalysis();
                $form->populate($pa_vazba->getProblemAnalysis($id_problem, $id_analyza));
            }
        }
    }

}

