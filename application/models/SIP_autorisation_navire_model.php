<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class sip_autorisation_navire_model extends CI_Model {
    protected $table = 'sip_autorisation_navire';

    public function add($sip_autorisation_navire) {
        $this->db->set($this->_set($sip_autorisation_navire))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $sip_autorisation_navire) {
        $this->db->set($this->_set($sip_autorisation_navire))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($sip_autorisation_navire) {
        return array(
            'id_navire'           => $sip_autorisation_navire['id_navire'],              
            'zone_autorisee'     => $sip_autorisation_navire['zone_autorisee'],              
            'espece_1_autorisee' => $sip_autorisation_navire['espece_1_autorisee'],              
            'espece_2_autorisee' => $sip_autorisation_navire['espece_2_autorisee']
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
                        ->order_by('zone_autorisee')
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
            FROM sip_autorisation_navire
            WHERE sip_autorisation_navire.id_navire = ".$id_navire."
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
