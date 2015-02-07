<?php

class Application_Model_DbTable_Attachment extends Zend_Db_Table_Abstract
{

    protected $_name = 'subor';
    protected $_primary = 'id';

    public function getAttachment($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row) {
            throw new Exception("Could not find row $id");
        }
        return $row->toArray();
    }

    public function addAttachment($data) {
        $this->insert($data);
    }

    public function updateAttachment($id, $data) {
        file_put_contents('test', $data);
        $this->update($data, 'id = '. (int)$id);
    }

    public function deleteAttachment($id) {
        $this->delete('id = ' . (int)$id);
    }

}