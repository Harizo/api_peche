<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Distribution_fractile_model extends CI_Model {
    protected $table = 'distribution_fractile';

    public function add($distribution_fractile) {
        $this->db->set($this->_set($distribution_fractile))
                 ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $distribution_fractile) {
        $this->db->set($this->_set($distribution_fractile))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1) {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($distribution_fractile) {
        return array(
            'code'     => $distribution_fractile['code'],  
            'libelle'  => $distribution_fractile['libelle'],  
            'adresse'  => $distribution_fractile['adresse'],  
        );
    }
    public function delete($id) {
        $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1) {
            return true;
        }else{
            return null;
        }  
    }
    public function findAll() {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('id', 'asc')
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                 
    }
    public function findById($id) {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("id", $id)
                        ->order_by('id', 'asc')
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                 
    }

    public function findByDegree($DegreesofFreedom) {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("DegreesofFreedom", $DegreesofFreedom)
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                 
    }
}
?>