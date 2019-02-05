<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Espece_capture_model extends CI_Model
{
    protected $table = 'espece_capture';


    public function add($espece_capture)
    {
        $this->db->set($this->_set($espece_capture))
                            ->set('date_creation', 'NOW()', false)
                            ->set('date_modification', 'NOW()', false)
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }


    public function update($id, $espece_capture)
    {
        $this->db->set($this->_set($espece_capture))
                            ->set('date_modification', 'NOW()', false)
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }

    public function _set($espece_capture)
    {
        return array(
            
            'id_fiche_echantillonnage_capture' => $espece_capture['fiche_echantillonnage_capture_id'],
            'id_echantillon' => $espece_capture['echantillon_id'],
            'id_espece' => $espece_capture['espece_id'],
            'capture' => $espece_capture['capture'],
            'prix' => $espece_capture['prix'],
            'id_user' => $espece_capture['user_id'],
            //'date_creation' => $espece_capture['date_creation'],
            //'date_modification' => $espece_capture['date_modification'],                     
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

    public function findAllByEspece($id_espece)
    {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('capture')
                        ->where("id_espece", $id_espece)
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
