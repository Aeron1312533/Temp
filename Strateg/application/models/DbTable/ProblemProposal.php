<?php

class Application_Model_DbTable_ProblemProposal extends Zend_Db_Table_Abstract
{

    protected $_name = 'problem_navrh';
    protected $_primary = 'id';

    public function getProblemProposal($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row) {
            throw new Exception("Could not find row $id");
        }
        return $row->toArray();
    }

    public function addProblemProposal($data) {
        $this->insert($data);
    }

    public function updateProblemProposal($id, $data) {
        file_put_contents('test', $data);
        $this->update($data, 'id = '. (int)$id);
    }

    public function deleteProblemProposal($id) {
        $this->delete('id = ' . (int)$id);
    }

}