<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_production_crevette_model extends CI_Model {
    protected $table = 'sip_production_crevette';

    public function add($SIP_production_crevette) {
        $this->db->set($this->_set($SIP_production_crevette))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $SIP_production_crevette) {
        $this->db->set($this->_set($SIP_production_crevette))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($SIP_production_crevette) {
        return array(
            'id_bateau_crevette'     =>      $SIP_production_crevette['id_bateau_crevette'],
            'zone_peche'             =>      $SIP_production_crevette['zone_peche'],      
            'annee'                  =>      $SIP_production_crevette['annee'],      
            'num_maree'              =>      $SIP_production_crevette['num_maree'],      
            'maree'                  =>      $SIP_production_crevette['maree'],      
            'qte_crevette'           =>      $SIP_production_crevette['qte_crevette'],      
            'qte_poisson'           =>      $SIP_production_crevette['qte_poisson'],      
            'nbr_fiche'              =>      $SIP_production_crevette['nbr_fiche']

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
                        ->order_by('nom')
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }

    public function findAllbybateau($id_bateau_crevette) {
               
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("id_bateau_crevette", $id_bateau_crevette)
                        ->order_by('annee')
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
