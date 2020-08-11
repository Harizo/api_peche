<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class sip_espece_model extends CI_Model {
    protected $table = 'sip_espece';
    public function add($sip_espece)
    {   $this->db->set($this->_set($sip_espece))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }
        else
        {
            return null;
        }                     
    }
    public function update($id, $sip_espece)
    {   $this->db->set($this->_set($sip_espece))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }
        else
        {
            return null;
        }                      
    }
    public function _set($sip_espece)
    {   return array(
            //'id'           =>      $sip_espece['id'],        
            'code'         =>      $sip_espece['code'],
            'nom'          =>      $sip_espece['nom'],                       
            'type_espece'  =>      $sip_espece['typ_esp_id']
        );
    }
    public function delete($id)
    {   $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }
        else
        {
            return null;
        }  
    }
    public function findAll()
    {   $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('nom')
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

    
    public function findById($id_type_espece)  {
        $this->db->where("id", $id_type_espece);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
           
    }
     public function findByIdtab($id)
    {   
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("id", $id)
                        ->order_by('nom')
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
  
    public function findFils($cle_etrangere)
    {
         $result =  $this->db->select('*')
                             ->from('sip_espece')
                             ->where('sip_espece.type_espece='.$cle_etrangere)
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
   /* public function DeleteFK($clef)
    {
        $sql = " delete
            FROM sip_espece
            WHERE sip_espece.type_espece = ".$clef."
        ";

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
    */
    public function find_all_by_type($type_espece) {
               
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('nom')
                        ->where("type_espece", $type_espece)
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }
  
         
}