<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Enquete_cadre_model extends CI_Model {
    protected $table = 'enquete_cadre';

    public function add($enquete_cadre) {
        $this->db->set($this->_set($enquete_cadre))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $enquete_cadre) {
        $this->db->set($this->_set($enquete_cadre))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1) {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($enquete_cadre) {
        return array(
            'annee'                   =>      $enquete_cadre['annee'],
            'id_region'               =>      $enquete_cadre['id_region'],
            'id_district'             =>      $enquete_cadre['id_district'],
            'id_site_embarquement'    =>      $enquete_cadre['id_site_embarquement'],
            'id_unite_peche'          =>      $enquete_cadre['id_unite_peche'],
            'nbr_unite_peche'         =>      $enquete_cadre['nbr_unite_peche']                     
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

    public function findAllbyannee($annee) {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("annee", $annee)
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

    public function findByannee_site_unite_peche_region($requete) {
        $result =  $this->db->select_sum('nbr_unite_peche')
                        ->from($this->table)
                        ->where($requete)
                        /*->where("annee", $annee)
                        ->where("id_region", $id_region)
                       // ->where("id_site_embarquement", $id_site_embarquement)
                        ->where("id_unite_peche", $id_unite_peche)*/
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

    public function findById($id)  {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {   
            return $q->row();
        }
           
    }



    public function get_last_year()
    {

         $this->db->select_max('annee');
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {   
            return $q->row();
        }
       
    }

    public function duplication($last_year, $now_year)
    {
        $requete="Insert into enquete_cadre (".
            "annee,". 
            "id_region,".    
            "id_district,".    
            "id_site_embarquement,".    
            "id_unite_peche,".    
            "nbr_unite_peche".    
               

            ") Select ". 
            $now_year.   
            ",a.id_region,".    
            "a.id_district,".    
            "a.id_site_embarquement,".    
            "a.id_unite_peche,".   
            "a.nbr_unite_peche ". 
            "From enquete_cadre as a Where a.annee=".$last_year;

        /*$query= $this->db->query($requete);
        return $query->result();*/
        if ($requete) {
            return $this->db->query($requete);
        }
        else
            return false;
    }

}
