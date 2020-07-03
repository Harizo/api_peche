<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_navire_model extends CI_Model {
    protected $table = 'sip_navire';
    public function add($sip_navire)
    {   $this->db->set($this->_set($sip_navire))
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
    public function update($id, $sip_navire)
    {   $this->db->set($this->_set($sip_navire))
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
    public function _set($sip_navire)
    {   return array(
            'id'                =>      $sip_navire['id'],        
            'immatricule'       =>      $sip_navire['immatricule'],
            'nom'               =>      $sip_navire['nom'],                       
            'pavillon'          =>      $sip_navire['pavillon'],
            'armateur'          =>      $sip_navire['armateur'],        
            'adresse'           =>      $sip_navire['adresse'],
            'tonnage_brute'     =>      $sip_navire['tonnage_brute'],                       
            'lht'               =>      $sip_navire['lht'],
            'capacite_cale'     =>      $sip_navire['capacite_cale'],        
            'indication_ratio'  =>      $sip_navire['indication_ratio'],
            'type_navire'       =>      $sip_navire['type_navire']
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

    
    public function findById($id_type_navire)  {
        $this->db->where("id", $id_type_navire);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
           
    }
     public function findByIdtab($id)
    {   
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("id", $id)
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
  
    public function findFils($cle_etrangere)
    {
         $result =  $this->db->select('*')
                             ->from('sip_navire')
                             ->where('sip_navire.type_navire='.$cle_etrangere)
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
         
}