<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_sequence_transbordement_model extends CI_Model {
    protected $table = 'sip_sequence_transbordement';
    public function add($sip_sequence_transbordement)
    {   $this->db->set($this->_set($sip_sequence_transbordement))
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
    public function update($id, $sip_sequence_transbordement)
    {   $this->db->set($this->_set($sip_sequence_transbordement))
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
    public function _set($sip_sequence_transbordement)
    {   return array(

            'id_sequence_peche'       => $sip_sequence_transbordement['id_sequence_peche'],
            'date'                          => $sip_sequence_transbordement['date'],
            'heurep'                        => $sip_sequence_transbordement['heurep'],
            'minutep'                       => $sip_sequence_transbordement['minutep'],
            'heuret'                        => $sip_sequence_transbordement['heuret'],
            'minutet'                       => $sip_sequence_transbordement['minutet'],
            'postlatitude'                  => $sip_sequence_transbordement['postlatitude'],
            'postlongitude'                 => $sip_sequence_transbordement['postlongitude'],
            'id_navire'                 => $sip_sequence_transbordement['id_navire']
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
                        ->order_by('date')
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

    
    public function findById($id_type_sequence_transbordement)  {
        $this->db->where("id", $id_type_sequence_transbordement);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
           
    }
 
  
         
}