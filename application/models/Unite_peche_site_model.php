<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Unite_peche_site_model extends CI_Model
{
    protected $table = 'unite_peche_site';


    public function add($unite_peche_site)
    {
        $this->db->set($this->_set($unite_peche_site))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }


    public function update($id, $unite_peche_site)
    {
        $this->db->set($this->_set($unite_peche_site))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }

    public function _set($unite_peche_site)
    {
        return array(
            'id_site_embarquement'     =>      $unite_peche_site['id_site_embarquement'],
            'id_unite_peche'     =>      $unite_peche_site['id_unite_peche'] ,
            'nbr_echantillon'     =>      $unite_peche_site['nbr_echantillon']                       
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
                        ->order_by('id_site_embarquement')
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
