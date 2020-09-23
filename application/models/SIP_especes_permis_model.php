<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_especes_permis_model extends CI_Model {
    protected $table = 'sip_especes_permis';

    public function add($SIP_especes_permis) {
        $this->db->set($this->_set($SIP_especes_permis))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    } 
    public function update($id, $SIP_especes_permis) {
        $this->db->set($this->_set($SIP_especes_permis))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($SIP_especes_permis) {
        return array(
            'id_permis'                 =>      $SIP_especes_permis['id_permis'],
            'id_espece'            =>      $SIP_especes_permis['id_espece']

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

    public function findAllby_permis($id_permis) 
    {
        $sql =  "
                    select

                        sep.id as id,
                        se.id as id_espece,
                        se.type_espece as type_espece,
                        se.code as code,
                        se.nom as nom,
                        se.nom_scientifique as nom_scientifique,
                        se.nom_francaise as nom_francaise,
                        se.nom_local as nom_local

                    from
                        sip_especes_permis as sep,
                        sip_espece as se
                    where
                        sep.id_espece = se.id
                        and sep.id_permis = ".$id_permis."

                ";

               
        return $this->db->query($sql)->result();              
    }


    public function findById($id)  {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }  
    }

    
}
