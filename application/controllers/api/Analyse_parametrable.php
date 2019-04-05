<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//harizo
// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Analyse_parametrable extends REST_Controller {

    public function __construct() {
        parent::__construct();
       // $this->load->model('site_model', 'SiteManager');
        $this->load->model('fiche_echantillonnage_capture_model', 'Fiche_echantillonnage_captureManager');
        $this->load->model('unite_peche_model', 'Unite_pecheManager');
        $this->load->model('region_model', 'RegionManager');
        $this->load->model('district_model', 'DistrictManager');
        $this->load->model('site_embarquement_model', 'Site_embarquementManager');
    }
   
    public function index_get() 
    {
        $menu = $this->get('menu');
        $annee = $this->get('annee');
        $date_fin = $this->get('date_fin');
        $id_region = $this->get('id_region');
        $id_district = $this->get('id_district');
        $id_site_embarquement = $this->get('id_site_embarquement');
        $id_unite_peche = $this->get('id_unite_peche');
        

        //*********************************** Nombre echantillon ***************************[0]->nombre

        if ($menu == "analyse_parametrable") 
        {
            if(($id_unite_peche!='*')&&($id_unite_peche!='undefined'))
            {
                $all_unite_peche = $this->Unite_pecheManager->findByIdtab($id_unite_peche);
            }
            else
            {
                $all_unite_peche=$this->Unite_pecheManager->findAll();
            }

            if($id_region)
            {
                $all_region = $this->RegionManager->findById($id_region);
                
            }
            
                 
               if($all_region)
               {
                    if(($id_district!='*')&&($id_district!='undefined'))
                    {
                        $all_district = $this->DistrictManager->findByIdtab($id_district);
                    }
                    else
                    {
                        $all_district=$this->DistrictManager->findByregion($all_region->id);
                    }

                    if(($id_site_embarquement!='*')&&($id_site_embarquement!='undefined'))
                    {
                        $all_site_embarquement = $this->Site_embarquementManager->findByIdtab($id_site_embarquement);
                    }
                    else
                    {
                        $all_site_embarquement=$this->Site_embarquementManager->findByregion($all_region->id);
                    }
                    foreach ($all_district as $key => $value)
                    {
                        foreach ($all_site_embarquement as $key1 => $value1)
                        {
                            foreach ($all_unite_peche as $key2 => $value2)
                            {
                                $capture_totales=$this->Fiche_echantillonnage_captureManager->som_capture_totales($this->generer_requete_analyse($annee, $id_region, $value->id, $value1->id, $value2->id));                       
                               if($capture_totales[0]->capture)
                               {
                                    
                                   $data[$key2]['region']= $all_region->nom;
                                   $data[$key2]['district']= $value->nom;
                                   $data[$key2]['site_embarquement']= $value1->libelle;
                                   $data[$key2]['unite_peche']= $value2->libelle;                               
                                   $data[$key2]['capture_totales']=$capture_totales[0]->capture;
                                   $data[$key2]['prix']=$capture_totales[0]->prix;
                               }
                            
                           }
                        }
                    }
            }
            /*foreach ($all_unite_peche as $key => $value)
            {
                $data[$key]['unite_peche']=$value->libelle;
                $capture_totales=$this->Fiche_echantillonnage_captureManager->som_capture_totales($this->generer_requete_analyse($annee, $id_region, $id_district, $id_site_embarquement, $value->id));

                if($capture_totales)
                {
                  $data[$key]['capture_totales']=$capture_totales->total_capture;  
                }
                if(($id_region!='*')&&($id_region!='undefined'))
                {
                    $region = $this->RegionManager->findById($id_region);
                    $data[$key]['region']=$region;
                }
                else
                {
                  // $region = $this->RegionManager->findById($capture_totales->id_region);
                   //$data[$key]['region']=$region; 
                }
                if(($id_district!='*')&&($id_district!='undefined'))
                {
                    $district = $this->DistrictManager->findById($id_district);
                    $data[$key]['district']=$district;
                }

            }*/
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

    public function generer_requete_analyse($annee, $id_region, $id_district, $id_site_embarquement, $id_unite_peche)
    {
        $requete = "date BETWEEN '".$annee."-01-01' AND '".$annee."-12-31' " ;
            

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