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
        $pivot = $this->get('pivot');

        $data = array() ;
        $donnees = array() ;
        

        //*********************************** Nombre echantillon ***************************[0]->nombre

        if ($menu == "analyse_parametrable") 
        {
            

            
            //initialisation
                if (($id_region!='*')&&($id_region!='undefined')) 
                {
                    $all_region = $this->RegionManager->findByIdtab($id_region);
                }
                else 
                {
                    $all_region = $this->RegionManager->findAll();
                }

                if(($id_unite_peche!='*')&&($id_unite_peche!='undefined'))
                {
                    $all_unite_peche = $this->Unite_pecheManager->findByIdtab($id_unite_peche);
                }
                else
                {
                    $all_unite_peche=$this->Unite_pecheManager->findAll();
                }

                if(($id_site_embarquement!='*')&&($id_site_embarquement!='undefined'))
                {
                    $all_site_embarquement = $this->Site_embarquementManager->findByIdtab($id_site_embarquement);
                }
                else
                {
                    $all_site_embarquement=$this->Site_embarquementManager->findAllByFiche($annee);
                }
            //initialisation


                
                
            //Pivot * 
                if ($pivot == "*") 
                {
                    $indice = 0 ;
                    $total_prix = 0 ;
                    $total_capture = 0 ;
                    $donnees=$this->Fiche_echantillonnage_captureManager->som_capture_totales($this->generer_requete_analyse($annee, $id_region, $id_district, $id_site_embarquement, $id_unite_peche));

                    if ($donnees[0]->capture != null )
                    {
                        $total_prix = $total_prix + $donnees[0]->prix ;
                        $total_capture = $total_capture + $donnees[0]->capture ;
                       

                        $donnees[0]->region = "-" ;
                        $donnees[0]->site_embarquement = "-" ;
                        $donnees[0]->unite_peche = "-" ;
                        $data[$indice] = $donnees[0] ;
                        $indice++ ;
                    }

                    /*$data['total_prix'] = $total_prix ;
                    $data['total_capture'] = $total_capture ;*/
                        
                    
                }
            //Pivot * 

            //Pivot region 
                if ($pivot == "id_region") 
                {
                    $indice = 0 ;
                    $total_prix = 0 ;
                    $total_capture = 0 ;
                    foreach ($all_region as $key_region => $value_region) 
                    {
                        $donnees=$this->Fiche_echantillonnage_captureManager->som_capture_totales($this->generer_requete_analyse($annee, $value_region->id, $id_district, $id_site_embarquement, $id_unite_peche));

                        if ($donnees[0]->capture != null )
                        {
                            $total_prix = $total_prix + $donnees[0]->prix ;
                            $total_capture = $total_capture + $donnees[0]->capture ;
                            $donnees[0]->region = $value_region->nom ;
                            $donnees[0]->site_embarquement = "-" ;
                            $donnees[0]->unite_peche = "-" ;
                            $data[$indice] = $donnees[0] ;
                            $indice++ ;
                        }
                        
                    }
                    /*$data['total_prix'] = $total_prix ;
                    $data['total_capture'] = $total_capture ;*/
                }
            //Pivot region
            //Pivot unite peche 
                if ($pivot == "id_unite_peche") 
                {
                    $indice = 0 ;
                    $total_prix = 0 ;
                    $total_capture = 0 ;
                    foreach ($all_unite_peche as $key => $value) 
                    {
                        $donnees=$this->Fiche_echantillonnage_captureManager->som_capture_totales($this->generer_requete_analyse($annee, $id_region , $id_district, $id_site_embarquement, $value->id));

                        if ($donnees[0]->capture != null )
                        {
                            $total_prix = $total_prix + $donnees[0]->prix ;
                            $total_capture = $total_capture + $donnees[0]->capture ;
                            $donnees[0]->region = "-" ;
                            $donnees[0]->site_embarquement = "-" ;
                            $donnees[0]->unite_peche = $value->libelle ;
                            $data[$indice] = $donnees[0] ;
                            $indice++ ;
                        }
                        
                    }
                   /* $data['total_prix'] = $total_prix ;
                    $data['total_capture'] = $total_capture ;*/
                }
            //Pivot unite peche 

            //Pivot site 
                if ($pivot == "id_site_embarquement") 
                {
                    $indice = 0 ;
                    $total_prix = 0 ;
                    $total_capture = 0 ;
                    if ($all_site_embarquement) {
                        foreach ($all_site_embarquement as $key => $value) 
                        {
                            $donnees=$this->Fiche_echantillonnage_captureManager->som_capture_totales($this->generer_requete_analyse($annee, $id_region , $id_district, $value->id_site_embarquement, $id_unite_peche));

                            if ($donnees[0]->capture != null )
                            {
                                $total_prix = $total_prix + $donnees[0]->prix ;
                                $total_capture = $total_capture + $donnees[0]->capture ;
                                $donnees[0]->region = "-" ;
                                $donnees[0]->site_embarquement = $value->libelle ;
                                $donnees[0]->unite_peche = "-" ;
                                $data[$indice] = $donnees[0] ;
                                $indice++ ;
                            }
                            
                        }
                    }
                    

                    /*$data['total_prix'] = $total_prix ;
                    $data['total_capture'] = $total_capture ;*/
                }
            //Pivot site 

            //Pivot region  and unite peche
                if ($pivot == "id_region_and_id_unite_peche") 
                {
                    $indice = 0 ;
                    $total_prix = 0 ;
                    $total_capture = 0 ;
                    foreach ($all_region as $key_region => $value_region) 
                    {
                        

                        foreach ($all_unite_peche as $key => $value) 
                        {
                            $donnees=$this->Fiche_echantillonnage_captureManager->som_capture_totales($this->generer_requete_analyse($annee, $value_region->id , $id_district, $id_site_embarquement, $value->id));

                            if ($donnees[0]->capture != null )
                            {
                                $total_prix = $total_prix + $donnees[0]->prix ;
                                $total_capture = $total_capture + $donnees[0]->capture ;
                                $donnees[0]->region = $value_region->nom ;
                                $donnees[0]->site_embarquement = "-" ;
                                $donnees[0]->unite_peche = $value->libelle ;
                                $data[$indice] = $donnees[0] ;
                                $indice++ ;
                            }
                            
                        }
                        
                    }

                    /*$data['total_prix'] = $total_prix ;
                    $data['total_capture'] = $total_capture ;*/
                }
            //Pivot region and unite peche
            //Pivot site  and unite peche
                if ($pivot == "id_site_embarquement_and_id_unite_peche") 
                {
                    $indice = 0 ;
                    $total_prix = 0 ;
                    $total_capture = 0 ;
                    foreach ($all_site_embarquement as $key_site => $value_site) 
                    {
                        

                        foreach ($all_unite_peche as $key => $value) 
                        {
                            $donnees=$this->Fiche_echantillonnage_captureManager->som_capture_totales($this->generer_requete_analyse($annee, $id_region , $id_district, $value_site->id_site_embarquement, $value->id));

                            if ($donnees[0]->capture != null )
                            {
                                $total_prix = $total_prix + $donnees[0]->prix ;
                                $total_capture = $total_capture + $donnees[0]->capture ;
                                $donnees[0]->region = "-";
                                $donnees[0]->site_embarquement = $value_site->libelle ;
                                $donnees[0]->unite_peche = $value->libelle ;
                                $data[$indice] = $donnees[0] ;
                                $indice++ ;
                            }

                               
                            
                        }
                        
                    }
                   /* $data['total_prix'] = $total_prix ;
                    $data['total_capture'] = $total_capture ;*/
                }
            //Pivot site and unite peche

                $total['total_prix'] = $total_prix ;
                    $total['total_capture'] = $total_capture ;
            

        }
        
        
        //********************************* fin Nombre echantillon *****************************
        if (count($data)>0) {
            $this->response([
                'status' => TRUE,
                'response' => $data,
                'total' => $total,
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