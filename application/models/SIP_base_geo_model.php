<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_base_geo_model extends CI_Model {
    protected $table = 'sip_base_geo';

    public function add($b_geo)
    {
        $this->db->set($this->_set($b_geo))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }


    public function update($id, $b_geo)
    {
        $this->db->set($this->_set($b_geo))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }

    public function _set($b_geo)
    {
        return array(
            'libelle'   =>      $b_geo['libelle']
        );
    }


    public function delete($id)
    {
        $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }  
    }

    public function findAll()
    {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('id')
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }

    public function findById($id)
    {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return null;
    }
    function get($id)
   {
       $this->db-> where ($this->idkey, $id);
       $query = $this-> db-> get ($this->table);
       return $query-> ressult_array ();   
   }
}