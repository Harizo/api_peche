<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_receptiondesdonneesmobile_model extends CI_Model {

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('SIP_carte_pecheur_model', 'SIP_CPec');
        $this->load->model('SIP_carte_pirogue_model', 'SIP_CPir');
        $this->load->model('SIP_engin_carte_pecheur_model', 'SIP_Egn');
    }
    

    
    public function enregistrer_tout($cpec, $egn_cpec, $cpir_cpec)
    {
        $this->db->trans_begin ();
        $id_cpec = $this->SIP_CPec->add($cpec);


        foreach ($egn_cpec as $key => $value) 
        {
            $data_egn = array(
                 
                    'id_carte_pecheur'          =>      $id_cpec,
                    'id_type_engin'             =>      $value->id_type_engin,
                    'nbr_engin'                 =>      $value->nbr_engin,      
                    'utilisation_engin'         =>      $value->utilisation_engin,      
                    'longueur'                  =>      $value->longueur,      
                    'largeur'                   =>      $value->largeur,
                    'hauteur'                   =>      $value->hauteur,
                    'maille'                    =>      $value->maille,
                    'hamecon'                   =>      $value->hamecon,
                    'etat_principale'           =>      $value->etat_principale
                );

            $dataId_egn = $this->SIP_Egn->add($data_egn);

        }

        foreach ($cpir_cpec as $key => $value) 
        {
            $data_cpir = array(
                 
                
                'id_carte_pecheur'          =>      $id_cpec,
                'immatriculation'           =>      $value->immatriculation,
                'an_cons'                   =>      $value->an_cons,           
                'longueur'                  =>      $value->longueur,      
                'largeur'                   =>      $value->largeur,
                'c'                         =>      $value->c,
                'coul'                      =>      $value->coul,
                'nat'                       =>      $value->nat,
                'prop'                      =>      $value->prop,
                'type'                      =>      $value->type,
                'observations'             =>       $value->observations,
                'etat_proprietaire'         =>      $value->etat_proprietaire,
                'proprietaire'              =>      $value->proprietaire
            );
            $dataId_cpir = $this->SIP_CPir->add($data_cpir);
        }

        if  ( $this->db->trans_status ()  ===  FALSE ) 
        { 
            $date=new datetime();
            $date_anio=$date->format('Y-m-d HH:mm:ss');                     
            error_log("Erreur dans SIP_receptiondesdonneesmobile_model - Function save_all :" . $date_anio.' (Rollback)');

            $this->db->trans_rollback (); return "ECHEC" ;
        } 
        else 
        { 
            $date=new datetime();
            $date_anio=$date->format('Y-m-d HH:mm:ss');    
            error_log("Erreur dans SIP_receptiondesdonneesmobile_model - Function save_all :" . $date_anio.' (test_commit)');
            $this->db->trans_commit ();   return $id_cpec ;
        }
    }

 
        
}
