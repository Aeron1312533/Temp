<?php

class Application_Model_Mail {
    //error codes
    const ERROR_NO_ID = 1;
    
    protected $storage;
    protected $transport;
    protected $mailbox_options;
    
    protected $id;
    protected $subject;
    protected $body;
    protected $to;
    protected $from;
    protected $cc;
    protected $bcc;
    
    public function __construct($id = NULL, $mailbox_options = NULL) {
        if (!is_null($id)) {
            $this->setId($id);
        }

        if (is_null($mailbox_options) || !is_array($mailbox_options)) {
            $mailbox_options = Zend_Registry::get('mailbox_options');
        }
        
        foreach($mailbox_options as $key => $value) {
                $this->mailbox_options[$key] = $value;
            }
    }
    
    public function setId($id) {
        $this->id = $id;
    }
    
    protected function loadValues() {
      /*  if (!isset($this->id)) {
            throw new Exception($message, ERROR_NO_ID, $previous)
        }*/
    }
}
