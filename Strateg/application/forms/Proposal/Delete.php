<?php

class Application_Form_Proposal_Delete extends Zend_Form {

    public function init() {
        $configFilePath = APPLICATION_PATH . "/forms/Proposal/configs/delete.ini";
        $config = new Zend_Config_Ini($configFilePath);        
        $this->setConfig($config);
    }
    
    public function showTheRest() {        
        $proposals = new Application_Model_DbTable_Proposal();
        $proposal = $proposals->getProposal($this->getElement('id')->getValue());
        $nazov = $proposal['nazov'];
        $this->addElement('html', 'info', 
                array('value' => '<p>Stlačením tlačidla Áno vymažete návrh \'' . $nazov. '\' a všetky jeho väzby.</a>'
        ));   
    }

}

