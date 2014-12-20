<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {    
    protected function _initStrategLibrary() {
        $loader = Zend_Loader_Autoloader::getInstance();
        $loader->registerNamespace('Strateg_');
    }
    
    protected function _initAcl() {         
       if (Zend_Auth::getInstance()->hasIdentity()){
            Zend_Registry::set ('role',
                     Zend_Auth::getInstance()->getStorage()
                                              ->read()
                                              ->role);
        } else {
            Zend_Registry::set('role', 'guest');
        }

        $acl = new Application_Model_Acl();
        Zend_Registry::set('acl', $acl);

    }
    
    protected function _initNavigations() {
        $nav_conf_files = glob(APPLICATION_PATH . '/configs/navigation/' . '*.ini');
        
        foreach($nav_conf_files as $item) {       
            $config = new Zend_Config_Ini($item); 
            $navigation = new Zend_Navigation($config); 
            
            Zend_Registry::set(basename($item, ".ini"), $navigation);
        }
    }
    
}

