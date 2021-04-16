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
               
        $sql = "select  esp.id as id, 
                        esp.code, 
                        esp.nom, 
                        esp.type_espece AS typ_esp_id, 
                        tesp.libelle AS type_lib, 
                        esp.nom_scientifique, 
                        esp.nom_francaise,
                        esp.id_famille AS id_famille, 
                        esp.nom_local, 
                        fam.libelle AS libelle_famille
                    
                    FROM sip_espece AS esp  
                    
                    INNER JOIN sip_type_espece AS tesp on esp.type_espece = tesp.id
                   
                    LEFT join sip_famille AS fam ON fam.id=esp.id_famille

                    order by esp.nom
           ";
           return $this->db->query($sql)->result();

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

    public function find_all_eau_douce_marine($id_type_espece1, $id_type_espece2) {
               
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('nom')
                        ->where("type_espece", $id_type_espece1)
                        ->or_where("type_espece", $id_type_espece2)
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
	public function find_by_type_espece($type_espece) {
        $requete="select distinct esp.id,esp.code,esp.nom as nom_espece,esp.type_espece as id_type_espece,esp.nom_francaise, esp.nom_scientifique,esp.nom_francaise,esp.nom_local,tesp.libelle as type_espece"
			        ." from sip_espece as esp" 
			        ." left outer join sip_type_espece as tesp on tesp.id=esp.type_espece" 
			        ." where esp.type_espece=".$type_espece;
		return $this->db->query($requete)->result();			  
		 if($result)
        {
            return $result;
        }else{
            return null;
        }
	}
}
