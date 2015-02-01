<?php

class Application_Form_Analysis_Delete extends Zend_Form {

    public function init() {
        $configFilePath = APPLICATION_PATH . "/forms/Analysis/configs/delete.ini";
        $config = new Zend_Config_Ini($configFilePath);        
        $this->setConfig($config);
    }
    
    public function showTheRest() {        
        $analyses = new Application_Model_DbTable_Analysis();
        $analysis = $analyses->getAnalysis($this->getElement('id')->getValue());
        $nazov = $analysis['nazov'];
        $this->addElement('html', 'info', 
                array('value' => '<p>Stlačením tlačidla Áno vymažete analýzu \'' . $nazov. '\' a všetky jej väzby.</a>'
        ));   
    }

}

