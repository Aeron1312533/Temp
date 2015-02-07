<?php

class Application_Form_Proposal_Edit extends Zend_Form {

    public function init() {
        $configFilePath = APPLICATION_PATH . "/forms/Proposal/configs/edit.ini";
        $config = new Zend_Config_Ini($configFilePath);     
        $this->setConfig($config);

        $this->getElement('nazov')->setRequired(true)->setErrorMessages(array(
            'isEmpty'=>'Prosím, zadajte názov návrhu'
        ));

        $this->getElement('ulozit')->setDecorators(Strateg_Decorator_Definitions::openButtonDecorators());
        $this->getElement('spat')->setDecorators(Strateg_Decorator_Definitions::closeButtonDecorators());
        
        $Pwrapper = new Zend_Form_SubForm();
        $Pwrapper->setLegend('Riešené problémy');
        $Pwrapper->addElement('submit', 'Ppridat', array(
            'label' => 'Pridať',
            'class' => 'btn btn-primary'
        ));
        
        $this->addSubForm($Pwrapper, 'problems');
    }
    
    public function initPselect($id_navrh) {
        $this->getSubForm('problems')->addElement('multiselect', 'Pselect', array(
            'multiOptions' => array (
            ),
            'label' => 'Vyberte problém'
        ));  
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        
        $sql = 'select * from problem where not exists (select * from problem_navrh where problem_navr'.
                'h.id_problem = problem.id and problem_navrh.id_navrh = '.$id_navrh.')';
        
        $statement = $dbAdapter->query($sql);
        $newSelectablePs = $statement->fetchAll();
        
        if (empty($newSelectablePs )) {
            $this->getSubForm('problems')->removeElement('Pselect');
            $this->getSubForm('problems')->removeElement('Ppridat');
            $this->getSubForm('problems')->addElement('html', 'info', array(
                'value' => '<div class="alert alert-warning">Nebol nájdený žiaden problém, ktorý by mohol byť pridaný</div>'
            ));
        }
        
        foreach ($newSelectablePs as $row) {
            $this->getSubForm('problems')->getElement('Pselect')->addMultiOption($row['id'],$row['nazov']);
        }        
    }
    
    public function addP(array $data) {       
        $Pwrapper = $this->getSubForm('problems');
        $Psubform = new Zend_Form_SubForm();
        // hidden id_problem
        $Psubform->addElement('hidden', 'id_problem', array(
            'value'=>$data['id_problem'],
            'decorators' => Strateg_Decorator_Definitions::hiddenDecorators()));
        // link to problem
        $Psubform->addElement('html', 'Pnazov-' . $data['id_navrh'] .'-'.$data['id_problem'], 
                array('value' => '<a href="../../../problem/detail/id/' . 
                    $data['id_problem'] . '">' . $data["name"] . '</a>',
                    'decorators' => Strateg_Decorator_Definitions::hiddenDecorators()
        ));
        $Psubform->addElement('multiCheckbox', 'uplne', array(
            'value' => $data['uplne'] == '1' ? true : false,
            'multiOptions' => array (
                '1' => 'Rieši problém úplne'
            )
        ));
        // P-P relation description
        $Psubform->addElement('textarea', 'popis',array(
            'rows' => 3,
            'value' => $data['popis']
        ));
        //edit button
        $Psubform->addElement('submit', 'edit', array(
            'label'=>'Uložiť', 
            'class' => 'btn btn-primary',
            'decorators' => Strateg_Decorator_Definitions::openButtonDecorators()));
        // delete button
        $Psubform->addElement('submit', 'remove', array(
            'label'=>'Vymazať',
            'class' => 'btn btn-danger',
            'decorators' => Strateg_Decorator_Definitions::closeButtonDecorators()));
        $Pwrapper->addSubForm($Psubform, $data['name']);
    }

}

