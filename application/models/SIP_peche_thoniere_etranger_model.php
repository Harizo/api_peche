<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_peche_thoniere_etranger_model extends CI_Model {
    protected $table = 'sip_peche_thoniere_etranger';

    public function add($SIP_peche_thoniere_etranger) {
        $this->db->set($this->_set($SIP_peche_thoniere_etranger))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $SIP_peche_thoniere_etranger) {
        $this->db->set($this->_set($SIP_peche_thoniere_etranger))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
 public function _set($sip_peche_thoniere_etranger) {
        return array(
            'id_navire'         => $sip_peche_thoniere_etranger['id_navire'],              
            'numfp'             => $sip_peche_thoniere_etranger['numfp'],              
            'nom_capitaine'     => $sip_peche_thoniere_etranger['nom_capitaine'],              
            'nbr_equipage'      => $sip_peche_thoniere_etranger['nbr_equipage'],                 
            'date_rapport'      => $sip_peche_thoniere_etranger['date_rapport'],                 
            'nom_declarant'     => $sip_peche_thoniere_etranger['nom_declarant'],                
            'date_depart'       => $sip_peche_thoniere_etranger['date_depart'],                 
            'date_arrive'       => $sip_peche_thoniere_etranger['date_arrive'],
            'port'              => $sip_peche_thoniere_etranger['port'],
            'nbr_jour_en_mer'   => $sip_peche_thoniere_etranger['nbr_jour_en_mer'],
            'nbr_peche'         => $sip_peche_thoniere_etranger['nbr_peche'],
            'nbr_lancers'        => $sip_peche_thoniere_etranger['nbr_lancers'],
            'num_sortie_peche'  => $sip_peche_thoniere_etranger['num_sortie_peche']
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
        $requete="select pthe.id,pthe.id_navire,n.immatricule,n.nom as nom_navire,pthe.numfp,pthe.nom_capitaine,pthe.nbr_equipage,             
            pthe.date_rapport,pthe.nom_declarant,pthe.date_depart,pthe.date_arrive,pthe.port,              
            pthe.nbr_jour_en_mer,pthe.nbr_peche,pthe.nbr_lancers,pthe.num_sortie_peche  
			 from sip_peche_thoniere_etranger as pthe 
			  join sip_navire as n on n.id=pthe.id_navire";
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
            FROM sip_peche_thoniere_etranger
            WHERE sip_peche_thoniere_etranger.id_navire = ".$id_navire."
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
