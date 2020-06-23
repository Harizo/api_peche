<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_saisie_collecte_halieutique_model extends CI_Model {
    protected $table = 'sip_saisie_collecte_halieutique';

    public function add($SIP_saisie_collecte_halieutique) {
        $this->db->set($this->_set($SIP_saisie_collecte_halieutique))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $SIP_saisie_collecte_halieutique) {
        $this->db->set($this->_set($SIP_saisie_collecte_halieutique))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($SIP_saisie_collecte_halieutique) {
        return array(
            'id_permis'                  	    =>      $SIP_saisie_collecte_halieutique['id_permis'],              
            'annee'               				=>      $SIP_saisie_collecte_halieutique['annee'],                 
            'mois'      						=>      $SIP_saisie_collecte_halieutique['mois'],                 
            'id_conservation'     				=>      $SIP_saisie_collecte_halieutique['id_conservation'],                 
            'quantite'        					=>      $SIP_saisie_collecte_halieutique['quantite'],                 
            'prix'        						=>      $SIP_saisie_collecte_halieutique['prix'],                 
            'id_presentation'        			=>      $SIP_saisie_collecte_halieutique['id_presentation'],                 
            'coefficiant_conservation'        	=>      $SIP_saisie_collecte_halieutique['coefficiant_conservation'],                 
            'valeur'        					=>      $SIP_saisie_collecte_halieutique['valeur']          

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


    public function find_all_join($id_permis)
    {

        $sql = 
        "
            select 

                sch.id as id,

                cons.id as id_conservation,
                cons.libelle as libelle_conservation,

                pres.id as id_presentation,
                pres.libelle as libelle_presentation,

                sch.annee,
                sch.mois,
                sch.quantite,
                sch.prix,
                sch.coefficiant_conservation,
                sch.valeur

            from
                sip_saisie_collecte_halieutique as sch,
                sip_permis as cm,
                sip_presentation as pres,
                sip_conservation as cons
            where
                sch.id_permis = cm.id
                
                and sch.id_presentation = pres.id
                and sch.id_conservation = cons.id
                and cm.id = ".$id_permis." 
            order by sch.annee desc

        " ;
        return $this->db->query($sql)->result();
    }
    public function findById($id)  {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }  
    }
}
