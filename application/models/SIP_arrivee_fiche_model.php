<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_arrivee_fiche_model extends CI_Model {
    protected $table = 'sip_arrivee_fiche';

    public function add($SIP_arrivee_fiche) {
        $this->db->set($this->_set($SIP_arrivee_fiche))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $SIP_arrivee_fiche) {
        $this->db->set($this->_set($SIP_arrivee_fiche))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($SIP_arrivee_fiche) {
        return array(
            'id_permis'                  	    	=>      $SIP_arrivee_fiche['id_permis'],              
            'annee'                                 =>      $SIP_arrivee_fiche['annee'],              
            'janvier'                       		=>      $SIP_arrivee_fiche['janvier'],
            'fevrier'                       		=>      $SIP_arrivee_fiche['fevrier'],
            'mars'                       			=>      $SIP_arrivee_fiche['mars'],
            'avril'                       			=>      $SIP_arrivee_fiche['avril'],
            'mai'                       			=>      $SIP_arrivee_fiche['mai'],
            'juin'                       			=>      $SIP_arrivee_fiche['juin'],
            'juillet'                       		=>      $SIP_arrivee_fiche['juillet'],
            'aout'                       			=>      $SIP_arrivee_fiche['aout'],
            'septembre'                       		=>      $SIP_arrivee_fiche['septembre'],
            'octobre'                       		=>      $SIP_arrivee_fiche['octobre'],
            'novembre'                       		=>      $SIP_arrivee_fiche['novembre'],
            'decembre'                       		=>      $SIP_arrivee_fiche['decembre']
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


    public function finda_ll_By_permis($id_permis) {
               
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('annee')
                        ->where("id_permis", $id_permis)
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
