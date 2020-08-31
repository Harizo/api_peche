<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_espece_model extends CI_Model {
    protected $table = 'sip_espece';

    public function add($sip_espece) {
        $this->db->set($this->_set($sip_espece))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $sip_espece) {
        $this->db->set($this->_set($sip_espece))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null; 
        }                      
    }
    public function _set($sip_espece) {
        
         return array( 
            'code'              =>      $sip_espece['code'],
            'nom'               =>      $sip_espece['nom'],
            'id_famille'        =>      $sip_espece['id_famille'],
            'nom_francaise'     =>      $sip_espece['nom_francaise'],        
            'nom_scientifique'  =>      $sip_espece['nom_scientifique'],
            'nom_local'         =>      $sip_espece['nom_local'],                        
            'type_espece'       =>      $sip_espece['typ_esp_id']
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
               
        $result =  $this->db->select('sp.id as id, sp.nom, sp.code, sp.nom_local, sp.nom_francaise, sp.nom_scientifique, tep.id as typ_esp_id, tep.libelle as type_lib, fam.id as id_famille, fam.libelle as libelle_famille')
                        ->from('sip_espece as sp, sip_type_espece as tep, sip_famille as fam')
                        ->where('sp.type_espece=tep.id')
                        ->where('sp.id_famille=fam.id')
                        ->order_by('sp.nom')
                        ->get()
                        ->result(); 
        if($result)
        {
            return $result;
        }else{
            return null;
        }  
    }


    public function find_all_by_type($type_espece) {
               
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('nom')
                        ->where("type_espece", $type_espece)
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }

	public function find_all_by_navire($id_navire) {
        $requete="select distinct esp.id,esp.code,esp.nom,esp.type_espece,esp.nom_francaise, esp.nom_scientifique,esp.nom_francaise,esp.nom_local
			 from sip_espece as esp,sip_autorisation_navire as nav  
			 where esp.id in (nav.espece_1_autorisee,nav.espece_2_autorisee) 
			   and nav.id_navire=".$id_navire;
		return $this->db->query($requete)->result();			  
		
	}
    
    public function findById($id)  {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }  
    }
     
     public function findFamille($id_famille)
    {
         $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where('id_famille='.$id_famille)
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        } 
    }    
}
