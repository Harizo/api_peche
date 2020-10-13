<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_reporting_halieutique_model extends CI_Model {
   
   
    public function get_somme_capture_all_espece_by_dist()
    {

        $sql = 
        "
        call sip_get_somme_capture_all_espece_by_dist

        " ;
        return $this->db->query($sql)->result();
    }


    public function sip_quantite_collecte_region()
    {

        $sql = 
        "
        call sip_quantite_collecte_region

        " ;
        return $this->db->query($sql)->result();
    }

    public function sip_quantite_collecte_mois()
    {

        $sql = 
        "
        call sip_quantite_collecte_mois

        " ;
        return $this->db->query($sql)->result();
    }

    public function sip_quantite_collecte_operateur()
    {

        $sql = 
        "
        call sip_quantite_collecte_operateur

        " ;
        return $this->db->query($sql)->result();
    }

    public function sip_quantite_collecte_espece()
    {

        $sql = 
        "
        call sip_quantite_collecte_espece

        " ;
        return $this->db->query($sql)->result();
    }

    public function sip_prix_moyenne_mois()
    {

        $sql = 
        "
        call sip_prix_moyenne_mois

        " ;
        return $this->db->query($sql)->result();
    }

    public function sip_prix_moyenne_district()
    {

        $sql = 
        "
        call sip_prix_moyenne_district

        " ;
        return $this->db->query($sql)->result();
    }

    public function sip_prix_moyenne_region()
    {

        $sql = 
        "
        call sip_prix_moyenne_region

        " ;
        return $this->db->query($sql)->result();
    }


   
}
