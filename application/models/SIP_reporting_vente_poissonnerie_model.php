<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_reporting_vente_poissonnerie_model extends CI_Model {
   
    public function qte_vendues_par_poissonneries()
    {
       $sql = " CALL sip_vente_poissonnerie_req_1()  ";

        return $this->db->query($sql)->result();
                                
        if($result)
            return $result;
        else
            return null;
    }

     public function qte_vendues_par_poissonneries_mois()
    {
       $sql = " CALL sip_vente_poissonnerie_req_2() " ;

        return $this->db->query($sql)->result();
                                
        if($result)
            return $result;
        else
            return null;
    }

        public function qte_vendues_par_famille()
    {
        $sql = " CALL sip_vente_poissonnerie_req_4() " ;

        return $this->db->query($sql)->result();
                                
        if($result)
            return $result;
        else
            return null;
    }

    public function qte_vendues_produit_par_poissonneries()
    {
        $sql = " CALL sip_vente_poissonnerie_req_7() " ;

        return $this->db->query($sql)->result();
                                
        if($result)
            return $result;
        else
            return null;
    }

    public function prix_moyen_prod_par_poissonnerie()
    {
        $sql = " CALL sip_vente_poissonnerie_req_3() " ;

        return $this->db->query($sql)->result();
                                
        if($result)
            return $result;
        else
            return null;
    }

    public function prix_moyenne_par_famille($value='')
    {
        $sql = " CALL sip_vente_poissonnerie_req_5() " ;
        
        return $this->db->query($sql)->result();
                                
        if($result)
            return $result;
        else
            return null;
    }

     public function chif_aff_par_produit_poissonneries($value='')
    {
        $sql = " CALL sip_vente_poissonnerie_req_6() " ;
        
        return $this->db->query($sql)->result();
                                
        if($result)
            return $result;
        else
            return null;
    }
}