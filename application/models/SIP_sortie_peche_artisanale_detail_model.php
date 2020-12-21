<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class SIP_sortie_peche_artisanale_detail_model extends CI_Model {
    protected $table = 'sip_sortie_peche_artisanale_detail';

    public function add($sip_sortie_peche_artisanale_detail) {
        $this->db->set($this->_set($sip_sortie_peche_artisanale_detail))


                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }

    public function update($id, $sip_sortie_peche_artisanale_detail) {
        $this->db->set($this->_set($sip_sortie_peche_artisanale_detail))

                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }

    public function _set($sip_sortie_peche_artisanale_detail) {
        return array(
            'id_sip_sortie_peche_artisanale' => $sip_sortie_peche_artisanale_detail['id_sip_sortie_peche_artisanale'],                 
            'id_espece'   	                 => $sip_sortie_peche_artisanale_detail['id_espece'],                 
            'quantite'                       => $sip_sortie_peche_artisanale_detail['quantite']        


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

        $requete="select poisart.id,poisart.id_sip_sortie_peche_artisanale,poisart.id_espece,poisart.quantite,e.nom as nom_espece,e.code as code_espece   
			 from sip_sortie_peche_artisanale_detail as poisart 
			  join sip_espece as e on e.id=poisart.id_espece";
		return $this->db->query($requete)->result();			  
	}
    public function findById_sortie_peche_artisanale($id_sortie_peche_artisanale) {

        $requete="select poisart.id,poisart.id_sip_sortie_peche_artisanale,poisart.id_espece,poisart.quantite,e.nom as nom_espece,e.code as code_espece,e.type_espece as id_type_espece,tesp.libelle as type_espece        
			 from sip_sortie_peche_artisanale_detail as poisart 
			  join sip_espece as e on e.id=poisart.id_espece   
			  left join sip_type_espece as tesp on tesp.id=e.type_espece 
			  where poisart.id_sip_sortie_peche_artisanale=".$id_sortie_peche_artisanale;
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
            FROM sip_sortie_peche_artisanale_detail
            WHERE sip_sortie_peche_artisanale_detail.id_navire = ".$id_navire."
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
            FROM sip_sortie_peche_artisanale_detail
            WHERE sip_sortie_peche_artisanale_detail.id_espece = ".$id_espece."
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
