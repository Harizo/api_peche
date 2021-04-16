<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_base_village_model extends CI_Model {
   
   
    
    //RELEVE CAPTURE
        public function get_all_region()
        {

            $sql = 
            "
                select 
                    DISTINCT(region) 
                from 
                    sip_base_villages

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
                    sip_base_villages
                where region = '".$region."'

            " ;
            return $this->db->query($sql)->result();
        }


        public function get_all($region, $district)
        {

            $sql = 
            "
                select 
                    * 
                from 
                    sip_base_villages
                where
                    region='".$region."'
                    and district = '".$district."'
                

            " ;
            return $this->db->query($sql)->result();
        }

     

    

   
}
