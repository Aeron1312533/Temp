<?php

class Application_Form_Analysis_Edit extends Zend_Form {

    public function init() {
        $configFilePath = APPLICATION_PATH . "/forms/Analysis/configs/edit.ini";
        $config = new Zend_Config_Ini($configFilePath);        
        $this->setConfig($config);
        
        $APwrapper = new Zend_Form_SubForm();
        $APwrapper->setLegend('Analyzované problémy');
        $APwrapper->addElement('submit', 'APpridat', array(
            'label' => 'Pridať vstupný problém'
        ));
        
        $OPwrapper = new Zend_Form_SubForm();
        $OPwrapper->setLegend('Výstupné problémy');
        $OPwrapper->addElement('submit', 'OPpridat', array(
            'label' => 'Pridať výstupný problém'
        ));
        
        $this->addSubForm($APwrapper, 'analyzedproblems');
        $this->addSubForm($OPwrapper, 'outputproblems');
        
        /*$this->addElement('submit', 'ulozit', array(
            'label' => 'Uložiť'
        ));
        
        $this->addElement('submit', 'spat', array(
            'label' => 'Späť'
        ));*/
    }
    
    public function initAPselect($id_analyza) {
        $this->getSubForm('analyzedproblems')->addElement('select', 'APselect', array(
            'multiOptions' => array (
                '0' => 'Vyberte vstupný problém'
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
                '0' => 'Vyberte výstupný problém'
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
                array('value' => '<a href="../../../problem/edit/id/' . 
                    $data['id_problem'] . '">' . $data["name"] . '</a>'
        ));      
        // delete button
        $APsubform->addElement('submit', 'remove', array('label'=>'Vymazať väzbu'));
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
                array('value' => '<a href="../../../problem/edit/id/' . 
                    $data['id_problem'] . '">' . $data["name"] . '</a>'
        ));      
        // delete button
        $OPsubform->addElement('submit', 'remove', array('label'=>'Vymazať väzbu'));
        // P-A relation description
        $OPsubform->addElement('textarea', 'popis', array(
            'rows' => 3,
            'value' => $data['popis']
        ));
        $OPwrapper->addSubForm($OPsubform, $data['name']);
    }

}

