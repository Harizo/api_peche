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
            'id_espece'                  	    =>      $SIP_saisie_collecte_halieutique['id_espece'],              
            'id_permis'                         =>      $SIP_saisie_collecte_halieutique['id_permis'],              
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

                esp.id as id_espece,
                esp.nom as nom,
                esp.nom_local as nom_local,
                esp.nom_francaise as nom_francaise,
                esp.nom_scientifique as nom_scientifique,


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
                sip_conservation as cons,
                sip_espece as esp
            where
                sch.id_permis = cm.id

                and esp.id = sch.id_espece
                
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

    public function findAllClefSaisie_collecte_halieutique($cle_etranger)
    {
        $sql = " select *
            FROM sip_saisie_collecte_halieutique
            WHERE sip_saisie_collecte_halieutique.id_presentation = ".$cle_etranger."
            OR sip_saisie_collecte_halieutique.id_conservation = ".$cle_etranger."
            OR sip_saisie_collecte_halieutique.id_permis = ".$cle_etranger."
        ";

        return $this->db->query($sql)->result();
                                
        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }

    }
    public function findClePresentation($id_presentation)
    {
        $sql = " select *
            FROM sip_saisie_collecte_halieutique
            WHERE sip_saisie_collecte_halieutique.id_presentation = ".$id_presentation."
        ";

        return $this->db->query($sql)->result();
                                
        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }

    }
    public function findCleConservation($id_conservation)
    {
        $sql = " select *
            FROM sip_saisie_collecte_halieutique
            WHERE sip_saisie_collecte_halieutique.id_conservation = ".$id_conservation."
        ";

        return $this->db->query($sql)->result();
                                
        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }

    }
    public function findClePermis($id_permis)
    {
        $sql = " select *
            FROM sip_saisie_collecte_halieutique
            WHERE sip_saisie_collecte_halieutique.id_permis = ".$id_permis."
        ";

        return $this->db->query($sql)->result();
                                
        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }

    }
}
