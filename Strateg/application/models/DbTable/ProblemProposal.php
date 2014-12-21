<?php

class Application_Model_DbTable_ProblemProposal extends Zend_Db_Table_Abstract
{

    protected $_name = 'problem_navrh';
    protected $_primary = array('id_problem', 'id_analyza');

    public function getProblemProposal($id_problem, $id_navrh)
    {
        $id_problem = (int)$id_problem;
        $id_navrh = (int)$id_navrh;
        $row = $this->fetchRow('id_problem = ' . $id_problem . 
                ' and id_navrh = '. $id_navrh);
        if (!$row) {
            throw new Exception("Could not find row $id_problem $id_navrh");
        }
        return $row->toArray();
    }

    public function addProblemProposal($data) {
        $this->insert($data);
    }

    public function updateProblemProposal($id_problem, $id_navrh, $data) {
        file_put_contents('test', $data);
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