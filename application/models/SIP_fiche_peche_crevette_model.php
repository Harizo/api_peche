<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_fiche_peche_crevette_model extends CI_Model {
    protected $table = 'sip_fiche_peche_crevette';

    public function add($SIP_fiche_peche_crevette) {
        $this->db->set($this->_set($SIP_fiche_peche_crevette))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $SIP_fiche_peche_crevette) {
        $this->db->set($this->_set($SIP_fiche_peche_crevette))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($SIP_fiche_peche_crevette) {
        return array(
            'id_bateau_crevette'        =>      $SIP_fiche_peche_crevette['id_bateau_crevette'],
            'numfp'                     =>      $SIP_fiche_peche_crevette['numfp'],      
            'nom_capitaine'             =>      $SIP_fiche_peche_crevette['nom_capitaine'],      
            'date_depart'               =>      $SIP_fiche_peche_crevette['date_depart'],      
            'date_retour'               =>      $SIP_fiche_peche_crevette['date_retour']

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
                        ->order_by('numfp')
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
                        ->order_by('numfp')
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
