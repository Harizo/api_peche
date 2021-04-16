<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_base_menage_model extends CI_Model {
   
   
    
    //RELEVE CAPTURE
        public function get_all_region()
        {

            $sql = 
            "
                select 
                    DISTINCT(region) 
                from 
                    sip_base_menages

            " ;
            return $this->db->query($sql)->result();
        }

        public function get_all_district_by_region($region)
        {

            $sql = 
            "
                select 
                    DISTINCT(district) 
                from 
                    sip_base_menages
                where region = '".$region."'

            " ;
            return $this->db->query($sql)->result();
        }


        public function get_all_commune_by_district($district)
        {

            $sql = 
            "
                select 
                    DISTINCT(commune) 
                from 
                    sip_base_menages
                where district = '".$district."'

            " ;
            return $this->db->query($sql)->result();
        }


        public function get_all($region, $district, $commune)
        {

            $sql = 
            "
                select 
                    * 
                from 
                    sip_base_menages
                where
                    region='".$region."'
                    and district = '".$district."'
                    and commune = '".$commune."'
                

            " ;
            return $this->db->query($sql)->result();
        }

     

    

   
}
