<?php

class Application_Model_DbTable_ProblemProposal extends Zend_Db_Table_Abstract
{

    protected $_name = 'problem_navrh';
    protected $_primary = array('id_problem', 'id_navrh');

    public function getProblemProposal($where)
    {
        $rows = $this->fetchAll($where);
        return $rows;
    }

    public function addProblemProposal($id_problem, $id_navrh, $uplne) {
        $data = array('id_problem'=>$id_problem,
            'id_navrh'=>$id_navrh, 'popis'=>'', 'uplne'=>$uplne);
        $this->insert($data);
    }

    public function updateProblemProposal($id_problem, $id_navrh, $data) {
        $this->update($data, 'id_problem = ' . (int)$id_problem .
                ' and id_navrh = '. (int)$id_navrh);
    }

    public function deleteProblemProposal($id_problem, $id_navrh) {
        $this->delete('id_problem = ' . (int)$id_problem . 
                ' and id_navrh = '. (int)$id_navrh);
    }
    
    public function deletePPbyProblem($id_problem) {
        $this->delete('id_problem = ' . (int)$id_problem);
    }
    
    public function deletePPbyProposal($id_navrh) {
        $this->delete('id_navrh = '. (int)$id_navrh);
    }

}