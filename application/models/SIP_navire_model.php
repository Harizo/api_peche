<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_navire_model extends CI_Model {
    protected $table = 'sip_navire';

    public function add($SIP_navire) {
        $this->db->set($this->_set($SIP_navire))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $SIP_navire) {
        $this->db->set($this->_set($SIP_navire))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
   public function _set($SIP_navire) {
        return array(
            'immatricule'	    => $SIP_navire['immatricule'],              
            'nom'	            => $SIP_navire['nom'],              
            'pavillon'	        => $SIP_navire['pavillon'],              
            'armateur'      	=> $SIP_navire['armateur'],              
            'adresse'	        => $SIP_navire['adresse'],              
            'tonnage_brute'    	=> $SIP_navire['tonnage_brute'],              
            'lht'	            => $SIP_navire['lht'],              
            'capacite_cale'	    => $SIP_navire['capacite_cale'],              
            'indication_ratio'	=> $SIP_navire['indication_ratio'],              
            'type_navire'	    => $SIP_navire['type_navire'],              
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
        $requete="select nav.id,nav.immatricule,nav.nom,nav.pavillon,nav.armateur,nav.adresse,nav.tonnage_brute,             
            nav.lht,nav.capacite_cale,nav.indication_ratio,nav.type_navire,tnav.libelle as libelle_type_navire 
			 from sip_navire as nav 
			  left join sip_type_navire as tnav on tnav.id=nav.type_navire";
		return $this->db->query($requete)->result();			  
    }
    public function findById($id)  {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }  
    }
}
