<?php

class Application_Form_Problem_Add extends Zend_Form {

    public function init() {
        $configFilePath = APPLICATION_PATH . "/forms/problem/configs/add.ini";
        $config = new Zend_Config_Ini($configFilePath);        
        $this->setConfig($config);
    }

}

