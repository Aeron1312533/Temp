<?php

class Application_Form_Analysis_Add extends Zend_Form {

    public function init() {
        $configFilePath = APPLICATION_PATH . "/forms/Analysis/configs/add.ini";
        $config = new Zend_Config_Ini($configFilePath);        
        $this->setConfig($config);

        $this->getElement('nazov')->setRequired(true)->setErrorMessages(array(
            'isEmpty'=>'Prosím, zadajte názov analýzy'
        ));
        
        $this->getElement('pridat')->setDecorators(Strateg_Decorator_Definitions::openButtonDecorators());
        $this->getElement('spat')->setDecorators(Strateg_Decorator_Definitions::closeButtonDecorators());
    }

}

