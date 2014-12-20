<?php

class Strateg_MyFlashMessenger_Message {
    const WARNING = 'warning';
    const INFO = 'info';
    const SUCCESS = 'success';
    const DANGER = 'danger';
    
    private $type;
    private $message;
    
    public function __construct($message, $type) {
       $this->setMessage($message);
       
       if (empty($type)) {
           $type = self::INFO;
       }
       $this->setType($type);
    }
    
    function getType() {
        return $this->type;
    }

    function getMessage() {
        return $this->message;
    }

    function setType($type) {
        $this->type = $type;
    }

    function setMessage($message) {
        $this->message = $message;
    }


}
