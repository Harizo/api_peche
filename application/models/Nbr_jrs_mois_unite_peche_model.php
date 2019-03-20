<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nbr_jrs_mois_unite_peche_model extends CI_Model
{
    protected $table = 'nbr_jrs_peche_mois_unite_peche';


    public function add($nbr_jrs_mois_unite_peche)
    {
        $this->db->set($this->_set($nbr_jrs_mois_unite_peche))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }


    public function update($id, $nbr_jrs_mois_unite_peche)
    {
        $this->db->set($this->_set($nbr_jrs_mois_unite_peche))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }

    public function _set($nbr_jrs_mois_unite_peche)
    {
        return array(
            'id_unite_peche'     =>      $nbr_jrs_mois_unite_peche['id_unite_peche'] ,
            'max_jrs_peche'     =>      $nbr_jrs_mois_unite_peche['max_jrs_peche']                       
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
                        ->order_by('max_jrs_peche')
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


}
