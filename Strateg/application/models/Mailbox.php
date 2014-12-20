<?php

class Application_Model_Mailbox {
    protected $storage;
    protected $transport;
    
    public function __construct($address = null) {
      /*  $host = 'imap.gmail.com';
        $ssl = 'SSL';
        $user = 'webgenalerts@gmail.com';
        $password = 'alerting';
        */
      /*  $this->mailbox = new Zend_Mail_Storage_Imap(
                Array(
                    'host' => $host,
                    'user' => $user,
                    'password' => $password
                ));*/
        
        /*$mail = new Zend_Mail_Storage_Imap(array('host'     => 'imap.gmail.com',
                                         'user'     => 'vladcatvorstva@gmail.com',
                                         'port'     => '993',
                                         'password' => 'aeronidas',
                                         'ssl'      => 'tls',
                                         'auth'     => 'login'
                                          ));*/
        //$mbox = imap_open ("{imap.gmail.com:993/SSL}INBOX", "webgenalerts@gmail.com", "alerting");
    }
}