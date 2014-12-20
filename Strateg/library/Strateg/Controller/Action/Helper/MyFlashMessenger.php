<?php

class Strateg_Controller_Action_Helper_MyFlashMessenger extends Zend_Controller_Action_Helper_FlashMessenger {
    public function addMessage($message,$namespace = null, $type = null) {
        if (!is_string($namespace) || $namespace == '') {
            $namespace = $this->getNamespace();
        }        
        
        if (self::$_messageAdded === false) {
            self::$_session->setExpirationHops(1, null, true);
        }

        if (!is_array(self::$_session->{$namespace})) {
            self::$_session->{$namespace} = array();
        }

        self::$_session->{$namespace}[] = new Strateg_MyFlashMessenger_Message($message, $type);
        self::$_messageAdded = true;

        return $this;
    }
}
