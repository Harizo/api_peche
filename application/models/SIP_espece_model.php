<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_espece_model extends CI_Model {
    protected $table = 'sip_espece';

    public function add($SIP_espece) {
        $this->db->set($this->_set($SIP_espece))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $SIP_espece) {
        $this->db->set($this->_set($SIP_espece))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($SIP_espece) {
        return array(
            'id_collecteurs'                  	=>      $SIP_espece['id_collecteurs'],
            'id_espece'                   		=>      $SIP_espece['id_espece'],                 
            'id_district'            			=>      $SIP_espece['id_district'],                 
            'numero_espece'               		=>      $SIP_espece['numero_espece'],                 
            'date_quittance'      				=>      $SIP_espece['date_quittance']

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
                        ->order_by('id_collecteur_mareyeur')
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }


    public function find_all_by_type($type_espece) {
               
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('nom')
                        ->where("type_espece", $type_espece)
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
}
