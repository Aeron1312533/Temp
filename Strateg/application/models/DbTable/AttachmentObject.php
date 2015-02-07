<?php

class Application_Model_DbTable_AttachmentObject extends Zend_Db_Table_Abstract
{

    protected $_name = 'objekt_subor';
    protected $_primary = 'id';

    public function getAttachmentObject($where)
    {
        $rows = $this->fetchAll($where);
        return $rows;
    }

    public function addAttachmentObject($id_objekt, $id_subor, $typ_objekt) {
        $data = array('id_objekt'=>$id_objekt,
            'id_subor'=>$id_subor, 'typ_objekt'=> $typ_objekt);
        $this->insert($data);
    }

    public function updateAttachmentObject($id, $data) {
        $this->update($data, 'id= ' . (int)$id);
    }

    public function deleteAttachmentObject($id) {
        $this->delete('id = ' . (int)$id);
    }
    
    public function deleteAOByIdObject($id_objekt, $typ) {
        $this->delete('id_objekt = ' . (int)$id_objekt . 
            " AND typ_objekt = '$typ'");
    }
    
    public function deleteAOByIdFile($id_subor) {
        $this->delete('id_subor = '. (int)$id_subor);
    }

}