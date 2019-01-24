<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_collect_model extends CI_Model
{
    protected $table = 'data_collect';


    public function add($data_collect)
    {
        $this->db->set($this->_set($data_collect))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }


    public function update($id, $data_collect)
    {
        $this->db->set($this->_set($data_collect))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }

    public function _set($data_collect)
    {
        return array(
            'code'       =>      $data_collect['code'],
            'libelle'    =>      $data_collect['libelle']                       
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

   /* public function findAllBySite($site_id)
    {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('nom')
                        ->where("site_id", $site_id)
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }*/

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
