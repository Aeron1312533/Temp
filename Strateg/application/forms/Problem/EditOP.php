<?php

class Application_Form_Problem_EditOP extends Zend_Form {

    public function init() {
        $configFilePath = APPLICATION_PATH . "/forms/Problem/configs/editop.ini";
        $config = new Zend_Config_Ini($configFilePath);        
        $this->setConfig($config);
        
        $this->getElement('nazov')->setRequired(true)->setErrorMessages(array(
            'isEmpty'=>'Prosím, zadajte názov problému'
        ));
        
        $this->getElement('ulozit')->setDecorators(Strateg_Decorator_Definitions::openButtonDecorators());
        $this->getElement('spat')->setDecorators(Strateg_Decorator_Definitions::closeButtonDecorators());
    }

}

