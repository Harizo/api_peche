<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class SIP_peche_thoniere_malagasy_model extends CI_Model {
    protected $table = 'sip_peche_thoniere_malagasy';

    public function add($SIP_peche_thoniere_malagasy) {
        $this->db->set($this->_set($SIP_peche_thoniere_malagasy))

                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }

    public function update($id, $sip_peche_thoniere_malagasy) {
        $this->db->set($this->_set($sip_peche_thoniere_malagasy))

                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }

    public function _set($sip_peche_thoniere_malagasy) {
        return array(
           'id_navire'         => $sip_peche_thoniere_malagasy['id_navire'],              
            'numfp'            => $sip_peche_thoniere_malagasy['numfp'],              
            'nom_capitaine'    => $sip_peche_thoniere_malagasy['nom_capitaine'],              
            'nbr_equipage'     => $sip_peche_thoniere_malagasy['nbr_equipage'],                 
            'date_rapport'     => $sip_peche_thoniere_malagasy['date_rapport'],                 
            'nom_declarant'    => $sip_peche_thoniere_malagasy['nom_declarant'],                
            'date_depart'      => $sip_peche_thoniere_malagasy['date_depart'],                 
            'date_arrive'      => $sip_peche_thoniere_malagasy['date_arrive'],
            'port'             => $sip_peche_thoniere_malagasy['port'],
            'nbr_jour_en_mer'  => $sip_peche_thoniere_malagasy['nbr_jour_en_mer'],
            'nbr_peche'        => $sip_peche_thoniere_malagasy['nbr_peche'],
            'nbr_peche_zee_mdg'=> $sip_peche_thoniere_malagasy['nbr_peche_zee_mdg']

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

        $requete="select pthm.id,pthm.id_navire,n.immatricule,n.nom as nom_navire,pthm.numfp,pthm.nom_capitaine,pthm.nbr_equipage,             
            pthm.date_rapport,pthm.nom_declarant,pthm.date_depart,pthm.date_arrive,pthm.port,              
            pthm.nbr_jour_en_mer,pthm.nbr_peche,pthm.nbr_peche_zee_mdg 
			 from sip_peche_thoniere_malagasy as pthm 
			  join sip_navire as n on n.id=pthm.id_navire";
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
            FROM sip_peche_thoniere_malagasy
            WHERE sip_peche_thoniere_malagasy.id_navire = ".$id_navire."
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
