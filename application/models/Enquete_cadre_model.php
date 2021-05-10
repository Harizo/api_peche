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

    public function findAllByRequetes_region_site_unite_peche($requete)//requete.php
    {
        $result =  $this->db->select('region.nom as nom_region, unite_peche.libelle as libelle_unite_peche, site_embarquement.libelle as libelle,enquete_cadre.nbr_unite_peche as nbr_unite_peche,enquete_cadre.annee as annee')
                        ->from($this->table)
                        ->join('site_embarquement', 'site_embarquement.id = enquete_cadre.id_site_embarquement')
                        ->join('unite_peche', 'unite_peche.id = enquete_cadre.id_unite_peche')
                        ->join('region', 'region.id = site_embarquement.id_region')

                        ->group_by('id_site_embarquement')                          
                        ->group_by('id_unite_peche')  
                        ->group_by('enquete_cadre.id_region')  
                        ->group_by('enquete_cadre.annee')  

                        ->where($requete)
                        
                        //->order_by('id_site_embarquement')
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }
//
    public function findAllByRequetes_region($requete)//requete.php
    {
        $result =  $this->db->select('SUM(enquete_cadre.nbr_unite_peche) as nbr_unite_peche,region.nom as nom_region, unite_peche.libelle as libelle_unite_peche ,enquete_cadre.annee as annee')
                        ->from($this->table)
                        ->join('site_embarquement', 'site_embarquement.id = enquete_cadre.id_site_embarquement')
                        ->join('unite_peche', 'unite_peche.id = enquete_cadre.id_unite_peche')
                        ->join('region', 'region.id = site_embarquement.id_region')

                        //->group_by('id_site_embarquement')                          
                        ->group_by('id_unite_peche')  
                        //->group_by('enquete_cadre.id_region')  
                        ->group_by('enquete_cadre.annee')  

                        ->where($requete)
                        
                        //->order_by('id_site_embarquement')
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

    public function findByannee_site_unite_peche_regionTALOHA($requete) {
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

    public function findByannee_site_unite_peche_region($annee, $id_region, $id_district,$id_site_embarquement, $id_unite_peche)
    {
        


        if (($id_district!='*')&&($id_district!='undefined')) 
        {
            if (($id_site_embarquement!='*')&&($id_site_embarquement!='undefined')) 
            {
                $sql = 
                "
                    select 
                        COUNT(ec.id_unite_peche) as nbr_unite_peche,
                        up.libelle
                    FROM 
                        enquete_cadre AS ec,
                        unite_peche AS up,
                        site_embarquement AS se,
                        district AS dist
                    WHERE 
                        ec.id_unite_peche = up.id
                        AND ec.id_site_embarquement = se.id
                        AND dist.id = se.id_district

                        AND ec.annee = ".$annee."
                        AND ec.id_region = ".$id_region."
                        AND dist.id = ".$id_district."
                        AND se.id = ".$id_site_embarquement."
                        AND ec.id_unite_peche = ".$id_unite_peche."
                ";
            }
            else
            {
                $sql = 
                "
                    select 
                        COUNT(ec.id_unite_peche) as nbr_unite_peche,
                        up.libelle
                    FROM 
                        enquete_cadre AS ec,
                        unite_peche AS up,
                        site_embarquement AS se,
                        district AS dist
                    WHERE 
                        ec.id_unite_peche = up.id
                        AND ec.id_site_embarquement = se.id
                        AND dist.id = se.id_district

                        AND ec.annee = ".$annee."
                        AND ec.id_region = ".$id_region."
                        AND dist.id = ".$id_district."
                        AND ec.id_unite_peche = ".$id_unite_peche."
                ";
            }
        }
        else
        {
            $sql = 
            "
                select 
                    COUNT(ec.id_unite_peche) as nbr_unite_peche,
                    up.libelle
                FROM 
                    enquete_cadre AS ec,
                    unite_peche AS up,
                    site_embarquement AS se,
                    district AS dist
                WHERE 
                    ec.id_unite_peche = up.id
                    AND ec.id_site_embarquement = se.id
                    AND dist.id = se.id_district

                    AND ec.annee = ".$annee."
                    AND ec.id_region = ".$id_region."
                    AND ec.id_unite_peche = ".$id_unite_peche."
            ";
        }

        if ($sql) 
        {
            $query= $this->db->query($sql);
            return $query->result();
        }
        else
            return false;
    }




    public function findById($id)  {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {   
            return $q->row();
        }
           
    }

    public function findBy_id_site_id_unite_peche_annee($id_site_embarquement, $id_unite_peche, $annee)  {
        $this->db->where("id_site_embarquement", $id_site_embarquement)
                 ->where("id_unite_peche", $id_unite_peche)
                 ->where("annee", $annee);
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

    public function get_last_year_by_site($id_site_embarquement)
    {

        $max_annee = $this->get_last_year();
        $sql =  "

                    select 
                        unite_peche.id as id,
                        unite_peche.libelle as libelle,
                        type_canoe.id as id_type_canoe,
                        type_engin.id as id_type_engin,
                        ".$id_site_embarquement." as id_site_embarquement
                        

                    from 
                        enquete_cadre,
                        unite_peche,
                        type_engin,
                        type_canoe
                    where
                        annee = ".$max_annee->annee."
                        and enquete_cadre.id_site_embarquement = ".$id_site_embarquement."
                        and unite_peche.id = enquete_cadre.id_unite_peche
                        and type_canoe.id = unite_peche.id_type_canoe
                        and type_engin.id = unite_peche.id_type_engin
                    group by id_unite_peche


                " ;

        return $this->db->query($sql)->result();
    }

}
