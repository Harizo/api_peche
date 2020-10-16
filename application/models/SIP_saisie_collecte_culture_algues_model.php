<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_saisie_collecte_culture_algues_model extends CI_Model {
    protected $table = 'SIP_saisie_collecte_culture_algues';

    public function add($SIP_saisie_collecte_culture_algues) {
        $this->db->set($this->_set($SIP_saisie_collecte_culture_algues))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $SIP_saisie_collecte_culture_algues) {
        $this->db->set($this->_set($SIP_saisie_collecte_culture_algues))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($SIP_saisie_collecte_culture_algues) {
        return array(
                      
            'id_commune'    =>      $SIP_saisie_collecte_culture_algues['id_commune'],
            'id_fokontany'  =>      $SIP_saisie_collecte_culture_algues['id_fokontany'],
            'annee'         =>      $SIP_saisie_collecte_culture_algues['annee'],
            'mois'          =>      $SIP_saisie_collecte_culture_algues['mois'],                 
            'village'      	=>      $SIP_saisie_collecte_culture_algues['village'],                 
            'quantite'     	=>      $SIP_saisie_collecte_culture_algues['quantite'],                 
            'montant'       =>      $SIP_saisie_collecte_culture_algues['montant']   

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
               
        $result =  $this->db->select('alg.id, alg.id_commune, cm.nom, alg.annee, alg.mois, alg.village, alg.quantite, alg.montant')
                        ->from('SIP_saisie_collecte_culture_algues as alg, commune as cm')
                        ->where('alg.id_commune=cm.id')
                        ->order_by('cm.nom')
                        ->get()
                        ->result() ;  
      
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
    
    public function findByFiltre($requete)
    {
      
      $sql = " select alg.id id, 
                alg.id_commune id_commune,
                cm.nom AS communes,
                alg.id_fokontany id_fokontany,
                fkt.nom AS fokontany,
                alg.annee,
                alg.mois,
                alg.village,
                alg.quantite,
                alg.montant
        FROM sip_saisie_collecte_culture_algues alg,
                commune cm,
                fokontany fkt
        WHERE alg.id_fokontany=fkt.id AND
                alg.id_commune=cm.id ".$requete."  
        ORDER BY alg.village
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

    
    public function findAndGetElements($requete)
    {
      
      $sql = " select 
                fkt.nom AS fokontany,
                alg.annee ,
                sip_convert_mois(alg.mois) AS mois,
                alg.village,
                CONCAT(ROUND(alg.quantite,2), ' Kg') AS quantite,
                CONCAT(ROUND(alg.montant, 2),' Ar') AS montant
        FROM sip_saisie_collecte_culture_algues alg,
                commune cm,
                fokontany fkt
        WHERE alg.id_fokontany=fkt.id AND
                alg.id_commune=cm.id ".$requete."  
        ORDER BY alg.village
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
