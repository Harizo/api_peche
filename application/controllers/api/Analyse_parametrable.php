<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//harizo
// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Analyse_parametrable extends REST_Controller {

    public function __construct() {
        parent::__construct();
       
        $this->load->model('fiche_echantillonnage_capture_model', 'Fiche_echantillonnage_captureManager');
        $this->load->model('unite_peche_model', 'Unite_pecheManager');
        $this->load->model('region_model', 'RegionManager');
        $this->load->model('district_model', 'DistrictManager');
        $this->load->model('site_embarquement_model', 'Site_embarquementManager');
        $this->load->model('Espece_model', 'EspeceManager');
    }
   
    public function index_get() 
    {
        set_time_limit(0);
        ini_set ('memory_limit', '1024M');
        $menu = $this->get('menu');
        $annee = $this->get('annee');
        $date_fin = $this->get('date_fin');
        $id_region = $this->get('id_region');
        $id_district = $this->get('id_district');
        $id_site_embarquement = $this->get('id_site_embarquement');
        $id_unite_peche = $this->get('id_unite_peche');
        $id_espece = $this->get('id_espece');
        $pivot = $this->get('pivot');
        $mois = "*" ;
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


                if(($id_espece!='*')&&($id_espece!='undefined'))
                {
                    $all_espece = $this->EspeceManager->findByIdtab($id_espece);
                }
                else
                {
                    $all_espece=$this->EspeceManager->findAllByFiche($annee);
                }
            //initialisation


                
                
            //Pivot * 
                if ($pivot == "*") 
                {
                    $indice = 0 ;
                    $total_prix = 0 ;
                    $total_capture = 0 ;
                    $donnees=$this->Fiche_echantillonnage_captureManager->som_capture_totales($this->generer_requete_analyse($annee,$mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece));

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
                        $donnees=$this->Fiche_echantillonnage_captureManager->som_capture_totales($this->generer_requete_analyse($annee,$mois, $value_region->id, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece));

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

            //Pivot mois 
                if ($pivot == "mois_strate_majeur") 
                {
                    $indice = 0 ;
                    $total_prix = 0 ;
                    $total_capture = 0 ;
                    for ($mois=1; $mois <=12 ; $mois++) 
                    {
                        $donnees=$this->Fiche_echantillonnage_captureManager->som_capture_totales($this->generer_requete_analyse($annee,$mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece));

                        if ($donnees[0]->capture != null )
                        {
                            $total_prix = $total_prix + $donnees[0]->prix ;
                            $total_capture = $total_capture + $donnees[0]->capture ;
                            $donnees[0]->mois = $this->affichage_mois($mois) ;
                            $donnees[0]->region = "-" ;
                            $donnees[0]->site_embarquement = "-" ;
                            $donnees[0]->unite_peche = "-" ;
                            $data[$indice] = $donnees[0] ;
                            $indice++ ;
                        }
                        
                    }
                
                }
            //Pivot mois

            //Pivot mois  unité de peche
                if ($pivot == "mois_and_id_unite_peche") 
                {
                    $indice = 0 ;
                    $total_prix = 0 ;
                    $total_capture = 0 ;
                    for ($mois=1; $mois <=12 ; $mois++) 
                    {
                        foreach ($all_unite_peche as $key => $value) 
                        {
                            $donnees=$this->Fiche_echantillonnage_captureManager->som_capture_totales($this->generer_requete_analyse($annee,$mois, $id_region , $id_district, $id_site_embarquement, $value->id, $id_espece));

                            if ($donnees[0]->capture != null )
                            {
                                $total_prix = $total_prix + $donnees[0]->prix ;
                                $total_capture = $total_capture + $donnees[0]->capture ;
                                $donnees[0]->region = "-" ;
                                $donnees[0]->mois = $this->affichage_mois($mois) ;
                                $donnees[0]->site_embarquement = "-" ;
                                $donnees[0]->unite_peche = $value->libelle ;
                                $data[$indice] = $donnees[0] ;
                                $indice++ ;
                            }
                            
                        }
                        
                    }
                
                }
            //Pivot mois unité de peche

            //Pivot mois  espece
                if ($pivot == "mois_and_id_espece") 
                {
                    $indice = 0 ;
                    $total_prix = 0 ;
                    $total_capture = 0 ;
                    for ($mois=1; $mois <=12 ; $mois++) 
                    {
                        if ($all_espece) 
                        {
                            foreach ($all_espece as $key => $value) 
                            {
                                $donnees=$this->Fiche_echantillonnage_captureManager->som_capture_totales($this->generer_requete_analyse($annee,$mois, $id_region , $id_district, $id_site_embarquement, $id_unite_peche, $value->id_espece));

                                if ($donnees[0]->capture != null )
                                {
                                    $total_prix = $total_prix + $donnees[0]->prix ;
                                    $total_capture = $total_capture + $donnees[0]->capture ;
                                    $donnees[0]->espece_nom_local = $value->nom_local ;
                                    $donnees[0]->espece_nom_scientifique = $value->nom_scientifique ;
                                    $donnees[0]->espece_code = $value->code ;
                                    $donnees[0]->region = "-" ;
                                    $donnees[0]->mois = $this->affichage_mois($mois) ;
                                    $donnees[0]->site_embarquement = "-" ;
                                    $donnees[0]->unite_peche = "-" ;
                                    $data[$indice] = $donnees[0] ;
                                    $indice++ ;
                                }
                                
                            }
                        }
                        
                    }
                
                }
            //Pivot mois espece
            //Pivot mois  unité de peche region
                if ($pivot == "mois_and_id_unite_peche_and_id_region") 
                {
                    $indice = 0 ;
                    $total_prix = 0 ;
                    $total_capture = 0 ;
                    for ($mois=1; $mois <=12 ; $mois++) 
                    {
                        foreach ($all_region as $key_region => $value_region) 
                        {
                            foreach ($all_unite_peche as $key => $value) 
                            {
                                $donnees=$this->Fiche_echantillonnage_captureManager->som_capture_totales($this->generer_requete_analyse($annee,$mois, $value_region->id , $id_district, $id_site_embarquement, $value->id, $id_espece));

                                if ($donnees[0]->capture != null )
                                {
                                    $total_prix = $total_prix + $donnees[0]->prix ;
                                    $total_capture = $total_capture + $donnees[0]->capture ;
                                    $donnees[0]->region = $value_region->nom ;
                                    $donnees[0]->mois = $this->affichage_mois($mois) ;
                                    $donnees[0]->site_embarquement = "-" ;
                                    $donnees[0]->unite_peche = $value->libelle ;
                                    $data[$indice] = $donnees[0] ;
                                    $indice++ ;
                                }
                                
                            }
                        }
                        
                    }
                
                }
            //Pivot mois unité de peche region
            //Pivot mois  unité de peche site
                if ($pivot == "mois_and_id_unite_peche_and_id_site_embarquement") 
                {
                    $indice = 0 ;
                    $total_prix = 0 ;
                    $total_capture = 0 ;
                    for ($mois=1; $mois <=12 ; $mois++) 
                    {
                        if ($all_site_embarquement) 
                        {
                            foreach ($all_site_embarquement as $key_site => $value_site) 
                            {
                                foreach ($all_unite_peche as $key => $value) 
                                {
                                    $donnees=$this->Fiche_echantillonnage_captureManager->som_capture_totales($this->generer_requete_analyse($annee,$mois, $id_region , $id_district, $value_site->id_site_embarquement, $value->id, $id_espece));

                                    if ($donnees[0]->capture != null )
                                    {
                                        $total_prix = $total_prix + $donnees[0]->prix ;
                                        $total_capture = $total_capture + $donnees[0]->capture ;
                                        $donnees[0]->region = "-" ;
                                        $donnees[0]->mois = $this->affichage_mois($mois) ;
                                        $donnees[0]->site_embarquement = $value_site->libelle ;
                                        $donnees[0]->unite_peche = $value->libelle ;
                                        $data[$indice] = $donnees[0] ;
                                        $indice++ ;
                                    }
                                    
                                }
                            }
                        }
                        
                    }
                
                }
            //Pivot mois unité de peche site
            //Pivot unite peche 
                if ($pivot == "id_unite_peche") 
                {
                    $indice = 0 ;
                    $total_prix = 0 ;
                    $total_capture = 0 ;
                    foreach ($all_unite_peche as $key => $value) 
                    {
                        $donnees=$this->Fiche_echantillonnage_captureManager->som_capture_totales($this->generer_requete_analyse($annee,$mois, $id_region , $id_district, $id_site_embarquement, $value->id, $id_espece));

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
                            $donnees=$this->Fiche_echantillonnage_captureManager->som_capture_totales($this->generer_requete_analyse($annee,$mois, $id_region , $id_district, $value->id_site_embarquement, $id_unite_peche, $id_espece));

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
                            $donnees=$this->Fiche_echantillonnage_captureManager->som_capture_totales($this->generer_requete_analyse($annee,$mois, $value_region->id , $id_district, $id_site_embarquement, $value->id, $id_espece));

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
                    if ($all_site_embarquement) 
                    {
                        foreach ($all_site_embarquement as $key_site => $value_site) 
                        {
                            

                            foreach ($all_unite_peche as $key => $value) 
                            {
                                $donnees=$this->Fiche_echantillonnage_captureManager->som_capture_totales($this->generer_requete_analyse($annee,$mois, $id_region , $id_district, $value_site->id_site_embarquement, $value->id, $id_espece));

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
                    }
                   /* $data['total_prix'] = $total_prix ;
                    $data['total_capture'] = $total_capture ;*/
                }
            //Pivot site and unite peche
            //Pivot espece 
                if ($pivot == "id_espece") 
                {
                    $indice = 0 ;
                    $total_prix = 0 ;
                    $total_capture = 0 ;
                    if ($all_espece) {
                        foreach ($all_espece as $key => $value) 
                        {
                            $donnees=$this->Fiche_echantillonnage_captureManager->som_capture_totales($this->generer_requete_analyse($annee,$mois, $id_region , $id_district, $id_site_embarquement, $id_unite_peche, $value->id_espece));

                            if ($donnees[0]->capture != null )
                            {
                                $total_prix = $total_prix + $donnees[0]->prix ;
                                $total_capture = $total_capture + $donnees[0]->capture ;
                                $donnees[0]->espece_nom_local = $value->nom_local ;
                                $donnees[0]->espece_nom_scientifique = $value->nom_scientifique ;
                                $donnees[0]->espece_code = $value->code ;
                                $donnees[0]->region = "-" ;
                                $donnees[0]->site_embarquement = "-" ;
                                $donnees[0]->unite_peche = "-" ;
                                $data[$indice] = $donnees[0] ;
                                $indice++ ;
                            }
                            
                        }
                    }
                    

                    /*$data['total_prix'] = $total_prix ;
                    $data['total_capture'] = $total_capture ;*/
                }
            //Pivot espece 


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

    public function generer_requete_analyse($annee,$mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece)
    {
        

        if ($mois == "*") 
        {
            $requete = "date BETWEEN '".$annee."-01-01' AND '".$annee."-12-31' " ;
        }
        else
        {
            $requete = "date BETWEEN '".$annee."-".$mois."-01' AND '".$annee."-".$mois."-31' " ;
        }
            

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

            if (($id_espece!='*')&&($id_espece!='undefined')) 
            {
                $requete = $requete." AND id_espece='".$id_espece."'" ;
            }
            
        return $requete ;
    }

    public function affichage_mois($mois_int)
    {
        switch ($mois_int) {
            case '1':
                return "Janvier";
                break;
            case '2':
                return "Février";
                break;
            case '3':
                return "Mars";
                break;
            case '4':
                return "Avril";
                break;
            case '5':
                return "Mai";
                break;
            case '6':
                return "Juin";
                break;
            case '7':
                return "Juillet";
                break;
            case '8':
                return "Août";
                break;
            case '9':
                return "Septembre";
                break;
            case '10':
                return "Octobre";
                break;
            case '11':
                return "Novembre";
                break;
            case '12':
                return "Décembre";
                break;
            
            default:
                return "";
                break;
        }
    }
    

}
/* End of file controllername.php */
/* Location: ./application/controllers/controllername.php */
?>