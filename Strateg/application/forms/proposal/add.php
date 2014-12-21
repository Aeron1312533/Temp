<?php

class Application_Form_Proposal_Add extends Zend_Form {

    public function init() {
        $configFilePath = APPLICATION_PATH . "/forms/proposal/configs/add.ini";
        $config = new Zend_Config_Ini($configFilePath);        
        $this->setConfig($config);
    }

}

