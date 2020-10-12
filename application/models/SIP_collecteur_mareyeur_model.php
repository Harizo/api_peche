<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_collecteur_mareyeur_model extends CI_Model {
    protected $table = 'sip_collecteurs_mareyeur';

    public function add($SIP_collecteur_mareyeur) {
        $this->db->set($this->_set($SIP_collecteur_mareyeur))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $SIP_collecteur_mareyeur) {
        $this->db->set($this->_set($SIP_collecteur_mareyeur))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($SIP_collecteur_mareyeur) {
        return array(
            'code'                  =>      $SIP_collecteur_mareyeur['code'],
            'nom'                   =>      $SIP_collecteur_mareyeur['nom'],                 
            'type_genre'            =>      $SIP_collecteur_mareyeur['type_genre'],                 
            'adresse'               =>      $SIP_collecteur_mareyeur['adresse'],                 
            'ref_autorisation'      =>      $SIP_collecteur_mareyeur['ref_autorisation'],                 
            'is_coll_eau_douce'     =>      $SIP_collecteur_mareyeur['is_coll_eau_douce'],                 
            'is_coll_marine'        =>      $SIP_collecteur_mareyeur['is_coll_marine'],                 
            'is_mareyeur'           =>      $SIP_collecteur_mareyeur['is_mareyeur']               

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

    public function findAllby_district_permis($id_district) {
               
        $result = $this->db->select(
                                        '
                                            scm.id as id,
                                            scm.code as code,
                                            scm.type_genre as type_genre,
                                            scm.nom,
                                            scm.adresse as adresse,
                                            scm.ref_autorisation as ref_autorisation,
                                            scm.is_mareyeur as is_mareyeur,
                                            scm.is_coll_marine as is_coll_marine,
                                            scm.is_coll_eau_douce

                                        '
                                    )
                            ->from('sip_collecteurs_mareyeur as scm')
                            ->join('sip_permis as sp', 'scm.id = sp.id_collecteur_mareyeur')
                            ->where("sp.id_district", $id_district)
                            ->order_by('scm.nom')
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
