<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_societe_crevette_model extends CI_Model {
    protected $table = 'sip_societe_crevette';

    public function add($SIP_societe_crevette) {
        $this->db->set($this->_set($SIP_societe_crevette))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $SIP_societe_crevette) {
        $this->db->set($this->_set($SIP_societe_crevette))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($SIP_societe_crevette) {
        return array(
            'code'                  =>      $SIP_societe_crevette['code'],
            'nom'                   =>      $SIP_societe_crevette['nom'],                 
            'deb_validite'          =>      $SIP_societe_crevette['deb_validite'],                 
            'fin_validite'            =>      $SIP_societe_crevette['fin_validite'],                 
            'base_geo'      		=>      $SIP_societe_crevette['base_geo'],                 
            'base_cote'     		=>      $SIP_societe_crevette['base_cote'],                 
            'an_creation'        	=>      $SIP_societe_crevette['an_creation'],                 
            'type'           		=>      $SIP_societe_crevette['type']               

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
                        ->order_by('nom')
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

     public function findBaseGeo($id_base_geo)
    {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where('base_geo=',$id_base_geo)
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }  
    
    }

    public function findBaseCote($id_base_cote)
    {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where('base_cote=',$id_base_cote)
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }  
    
    }
}
