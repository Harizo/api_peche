<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class sip_sortie_peche_artisanale_model extends CI_Model {
    protected $table = 'sip_sortie_peche_artisanale';

    public function add($sip_sortie_peche_artisanale) {
        $this->db->set($this->_set($sip_sortie_peche_artisanale))
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
            'id_espece'   	 =>      $sip_sortie_peche_artisanale['id_espece'],                 
            'quantite'       =>      $sip_sortie_peche_artisanale['quantite']        

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
               
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('nom_capitaine')
                        ->get()
                        ->result();
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
