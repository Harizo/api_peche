<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class SIP_sortie_peche_artisanale_model extends CI_Model {
    protected $table = 'sip_sortie_peche_artisanale';

    public function add($SIP_poisson_demersaux) {
        $this->db->set($this->_set($SIP_poisson_demersaux))


                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }

    public function update($id, $sip_sortie_peche_artisanale) {
        $this->db->set($this->_set($sip_sortie_peche_artisanale))

                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }

    public function _set($sip_sortie_peche_artisanale) {
        return array(
            'id_navire'      =>      $sip_sortie_peche_artisanale['id_navire'],              
            'nom_capitaine'  =>      $sip_sortie_peche_artisanale['nom_capitaine'],              
            'port'           =>      $sip_sortie_peche_artisanale['port'],
            'num_maree'      =>      $sip_sortie_peche_artisanale['num_maree'],                 
            'date_depart'    =>      $sip_sortie_peche_artisanale['date_depart'],                 
            'date_arrive'    =>      $sip_sortie_peche_artisanale['date_arrive'],
            'annee'          =>      $sip_sortie_peche_artisanale['annee'],
            'mois'           =>      $sip_sortie_peche_artisanale['mois'],
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
             pthe.num_maree,pthe.date_depart,pthe.date_arrive,pthe.annee,pthe.mois                 
			 from sip_sortie_peche_artisanale as pthe 
			  join sip_navire as n on n.id=pthe.id_navire "; 
		return $this->db->query($requete)->result();			  
	}
    public function SelectByFiltre($filtre) {

        $requete="select pthe.id,pthe.id_navire,n.immatricule,n.nom as nom_navire,pthe.nom_capitaine,pthe.port,             
             pthe.num_maree,pthe.date_depart,pthe.date_arrive,pthe.annee,pthe.mois                 
			 from sip_sortie_peche_artisanale as pthe 
			  join sip_navire as n on n.id=pthe.id_navire ".$filtre; 
		return $this->db->query($requete)->result();			  
	}
    public function SelectAnnee()  {
		$requete="select distinct year(date_depart) as annee from sip_sortie_peche_artisanale"
		." union "
		."select distinct year(date_arrive) as annee from sip_sortie_peche_artisanale"
		." union "
		."select distinct annee from sip_sortie_peche_artisanale"
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


    public function findCleNavire($id_navire)
    {
        $sql = " select *
            FROM sip_sortie_peche_artisanale
            WHERE sip_sortie_peche_artisanale.id_navire = ".$id_navire."
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

    public function findCleEspecce($id_espece)
    {
        $sql = " select *
            FROM sip_sortie_peche_artisanale
            WHERE sip_sortie_peche_artisanale.id_espece = ".$id_espece."
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
