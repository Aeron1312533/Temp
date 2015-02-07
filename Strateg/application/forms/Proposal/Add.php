<?php

class Application_Form_Proposal_Add extends Zend_Form {

    public function init() {
        $configFilePath = APPLICATION_PATH . "/forms/Proposal/configs/add.ini";
        $config = new Zend_Config_Ini($configFilePath);        
        $this->setConfig($config);
        
        $this->getElement('nazov')->setRequired(true)->setErrorMessages(array(
            'isEmpty'=>'Prosím, zadajte názov návrhu'
        ));
        
        $this->getElement('pridat')->setDecorators(Strateg_Decorator_Definitions::openButtonDecorators());
        $this->getElement('spat')->setDecorators(Strateg_Decorator_Definitions::closeButtonDecorators());
    }

}

