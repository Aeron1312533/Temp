<?php

class Application_Form_Problem_Delete extends Zend_Form {

    public function init() {
        $configFilePath = APPLICATION_PATH . "/forms/problem/configs/delete.ini";
        $config = new Zend_Config_Ini($configFilePath);
        
        $this->setConfig($config);
    }

}

