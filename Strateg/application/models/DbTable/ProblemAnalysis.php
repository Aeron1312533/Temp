<?php

class Application_Model_DbTable_ProblemAnalysis extends Zend_Db_Table_Abstract
{

    protected $_name = 'problem_analyza';
    protected $_primary = array('id_problem', 'id_analyza');

    public function getProblemAnalysis($where)
    {
        $rows = $this->fetchAll($where);
        return $rows;
    }

    public function addProblemAnalysis($id_problem, $id_analyza, $vstup) {
        $data = array('id_problem'=>$id_problem,
            'id_analyza'=>$id_analyza, 'popis'=>'', 'vstup'=>$vstup);
        $this->insert($data);
    }

    public function updateProblemAnalysis($id_problem, $id_analyza, $data) {
        $this->update($data, 'id_problem = ' . (int)$id_problem .
                ' and id_analyza = '. (int)$id_analyza);
    }

    public function deleteProblemAnalysis($id_problem, $id_analyza) {
        $this->delete('id_problem = ' . (int)$id_problem . 
                ' and id_analyza = '. (int)$id_analyza);
    }
    
    public function deletePAbyProblem($id_problem) {
        $this->delete('id_problem = ' . (int)$id_problem);
    }
    
    public function deletePAbyAnalysis($id_analyza) {
        $this->delete('id_analyza = '. (int)$id_analyza);
    }

}