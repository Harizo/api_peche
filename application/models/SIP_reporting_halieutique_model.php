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
   
}
