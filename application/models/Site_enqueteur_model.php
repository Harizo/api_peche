<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site_enqueteur_model extends CI_Model
{
    protected $table = 'site_enqueteur';


    public function add($site_enqueteur)
    {
        $this->db->set($this->_set($site_enqueteur))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }


    public function update($id, $site_enqueteur)
    {
        $this->db->set($this->_set($site_enqueteur))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }

    public function _set($site_enqueteur)
    {
        return array(
            'id_site'     =>      $site_enqueteur['id_site_embarquement'],
            'id_enqueteur'     =>      $site_enqueteur['id_enqueteur']                       
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
                        ->order_by('id_site')
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
    public function findAllByEnqueteur($cle_etranger)
    {
        $result =  $this->db->select('*')
                                ->from($this->table)
                                ->join('site_embarquement', 'site_enqueteur.id_site = site_embarquement.id', 'inner')
                                ->where("id_enqueteur", $cle_etranger )
                                ->order_by('id_enqueteur')
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
