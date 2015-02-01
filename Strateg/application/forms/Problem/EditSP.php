<?php

class Application_Form_Problem_EditSP extends Zend_Form {

    public function init() {
        $configFilePath = APPLICATION_PATH . "/forms/Problem/configs/editsp.ini";
        $config = new Zend_Config_Ini($configFilePath);        
        $this->setConfig($config);
    }
}

