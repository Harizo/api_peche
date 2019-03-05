<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Espece_model extends CI_Model
{
    protected $table = 'espece';


    public function add($espece)
    {
        $this->db->set($this->_set($espece))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }


    public function update($id, $espece)
    {
        $this->db->set($this->_set($espece))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }

    public function _set($espece)
    {
        return array(
            'code'       =>      $espece['code'],
            'nom_local'    =>      $espece['nom_local'], 
            'nom_scientifique'=>      $espece['nom_scientifique'], 
            'url_image'    =>      $espece['url_image']                       
        );
    }


    public function delete($id)
    {
        $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }  
    }

    public function findAll()
    {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('id')
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }

    public function findById($id)
    {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return null;
    }

}
