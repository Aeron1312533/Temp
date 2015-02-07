<?php

class Application_Form_User_Login extends Zend_Form {

    public function init() {
        $configFilePath = APPLICATION_PATH . "/forms/User/configs/login.ini";
        $config = new Zend_Config_Ini($configFilePath);
        
        $this->setConfig($config);
        $this->getElement('username')->setRequired(true)->setErrorMessages(array(
            'isEmpty'=>'Prosím, zadajte prihlasovacie meno'
        ));
        $this->getElement('password')->setRequired(true)->setErrorMessages(array(
            'isEmpty'=>'Prosím, zadajte heslo'
        ));
    }

}

