<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_carte_pirogue_model extends CI_Model {
    protected $table = 'sip_carte_pirogue';

    public function add($SIP_carte_pirogue) {
        $this->db->set($this->_set($SIP_carte_pirogue))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $SIP_carte_pirogue) {
        $this->db->set($this->_set($SIP_carte_pirogue))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($SIP_carte_pirogue) {
        return array(
            'id_carte_pecheur'          =>      $SIP_carte_pirogue['id_carte_pecheur'],
            'immatriculation'           =>      $SIP_carte_pirogue['immatriculation'],
            'an_cons'                   =>      $SIP_carte_pirogue['an_cons'],           
            'longueur'                  =>      $SIP_carte_pirogue['longueur'],      
            'largeur'                   =>      $SIP_carte_pirogue['largeur'],
            'c'                         =>      $SIP_carte_pirogue['c'],
            'coul'                      =>      $SIP_carte_pirogue['coul'],
            'nat'                       =>      $SIP_carte_pirogue['nat'],
            'prop'                      =>      $SIP_carte_pirogue['prop'],
            'type'                      =>      $SIP_carte_pirogue['type'],
            'observations'             =>      $SIP_carte_pirogue['observations'],
            'etat_proprietaire'         =>      $SIP_carte_pirogue['etat_proprietaire'],
            'proprietaire'              =>      $SIP_carte_pirogue['proprietaire']

        );
    }
    public function delete($id) {
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
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }



    public function findAll_by_carte_pecheur($id_carte_pecheur) {
               
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("id_carte_pecheur", $id_carte_pecheur)
                        ->order_by('id','desc')
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
    }
}
