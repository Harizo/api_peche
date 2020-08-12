<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class SIP_autorisation_navire_model extends CI_Model {
    protected $table = 'sip_autorisation_navire';

    public function add($SIP_autorisation_navire) {
        $this->db->set($this->_set($SIP_autorisation_navire))

                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
   
    public function update($id, $SIP_autorisation_navire) {
        $this->db->set($this->_set($SIP_autorisation_navire))

                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }

 public function _set($SIP_autorisation_navire) {
        return array(
            'id_navire'	            => $SIP_autorisation_navire['id_navire'],
            'zone_autorisee'	    => $SIP_autorisation_navire['zone_autorisee'],
            'espece_1_autorisee'   	=> $SIP_autorisation_navire['espece_1_autorisee'],        
            'espece_2_autorisee'    => $SIP_autorisation_navire['espece_2_autorisee'],  

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
        $requete="select autn.id,autn.zone_autorisee,autn.espece_1_autorisee,autn.espece_2_autorisee,             
             e1.code as code_espece1,e1.nom as nom_espece1,e2.code as code_espece2,e2.nom as nom_espece2,autn.id_navire,
            nav.nom as nom_navire,nav.immatricule    
			 from sip_autorisation_navire as autn 
			  left join sip_espece as e1 on e1.id=autn.espece_1_autorisee  
			  left join sip_espece as e2 on e2.id=autn.espece_2_autorisee  
			  left join sip_navire as nav on nav.id=autn.id_navire";
		return $this->db->query($requete)->result();			                 
    }
    public function findById($id)  {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }  
    }
    public function findByNavire($id_navire)  {
        $requete="select autn.id,autn.zone_autorisee,autn.espece_1_autorisee,autn.espece_2_autorisee,             
             e1.code as code_espece1,e1.nom as nom_espece1,e2.code as code_espece2,e2.nom as nom_espece2,autn.id_navire,
            nav.nom as nom_navire,nav.immatricule    
			 from sip_autorisation_navire as autn 
			  left join sip_espece as e1 on e1.id=autn.espece_1_autorisee  
			  left join sip_espece as e2 on e2.id=autn.espece_2_autorisee  
			  left join sip_navire as nav on nav.id=autn.id_navire  
			  where id_navire=".$id_navire;
		return $this->db->query($requete)->result();			                 
    }
}
