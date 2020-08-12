<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class sip_type_espece_model extends CI_Model {
    protected $table = 'sip_type_espece';

    public function add($sip_type_espece)
    {
        $this->db->set($this->_set($sip_type_espece))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }


    public function update($id, $sip_type_espece)
    {
        $this->db->set($this->_set($sip_type_espece))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }

    public function _set($sip_type_espece)
    {
        return array(
            'id'        =>      $sip_type_espece['id'],
            'libelle'         =>      $sip_type_espece['libelle'],
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

    public function findByIdtab($id)
    {   $result =  $this->db->select('id as id_sip_type_espece, libelle')
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

    public function findByIdtable($id)
    {   $result =  $this->db->select('*')
                        ->from('sip_espece')
                        ->join('sip_type_espece', 'inner')
                        ->where("sip_espece.type_espece= sip_type_espece.id", $id)
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

    public function DelFils($id)
    {
         $this->db->where('type_espece', (int) $cle_etrangere)->delete('sip_espece');
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }  
    }

    function getlist()
    {
        $this->db->get($this->table);
        return $query->result_array();
        
    }


   function get($id)
   {
       $this->db-> where ($this->idkey, $id);
       $query = $this-> db-> get ($this->table);
       return $query-> ressult_array ();   
   }
}