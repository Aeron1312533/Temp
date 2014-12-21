<?php

class Application_Model_DbTable_ProblemAnalysis extends Zend_Db_Table_Abstract
{

    protected $_name = 'problem_analyza';
    protected $_primary = array('id_problem', 'id_analyza');

    public function getProblemAnalysis($id_problem = null, $id_analyza = null)
    {
        if (is_null($id_problem) && is_null($id_analyza)) {
            throw new Exception('Id problem and id analysis not set');
        }
        
        $where = !is_null($id_problem) ? 'id_problem = ' . (int) $id_problem : '';
        $where .= !empty($where) && !is_null($id_analyza) ? 'AND' : '';
        $where .= !is_null($id_analyza) ? 'id_analyza = '. (int) $id_analyza : '';
        
        $rows = $this->fetchAll($where);

        return $rows;
    }

    public function addProblemAnalysis($data) {
        $this->insert($data);
    }

    public function updateProblemAnalysis($id_problem, $id_analyza, $data) {
        file_put_contents('test', $data);
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