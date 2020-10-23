<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_reporting_production_commercialisation_region_model extends CI_Model {
   
    public function prod_commerc_region_product_par_region()
    {
       $sql=" CALL sip_prod_commerc_region_product_par_region_req9_1 () " ;
        return $this->db->query($sql)->result();
    }

     public function prod_commerc_region_product_par_region_mois()
    {
        $sql=" CALL sip_prod_commerc_region_product_par_region_mois_req9_2 () " ;
       return $this->db->query($sql)->result();
    }

    public function prod_commerc_region_product_par_region_nbr()
    {
        $sql=" CALL sip_prod_commerc_region_product_par_region_nbr_req9_3 () " ;
        return $this->db->query($sql)->result();
    }

    public function prod_commerc_region_product_par_region_mois_nbr()
    {
        $sql=" CALL sip_prod_commerc_region_product_par_region_mois_nbr_req9_4 () " ;
        return $this->db->query($sql)->result();
    }

    public function prod_commerc_region_commercialisation()
    {
        $sql=" CALL sip_prod_commerc_region_commercialisation_req10_1() " ;
        return $this->db->query($sql)->result();
    }

     public function prod_commerc_region_commercialisation_mois()
    {

        $sql=" CALL sip_prod_commerc_region_commercialisation_mois_req10_2() " ;
        return $this->db->query($sql)->result();
    }
     public function prod_commerc_region_commercialisation_par_region()
    {
        $sql=" CALL sip_prod_commerc_region_commercialisat_regionmois_req10_3() " ;

        return $this->db->query($sql)->result();
    }

    public function prod_commerc_region_quantite_production_nationale()
    {
        $sql=" CALL sip_prod_comm_region_quantite_production_nationale_req9_5() " ;

        return $this->db->query($sql)->result();
    }
   
}