<?php

class ProblemController extends Strateg_Controller_Action
{

    public function init() {
        parent::init();
    }
    
    public function indexAction() {
        $this->_helper->redirector('detail');
    }

    public function detailAction()
    {      
            
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            
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
                $attachment= new Application_Model_DbTable_Attachment();
                $attachment_array = $attachment->getAttachment($id);
                $this->view->attachment = $attachment_array;
                
                //ziskaj objekt, ku ktoremu patri
                $attachmentObject = new Application_Model_DbTable_AttachmentObject();
                $rows = $attachmentObject->getAttachmentObject('id_subor = '. $id);
                $Os[] = array();
                
                 foreach ($rows as $row) {
                     switch ($row['typ_objekt']) {
                         case 'problem' :
                             $object = new Application_Model_DbTable_Problem();
                             $object_array = $object->getProblem($row['id_objekt']);
                             break;
                         case 'analyza' :
                             $object = new Application_Model_DbTable_Analysis();
                             $object_array = $object->getAnalysis($row['id_objekt']);
                             break;
                         case 'navrh' :
                             $object = new Application_Model_DbTable_Proposal();
                             $object_array = $object->getProposal($row['id_objekt']);
                             break;
                         default:
                             break;
                             
                     }
                    $analysisRow = $analysis->getAnalysis($row->id_analyza);                        
                    $APs[] = array(
                        'id' => $row->id_analyza,
                        'name' => $analysisRow['nazov'],
                        'popis' => $row->popis);
                }
                
            }
            
            $this->view->FPs = $FPs;
            $this->view->PPs = $PPs;
            $this->view->APs = $APs;
            $this->view->OPs = $OPs;
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

