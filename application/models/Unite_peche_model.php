<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Unite_peche_model extends CI_Model {
    protected $table = 'unite_peche';

    public function add($unite_peche)
    {   $this->db->set($this->_set($unite_peche))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }
        else
        {
            return null;
        }                    
    }
    public function update($id, $unite_peche)
    {   $this->db->set($this->_set($unite_peche))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }
        else
        {
            return null;
        }                      
    }
    public function _set($unite_peche)
    {   return array(
            'id_type_canoe'           =>      $unite_peche['id_type_canoe'],
            'id_type_engin'           =>      $unite_peche['id_type_engin'],
          //  'id_site_embarquement'    =>      $unite_peche['site_embarquement_id'],
            'libelle'                 =>      $unite_peche['libelle']                       
        );
    }
    public function delete($id)
    {   $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }
        else
        {
            return null;
        }  
    }
    public function findAll()
    {   $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('libelle')
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
    public function findById($id)  {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
           
    }   
  /*public function findById($id)
    {   $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("id", $id)
                        ->order_by('id', 'asc')
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
    }*/

    public function findAllBySite_embarquement($site_embarquement_id) {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('id_site_embarquement')
                        ->where("id_site_embarquement", $site_embarquement_id)
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                 
    }	
    
}
