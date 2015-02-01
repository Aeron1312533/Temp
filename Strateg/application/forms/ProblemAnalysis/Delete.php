<?php

class Application_Form_ProblemAnalysis_Delete extends Zend_Form {

    public function init() {
        $configFilePath = APPLICATION_PATH . "/forms/ProblemAnalysis/configs/delete.ini";
        $config = new Zend_Config_Ini($configFilePath);        
        $this->setConfig($config);
    }

}

