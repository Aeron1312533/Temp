<?php

class Application_Form_Analysis_Edit extends Zend_Form {

    public function init() {
        $configFilePath = APPLICATION_PATH . "/forms/Analysis/configs/edit.ini";
        $config = new Zend_Config_Ini($configFilePath);        
        $this->setConfig($config);
        
        $APwrapper = new Zend_Form_SubForm();
        $APwrapper->setLegend('Analyzované problémy');
        $APwrapper->addElement('submit', 'APpridat', array(
            'label' => 'Pridať',
            'class' => 'btn btn-primary'
        ));
        
        $OPwrapper = new Zend_Form_SubForm();
        $OPwrapper->setLegend('Výstupné problémy');
        $OPwrapper->addElement('submit', 'OPpridat', array(
            'label' => 'Pridať',
            'class' => 'btn btn-primary'
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
        $this->getSubForm('analyzedproblems')->addElement('multiselect', 'APselect', array(
            'multiOptions' => array (
            ),
            'label' => 'Vyberte vstupný problém'
        ));  
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        
        $sql = 'select * from problem where not exists (select * from problem_analyza where problem_anal'.
                'yza.id_problem = problem.id and problem_analyza.id_analyza = '.$id_analyza.')';
        
        $statement = $dbAdapter->query($sql);
        $newSelectableAPs = $statement->fetchAll();
        
        if (empty($newSelectableAPs )) {
            $this->getSubForm('analyzedproblems')->removeElement('APselect');
            $this->getSubForm('analyzedproblems')->removeElement('APpridat');
            $this->getSubForm('analyzedproblems')->addElement('html', 'info', array(
                'value' => '<div class="alert alert-warning">Nebol nájdený žiaden problém, ktorý by mohol byť pridaný</div>'
            ));
        }
        
        foreach ($newSelectableAPs as $row) {
            $this->getSubForm('analyzedproblems')->getElement('APselect')->addMultiOption($row['id'],$row['nazov']);
        }        
    }

    public function initOPselect($id_analyza) {
        $this->getSubForm('outputproblems')->addElement('multiselect', 'OPselect', array(
            'multiOptions' => array (
            ),
            'label' => 'Vyberte výstupný problém'
        ));  
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        
        $sql = 'select * from problem where not(subjektivny) and not exists (select * from proble'.
                'm_analyza where problem_analyza.id_problem = problem.id and problem_analyza.i'.
                'd_analyza = '.$id_analyza.')';
        
        $statement = $dbAdapter->query($sql);
        $newSelectableOPs = $statement->fetchAll();
        
        if (empty($newSelectableOPs )) {
            $this->getSubForm('outputproblems')->removeElement('OPselect');
            $this->getSubForm('outputproblems')->removeElement('OPpridat');
            $this->getSubForm('outputproblems')->addElement('html', 'info', array(
                'value' => '<div class="alert alert-warning">Nebol nájdený žiaden problém, ktorý by mohol byť pridaný</div>'
            ));
        }
        
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
                array('value' => '<a href="../../../problem/detail/id/' . 
                    $data['id_problem'] . '">' . $data["name"] . '</a>'
        ));      
        // P-A relation description
        $APsubform->addElement('textarea', 'popis',array(
            'rows' => 3,
            'value' => $data['popis']
        ));
        // delete button
        $APsubform->addElement('submit', 'remove', array('label'=>'Vymazať', 'class' => 'btn btn-danger'));
        //edit button
        $APsubform->addElement('submit', 'edit', array('label'=>'Uložiť', 'class' => 'btn btn-primary'));
        $APwrapper->addSubForm($APsubform, $data['name']);
    }
    
    public function addOP(array $data) {       
        $OPwrapper = $this->getSubForm('outputproblems');
        $OPsubform = new Zend_Form_SubForm();
        // hidden id_problem
        $OPsubform->addElement('hidden', 'id_problem', array('value'=>$data['id_problem']));
        // link to problem
        $OPsubform->addElement('html', 'OPnazov-' . $data['id_analyza'] .'-'.$data['id_problem'], 
                array('value' => '<a href="../../../problem/detail/id/' . 
                    $data['id_problem'] . '">' . $data["name"] . '</a>'
        ));      
        // delete button
        $OPsubform->addElement('submit', 'remove', array('label'=>'Vymazať', 'class' => 'btn btn-danger'));
        //edit button
        $OPsubform->addElement('submit', 'edit', array('label'=>'Uložiť', 'class' => 'btn btn-primary'));
        // P-A relation description
        $OPsubform->addElement('textarea', 'popis', array(
            'rows' => 3,
            'value' => $data['popis']
        ));
        $OPwrapper->addSubForm($OPsubform, $data['name']);
    }

}

