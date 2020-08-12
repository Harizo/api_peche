<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_commercialisation_eau_douce_model extends CI_Model {
    protected $table = 'sip_commercialisation_eau_douce';

    public function add($SIP_commercialisation_eau_douce) {
        $this->db->set($this->_set($SIP_commercialisation_eau_douce))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $SIP_commercialisation_eau_douce) {
        $this->db->set($this->_set($SIP_commercialisation_eau_douce))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($SIP_commercialisation_eau_douce) {
        return array(
            'id_permis'                  	    =>      $SIP_commercialisation_eau_douce['id_permis'],               
            'id_espece'                         =>      $SIP_commercialisation_eau_douce['id_espece'],            
            'numero_visa'                       =>      $SIP_commercialisation_eau_douce['numero_visa'],              
            'numero_cos'                        =>      $SIP_commercialisation_eau_douce['numero_cos'],              
            'annee'               				=>      $SIP_commercialisation_eau_douce['annee'],                 
            'mois'      						=>      $SIP_commercialisation_eau_douce['mois'],                 
            'id_conservation'     				=>      $SIP_commercialisation_eau_douce['id_conservation'],                
            'id_presentation'        			=>      $SIP_commercialisation_eau_douce['id_presentation'],                 
            'coefficiant_conservation'        	=>      $SIP_commercialisation_eau_douce['coefficiant_conservation'],

            'vl_qte'                            =>      $SIP_commercialisation_eau_douce['vl_qte'],
            'vl_prix_par_kg'                    =>      $SIP_commercialisation_eau_douce['vl_prix_par_kg'],
            'vl_poids_vif'                      =>      $SIP_commercialisation_eau_douce['vl_poids_vif']


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
                sch.coefficiant_conservation,

                esp.id as id_espece,
                esp.nom as nom,
                esp.nom_local as nom_local,
                esp.nom_francaise as nom_francaise,
                esp.nom_scientifique as nom_scientifique,

                sch.numero_visa,
                sch.numero_cos,
                sch.annee,
                sch.mois,

                sch.vl_qte,
                sch.vl_prix_par_kg,
                sch.vl_poids_vif
               

            from
                sip_commercialisation_eau_douce as sch,
                sip_permis as cm,
                sip_presentation as pres,
                sip_conservation as cons,
                sip_espece as esp
            where
                sch.id_permis = cm.id
                
                and sch.id_espece = esp.id
                and sch.id_presentation = pres.id
                and sch.id_conservation = cons.id
                and cm.id = ".$id_permis." 
            order by sch.annee desc
        " ;
        
        return $this->db->query($sql)->result();
    }

    public function compte_nbr_fiche($id_permis)
    {
        $sql =  "
                    select 
                        scm.annee as annee_pple,

                        (
                            select 
                                count(scm_fils.id) 
                            from 
                                sip_commercialisation_eau_douce as scm_fils
                            where 
                                scm_fils.id_permis = ".$id_permis."
                                and scm_fils.annee = annee_pple
                                and scm_fils.mois = 1

                        ) as nbr_janvier,

                        (
                            select 
                                count(scm_fils.id) 
                            from 
                                sip_commercialisation_eau_douce as scm_fils
                            where 
                                scm_fils.id_permis = ".$id_permis."
                                and scm_fils.annee = annee_pple
                                and scm_fils.mois = 2

                        ) as nbr_fevrier,
                        (
                            select 
                                count(scm_fils.id) 
                            from 
                                sip_commercialisation_eau_douce as scm_fils
                            where 
                                scm_fils.id_permis = ".$id_permis."
                                and scm_fils.annee = annee_pple
                                and scm_fils.mois = 3

                        ) as nbr_mars,
                        (
                            select 
                                count(scm_fils.id) 
                            from 
                                sip_commercialisation_eau_douce as scm_fils
                            where 
                                scm_fils.id_permis = ".$id_permis."
                                and scm_fils.annee = annee_pple
                                and scm_fils.mois = 4

                        ) as nbr_avril,
                        (
                            select 
                                count(scm_fils.id) 
                            from 
                                sip_commercialisation_eau_douce as scm_fils
                            where 
                                scm_fils.id_permis = ".$id_permis."
                                and scm_fils.annee = annee_pple
                                and scm_fils.mois = 5

                        ) as nbr_mai,
                        (
                            select 
                                count(scm_fils.id) 
                            from 
                                sip_commercialisation_eau_douce as scm_fils
                            where 
                                scm_fils.id_permis = ".$id_permis."
                                and scm_fils.annee = annee_pple
                                and scm_fils.mois = 6

                        ) as nbr_juin,
                        (
                            select 
                                count(scm_fils.id) 
                            from 
                                sip_commercialisation_eau_douce as scm_fils
                            where 
                                scm_fils.id_permis = ".$id_permis."
                                and scm_fils.annee = annee_pple
                                and scm_fils.mois = 7

                        ) as nbr_juillet,
                        (
                            select 
                                count(scm_fils.id) 
                            from 
                                sip_commercialisation_eau_douce as scm_fils
                            where 
                                scm_fils.id_permis = ".$id_permis."
                                and scm_fils.annee = annee_pple
                                and scm_fils.mois = 8

                        ) as nbr_aout,
                        (
                            select 
                                count(scm_fils.id) 
                            from 
                                sip_commercialisation_eau_douce as scm_fils
                            where 
                                scm_fils.id_permis = ".$id_permis."
                                and scm_fils.annee = annee_pple
                                and scm_fils.mois = 9

                        ) as nbr_septembre,
                        (
                            select 
                                count(scm_fils.id) 
                            from 
                                sip_commercialisation_eau_douce as scm_fils
                            where 
                                scm_fils.id_permis = ".$id_permis."
                                and scm_fils.annee = annee_pple
                                and scm_fils.mois = 10

                        ) as nbr_octobre,
                        (
                            select 
                                count(scm_fils.id) 
                            from 
                                sip_commercialisation_eau_douce as scm_fils
                            where 
                                scm_fils.id_permis = ".$id_permis."
                                and scm_fils.annee = annee_pple
                                and scm_fils.mois = 11

                        ) as nbr_novembre,
                        (
                            select 
                                count(scm_fils.id) 
                            from 
                                sip_commercialisation_eau_douce as scm_fils
                            where 
                                scm_fils.id_permis = ".$id_permis."
                                and scm_fils.annee = annee_pple
                                and scm_fils.mois = 12

                        ) as nbr_decembre,
                        (
                            select 
                                count(scm_fils.id) 
                            from 
                                sip_commercialisation_eau_douce as scm_fils
                            where 
                                scm_fils.id_permis = ".$id_permis."
                                and scm_fils.annee = annee_pple
                                and scm_fils.mois = 1

                        ) as nbr_janvier
                    from 
                        sip_commercialisation_eau_douce as scm
                    where
                        id_permis=".$id_permis."
                    group by 
                        annee

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
/*
     public function findAllBySIP_commercialisation_eau_douce($cle_etranger)
    {
        $sql = " select *
            FROM sip_commercialisation_eau_douce
            WHERE sip_commercialisation_eau_douce.id_presentation = ".$cle_etranger."
            OR sip_commercialisation_eau_douce.id_conservation = ".$cle_etranger."
            OR sip_commercialisation_eau_douce.id_permis = ".$cle_etranger."
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
*/
    public function findClePresentation($id_presentation)
    {
        $sql = " select *
            FROM sip_commercialisation_eau_douce
            WHERE sip_commercialisation_eau_douce.id_presentation = ".$id_presentation."
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
            FROM sip_commercialisation_eau_douce
            WHERE sip_commercialisation_eau_douce.id_conservation = ".$id_conservation."
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
            FROM sip_commercialisation_eau_douce
            WHERE sip_commercialisation_eau_douce.id_permis = ".$id_permis."
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
