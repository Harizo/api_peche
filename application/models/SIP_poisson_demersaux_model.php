<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_poisson_demersaux_model extends CI_Model {
    protected $table = 'sip_poisson_demersaux';

    public function add($SIP_poisson_demersaux) {
        $this->db->set($this->_set($SIP_poisson_demersaux))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $SIP_poisson_demersaux) {
        $this->db->set($this->_set($SIP_poisson_demersaux))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
 public function _set($SIP_poisson_demersaux) {
        return array(
            'id_navire'	            => $SIP_poisson_demersaux['id_navire'],              
            'nom_capitaine'	        => $SIP_poisson_demersaux['nom_capitaine'],              
            'port'              	=> $SIP_poisson_demersaux['port'],              
            'num_maree'	            => $SIP_poisson_demersaux['num_maree'],              
            'date_depart'	        => $SIP_poisson_demersaux['date_depart'],              
            'date_arrive'	        => $SIP_poisson_demersaux['date_arrive'],              
            'annee'	                => $SIP_poisson_demersaux['annee'],              
            'mois'	                => $SIP_poisson_demersaux['mois'],              
            'reference_produit'	    => $SIP_poisson_demersaux['reference_produit'],              
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
        $requete="select pthe.id,pthe.id_navire,n.immatricule,n.nom as nom_navire,pthe.nom_capitaine,pthe.port,             
             pthe.num_maree,pthe.date_depart,pthe.date_arrive,pthe.annee,pthe.mois,              
           pthe.reference_produit    
			 from sip_poisson_demersaux as pthe 
			  join sip_navire as n on n.id=pthe.id_navire "; 
		return $this->db->query($requete)->result();			  
    }
    public function SelectByFiltre($filtre) {
        $requete="select pthe.id,pthe.id_navire,n.immatricule,n.nom as nom_navire,pthe.nom_capitaine,pthe.port,             
             pthe.num_maree,pthe.date_depart,pthe.date_arrive,pthe.annee,pthe.mois,              
           pthe.reference_produit    
			 from sip_poisson_demersaux as pthe 
			  join sip_navire as n on n.id=pthe.id_navire ".$filtre; 
		return $this->db->query($requete)->result();			  
    }
    public function SelectAnnee()  {
		$requete="select distinct year(date_depart) as annee from sip_poisson_demersaux"
		." union "
		."select distinct year(date_arrive) as annee from sip_poisson_demersaux"
		." union "
		."select distinct annee from sip_poisson_demersaux"
		." order by annee";
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
