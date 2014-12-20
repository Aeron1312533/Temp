<?php

class Application_Form_Problem_Edit extends Zend_Form {

    public function init() {
        $configFilePath = APPLICATION_PATH . "/forms/problem/configs/edit.ini";
        $config = new Zend_Config_Ini($configFilePath);
        
        $this->setConfig($config);
    }

}

