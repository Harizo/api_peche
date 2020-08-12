<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class sip_famille_model extends CI_Model {
    protected $table = 'sip_famille';

    public function add($sip_famille)
    {
        $this->db->set($this->_set($sip_famille))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }


    public function update($id, $sip_famille)
    {
        $this->db->set($this->_set($sip_famille))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }

    public function _set($sip_famille)
    {
        return array(
            'id'        =>      $sip_famille['id'],
            'libelle'         =>      $sip_famille['libelle'],
          //  'type_espece' =>      $sip_famille['type_espece']                       
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

    public function findByIdtab($id)
    {   $result =  $this->db->select('id as id_sip_famille, libelle')
                        ->from($this->table)
                        ->where("id", $id)
                        ->order_by('id')
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

    public function findByIdtable($id)
    {   $result =  $this->db->select('*')
                        ->from('sip_espece')
                        ->join('sip_famille', 'inner')
                        ->where("sip_saisie_vente_poissonnerie.famille_rh= sip_famille.id", $id)
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

     /**
        * Obtenir une liste de marchandises exemple
    */
    function getlist()
    {
        $this->db->get($this->table);
        return $query->result_array();
        
    }


     /**
      * Obtention des données produit exemple
      */
   function get($id)
   {
       $this->db-> where ($this->idkey, $id);
       $query = $this-> db-> get ($this->table);
       return $query-> ressult_array ();   
   }
}