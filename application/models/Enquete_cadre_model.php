<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Enquete_cadre_model extends CI_Model {
    protected $table = 'enquete_cadre';

    public function add($enquete_cadre) {
        $this->db->set($this->_set($enquete_cadre))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $enquete_cadre) {
        $this->db->set($this->_set($enquete_cadre))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1) {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($enquete_cadre) {
        return array(
            'id_region'               =>      $enquete_cadre['id_region'],
            'id_district'             =>      $enquete_cadre['id_district'],
            'id_site_embarquement'    =>      $enquete_cadre['id_site_embarquement'],
            'id_unite_peche'          =>      $enquete_cadre['id_unite_peche'],
            'nbr_unite_peche'         =>      $enquete_cadre['nbr_unite_peche']                     
        );
    }
    public function delete($id) {
        $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1) {
            return true;
        }else{
            return null;
        }  
    }
    public function findAll() {
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

public function findById($id)  {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
           
    }

}
