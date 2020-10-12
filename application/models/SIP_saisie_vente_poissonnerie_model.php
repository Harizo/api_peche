<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_saisie_vente_poissonnerie_model extends CI_Model {
    protected $table = 'sip_saisie_vente_poisonnerie';

    public function add($SIP_saisie_vente_poissonnerie) {
        $this->db->set($this->_set($SIP_saisie_vente_poissonnerie))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $SIP_saisie_vente_poissonnerie) {
        $this->db->set($this->_set($SIP_saisie_vente_poissonnerie))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
            return true;
        else
            return null;
    }
    public function _set($SIP_saisie_vente_poissonnerie) {
        return array(
            'id_poissonnerie'           =>      $SIP_saisie_vente_poissonnerie['id_poissonnerie'],
            'reference_fournisseur'     =>      $SIP_saisie_vente_poissonnerie['reference_fournisseur'],
           'famille_rh'                 =>      $SIP_saisie_vente_poissonnerie['famille_rh'],                 
            'origine_produits'      	=>      $SIP_saisie_vente_poissonnerie['origine_produits'],                 
            'id_conservation'     	    =>      $SIP_saisie_vente_poissonnerie['id_conservation'],                 
            'designation_article'       =>      $SIP_saisie_vente_poissonnerie['designation_article'],                 
            'type_famille'              =>      $SIP_saisie_vente_poissonnerie['type_famille'],                 
            'mois'                      =>      $SIP_saisie_vente_poissonnerie['mois'],                 
            'annee'                     =>      $SIP_saisie_vente_poissonnerie['annee'],  
            'quantite_vendu'            =>      $SIP_saisie_vente_poissonnerie['quantite_vendu'],                 
            'id_presentation'           =>      $SIP_saisie_vente_poissonnerie['id_presentation'],                 
            'chiffre_affaire'        	=>      $SIP_saisie_vente_poissonnerie['chiffre_affaire'],                 
            'prix_kg'        			=>      $SIP_saisie_vente_poissonnerie['prix_kg'] ,          
            'observations'              =>      $SIP_saisie_vente_poissonnerie['observations']          

        );
    }
    public function delete($id) {
        $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1)
        
            return true;
        else
            return null;
    }
    public function findAll() {

        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('designation_article')
                        ->get()
                        ->result();

        if($result)
            return $result;
        else
            return null;
    }

    public function findById($id)  {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) 
            return $q->row();
    }

   
    public function findClePresentation($id_presentation)
    {
        $sql = " select *
            FROM sip_saisie_vente_poisonnerie
            WHERE sip_saisie_vente_poisonnerie.id_presentation = ".$id_presentation."
        ";

        return $this->db->query($sql)->result();
                                
        if($result)
            return $result;
        else
            return null;

    }
    public function findCleConservation($id_conservation)
    {
        $sql = " select *
            FROM sip_saisie_vente_poisonnerie
            WHERE sip_saisie_vente_poisonnerie.id_conservation = ".$id_conservation."
        ";

        return $this->db->query($sql)->result();
                                
        if($result)
            return $result;
        else
            return null;

    }
    public function findCleFamille($famille_rh)
    {
        $sql = " select *
            FROM sip_saisie_vente_poisonnerie
            WHERE sip_saisie_vente_poisonnerie.famille_rh = ".$famille_rh."
        ";

        return $this->db->query($sql)->result();
                                
        if($result)
            return $result;
        else
            return null;

    }
    public function findClePoissonnerie($id_poissonnerie)
    {
        $sql = " select vp.id as id,
                        vp.annee,
                        vp.mois,
                        vp.reference_fournisseur, 
                        vp.origine_produits, 
                        fm.libelle AS libelle_famille, 
                        vp.type_famille,
                        vp.designation_article, 
                        pres.libelle AS libelle_presentation,
                        cons.libelle AS libelle_conservation,
                        CONCAT(ROUND(vp.quantite_vendu,2), ' Kg') AS quantite_vendu,
                        CONCAT(ROUND(vp.chiffre_affaire,2), ' Ar') AS chiffre_affaire, 
                        CONCAT(ROUND(vp.prix_kg,2), ' Ar/kg') AS prix_kg,
                        vp.observations
            
                FROM    sip_saisie_vente_poisonnerie AS vp, 
                        sip_famille AS fm, 
                        sip_poissonnerie AS pois,
                        sip_presentation AS pres,  
                        sip_conservation AS cons 
                
                WHERE vp.famille_rh=fm.id 
                      and vp.id_presentation=pres.id 
                      and vp.id_conservation=cons.id 
                      and vp.id_poissonnerie = ".$id_poissonnerie."
                      and pois.id=vp.id_poissonnerie
        
                ORDER BY vp.reference_fournisseur ";

        return $this->db->query($sql)->result();
                                
        if($result)
            return $result;
        else
            return null;

    }

    public function findPoissonnerieByAnneeMois($id_poissonnerie,$annee,$mois)
    {

        $sql = " select vp.id as id,
                        vp.annee,
                        vp.mois,
                        vp.reference_fournisseur, 
                        vp.origine_produits, 
                        fm.libelle AS libelle_famille, 
                        vp.type_famille,
                        vp.designation_article, 
                        pres.libelle AS libelle_presentation,
                        cons.libelle AS libelle_conservation,
                        CONCAT(ROUND(vp.quantite_vendu,2), ' Kg') AS quantite_vendu,
                        CONCAT(ROUND(vp.chiffre_affaire,2), ' Ar') AS chiffre_affaire, 
                        CONCAT(ROUND(vp.prix_kg,2), ' Ar/kg') AS prix_kg, 
                        vp.observations
            
                FROM    sip_saisie_vente_poisonnerie AS vp, 
                        sip_famille AS fm, 
                        sip_poissonnerie AS pois,
                        sip_presentation AS pres, 
                        sip_conservation AS cons 
                
                WHERE vp.famille_rh=fm.id 
                      and vp.id_presentation=pres.id 
                      and vp.id_conservation=cons.id 
                      and vp.id_poissonnerie = ".$id_poissonnerie."
                      and vp.annee=".$annee."
                      and vp.mois=".$mois."
                      and pois.id=vp.id_poissonnerie
        
                ORDER BY vp.annee DESC ";

        return $this->db->query($sql)->result();
                                
        if($result)
            return $result;
        else
            return null;

    }

    public function findPoissonnerieByMois($id_poissonnerie,$mois)
    {

        $sql = " select vp.id as id,
                        vp.annee,
                        vp.mois,
                        vp.reference_fournisseur, 
                        vp.origine_produits, 
                        fm.libelle AS libelle_famille, 
                        vp.type_famille,
                        vp.designation_article, 
                        pres.libelle AS libelle_presentation,
                        cons.libelle AS libelle_conservation,
                        CONCAT(ROUND(vp.quantite_vendu,2), ' Kg') AS quantite_vendu,
                        CONCAT(ROUND(vp.chiffre_affaire,2), ' Ar') AS chiffre_affaire, 
                        CONCAT(ROUND(vp.prix_kg,2), ' Ar/kg') AS prix_kg,
                        vp.observations
            
                FROM    sip_saisie_vente_poisonnerie AS vp, 
                        sip_famille AS fm, 
                        sip_poissonnerie AS pois,
                        sip_presentation AS pres, 
                        sip_conservation AS cons 
                
                WHERE vp.famille_rh=fm.id 
                      and vp.id_presentation=pres.id 
                      and vp.id_conservation=cons.id 
                      and vp.id_poissonnerie = ".$id_poissonnerie."
                      and pois.id=vp.id_poissonnerie
                      and vp.mois=".$mois."
        
                ORDER BY vp.annee DESC ";

        return $this->db->query($sql)->result();
                                
        if($result)
            return $result;
        else
            return null;

    }

    public function findPoissonnerieByAnne($id_poissonnerie,$annee)
    {

        $sql = " select vp.id as id,
                        vp.annee,
                        vp.mois,
                        vp.reference_fournisseur, 
                        vp.origine_produits, 
                        fm.libelle AS libelle_famille, 
                        vp.type_famille,
                        vp.designation_article, 
                        pres.libelle AS libelle_presentation,
                        cons.libelle AS libelle_conservation,
                        CONCAT(ROUND(vp.quantite_vendu,2), ' Kg') AS quantite_vendu,
                        CONCAT(ROUND(vp.chiffre_affaire,2), ' Ar') AS chiffre_affaire, 
                        CONCAT(ROUND(vp.prix_kg,2), ' Ar/kg') AS prix_kg, 
                        vp.observations
            
                FROM    sip_saisie_vente_poisonnerie AS vp, 
                        sip_famille AS fm, 
                        sip_poissonnerie AS pois,
                        sip_presentation AS pres, 
                        sip_conservation AS cons 
                
                WHERE vp.famille_rh=fm.id 
                      and vp.id_presentation=pres.id 
                      and vp.id_conservation=cons.id 
                      and vp.id_poissonnerie = ".$id_poissonnerie."
                      and pois.id=vp.id_poissonnerie
                      and vp.annee=".$annee."
        
                ORDER BY vp.mois DESC ";

        return $this->db->query($sql)->result();
                                
        if($result)
            return $result;
        else
            return null;

    }

