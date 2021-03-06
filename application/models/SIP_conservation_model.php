<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_conservation_model extends CI_Model {
    protected $table = 'sip_conservation';

    public function add($SIP_conservation) {
        $this->db->set($this->_set($SIP_conservation))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    } 
    public function update($id, $SIP_conservation) {
        $this->db->set($this->_set($SIP_conservation))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($SIP_conservation) {
        return array(
            'libelle'            =>      $SIP_conservation['libelle']

        );
    }
    public function delete($id) {
        $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }  
    }
    public function findAll() {
               
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('libelle')
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }
    public function findById($id)  {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }  
    }

     public function findByIdtab($id)
    {   
        $result =  $this->db->select('id as id_sip_conservation, libelle')
                        ->from($this->table)
                        ->where("id", $id)
                        ->order_by('id')
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }                 
    }
}
