<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of user
 *
 * @author peter.pekarovic
 */
class Application_Model_Service_User {
    public function __construct() {
        
    }
    
    public function registerToDatabase(Application_Form_User_Register $form) {
        $user = new Application_Model_Entity_User();
        //assign values
        try {
            $user->nickname = $form->getValue('nickname');
            $user->email = $form->getValue('email');
            $user->password = $form->getValue('password');
            $user->role = $form->getValue('role');
            $user->language = $form->getValue('language');
        
            $password = new Game_Password($form->getValue('password'));
        
            $user->password = $password->getPasswordEncrypted();
            $user->passwordSalt = $password->getSalt();
        
            //insert into database
            $mapper = Application_Model_dataMapper_dbTable_User::getInstance();
            $mapper->insert($user);
        } catch (Exception $e) {
            throw $e;
        }

    }
 
}
