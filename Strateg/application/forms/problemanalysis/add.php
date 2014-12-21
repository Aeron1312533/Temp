<?php

class Application_Form_ProblemAnalysis_Add extends Zend_Form {

    public function init() {
        $configFilePath = APPLICATION_PATH . "/forms/problemanalysis/configs/add.ini";
        $config = new Zend_Config_Ini($configFilePath);        
        $this->setConfig($config);
    }

}

