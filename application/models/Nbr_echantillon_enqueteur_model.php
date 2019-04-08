<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nbr_echantillon_enqueteur_model extends CI_Model
{
    protected $table = 'nbr_echantillon_enqueteur';


    public function add($nbr_echantillon_enqueteur)
    {
        $this->db->set($this->_set($nbr_echantillon_enqueteur))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }


    public function update($id, $nbr_echantillon_enqueteur)
    {
        $this->db->set($this->_set($nbr_echantillon_enqueteur))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }

    public function _set($nbr_echantillon_enqueteur)
    {
        return array(
            'id_enqueteur'         => $nbr_echantillon_enqueteur['id_enqueteur'],
            'id_site_embarquement' => $nbr_echantillon_enqueteur['id_site_embarquement'],
            'id_unite_peche'       => $nbr_echantillon_enqueteur['id_unite_peche'],
            'nbr_max_echantillon'  => $nbr_echantillon_enqueteur['nbr_max_echantillon']                       
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
                        ->order_by('id_enqueteur')
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

    public function findByenqueteur_unite_peche_site($id_enqueteur, $id_unite_peche, $id_site_embarquement)
    {
        $this->db->where("id_enqueteur", $id_enqueteur)
                 ->where("id_unite_peche", $id_unite_peche)
                 ->where("id_site_embarquement", $id_site_embarquement);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return null;
    }  

}
