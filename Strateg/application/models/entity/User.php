<?php
/**
 * Description of User
 *
 * @author peter.pekarovic
 */
class Application_Model_Entity_User extends Game_Entity_Abstract {
    protected $entityName = 'User';
    
    protected $data = array(
        'idUser' => null,
        'nickname' => '',
        'email' => '',
        'password' => '',
        'passwordSalt' => '',
        'role' => '',
        'dateCreated' => '',
        'language' => ''
    );
        
    public function filter($name, $value) {
        $this->{$name} = $value;
    }
    public function isValid($name, $filteredValue) {
        return TRUE;
    }

    
}
