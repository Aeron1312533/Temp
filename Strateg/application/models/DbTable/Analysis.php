<?php

class Application_Model_DbTable_Analysis extends Zend_Db_Table_Abstract
{

    protected $_name = 'analyza';
    protected $_primary = 'id';

    public function getAnalysis($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row) {
            throw new Exception("Could not find row $id");
        }
        return $row->toArray();
    }

    public function addAnalysis($data) {
        $this->insert($data);
    }

    public function updateAnalysis($id, $data) {
        file_put_contents('test', $data);
        $this->update($data, 'id = '. (int)$id);
    }

    public function deleteAnalysis($id) {
        $this->delete('id = ' . (int)$id);
    }

}