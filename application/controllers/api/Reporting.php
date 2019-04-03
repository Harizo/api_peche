<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//harizo
// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Reporting extends REST_Controller {

    public function __construct() {
        parent::__construct();
       // $this->load->model('site_model', 'SiteManager');
        $this->load->model('fiche_echantillonnage_capture_model', 'Fiche_echantillonnage_captureManager');
        $this->load->model('unite_peche_model', 'Unite_pecheManager');
        $this->load->model('enquete_cadre_model', 'Enquete_cadreManager');  
    }
   
    public function index_get() 
    {
        $menu = $this->get('menu');
        $annee = $this->get('annee');
        $date_fin = $this->get('date_fin');
        $id_espece = $this->get('id_espece');
        $id_region = $this->get('id_region');
        $id_district = $this->get('id_district');
        $id_site_embarquement = $this->get('id_site_embarquement');
        $id_unite_peche = $this->get('id_unite_peche');
        

        //*********************************** Nombre echantillon ***************************[0]->nombre

        if ($menu == "nombre_echantillon") 
        {

            

            if (($id_unite_peche!='*')&&($id_unite_peche!='undefined')) 
            {
                $all_unite_peche = $this->Unite_pecheManager->findByIdtab($id_unite_peche);
            }
            else 
            {
                $all_unite_peche = $this->Unite_pecheManager->findAll();
            }

            foreach ($all_unite_peche as $key => $value) 
            {
                $_1 = 0 ;
                $_2 = 0 ;
                $_3 = 0 ;
                $_4 = 0 ;
                $_5 = 0 ;
                $_6 = 0 ;
                $_7 = 0 ;
                $_8 = 0 ;
                $_9 = 0 ;
                $_10 = 0 ;
                $_11 = 0 ;
                $_12 = 0 ;

               
                $data[$key]['unite_peche'] = $value->libelle ;

                $enquete_cadre = $this->Enquete_cadreManager->findByannee_site_unite_peche_region($annee, $id_region, $value->id);//enquete cadre

                if ($enquete_cadre[0]->nbr_unite_peche != null) 
                {
                    $data[$key]['enquete_cadre'] = $enquete_cadre[0]->nbr_unite_peche ;
                }
                else 
                {
                    $data[$key]['enquete_cadre'] = '0' ;
                }
                


                //**********recuperation nbr_echantillon par mois

                $nbr_ech_1 = $this->Fiche_echantillonnage_captureManager->get_nbr_echantillon($this->generer_requete($annee, 01, $id_region, $id_district, $id_site_embarquement, $value->id));

                if ($nbr_ech_1) 
                {
                    $_1 = $nbr_ech_1[0]->nombre ;
                }
                

                $nbr_ech_2 = $this->Fiche_echantillonnage_captureManager->get_nbr_echantillon($this->generer_requete($annee, 02, $id_region, $id_district, $id_site_embarquement, $value->id));

               if ($nbr_ech_2) 
                {
                    $_2 = $nbr_ech_2[0]->nombre ;
                }

                $nbr_ech_3 = $this->Fiche_echantillonnage_captureManager->get_nbr_echantillon($this->generer_requete($annee, 03, $id_region, $id_district, $id_site_embarquement, $value->id));

                if ($nbr_ech_3) 
                {
                    $_3 = $nbr_ech_3[0]->nombre ;
                }

                $nbr_ech_4 = $this->Fiche_echantillonnage_captureManager->get_nbr_echantillon($this->generer_requete($annee, 04, $id_region, $id_district, $id_site_embarquement, $value->id));

                if ($nbr_ech_4) 
                {
                    $_4 = $nbr_ech_4[0]->nombre ;
                }

                $nbr_ech_5 = $this->Fiche_echantillonnage_captureManager->get_nbr_echantillon($this->generer_requete($annee, 05, $id_region, $id_district, $id_site_embarquement, $value->id));

                if ($nbr_ech_5) 
                {
                    $_5 = $nbr_ech_5[0]->nombre ;
                }

                $nbr_ech_6 = $this->Fiche_echantillonnage_captureManager->get_nbr_echantillon($this->generer_requete($annee, 06, $id_region, $id_district, $id_site_embarquement, $value->id));
                if ($nbr_ech_6) 
                {
                    $_6 = $nbr_ech_6[0]->nombre ;
                }

                $nbr_ech_7 = $this->Fiche_echantillonnage_captureManager->get_nbr_echantillon($this->generer_requete($annee, 07, $id_region, $id_district, $id_site_embarquement, $value->id));
                if ($nbr_ech_7) 
                {
                    $_7 = $nbr_ech_7[0]->nombre ;
                }

                $nbr_ech_8 = $this->Fiche_echantillonnage_captureManager->get_nbr_echantillon($this->generer_requete($annee, 8, $id_region, $id_district, $id_site_embarquement, $value->id));
                if ($nbr_ech_8) 
                {
                    $_8 = $nbr_ech_8[0]->nombre ;
                }

                $nbr_ech_9 = $this->Fiche_echantillonnage_captureManager->get_nbr_echantillon($this->generer_requete($annee, 9, $id_region, $id_district, $id_site_embarquement, $value->id));
                if ($nbr_ech_9) 
                {
                    $_9 = $nbr_ech_9[0]->nombre ;
                }

                $nbr_ech_10 = $this->Fiche_echantillonnage_captureManager->get_nbr_echantillon($this->generer_requete($annee, 10, $id_region, $id_district, $id_site_embarquement, $value->id));
                if ($nbr_ech_10) 
                {
                    $_10 = $nbr_ech_10[0]->nombre ;
                }

                $nbr_ech_11 = $this->Fiche_echantillonnage_captureManager->get_nbr_echantillon($this->generer_requete($annee, 11, $id_region, $id_district, $id_site_embarquement, $value->id));
                if ($nbr_ech_11) 
                {
                    $_11 = $nbr_ech_11[0]->nombre ;
                }

                $nbr_ech_12 = $this->Fiche_echantillonnage_captureManager->get_nbr_echantillon($this->generer_requete($annee, 12, $id_region, $id_district, $id_site_embarquement, $value->id));
                if ($nbr_ech_12) 
                {
                    $_12 = $nbr_ech_12[0]->nombre ;
                }



               
                //**********recuperation nbr_echantillon par mois

                $data[$key]['jan'] = $_1 ;
                $data[$key]['fev'] = $_2 ;
                $data[$key]['mar'] = $_3 ;
                $data[$key]['avr'] = $_4 ;
                $data[$key]['mai'] = $_5 ;
                $data[$key]['juin'] = $_6 ;
                $data[$key]['jui'] = $_7 ;
                $data[$key]['aou'] = $_8 ;
                $data[$key]['sep'] = $_9 ;
                $data[$key]['oct'] = $_10 ;
                $data[$key]['nov'] = $_11 ;
                $data[$key]['dec'] = $_12 ;
               
            }
            
            
            


        }
        
        
        //********************************* fin Nombre echantillon *****************************
        if (count($data)>0) {
            $this->response([
                'status' => TRUE,
                'response' => $data,
                'message' => 'Get data success',
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => FALSE,
                'response' => array(),
                'message' => 'No data were found'
            ], REST_Controller::HTTP_OK);
        }
    }

    public function generer_requete($annee, $month, $id_region, $id_district, $id_site_embarquement, $id_unite_peche)
    {
        $requete = "date BETWEEN '".$annee."-".$month."-01' AND '".$annee."-".$month."-31' " ;
            

            if (($id_region!='*')&&($id_region!='undefined')) 
            {
                $requete = $requete." AND id_region='".$id_region."'" ;
            }

            if (($id_district!='*')&&($id_district!='undefined')) 
            {
                $requete = $requete." AND id_district='".$id_district."'" ;
            }

            if (($id_site_embarquement!='*')&&($id_site_embarquement!='undefined')) 
            {
                $requete = $requete." AND id_site_embarquement='".$id_site_embarquement."'" ;
            }

            if (($id_unite_peche!='*')&&($id_unite_peche!='undefined')) 
            {
                $requete = $requete." AND id_unite_peche='".$id_unite_peche."'" ;
            }

        return $requete ;
    }
    

}
/* End of file controllername.php */
/* Location: ./application/controllers/controllername.php */
?>