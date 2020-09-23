<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_sequence_pij_peche_crevette_model extends CI_Model {
    protected $table = 'sip_sequence_pij_peche_crevette';
    public function add($sip_sequence_pij_peche_crevette)
    {   $this->db->set($this->_set($sip_sequence_pij_peche_crevette))
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
    public function update($id, $sip_sequence_pij_peche_crevette)
    {   $this->db->set($this->_set($sip_sequence_pij_peche_crevette))
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
    public function _set($sip_sequence_pij_peche_crevette)
    {   return array(

            'id_sequence_peche'             => $sip_sequence_pij_peche_crevette['id_sequence_peche'],
            'date'                          => $sip_sequence_pij_peche_crevette['date'],
            'j_ou_n'                        => $sip_sequence_pij_peche_crevette['j_ou_n'],
            'zone'                          => $sip_sequence_pij_peche_crevette['zone'],
            'carre'                         => $sip_sequence_pij_peche_crevette['carre'],
            'activite'                      => $sip_sequence_pij_peche_crevette['activite'],
            'sonde'                         => $sip_sequence_pij_peche_crevette['sonde'],
            'nb_traits'                     => $sip_sequence_pij_peche_crevette['nb_traits'],
            'heurep'                        => $sip_sequence_pij_peche_crevette['heurep'],
            'minutep'                       => $sip_sequence_pij_peche_crevette['minutep'],
            'heuret'                        => $sip_sequence_pij_peche_crevette['heuret'],
            'minutet'                       => $sip_sequence_pij_peche_crevette['minutet']
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

    
    public function findById($id_type_sequence_pij_peche_crevette)  {
        $this->db->where("id", $id_type_sequence_pij_peche_crevette);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
           
    }
 
  
         
}