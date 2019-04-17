<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site_embarquement_model extends CI_Model {
    protected $table = 'site_embarquement';

    public function add($site_embarquement) {
        $this->db->set($this->_set($site_embarquement))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $site_embarquement) {
        $this->db->set($this->_set($site_embarquement))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1) {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($site_embarquement) {
        return array(
            'code'           =>      $site_embarquement['code'],
            'libelle'        =>      $site_embarquement['libelle'],
            'code_unique'    =>      $site_embarquement['code_unique'],
            'id_region'      =>      $site_embarquement['id_region'],
            'id_district'    =>      $site_embarquement['id_district'],
            'latitude'       =>      $site_embarquement['latitude'],
            'longitude'      =>      $site_embarquement['longitude'],
            'altitude'       =>      $site_embarquement['altitude'],
            'limite'         =>      $site_embarquement['limite'],
            'type_effort_peche' =>   $site_embarquement['type_effort_peche'],                       
        );
    }
    public function delete($id) {
        $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1) {
            return true;
        }else{
            return null;
        }  
    }
    public function findAll() {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('code')
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }	

    public function findAllByFiche($annee) 
    {
        $requete = "date BETWEEN '".$annee."-01-01' AND '".$annee."-12-31' " ;

        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->join('fiche_echantillonnage_capture', 'fiche_echantillonnage_capture.id_site_embarquement = site_embarquement.id')
                        ->order_by('code')
                        ->group_by('site_embarquement.id')
                        ->where($requete) 
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
    public function findByIdtab($id)
    {   $result =  $this->db->select('id as id_site_embarquement,libelle')
                        ->from($this->table)
                        ->where("id", $id)
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

    public function findByregion($id_region)
    {   $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("id_region", $id_region)
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
