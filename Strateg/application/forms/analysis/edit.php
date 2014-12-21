<?php

class Application_Form_Analysis_Edit extends Zend_Form {

    public function init() {
        $configFilePath = APPLICATION_PATH . "/forms/analysis/configs/edit.ini";
        $config = new Zend_Config_Ini($configFilePath);        
        $this->setConfig($config);
        
        $APwrapper = new Zend_Form_SubForm();
        $APwrapper->setLegend('Analyzovane problemy');
        $APwrapper->addElement('submit', 'APpridat', array(
            'label' => 'Pridat'
        ));
        
        $OPwrapper = new Zend_Form_SubForm();
        $OPwrapper->setLegend('Vystupne problemy');
        $OPwrapper->addElement('submit', 'OPpridat', array(
            'label' => 'Pridat'
        ));
        $OPwrapper->addElement('select', 'OPselect');
        
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
        
        $pa = new Application_Model_DbTable_ProblemAnalysis();
        $problem = new Application_Model_DbTable_Problem();
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        
        $sql = 'select * from problem where not exists (select * from problem_anal'.
                'yza where problem_analyza.id_problem = problem.id)';
        
        $statement = $dbAdapter->query($sql);
        $newSelectableAPs = $statement->fetchAll();
        
        foreach ($newSelectableAPs as $row) {
            $this->getSubForm('analyzedproblems')->getElement('APselect')->addMultiOption($row['id'],$row['nazov']);
        }        
    }
    
    public function addAP(array $data) {       
        $APwrapper = $this->getSubForm('analyzedproblems');
        $APsubform = new Zend_Form_SubForm();
        $APsubform->addElement('hidden', 'id_problem-' . $data['id_problem']);
        $APsubform->addElement('html', 'APnazov-' . $data['id_analyza'] . $data['id_problem'], array(
            'value' => '<a href="/tmp/Strateg/public/problem/edit/id/' . $data['id_problem'] . '">' . $data["name"] . '</a>'
        ));          
        $APsubform->addElement('textarea', 'APpopis-' . $data['id_analyza'] . $data['id_problem'], array(
            'rows' => 3,
            'value' => $data['popis']
        ));
        $APwrapper->addSubForm($APsubform, $data['name']);
    }

}

