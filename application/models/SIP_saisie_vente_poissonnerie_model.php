<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_saisie_vente_poissonnerie_model extends CI_Model {
    protected $table = 'SIP_saisie_vente_poissonnerie';

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
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($SIP_saisie_vente_poissonnerie) {
        return array(
            'id_poissonnerie'           =>      $SIP_saisie_vente_poissonnerie['id_poissonnerie'],              
            'reference_fournisseur'     =>      $SIP_saisie_vente_poissonnerie['reference_fournisseur'],
           'famille_rh'                 =>      $SIP_saisie_vente_poissonnerie['famille_rh'],                 
            'origine_produits'      	=>      $SIP_saisie_vente_poissonnerie['origine_produits'],                 
            'id_conservation'     	    =>      $SIP_saisie_vente_poissonnerie['id_conservation'],                 
            'designation_article'       =>      $SIP_saisie_vente_poissonnerie['designation_article'],                 
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
        {
            return true;
        }else{
            return null;
        }  
    }
    public function findAll() {
               
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('designation_article')
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

   
    public function findClePresentation($id_presentation)
    {
        $sql = " select *
            FROM SIP_saisie_vente_poissonnerie
            WHERE SIP_saisie_vente_poissonnerie.id_presentation = ".$id_presentation."
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
            FROM SIP_saisie_vente_poissonnerie
            WHERE SIP_saisie_vente_poissonnerie.id_conservation = ".$id_conservation."
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
    public function findCleFamille($famille_rh)
    {
        $sql = " select *
            FROM SIP_saisie_vente_poissonnerie
            WHERE SIP_saisie_vente_poissonnerie.famille_rh = ".$famille_rh."
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
    public function findClePoissonnerie($id_poissonnerie)
    {
        $sql = " select *
            FROM SIP_saisie_vente_poissonnerie
            WHERE SIP_saisie_vente_poissonnerie.id_poissonnerie = ".$id_poissonnerie."
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
