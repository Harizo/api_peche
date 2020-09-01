<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_type_navire_model extends CI_Model {
    protected $table = 'sip_type_navire';


    public function add($sip_type_navire) {
        $this->db->set($this->_set($sip_type_navire))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {

            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }



    public function update($id, $sip_type_navire)
    {
        $this->db->set($this->_set($sip_type_navire))

                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }


    public function _set($sip_type_navire)
    {
        return array(
            'libelle'	=> $sip_type_navire['libelle']  
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


    public function findAll() {
               
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('id')
                        ->order_by('libelle')
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
        return null;
    }

    public function findByIdtab($id)
    {   $result =  $this->db->select('id as id_sip_type_navire, libelle')
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
                        ->from('sip_navire')
                        ->join('sip_type_navire', 'inner')
                        ->where("sip_navire.type_navire= sip_type_navire.id", $id)
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

