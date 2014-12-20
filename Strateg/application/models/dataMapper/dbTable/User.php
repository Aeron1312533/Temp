<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Database
 *
 * @author peter.pekarovic
 */
class Application_Model_dataMapper_dbTable_User extends Game_DataMapper_Database {
    protected $storageClass = 'Application_Model_Storage_dbTable_User';
    protected $entityClass = 'Application_Model_Entity_User';
    
    protected static $instance;
    
    protected function init() {
        $className = $this->getStorageClass();
        $this->setStorage(new $className());
    }
}
