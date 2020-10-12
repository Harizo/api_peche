<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Commune_model extends CI_Model {
    protected $table = 'commune';

    public function add($commune) {
        $this->db->set($this->_set($commune))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $commune) {
        $this->db->set($this->_set($commune))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1) {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($commune) {
        return array(
            'code'           =>      $commune['code'],
            'nom'            =>      $commune['nom'],
            'id_district'    =>      $commune['id_district']                       
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

    public function findAll_by_district($id_district) {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("id_district", $id_district)
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

    public function findCleRegion($id_region)
    {
        $sql = "select 
                cm.id, 
                cm.nom as communes, 
                d.nom as districts, 
                r.nom as nom_region
           
            FROM commune AS cm
            LEFT JOIN district AS d ON d.id = cm.id_district
            RIGHT JOIN region AS r ON r.id = d.id_region
            WHERE r.id = ".$id_region." 
            
            ORDER BY cm.nom" ;
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
    
}
