<?php

class Application_Model_Storage_dbTable_User extends Zend_Db_Table_Abstract {

    protected $_name = 'user';
    protected $_primary = 'id_user';
    
    public function getPrimary() {
        return $this->_primary;
    }
}