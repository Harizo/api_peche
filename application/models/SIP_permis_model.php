<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_permis_model extends CI_Model {
    protected $table = 'sip_permis';

    public function add($SIP_permis) {
        $this->db->set($this->_set($SIP_permis))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $SIP_permis) {
        $this->db->set($this->_set($SIP_permis))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($SIP_permis) {
        return array(
            'id_collecteur_mareyeur'            =>      $SIP_permis['id_collecteur_mareyeur'],
            'id_espece'                   		=>      $SIP_permis['id_espece'],                 
            'id_district'            			=>      $SIP_permis['id_district'],                 
            'numero_permis'               		=>      $SIP_permis['numero_permis'],                 
            'date_quittance'      				=>      $SIP_permis['date_quittance']

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
                        ->order_by('id_collecteur_mareyeur')
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }


    public function find_all_join($id_collecteurs)
    {

        $sql = 
        "
            select 

                sch.id as id,

                cm.id as id_collecteurs,
                cm.nom as nom_collecteurs,

                e.id as id_espece,
                e.nom as nom_espece,

                d.id as id_district,
                d.nom as nom_district,


                reg.id as id_region,
                reg.nom as nom_region,

            

                sch.numero_permis,
                sch.date_quittance

            from
                sip_permis as sch,
                sip_collecteurs_mareyeur as cm,
                sip_espece as e,
                district as d,
                region as reg
            where
                sch.id_collecteur_mareyeur = cm.id
                and sch.id_espece = e.id
                and sch.id_district = d.id
                and d.id_region = reg.id
                and sch.id_collecteur_mareyeur = ".$id_collecteurs." 
            order by sch.id_collecteur_mareyeur desc

        " ;
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
