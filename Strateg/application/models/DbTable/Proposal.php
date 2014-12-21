<?php

class Application_Model_DbTable_Proposal extends Zend_Db_Table_Abstract
{

    protected $_name = 'navrh';
    protected $_primary = 'id';

    public function getProposal($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row) {
            throw new Exception("Could not find row $id");
        }
        return $row->toArray();
    }

    public function addProposal($data) {
        $this->insert($data);
    }

    public function updateProposal($id, $data) {
        file_put_contents('test', $data);
        $this->update($data, 'id = '. (int)$id);
    }

    public function deleteProposal($id) {
        $this->delete('id = ' . (int)$id);
    }

}