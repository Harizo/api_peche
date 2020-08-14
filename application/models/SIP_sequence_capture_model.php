<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_sequence_capture_model extends CI_Model {
    protected $table = 'sip_sequence_capture';
    public function add($sip_sequence_capture)
    {   $this->db->set($this->_set($sip_sequence_capture))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }
        else
        {
            return null;
        }                     
    }
    public function update($id, $sip_sequence_capture)
    {   $this->db->set($this->_set($sip_sequence_capture))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }
        else
        {
            return null;
        }                      
    }
    public function _set($sip_sequence_capture)
    {   return array(

            'id_sequence_peche'       => $sip_sequence_capture['id_sequence_peche'],
            'id_espece'               => $sip_sequence_capture['id_espece'],
            'quantite'                => $sip_sequence_capture['quantite']
        );
    }
    public function delete($id)
    {   $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }
        else
        {
            return null;
        }  
    }
    public function findAll()
    {   $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('nom')
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }                 
    }

    public function findAll_by_fiche_peche_crevette($id_sequence_peche)
    {   $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("id_sequence_peche", $id_sequence_peche)
                        ->order_by('id')
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }                 
    }

    
    public function findById($id_type_sequence_capture)  {
        $this->db->where("id", $id_type_sequence_capture);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
           
    }
 
  
         
}