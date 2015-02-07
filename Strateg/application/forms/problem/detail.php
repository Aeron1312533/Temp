<?php

class Application_Form_Problem_Detail extends Zend_Form {

    public function init() {
        $configFilePath = APPLICATION_PATH . "/forms/problem/configs/detail.ini";
        $config = new Zend_Config_Ini($configFilePath);        
        $this->setConfig($config);
        
        $this->getElement('edit_button')->setDecorators(Strateg_Decorator_Definitions::openButtonDecorators());
        $this->getElement('spat')->setDecorators(Strateg_Decorator_Definitions::closeButtonDecorators());
    }

    public function addEditButton($role) {
        //alter form according to users permissions     
        if ($role != 'manager') {
            $this->removeElement('edit_button');        
        }
    }
}

