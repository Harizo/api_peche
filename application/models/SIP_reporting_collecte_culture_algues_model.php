<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_reporting_collecte_culture_algues_model extends CI_Model {
   
    public function quantite_par_mois_culture_dalgues()
    {
       $sql = " CALL sip_collecte_culture_algues_req8_1()  ";

        return $this->db->query($sql)->result();
    }

     public function quantite_par_villagee_culture_dalgues()
    {
       $sql = " CALL sip_collecte_culture_algues_req8_2() " ;

        return $this->db->query($sql)->result();
    }

    public function quantite_par_commune_culture_dalgues()
    {
        $sql = " CALL sip_collecte_culture_algues_req8_3() " ;

        return $this->db->query($sql)->result();
    }

    public function montant_par_mois_culture_dalgues()
    {
        $sql = " CALL sip_collecte_culture_algues_req8_4() " ;

        return $this->db->query($sql)->result();
    }

    public function montant_par_village_culture_dalgues()
    {
        $sql = " CALL sip_collecte_culture_algues_req8_5() " ;
        
        return $this->db->query($sql)->result();
    }
}