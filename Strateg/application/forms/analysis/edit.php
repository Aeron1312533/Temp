<?php

class Application_Form_Analysis_Edit extends Zend_Form {

    public function init() {
        $configFilePath = APPLICATION_PATH . "/forms/analysis/configs/edit.ini";
        $config = new Zend_Config_Ini($configFilePath);        
        $this->setConfig($config);
        
        $APwrapper = new Zend_Form_SubForm();
        $APwrapper->setLegend('Analyzovane problemy');
        $APwrapper->addElement('submit', 'APpridat', array(
            'label' => 'Pridat vstupny problem'
        ));
        
        $OPwrapper = new Zend_Form_SubForm();
        $OPwrapper->setLegend('Vystupne problemy');
        $OPwrapper->addElement('submit', 'OPpridat', array(
            'label' => 'Pridat vystupny problem'
        ));
        
        $this->addSubForm($APwrapper, 'analyzedproblems');
        $this->addSubForm($OPwrapper, 'outputproblems');
        
        $this->addElement('submit', 'ulozit', array(
            'label' => 'Ulozit'
        ));
        
        $this->addElement('submit', 'spat', array(
            'label' => 'Spat'
        ));
    }
    
    public function initAPselect($id_analyza) {
        $this->getSubForm('analyzedproblems')->addElement('select', 'APselect', array(
            'multiOptions' => array (
                '0' => 'Vyberte vstupny problem'
            )
        ));  
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        
        $sql = 'select * from problem where not exists (select * from problem_analyza where problem_anal'.
                'yza.id_problem = problem.id and problem_analyza.id_analyza = '.$id_analyza.')';
        
        $statement = $dbAdapter->query($sql);
        $newSelectableAPs = $statement->fetchAll();
        
        foreach ($newSelectableAPs as $row) {
            $this->getSubForm('analyzedproblems')->getElement('APselect')->addMultiOption($row['id'],$row['nazov']);
        }        
    }

    public function initOPselect($id_analyza) {
        $this->getSubForm('outputproblems')->addElement('select', 'OPselect', array(
            'multiOptions' => array (
                '0' => 'Vyberte vystupny problem'
            )
        ));  
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        
        $sql = 'select * from problem where not(subjektivny) and not exists (select * from proble'.
                'm_analyza where problem_analyza.id_problem = problem.id and problem_analyza.i'.
                'd_analyza = '.$id_analyza.')';
        
        $statement = $dbAdapter->query($sql);
        $newSelectableOPs = $statement->fetchAll();
        
        foreach ($newSelectableOPs as $row) {
            $this->getSubForm('outputproblems')->getElement('OPselect')->addMultiOption($row['id'],$row['nazov']);
        }        
    }
    
    public function addAP(array $data) {       
        $APwrapper = $this->getSubForm('analyzedproblems');
        $APsubform = new Zend_Form_SubForm();
        // hidden id_problem
        $APsubform->addElement('hidden', 'id_problem', array('value'=>$data['id_problem']));
        // link to problem
        $APsubform->addElement('html', 'APnazov-' . $data['id_analyza'] .'-'.$data['id_problem'], 
                array('value' => '<a href="/tmp/Strateg/public/problem/edit/id/' . 
                    $data['id_problem'] . '">' . $data["name"] . '</a>'
        ));      
        // delete button
        $APsubform->addElement('submit', 'remove', array('label'=>'Vymazat vazbu'));
        // P-A relation description
        $APsubform->addElement('textarea', 'popis',array(
            'rows' => 3,
            'value' => $data['popis']
        ));
        $APwrapper->addSubForm($APsubform, $data['name']);
    }
    
    public function addOP(array $data) {       
        $OPwrapper = $this->getSubForm('outputproblems');
        $OPsubform = new Zend_Form_SubForm();
        // hidden id_problem
        $OPsubform->addElement('hidden', 'id_problem', array('value'=>$data['id_problem']));
        // link to problem
        $OPsubform->addElement('html', 'OPnazov-' . $data['id_analyza'] .'-'.$data['id_problem'], 
                array('value' => '<a href="/tmp/Strateg/public/problem/edit/id/' . 
                    $data['id_problem'] . '">' . $data["name"] . '</a>'
        ));      
        // delete button
        $OPsubform->addElement('submit', 'remove', array('label'=>'Vymazat vazbu'));
        // P-A relation description
        $OPsubform->addElement('textarea', 'popis', array(
            'rows' => 3,
            'value' => $data['popis']
        ));
        $OPwrapper->addSubForm($OPsubform, $data['name']);
    }

}

