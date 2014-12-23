<?php

class Application_Form_Problem_Delete extends Zend_Form {

    public function init() {
        $configFilePath = APPLICATION_PATH . "/forms/Problem/configs/delete.ini";
        $config = new Zend_Config_Ini($configFilePath);        
        $this->setConfig($config);
    }
    
    public function showTheRest() {        
        $problems = new Application_Model_DbTable_Problem();
        $problem = $problems->getProblem($this->getElement('id')->getValue());
        $nazov = $problem['nazov'];
        $this->addElement('html', 'info', 
                array('value' => '<p>Stlačením tlačidla Áno vymažete problém \'' . $nazov. '\' a všetky jeho väzby.</a>'
        ));   
    }

}

