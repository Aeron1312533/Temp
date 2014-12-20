<?php

class Application_Model_DbTable_Problem extends Zend_Db_Table_Abstract
{

    protected $_name = 'problem';
    protected $_primary = 'id';

    public function getProblem($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row) {
            throw new Exception("Could not find row $id");
        }
        return $row->toArray();
    }

    public function addProblem($data) {
        $this->insert($data);
    }

    public function updateProblem($id, $data) {
        file_put_contents('test', $data);
        $this->update($data, 'id = '. (int)$id);
    }

    public function deleteProblem($id) {
        $this->delete('id = ' . (int)$id);
    }

}