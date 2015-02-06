<?php

class Application_Form_Problem_Detail extends Zend_Form {

    public function init() {
        $configFilePath = APPLICATION_PATH . "/forms/problem/configs/detail.ini";
        $config = new Zend_Config_Ini($configFilePath);        
        $this->setConfig($config);
    }

    public function addEditButton($role) {
        //alter form according to users permissions     
        if ($role === 'manager') {
            $this->addElement(
                'submit',
                'edit_button',
                array(
                    'label' => 'Upravit',
                    'link' => 'bla',
                    'class' => 'btn btn-info'
                )
            );           
        }
    }
}

