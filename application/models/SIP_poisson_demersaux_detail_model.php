<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_poisson_demersaux_detail_model extends CI_Model {
    protected $table = 'sip_poisson_demersaux_detail';

    public function add($SIP_poisson_demersaux_detail) {
        $this->db->set($this->_set($SIP_poisson_demersaux_detail))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $SIP_poisson_demersaux_detail) {
        $this->db->set($this->_set($SIP_poisson_demersaux_detail))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
 public function _set($SIP_poisson_demersaux_detail) {
        return array(
            'id_sip_poisson_demersaux' => $SIP_poisson_demersaux_detail['id_sip_poisson_demersaux'],              
            'id_espece'	               => $SIP_poisson_demersaux_detail['id_espece'],              
            'quantite'	               => $SIP_poisson_demersaux_detail['quantite'],              
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
        $requete="select pdemers.id,pdemers.id_sip_poisson_demersaux,pdemers.id_espece,pdemers.quantite,e.nom as nom_espece,e.code as code_espece    
			 from sip_poisson_demersaux_detail as pdemers 
			  join sip_espece as e on e.id=pdemers.id_espece";
		return $this->db->query($requete)->result();			  
    }
    public function findById_poisson_demersaux($id_poisson_demersaux) {
        $requete="select pdemers.id,pdemers.id_sip_poisson_demersaux,pdemers.id_espece,pdemers.quantite,e.nom as nom_espece,e.code as code_espece    
			 from sip_poisson_demersaux_detail as pdemers 
			  join sip_espece as e on e.id=pdemers.id_espece   
			  where pdemers.id_sip_poisson_demersaux=".$id_poisson_demersaux;
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
