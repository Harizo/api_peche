<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//harizo
// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Requetes extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('site_embarquement_model', 'Site_embarquementManager');
        $this->load->model('region_model', 'RegionManager');
        $this->load->model('fiche_echantillonnage_capture_model', 'Fiche_echantillonnage_captureManager');
        $this->load->model('unite_peche_model', 'Unite_pecheManager');
        $this->load->model('enquete_cadre_model', 'Enquete_cadreManager');  
        $this->load->model('distribution_fractile_model', 'Distribution_fractileManager');  
    }
   
    public function index_get() 
    {
        $pivot = $this->get('menu');
        $annee = $this->get('annee');
        $mois = $this->get('mois');
        $date = $this->get('date');
        $date_fin = $this->get('date_fin');
        $id_espece = $this->get('id_espece');
        $id_region = $this->get('id_region');
        $id_district = $this->get('id_district');
        $id_site_embarquement = $this->get('id_site_embarquement');
        $id_unite_peche = $this->get('id_unite_peche');



        

        //******************************** Debut ***************************
             //initialisation
                //region
                  $region = $this->RegionManager->findById($id_region);
                //fin region
                //site
                  $sites_par_region = $this->Site_embarquementManager->findAllByRegion($region->id, $annee);
                //fin site
                  

                  if(($id_unite_peche!='*')&&($id_unite_peche!='undefined'))
                  {
                      $all_unite_peche = $this->Unite_pecheManager->findByIdtab($id_unite_peche);
                  }
                  else
                  {
                      $all_unite_peche=$this->Unite_pecheManager->findAll();
                  }
                
            //initialisation
            //RQ1
                  if ($pivot == 'req_1') 
                  {
                    $data = $this->get_cpue_journaliere($sites_par_region,$all_unite_peche,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece) ;
                  }
                  
                  
            //FIN RQ1
                  if (($pivot == 'req_2') || $pivot == 'req_3') 
                  {
                    $data = $this->get_cpue_moy_par_strate_mineur($sites_par_region,$all_unite_peche,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece) ;
                  }
            //RQ2
            //fin RQ2
      
        //*********************************  Fin *****************************
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

    public function generer_requete($pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece)
    {

        
        

        if (($mois!='*')&&($mois!='undefined')) 
        {
          $moi = mktime( 0, 0, 0, $mois, 1, $annee ); 
          $nbr_jour = intval(date("t",$moi));
          
          $requete = "date BETWEEN '".$annee."-".$mois."-01' AND '".$annee."-".$mois."-".$nbr_jour."'" ;
        }
        else
        {
          $requete = "date BETWEEN '".$annee."-01-01' AND '".$annee."-12-31' " ;
        }
            

            if (($id_region!='*')&&($id_region!='undefined')) 
            {
                $requete = $requete." AND fiche_echantillonnage_capture.id_region=".$id_region ;
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

    public function get_cpue_journaliere($site_embarquements, $unite_peches,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece)
    {
       $data = array();
       $indice = 0 ;
      
         
            $donnees=$this->Fiche_echantillonnage_captureManager->som_capture_totales_journaliere($this->generer_requete($pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece));

            if ($donnees != null) 
            {
               
              $data = $donnees ;
              /*$data[$indice]['site'] = $value_site->libelle ;
            //  $data[$indice]['unite_peche'] = $value->libelle ;
              $data[$indice]['somme_capture'] = $donnees[0]->capture ;
              $data[$indice]['cpue'] =  number_format($donnees[0]->capture, 2,",",".");*/
              $indice++ ;
            }
          
           
       


       return $data ;



    }


    public function get_cpue_moy_par_strate_mineur($site_embarquements, $unite_peches,$pivot, $date, $annee, $mois_angularjs, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece)
    {
      $data = array();
      $indice = 0 ;
      for ($mois=1; $mois <= 12; $mois++) 
      { 
        $donnees=$this->Fiche_echantillonnage_captureManager->som_capture_totales_average($this->generer_requete($pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece));
        if ($donnees != null) 
            {

              foreach ($donnees as $key => $value) 
              {
                $data[$indice]['mois'] = $this->affichage_mois($mois) ;
                $data[$indice]['libelle_unite_peche'] = $value->libelle_unite_peche ;
                $data[$indice]['id_unite_peche'] = $value->id_unite_peche ;
                $data[$indice]['id_region'] = $value->id_region ;
                $data[$indice]['nombre'] = $value->nombre ;
                $data[$indice]['somme'] = $value->somme ;
                $degree = ($value->nombre - 1) ;
                $data[$indice]['degree'] = (String)$degree;
                
                $data[$indice]['moyenne'] = $value->moyenne ;
                $sqrt = sqrt ($value->nombre) ;
                $data[$indice]['sqrt'] = number_format($sqrt, 2,",",".") ;
               // $captures = $this->Fiche_echantillonnage_captureManager->findAll_unite_peche_date($this->generer_requete($pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece),$value->id_region,$value->id_unite_peche); 
                $ecart_type_obj = $this->Fiche_echantillonnage_captureManager->ecart_type($this->generer_requete($pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece),$value->id_region,$value->id_unite_peche); 
                //$data[$indice]['captures'] = $captures ;
                $ecart_type = $ecart_type_obj[0]->ecart_type ;
                $data[$indice]['ecart_type'] = number_format($ecart_type, 2,",",".") ; 

                //get t-distribution , CLCPUE , ERROR RELATIVE
                  $distribution = $this->Distribution_fractileManager->findByDegree($degree);
                  $tdistriburion90 = $distribution[0]->PercentFractile90 ;
                  $data[$indice]['distribution_90'] = $tdistriburion90 ;

                  $clcpue = ($tdistriburion90 * $ecart_type) / $sqrt ;
                  $data[$indice]['clcpue'] = number_format($clcpue, 2,",",".") ; 

                  $erreur_relative = ($clcpue / $value->moyenne ) * 100;
                  $data[$indice]['erreur_relative'] = number_format($erreur_relative, 0,",",".");

                  $max_cpue = $clcpue + $value->moyenne ;
                  $data[$indice]['max_cpue'] = number_format($max_cpue, 2,",",".");

                //fin get t-distribution , CLCPUE , ERROR RELATIVE
                 
               
                $indice++ ;
              }
               
              //$data[$mois] = $donnees ;
              /*$data[$indice]['site'] = $value_site->libelle ;
            //  $data[$indice]['unite_peche'] = $value->libelle ;
              $data[$indice]['somme_capture'] = $donnees[0]->capture ;
              $data[$indice]['cpue'] =  number_format($donnees[0]->capture, 2,",",".");*/
              //$indice++ ;
            }
      }

      return $data ;
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