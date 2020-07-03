<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Update_id_commune_in_fokontany_model extends CI_Model {
    protected $dist_tmp = 'district_tmp';
    protected $dist = 'district';
    protected $com = 'commune';
    protected $com_tmp = 'commune_tmp';

    public function update_district_tmp($id_pgsql, $id_region) {
                    $this->db->set('id_region', $id_region, false)
                            ->where('id_pgsql', (int) $id_pgsql)
                            ->update($this->dist_tmp);
        if($this->db->affected_rows() === 1) {
            return true;
        }else{
            return null;
        }                      
    }

    public function update_commune_tmp($id_district_pgsql, $id_district) {
                    $this->db->set('id_district', $id_district, false)
                            ->where('id_district_pgsql', (int) $id_district_pgsql)
                            ->update($this->com_tmp);
        if($this->db->affected_rows() === 1) {
            return true;
        }else{
            return null;
        }                      
    }


    public function _set($dist) {
        return array(
            'id'           =>      $dist['id']                   
        );
    }
 
    public function findAlldistrict() {
        $result =  $this->db->select('*')
                        ->from($this->dist_tmp)
                        ->order_by('nom')
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }
    public function findBy_dist_by_nom($nom)
    {
        $this->db->where("nom", $nom);
        $q = $this->db->get($this->dist);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return null;
    }

    public function findBynom_commune($nom)
    {
        $this->db->where("lower(nom)", $nom);
        $q = $this->db->get($this->com);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return null;
    }
    
}