//  MITOVY NY qte_vendues_par_poissonneries SY NY qte_vendues_par_poissonneries_mois
    // FA NY RAY MISY MOIS NY RAY TSISI

    public function qte_vendues_par_poissonneries()
    {
       $sql = " CALL sip_vente_poissonnerie_req_1()  ";

        return $this->db->query($sql)->result();
                                
        if($result)
            return $result;
        else
            return null;
    }

     public function qte_vendues_par_poissonneries_mois()
    {
       $sql = " CALL sip_vente_poissonnerie_req_2() " ;

        return $this->db->query($sql)->result();
                                
        if($result)
            return $result;
        else
            return null;
    }

    
// misy erreur ato
    public function qte_vendues_par_famille()
    {
        $sql = " CALL sip_vente_poissonnerie_req_4() " ;

        return $this->db->query($sql)->result();
                                
        if($result)
            return $result;
        else
            return null;
    }

    public function qte_vendues_produit_par_poissonneries()
    {
        $sql = " CALL sip_vente_poissonnerie_req_7() " ;

        return $this->db->query($sql)->result();
                                
        if($result)
            return $result;
        else
            return null;
    }

    public function prix_moyen_prod_par_poissonnerie()
    {
        $sql = " CALL sip_vente_poissonnerie_req_3() " ;

        return $this->db->query($sql)->result();
                                
        if($result)
            return $result;
        else
            return null;
    }

    public function prix_moyenne_par_famille($value='')
    {
        $sql = " CALL sip_vente_poissonnerie_req_5() " ;
        
        return $this->db->query($sql)->result();
                                
        if($result)
            return $result;
        else
            return null;
    }

     public function chif_aff_par_produit_poissonneries($value='')
    {
        $sql = " CALL sip_vente_poissonnerie_req_6() " ;
        
        return $this->db->query($sql)->result();
                                
        if($result)
            return $result;
        else
            return null;
    }
}