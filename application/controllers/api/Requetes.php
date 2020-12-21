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
        $this->load->model('distribution_fractile_model', 'Distribution_fractileManager');  
        $this->load->model('unite_peche_site_model', 'Unite_peche_site_Manager');
        $this->load->model('enquete_cadre_model', 'Enquete_cadreManager');
        $this->load->model('Espece_model', 'EspeceManager'); 
        
    }
   
    public function index_get() 
    {
        set_time_limit(0);
        ini_set ('memory_limit', '4096M');
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
        $menu_excel = $this->get('menu_excel');
        $repertoire = $this->get('repertoire');

        

        //******************************** Debut ***************************
             //initialisation
                //region
                  $region = $this->RegionManager->findById($id_region);
                //fin region
                //site
                  if ($region) 
                  {
                    $sites_par_region = $this->Site_embarquementManager->findAllByRegion($region->id, $annee);
                    if (!$sites_par_region) 
                    {
                      $sites_par_region = array();
                    }
                  }
                  else
                  {
                    $sites_par_region = array();
                  }
                  
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

            //initialisation Requete_8

                  if(($id_unite_peche!='*')&&($id_unite_peche!='undefined'))
                  {
                      $all_unite_pecheReq_8 = $this->Unite_pecheManager->findByIdtable($id_unite_peche,$annee);
                  }
                  else
                  {
                      $all_unite_pecheReq_8=$this->Unite_pecheManager->findAllInTable($annee);
                  }

                  $regionReq_8 = $this->RegionManager->findByIdtable($id_region,$annee);

                  if(($id_site_embarquement!='*')&&($id_site_embarquement!='undefined'))
                  {
                      $all_site_embarquementReq_8 = $this->Site_embarquementManager->findByIdtable($id_site_embarquement,$annee);
                  }
                  else
                  {
                      $all_site_embarquementReq_8=$this->Site_embarquementManager->findAllInTable($annee);
                  }


                  if(($id_espece!='*')&&($id_espece!='undefined'))
                  {
                      $all_especeReq_8 = $this->EspeceManager->findByIdtable($id_espece,$annee);
                  }
                  else
                  {
                      $all_especeReq_8=$this->EspeceManager->findAllInTable($annee);
                  }

            //initialisation Requete_8
            //RQ1
                  if ($pivot == 'req_1_site_date_unite_capturet_cpues') 
                  {
                    $data = $this->get_cpue_journaliere($sites_par_region,$all_unite_peche,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece) ;
                  }
                  
                  
            //FIN RQ1
                  if (($pivot == 'req_2_moi_unite_cpuemoy_stdevcpue_nbrechantillon_sqr_degre') || $pivot == 'req_3_moi_unite_cpuemoy_stdevcpue_nbrechantillon_sqr_fractil90_clcpue_erelative_maxcpue') 
                  {
                    $data = $this->get_cpue_moy_par_strate_mineur($sites_par_region,$all_unite_peche,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece) ;
                  }
            //RQ2
            //fin RQ2

            //RQ4
                  if (($pivot == 'req_4_1_annee_site_unite_nbrunite') || ($pivot == 'req_4_2_annee_unite_nbrunite'))
                  {
                    $data = $this->nbr_unite_peche($sites_par_region,$all_unite_peche,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece) ;
                  }
            //Fin RQ4

            //RQ5
                  if (($pivot == 'req_5_1_codeteteuniq_site_unite_date_phier_pavanthier_nbrjrssemdern_pabs') )
                  {
                    $data = $this->requete_5_pab($sites_par_region,$all_unite_peche,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece) ;
                  }
            //Fin RQ5
            //RQ5.2
                  if (($pivot == 'req_5_2_unite_pabmoy_stdevpab_nbrechantilonpab_nbrjrpeche_sqr_degre') )
                  {
                    $data = $this->requete_5_2($sites_par_region,$all_unite_peche,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece) ;
                  }
            //Fin RQ5.2

            //RQ5.2
                  if (($pivot == 'req_5_3_unite_pabmoy_maxpab_stdevpab_sqr_fractil90_erelative_clpab') || ($pivot == 'req_5_4_moi_jrmensuel_unite_pabmoy_jrpechemenspab_moymaxxpab_moynmaxxpabcor_maxjrpechemensupab') )
                  {
                    $data = $this->requete_5_3_et_5_4($sites_par_region,$all_unite_peche,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece) ;
                  }
            //Fin RQ5.2

            //RQ5.2
                  if (($pivot == 'req_6_1_annee_unite_totalannuel')  )
                  {
                    $data = $this->requete_6_1_acces6_2($sites_par_region,$all_unite_peche,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece) ;
                  }
            //Fin RQ5.2
            //RQ6.2
                  if (($pivot == 'req_6_2_moi_jrmensuel_site_unite_nbrunite_jrpechemenspab_jrtotalmensuel_cpuemoy_capturtotal_erelative_nbrechantillon_moymaxxpab_maxcpue_maxcapttot_clcapt_erelcapttot')  )
                  {
                    if ($regionReq_8!=null)
                    {
                      $data = $this->requete_6_2($sites_par_region,$all_unite_peche,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece) ;
                    }else
                    {
                      $data=array();
                    }
                   
                  }
            //Fin RQ6.2

            //RQ7.1
                  if (($pivot == 'req_7_1_moi_unite_code3Al_caapture_prixunitmoy')  )
                  {
                    $data = $this->requete_7_1($sites_par_region,$all_unite_peche,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece) ;
                  }
            //Fin RQ7.1

            //RQ7.2
                  if (($pivot == 'req_7_2_moi_unite_caapture')  )
                  {
                    $data = $this->requete_7_2($sites_par_region,$all_unite_peche,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece) ;
                  }
            //Fin RQ7.2

            //RQ7.3
                  /*if (($pivot == 'req_7_3')  )
                  {
                    $data = $this->requete_7_3($sites_par_region,$all_unite_peche,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece) ;
                  }*/
            //Fin RQ7.

                  //RQ7.3
                  if (($pivot == 'req_7_3_moi_unite_code3Al_captureespece_captureunite_composi_prixmoy')  )
                  {
                    $data = $this->requete_7_3_new($sites_par_region,$all_unite_peche,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece) ;
                  }
            //Fin RQ7.3

            //RQ8
                  if (($pivot == 'req_8_moi_site_unite_jrpechemenspab_jrtotalmensuel_code3Al_erelative_erelcapttot_nbrechantillon_capturtotal_composi_captureespece_prixmoy_pprix')  )
                  {
                    if ($regionReq_8!=null)
                    {
                      $data = $this->requete_8($all_site_embarquementReq_8,$all_unite_pecheReq_8,$all_especeReq_8,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece);
                    }else
                    {
                      $data=array();
                    }
                    
                  }
            //Fin RQ8

            //RQ9
                  if (($pivot == 'req_9_annee_site_unite_nbrunite')  )
                  {
                    $data = $this->requete_9($sites_par_region,$all_unite_peche,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece) ;
                  }
            //Fin RQ9
            //RQ10
                  if (($pivot == 'req_10_annee_region_unite_nbrunite')  )
                  {
                    $data = $this->requete_10($sites_par_region,$all_unite_peche,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece) ;
                  }
            //Fin RQ10
        //*********************************  Fin *****************************
        if (count($data)>0) {
          if ($menu_excel=="excel_requetes")
            {
                $export=$this->export_excel($repertoire,$data,$pivot,$id_region,$id_district,$id_site_embarquement,$id_unite_peche,$id_espece,$annee,$mois);
            }else {
                $this->response([
                'status' => TRUE,
                'response' => $data,
                'message' => 'Get data success',
            ], REST_Controller::HTTP_OK);
           
            }
            
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

        
        

        if (($pivot == 'req_4_1_annee_site_unite_nbrunite') || ($pivot == 'req_4_2_annee_unite_nbrunite')|| ($pivot == 'req_9_annee_site_unite_nbrunite')|| ($pivot == 'req_10_annee_region_unite_nbrunite')) 
        {
          $requete = "annee = '".$annee."' " ;
        }
        else
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
        }
            

            if (($id_region!='*')&&($id_region!='undefined')) 
            {
              if (($pivot == 'req_4_1_annee_site_unite_nbrunite')||($pivot == 'req_4_2_annee_unite_nbrunite')|| ($pivot == 'req_9_annee_site_unite_nbrunite')|| ($pivot == 'req_10_annee_region_unite_nbrunite')) 
              {
                $requete = $requete." AND enquete_cadre.id_region=".$id_region ;
              }
              else
              {

                $requete = $requete." AND fiche_echantillonnage_capture.id_region=".$id_region ;
              }
            }

           

            if (($id_site_embarquement!='*')&&($id_site_embarquement!='undefined')) 
            {
                $requete = $requete." AND id_site_embarquement='".$id_site_embarquement."'" ;
            }

            if (($id_unite_peche!='*')&&($id_unite_peche!='undefined')) 
            {
                $requete = $requete." AND id_unite_peche='".$id_unite_peche."'" ;
            }
            if (($pivot != 'req_4_1_annee_site_unite_nbrunite')&&($pivot != 'req_5_1_codeteteuniq_site_unite_date_phier_pavanthier_nbrjrssemdern_pabs')&& ($pivot != 'req_9_annee_site_unite_nbrunite')&& ($pivot != 'req_10_annee_region_unite_nbrunite'))
            {
              if (($id_espece!='*')&&($id_espece!='undefined')) 
              {
                  $requete = $requete." AND id_espece='".$id_espece."'" ;
              }
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
                $data[$indice]['id_fiche'] = $value->id_fiche ;

                $nombre=$this->Fiche_echantillonnage_captureManager->nbr_echantillon_par_unite_peche_fiche($value->id_fiche, $value->id_unite_peche);


                $data[$indice]['nombre'] = $value->nombre ;
               
                $data[$indice]['somme'] = $value->somme ;
                $degree = ($value->nombre - 1) ;
                $data[$indice]['degree'] = (String)$degree;
                
                $data[$indice]['moyenne'] = $value->moyenne ;
             
                $sqrt = sqrt ($value->nombre) ;
                $data[$indice]['sqrt'] = number_format($sqrt, 2,",",".") ;
              
                $ecart_type = $this->Fiche_echantillonnage_captureManager->ecartypeAnalyse($this->generer_requete($pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece),$value->id_fiche,$value->id_region,$value->id_unite_peche); 
                
                //$ecart_type = $ecart_type_obj[0]->ecart_type ;
                $data[$indice]['ecart_type'] = number_format($ecart_type, 2,",",".") ; 

                //get t-distribution , CLCPUE , ERROR RELATIVE
                  $distribution = $this->Distribution_fractileManager->findByDegree($degree);
                  $tdistriburion90 = $distribution[0]->PercentFractile90 ;
                  $data[$indice]['distribution_90'] = $tdistriburion90 ;

                  $clcpue = ($tdistriburion90 * $ecart_type) / $sqrt ;
                  $data[$indice]['clcpue'] = number_format($clcpue, 2,",",".") ; 

                  $erreur_relative = ($clcpue / $value->moyenne ) * 100;
                  $data[$indice]['erreur_relative_90'] = number_format($erreur_relative, 0,",",".");

                  $max_cpue = $clcpue + $value->moyenne ;
                  $data[$indice]['max_cpue'] = number_format($max_cpue, 2,",",".");

                //fin get t-distribution , CLCPUE , ERROR RELATIVE
                 
               
                $indice++ ;
              }
               
           
            }
      }

      return $data ;
    }

    public function nbr_unite_peche($site_embarquements, $unite_peches,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece)
    {
      //$donnees = $this->Unite_peche_site_Manager->findAllByRequetes();
      

      if ($pivot == "req_4_1_annee_site_unite_nbrunite") 
      {
        $enquete_cadre = $this->Enquete_cadreManager->findAllByRequetes_region_site_unite_peche($this->generer_requete($pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece));
      }
      else
      {
        $enquete_cadre = $this->Enquete_cadreManager->findAllByRequetes_region($this->generer_requete($pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece));
      }
      return $enquete_cadre ;
    }


    public function requete_5_pab($site_embarquements, $unite_peches,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece)
    {
      $pab = $this->Fiche_echantillonnage_captureManager->get_pab_region_site_unite_peche($this->generer_requete($pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece));

      return $pab ;
    }

    public function requete_5_2($site_embarquements, $unite_peches,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece)
    {
      $pab = $this->Fiche_echantillonnage_captureManager->get_pab_moy_ecart_nbr_jrs_par_unite_peche($this->generer_requete($pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece));

      return $pab ;
    }

    public function requete_5_3_et_5_4($site_embarquements, $unite_peches,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece)
    {
      $pab = $this->Fiche_echantillonnage_captureManager->erreur_relative_pab_moy_par_unite_peche($this->generer_requete($pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece));

      

      if ($pab) 
      {
        foreach ($pab as $key => $value) 
        {
          //$data[$key]['nombre_echantillon'] = $value->nombre_echantillon ;
          $data[$key]['libelle_unite_peche'] = $value->libelle_unite_peche ;
          //$data[$key]['somme_pab'] = $value->somme_pab ;
          $data[$key]['ecart_type'] = $value->ecart_type ;
          $data[$key]['pab_moy'] = $value->pab_moy ;
          //$data[$key]['nbr_jrs_peche_mois'] = $value->nbr_jrs_peche_mois ;
          $data[$key]['sqrt'] = $value->sqrt ;



          //$data[$key]['degree'] = $value->degree ;


          $distribution = $this->Distribution_fractileManager->findByDegree($value->degree);
          $tdistriburion90 = $distribution[0]->PercentFractile90 ;
          $data[$key]['distribution_90'] = $tdistriburion90 ;

          //erreur relative
          $erreur_relative_90 = ($value->ecart_type * $tdistriburion90) / $value->sqrt ;
          $data[$key]['erreur_relative_90'] = (string)$erreur_relative_90 ;

          $clpab = $erreur_relative_90 * $value->pab_moy ;
          $data[$key]['clpab'] = (string)$clpab ;

          $max_pab = ($clpab + $value->pab_moy) ;
          $data[$key]['max_pab'] = (string)($max_pab) ;


          //erreur relative
          $date = $value->date ;
          $tab_date = explode('-', $date) ;
         
          //calcule jour de peche 5-4
            $res = mktime( 0, 0, 0, $tab_date[1], 1, $tab_date[0] ); 
            $nbr_jour = intval(date("t",$res));
     
            $data[$key]['mois'] = $this->affichage_mois($tab_date[1]) ;
            $data[$key]['nbr_jour_mois'] = $nbr_jour ;


            $data[$key]['nbr_jrs_peche_mensuel_pab'] = $value->pab_moy * $nbr_jour ;

            if ($max_pab > 1 ) 
            {
              $moy_pax_pab_correcte = 1 ;
            }
            else
            {
              $moy_pax_pab_correcte = $max_pab ;
            }

            $data[$key]['moy_pax_pab_correcte'] = $moy_pax_pab_correcte ;

            $data[$key]['max_nbr_jrs_peche_mensuel_pab'] = $moy_pax_pab_correcte * $nbr_jour ;

          //fin calcule jour de peche 5-4

        }
      }
      else
      {
        $data = array();
      }

      return $data ;
    }


    public function requete_6_1_acces6_2($site_embarquements, $unite_peches,$pivot, $date, $annee, $mois_angular, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece)
    {
      
     
        $indice = 0 ;
        

          foreach ($unite_peches as $key => $value) 
          {
            $somme = 0 ;
            for ($mois=1; $mois <=12 ; $mois++) 
            {
            
              $pab_moy_mois = $this->Fiche_echantillonnage_captureManager->nbr_jrs_peche_mensuel_pab($mois, $annee,  $value->id);

              if ($pab_moy_mois) 
              {
                $res = mktime( 0, 0, 0, $mois, 1, $annee ); 
                $nbr_jour = intval(date("t",$res)) ;
                $somme = $somme + round($pab_moy_mois[0]->pab_moy * $nbr_jour);
              
              }

              

            
            }

            $data[$indice]['annee'] = $annee ;
            $data[$indice]['somme'] = $somme ;
            $data[$indice]['libelle_unite_peche'] = $value->libelle  ;
              $indice++ ;

           
                  
          }

        return $data ;
      
    }

    public function requete_7_1($site_embarquements, $unite_peches,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece)
    {
      $pab = $this->Fiche_echantillonnage_captureManager->req_7_1($this->generer_requete($pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece));

      return $pab ;
    }

    public function requete_7_2($site_embarquements, $unite_peches,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece)
    {
      $pab = $this->Fiche_echantillonnage_captureManager->req_7_2($this->generer_requete($pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece));

      return $pab ;
    }

    public function requete_7_3($site_embarquements, $unite_peches,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece)
    {
      $pab = $this->Fiche_echantillonnage_captureManager->req_7_1($this->generer_requete($pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece));

      if ($pab) 
      {
        foreach ($pab as $key => $value) 
        {
          $data[$key]['capture_par_espece'] = $value->capture_par_espece ;
          $data[$key]['libelle_unite_peche'] = $value->libelle_unite_peche ;
          $data[$key]['id_unite_peche'] = $value->id_unite_peche ;
          $data[$key]['code'] = $value->code ;
          $data[$key]['nombre'] = $value->nbr ;
          $data[$key]['prix_unitaire_total'] = $value->prix_unitaire_total ;
          $data[$key]['prix_unitaire_moyenne'] = $value->prix_unitaire_moyenne ;
          $data[$key]['mois'] = $value->mois ;
          $data[$key]['date'] = $value->date ;
          $data[$key]['annee'] = $value->annee ;
          $data[$key]['id_region'] = $value->id_region ;
          $obj= $this->Fiche_echantillonnage_captureManager->pour_7_3($value->mois, $value->annee, $value->id_unite_peche, $value->id_region) ;
          $capture_total = $obj[0]->capture_total ;
          $data[$key]['capture_total'] = $capture_total ;
          $data[$key]['composition_espece'] = ($value->capture_par_espece / $capture_total) * 100 ;
        }

       
      }
      else
      {
        $data = array();
      }

      return $data ;
    }

    public function requete_7_3_new($site_embarquements, $unite_peches,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece)
    {
      $pab = $this->Fiche_echantillonnage_captureManager->req_7_3_new($this->generer_requete($pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece));

      if ($pab) 
      {
        
        $data = $pab ;
      }
      else
      {
        $data = array();
      }

      return $data ;
    }
     public function requete_8($all_site_embarquementReq_8,$all_unite_pecheReq_8,$all_especeReq_8,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece)
    {
      $data=array();  
      $indice = 0 ;
      $total_prix = 0 ;
      $total_capture = 0 ;
      $erreur_rel_capture=0;
      $erreur_relative=0;
      for ($moi=1; $moi <=12 ; $moi++)
      {   
          if ($all_site_embarquementReq_8!=null && $all_unite_pecheReq_8!=null && $all_especeReq_8!=null)
          {
            foreach ($all_site_embarquementReq_8 as $kSite => $vSite)
            {
              foreach ($all_unite_pecheReq_8 as $kUnite_peche => $vUnite_peche)
              {
                foreach ($all_especeReq_8 as $kEspece => $vEspece)
                {
                  $result =   $this->Fiche_echantillonnage_captureManager->essai(
                              $this->generer_requete_analyse($annee,$moi, $id_region,$id_district, $id_site_embarquement,$id_unite_peche, $id_espece),
                              $this->generer_requete_analyse($annee,$moi,$id_region,'*','*', $vUnite_peche->id, '*'),$this->generer_requete_analyse_cadre($annee,$moi,$id_region,'*',$vSite->id, $vUnite_peche->id,$vEspece->id),$annee);
                                 
                  $cpue = $this->Fiche_echantillonnage_captureManager->cpue($this->generer_requete($pivot, $date,$annee,$mois,$id_region,'*','*', $id_unite_peche, '*'));
      
                  if ($result != null )
                  { 
                      $cpuecol= array_column($cpue, 'cp');
                      $cpuemoyenne = array_sum($cpuecol)/count($cpuecol);                       
                    $i=1;
                    $donnees=array();
                    foreach ($result as $key => $value)
                    {
                      $tab_capture_par_espece =   $this->Fiche_echantillonnage_captureManager->capture_total_par_espece($this->generer_requete_analyse($annee,$value->mois, $value->id_reg, '*', '*',$value->id_unite, $vEspece->id));

                      $ecart_type = $this->Fiche_echantillonnage_captureManager->ecartypeAnalyse(
                                    $this->generer_requete_analyse($annee,$value->mois, $value->id_reg, '*','*',$value->id_unite, '*'));

                      $date_t     = explode('-', $value->date) ;
                      $res        = mktime( 0, 0, 0, $date_t[1], 1, $date_t[0] );
                      $nbr_jour   = intval(date("t",$res));

                      $nbr_jrs_peche_mensuel_pab = $value->pab_moy * $nbr_jour;
                                          
                      $captures_t = ($value->nbr_unit_peche * $nbr_jrs_peche_mensuel_pab * $cpuemoyenne);                            
                                          
                      $distribution    = $this->Distribution_fractileManager->findByDegree($value->degree);
                      $tdistriburion90 = $distribution[0]->PercentFractile90 ;
                      $clcpue          = ($tdistriburion90 * $ecart_type) / $value->sqrt ;
                      if ($cpuemoyenne )
                      {
                        $erreur_relative = ($clcpue / $cpuemoyenne ) * 100;
                      }
                      
                      $distributionpab = $this->Distribution_fractileManager->findByDegree($value->degreepab);
                      $clpab           = ($distributionpab[0]->PercentFractile90 * $value->ecart_typepab) / $value->sqrtpab ;
                      $max_pab          = ($clpab + $value->pab_moy) ;
                                          
                      if ($max_pab > 1 ) 
                      {$moy_pax_pab = 1 ;}
                      else{$moy_pax_pab = $max_pab ;}
                                          
                      $max_cpue = $clcpue + $cpuemoyenne;

                      $nbr_total_jrs_peche_mensuel = $value->nbr_unit_peche * $nbr_jrs_peche_mensuel_pab;
                      $max_captures_totales = ($value->nbr_unit_peche * $moy_pax_pab * $nbr_jour * $max_cpue)/1000;

                      $cl_captures_totales = $max_captures_totales - ($captures_t/1000);
                                       
                      $erreur_relative_capture_total_90=1;
                      if ($captures_t)
                      {
                        $erreur_relative_capture_total_90 = ($cl_captures_totales / ($captures_t/1000)) * 100;
                      } 

                      $j=0;
                      $tab_capture_espece_total = array();
                      $prix_espece              = array();
                      $tab_composition          = array();
                      $tab_capture_espece       = array();          
                      if ($tab_capture_par_espece) 
                      {
                        foreach ($tab_capture_par_espece as $val)
                        {
                          $tab_capture_espece_total[$j]=($val->capture_total_par_espece/$value->capture_total_par_unite)*($captures_t);
                          $prix_espece[$j]=(($val->capture_total_par_espece/$value->capture_total_par_unite)*($captures_t))*$val->prix;
                          $tab_composition[$j]=($val->capture_total_par_espece/$value->capture_total_par_unite)*100;
                          $j++;
                        }
                      }
                                                                     
                      $som_tab_capture_par_espece =array_sum($tab_capture_espece_total);
                      $donnees[$i]['Total_capture_espece'] = $som_tab_capture_par_espece;

                      $total_prix_unite=array_sum($prix_espece);

                      $total_composition=array_sum($tab_composition);


                      $donnees[$i]['total_prix_unite'] = $total_prix_unite;
                      $donnees[$i]['erreur_relative_capture_total_90'] = $erreur_relative_capture_total_90;
                      $donnees[$i]['erreur_relative'] = $erreur_relative;
                      $donnees[$i]['unite_peche'] = $value->libelle;
                      $donnees[$i]['composition_espece'] = $total_composition;
                    
                      $donnees[$i]['esp'] = $tab_capture_par_espece;
                      $donnees[$i]['capture_t'] = $captures_t;

                      $i++;  
                    }
                    
                    $Total_captureEspece  = array_column($donnees, 'Total_capture_espece');
                    $total_captureEspece= array_sum($Total_captureEspece);

                    $Total_prixUnite_peche     = array_column($donnees, 'total_prix_unite');
                    $total_prixUnite_peche    = array_sum($Total_prixUnite_peche)/1000;
                    //$countprix=count($Total_prixUnite_peche);
                    //$pr=$total_prixUnite_peche/$countprix;

                    $composition_esp = array_column($donnees, 'composition_espece');
                    $composition_espece = array_sum($composition_esp);

                  

                    $capture_tot = array_column($donnees, 'capture_t');
                    $capture_to = array_sum($capture_tot);

                    $erreur_captureUnite_peche = array_column($donnees, 'erreur_relative_capture_total_90');
                    $n_captureUnite_peche      = count($erreur_captureUnite_peche);
                    $erreur_rel_captureUnite_peche = number_format ( array_sum($erreur_captureUnite_peche)/$n_captureUnite_peche ,0);

                    $erreurUnite_peche = array_column($donnees, 'erreur_relative');
                    $nUnite_peche      = count($erreurUnite_peche);
                    $erreur_relativeUnite_peche = number_format ( array_sum($erreurUnite_peche)/$nUnite_peche,0);
                    
                                  
                    $data[$indice]['mois'] = $moi;
                    $data[$indice]['libelle'] = $vSite->libelle;
                    $data[$indice]['libelle_unite_peche'] = $vUnite_peche->libelle;
                    $data[$indice]['code']=$vEspece->code;
                    $data[$indice]['nbr_jrs_peche_mensuel_pab']=number_format ($nbr_jrs_peche_mensuel_pab,0,',','.');
                    $data[$indice]['nbr_total_jrs_peche_mensuel']=number_format ($nbr_total_jrs_peche_mensuel,0,',','.');
                    //$data[$indice]['espece_nom_local']=$vEspece->nom_local;
                    $data[$indice]['somme_capture_par_espece'] = $total_captureEspece;
                    $data[$indice]['prix']    = $total_prixUnite_peche;
                    $data[$indice]['prix_moyenne'] = $value->prix_moyenne; 
                    $data[$indice]['erreur_relative_90']    = $erreur_relativeUnite_peche;
                    $data[$indice]['erreur_relative_capture_total_90'] = $erreur_rel_captureUnite_peche;
                    $data[$indice]['composition_espece'] = $composition_espece;
                    //$data[$indice]['capture'] = $capture_to;captures_totales_t
                    $data[$indice]['captures_totales_t'] = $capture_to/1000;
                    $data[$indice]['nombre'] = $value->nombre_echantillon;
                    $data[$indice]['Donnee'] = $donnees;
                        
                    $indice++ ;  
                  }

                }
              }
            }
          }          
      }
      return $data;
    }

    public function requete_9($site_embarquements, $unite_peches,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece)
    {
      $pab = $this->Fiche_echantillonnage_captureManager->req_9($this->generer_requete($pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece));

      if ($pab) 
      {
        
        $data = $pab ;
      }
      else
      {
        $data = array();
      }

      return $data ;
    }

    public function requete_10($site_embarquements, $unite_peches,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece)
    {
      $pab = $this->Fiche_echantillonnage_captureManager->req_10($this->generer_requete($pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece));

      if ($pab) 
      {
        
        $data = $pab ;
      }
      else
      {
        $data = array();
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


    public function requete_6_2($site_embarquements, $unite_peches,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece)
    { 
      $data = array();
      $datacpue=array();
      $i=1;
      $result =   $this->Fiche_echantillonnage_captureManager->essai(
                            $this->generer_requete($pivot,$date,$annee,$mois,$id_region,'*',$id_site_embarquement,$id_unite_peche, $id_espece),
                            $this->generer_requete($pivot, $date,$annee,$mois,$id_region,'*','*', $id_unite_peche, '*'),$this->generer_requete($pivot,$date,$annee,$mois,$id_region,'*',$id_site_embarquement,$id_unite_peche, $id_espece),$annee);
      
      $cpue = $this->Fiche_echantillonnage_captureManager->cpue($this->generer_requete($pivot, $date,$annee,$mois,$id_region,'*','*', $id_unite_peche, '*'));
      
      if ($result != null )
      { 
          $cpuecol= array_column($cpue, 'cp');
          $cpuemoyenne = array_sum($cpuecol)/count($cpuecol);
        
        for ($j=1; $j <13 ; $j++) { 
        //$donnees=array();
        foreach ($result as $key => $value)
        { 
          if ($value->mois==$j)
          {

            $ecart_type = $this->Fiche_echantillonnage_captureManager->ecartypeAnalyse(
                          $this->generer_requete($pivot,$date,$annee,$value->mois,$value->id_reg,'*','*',$value->id_unite,$id_espece));
            $date_t     = explode('-', $value->date) ;
            $res        = mktime( 0, 0, 0, $date_t[1], 1, $date_t[0] );
            $nbr_jour   = intval(date("t",$res));

            $nbr_jrs_peche_mensuel_pab = $value->pab_moy * $nbr_jour;
                              
           $captures_t = ($value->nbr_unit_peche * $nbr_jrs_peche_mensuel_pab * $cpuemoyenne/1000);                            
            $distribution    = $this->Distribution_fractileManager->findByDegree($value->degree);
            $tdistriburion90 = $distribution[0]->PercentFractile90 ;
            $clcpue          = ($tdistriburion90 * $ecart_type) / $value->sqrt ;
            if ($cpuemoyenne )
            {
               $erreur_relative = ($clcpue / $cpuemoyenne ) * 100;
            }
            
            $distributionpab = $this->Distribution_fractileManager->findByDegree($value->degreepab);
            $clpab = ($distributionpab[0]->PercentFractile90 * $value->ecart_typepab) / $value->sqrtpab ;
            $max_pab          = ($clpab + $value->pab_moy) ;
                              
            if ($max_pab > 1 ) 
            {$moy_pax_pab = 1 ;}
            else{$moy_pax_pab = $max_pab ;}
                              
            $max_cpue = $clcpue + $cpuemoyenne;

            $nbr_total_jrs_peche_mensuel = $value->nbr_unit_peche * $nbr_jrs_peche_mensuel_pab;
            $max_captures_totales = ($value->nbr_unit_peche * $moy_pax_pab * $nbr_jour * $max_cpue)/1000;

            $cl_captures_totales = $max_captures_totales - ($captures_t);
                           
            $erreur_relative_capture_total_90=1;
            if ($captures_t)
            {
              $erreur_relative_capture_total_90 = ($cl_captures_totales / ($captures_t)) * 100;
            }
            
            
             $data[$i]['mois'] = $value->mois;
             $data[$i]['region'] = $value->nom_region;
             //$data[$i]['cp'] = $value->cp;
             $data[$i]['cpp'] = $cpuecol;
             $data[$i]['libelle'] = $value->site_embarquement;
             //$data[$i]['unite_peche_libelle']= $value->libelle;
             $data[$i]['libelle_unite_peche']= $value->libelle;
             $data[$i]['nbr_unite_peche']= $value->nbr_unit_peche;
            // $data[$i]['nbr_jrs_peche_mensuel_pab']=$nbr_jrs_peche_mensuel_pab;
             $data[$i]['nbr_jrs_peche_mensuel_pab']=$nbr_jrs_peche_mensuel_pab;
             $data[$i]['nbr_total_jrs_peche_mensuel']= $nbr_total_jrs_peche_mensuel;
             $data[$i]['nbr_jour_mois']= $nbr_jour;
             $data[$i]['moyenne']= $cpuemoyenne;
             //$data[$i]['erreur_relative_90_cpue'] = number_format($erreur_relative, 0);
             $data[$i]['erreur_relative_90'] = number_format($erreur_relative, 0);
             //$data[$i]['nombre_echantillon_cpue'] = $value->nombre_echantillon;
             $data[$i]['nombre'] = $value->nombre_echantillon;
             //$data[$i]['moy_pax_pab'] = $moy_pax_pab ;
             $data[$i]['max_pab'] = $moy_pax_pab ;
             $data[$i]['max_cpue'] = $max_cpue;
             $data[$i]['stDevCPUE'] = $ecart_type;
             $data[$i]['captures_totales_t']= $captures_t;
             $data[$i]['max_captures_totales']= $max_captures_totales;
             $data[$i]['cl_captures_totales']= $cl_captures_totales;
             $data[$i]['erreur_relative_capture_total_90']= number_format($erreur_relative_capture_total_90, 0,",",".");
                     //$data[$i]['erreur_relative_capture_total_90']= $erreur_relative_capture_total_90;
                     // $data[$i]['ecart_type'] = number_format($ecart_type, 2,",",".") ;

            $i++ ;
          }
          
        }
      }
        
    }
    
    return $data;
  }
    
public function generer_requete_analyse($annee,$mois,$id_region,$id_district,$id_site_embarquement,$id_unite_peche,$id_espece)
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
                $requete = $requete." AND fiche_echantillonnage_capture.id_region='".$id_region."'" ;
            }

            if (($id_district!='*')&&($id_district!='undefined')) 
            {
                $requete = $requete." AND fiche_echantillonnage_capture.id_district='".$id_district."'" ;
            }

            if (($id_site_embarquement!='*')&&($id_site_embarquement!='undefined')) 
            {
                $requete = $requete." AND fiche_echantillonnage_capture.id_site_embarquement='".$id_site_embarquement."'" ;
            }

            if (($id_unite_peche!='*')&&($id_unite_peche!='undefined')) 
            {
                $requete = $requete." AND echantillon.id_unite_peche='".$id_unite_peche."'" ;
            }

            if (($id_espece!='*')&&($id_espece!='undefined')) 
            {
                $requete = $requete." AND espece_capture.id_espece='".$id_espece."'" ;
            }
            
        return $requete ;
    }

    public function generer_requete_analyse_cadre($annee,$mois,$id_region,$id_district,$id_site_embarquement,$id_unite_peche,$id_espece)
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
                $requete = $requete." AND enquete_cadre.id_region='".$id_region."'" ;
            }

            if (($id_district!='*')&&($id_district!='undefined')) 
            {
                $requete = $requete." AND enquete_cadre.id_district='".$id_district."'" ;
            }

            if (($id_site_embarquement!='*')&&($id_site_embarquement!='undefined')) 
            {
                $requete = $requete." AND enquete_cadre.id_site_embarquement='".$id_site_embarquement."'" ;
            }

            if (($id_unite_peche!='*')&&($id_unite_peche!='undefined')) 
            {
                $requete = $requete." AND echantillon.id_unite_peche='".$id_unite_peche."'" ;
            }

            if (($id_espece!='*')&&($id_espece!='undefined')) 
            {
                $requete = $requete." AND espece_capture.id_espece='".$id_espece."'" ;
            }
            
        return $requete ;
    }


    public function export_excel($repertoire,$data,$pivot,$id_region,$id_district,$id_site_embarquement,$id_unite_peche,$id_espece,$annee,$mois)
    {
        require_once 'Classes/PHPExcel.php';
        require_once 'Classes/PHPExcel/IOFactory.php';      

        $nom_file='requetes';
        $directoryName = dirname(__FILE__) ."/../../../../../../assets/excel/".$repertoire;
            
            //Check if the directory already exists.
        if(!is_dir($directoryName))
        {
            mkdir($directoryName, 0777,true);
        }
            
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Myexcel")
                    ->setLastModifiedBy("Me")
                    ->setTitle("requetes")
                    ->setSubject("requetes")
                    ->setDescription("requetes")
                    ->setKeywords("requetes")
                    ->setCategory("requetes");

        $ligne=1;            
            // Set Orientation, size and scaling
            // Set Orientation, size and scaling
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

        $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
        $objPHPExcel->getActiveSheet()->getPageMargins()->SetLeft(0.64); //***pour marge gauche
        $objPHPExcel->getActiveSheet()->getPageMargins()->SetRight(0.64); //***pour marge droite
        $objPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
           
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
            
        $objPHPExcel->getActiveSheet()->setTitle("requetes");

        $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&11&B Page &P / &N');
        $objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&R&11&B Page &P / &N');

        $styleTitre = array
        (
        'alignment' => array
            (
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    
            ),
        'font' => array
            (
                //'name'  => 'Times New Roman',
                'bold'  => true,
                'size'  => 14
            ),
        );
        $stylesousTitre = array
        (
            'borders' => array
            (
                'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
            ),
            
            'alignment' => array
            (
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    
            ),
            'font' => array
            (
                    //'name'  => 'Times New Roman',
                'bold'  => true,
                'size'  => 12
            ),
        );
        
        $styleEntete = array
        (
            'alignment' => array
            (
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    
            ),
                
            'font' => array
            (
                'name'  => 'Calibri',
                'bold'  => true,
                'size'  => 11
            ),
        );
            
        $stylecontenu = array
        (
            'borders' => array
            (
                'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
            ),
            'alignment' => array
            (
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            )
        );

        if ($pivot=='req_1_site_date_unite_capturet_cpues')
        {

            $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
            $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":E".$ligne);
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":E".$ligne)->applyFromArray($styleTitre);            
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, 'Req 1 : CPUE journalière / Unité de pêche');                       
            $ligne++;
            $ligne_entete= $this->insertion_entete($styleEntete,$ligne,$objPHPExcel,$id_region,$id_district,$id_site_embarquement,$id_unite_peche,$id_espece,$annee,$mois);
            if ($ligne_entete!=$ligne)
            {
                $ligne=$ligne_entete+1;
            }

            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":E".$ligne)->applyFromArray($stylesousTitre);
            $objPHPExcel-> getActiveSheet()->getStyle("A".$ligne.":E".$ligne)->getNumberFormat()->setFormatCode('00');
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":E".$ligne)->getAlignment()->setWrapText(true);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, 'Site de débarquement');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, 'Unité de pêche');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, 'Date');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'Total capture (Kg)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, 'CPUE');
            $ligne++;
            foreach ($data as $key => $value)
            {
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":E".$ligne)->applyFromArray($stylecontenu);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne,$value->libelle);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, $value->libelle_unite_peche);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, $value->date);
               
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, $value->capture);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, $value->cpue);

                $ligne++;
            }
        }


        if ($pivot=="req_2_moi_unite_cpuemoy_stdevcpue_nbrechantillon_sqr_degre")
        {
            $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
            $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":G".$ligne);
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":G".$ligne)->applyFromArray($styleTitre);            
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, 'Req 2 : CPUEmoy par strate mineure/mois/année');                       
            $ligne++;
            $ligne_entete= $this->insertion_entete($styleEntete,$ligne,$objPHPExcel,$id_region,$id_district,$id_site_embarquement,$id_unite_peche,$id_espece,$annee,$mois);
            if ($ligne_entete!=$ligne)
            {
                $ligne=$ligne_entete+1;
            }

            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":G".$ligne)->applyFromArray($stylesousTitre);
            $objPHPExcel-> getActiveSheet()->getStyle("A".$ligne.":G".$ligne)->getNumberFormat()->setFormatCode('00');
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":G".$ligne)->getAlignment()->setWrapText(true);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, ' Mois'.'        ');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, 'Unité de pêche');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, 'CPUE Moyenne');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'stDev CPUE');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, 'SQR(n)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, 'Dégrées Libertés');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ligne, 'Nombre echantillon CPUE');
            $ligne++;
            foreach ($data as $key => $value)
            {
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":G".$ligne)->applyFromArray($stylecontenu);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne,$value['mois']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, $value['libelle_unite_peche']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, number_format($value['moyenne'], 2,","," "));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, $value['ecart_type']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne,$value['sqrt']);
               
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, $value['degree']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ligne, $value['nombre']);

                $ligne++;
            }
          
        }


         if ($pivot=="req_3_moi_unite_cpuemoy_stdevcpue_nbrechantillon_sqr_fractil90_clcpue_erelative_maxcpue")
        {
            $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
            $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":J".$ligne);
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":J".$ligne)->applyFromArray($styleTitre);            
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, 'Req 3 : Erreur relative CPUEmoy par strate mineure/mois/année');                       
            $ligne++;
            $ligne_entete= $this->insertion_entete($styleEntete,$ligne,$objPHPExcel,$id_region,$id_district,$id_site_embarquement,$id_unite_peche,$id_espece,$annee,$mois);
            if ($ligne_entete!=$ligne)
            {
                $ligne=$ligne_entete+1;
            }

            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":J".$ligne)->applyFromArray($stylesousTitre);
            $objPHPExcel-> getActiveSheet()->getStyle("A".$ligne.":J".$ligne)->getNumberFormat()->setFormatCode('00');
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":J".$ligne)->getAlignment()->setWrapText(true);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, 'Mois');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, 'Unite de pêche');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, 'CPUE Moyenne');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'stDev CPUE');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, 'SQR(n)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, '90% Fractile');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ligne, 'CLCPUE');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, 'Erreur Relative 90%');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ligne, 'Nombre echantillon CPUE');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ligne, 'Max CPUE');
            $ligne++;
            foreach ($data as $key => $value)
            {
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":J".$ligne)->applyFromArray($stylecontenu);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne,$value['mois']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne,$value['libelle_unite_peche']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, number_format($value['moyenne'], 2,","," "));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, $value['ecart_type']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne,$value['sqrt']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, $value['distribution_90']);
               
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ligne, $value['clcpue']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, $value['erreur_relative_90']." %");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ligne, $value['nombre']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ligne, $value['max_cpue']);
                $ligne++;
            }
          
        }

        if ($pivot=="req_4_1_annee_site_unite_nbrunite")
        {
            $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
            $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":J".$ligne);
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":J".$ligne)->applyFromArray($styleTitre);            
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, 'Req 4.1 : Nombre unite de peche par strate majeure/strate mineure/site');                       
            $ligne++;
            $ligne_entete= $this->insertion_entete($styleEntete,$ligne,$objPHPExcel,$id_region,$id_district,$id_site_embarquement,$id_unite_peche,$id_espece,$annee,$mois);
            if ($ligne_entete!=$ligne)
            {
                $ligne=$ligne_entete+1;
            }

            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":D".$ligne)->applyFromArray($stylesousTitre);
            $objPHPExcel-> getActiveSheet()->getStyle("A".$ligne.":D".$ligne)->getNumberFormat()->setFormatCode('00');
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":D".$ligne)->getAlignment()->setWrapText(true);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, 'Année');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, 'Site de débarquement');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, 'Unité de pêche');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'Nombre unité de pêche');
            $ligne++;
            foreach ($data as $key => $value)
            {
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":D".$ligne)->applyFromArray($stylecontenu);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne,$value->annee);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne,$value->libelle);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne,$value->libelle_unite_peche);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, $value->nbr_unite_peche);
                $ligne++;
            }  
        }

        if ($pivot=="req_4_2_annee_unite_nbrunite")
        {
            $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
            $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":C".$ligne)->applyFromArray($styleTitre);            
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, 'Req 4_2 : Nombre d\'unité de pêche par strate majeure/strate mineure/site');                       
            $ligne++;
            $ligne_entete= $this->insertion_entete($styleEntete,$ligne,$objPHPExcel,$id_region,$id_district,$id_site_embarquement,$id_unite_peche,$id_espece,$annee,$mois);
            if ($ligne_entete!=$ligne)
            {
                $ligne=$ligne_entete+1;
            }

            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":C".$ligne)->applyFromArray($stylesousTitre);
            $objPHPExcel-> getActiveSheet()->getStyle("A".$ligne.":C".$ligne)->getNumberFormat()->setFormatCode('00');
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":C".$ligne)->getAlignment()->setWrapText(true);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, 'Année');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, 'Unité de pêche');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, 'Nombre unité de pêche');
            $ligne++;
            foreach ($data as $key => $value)
            {
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":C".$ligne)->applyFromArray($stylecontenu);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne,$value->annee);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne,$value->libelle_unite_peche);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, $value->nbr_unite_peche);
                $ligne++;
            }  
        }

        if ($pivot=="req_5_1_codeteteuniq_site_unite_date_phier_pavanthier_nbrjrssemdern_pabs")
              {
                  $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                  $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":H".$ligne);
                  $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":H".$ligne)->applyFromArray($styleTitre);            
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, 'Req 5_1 : PAB ou Probalité Activité de Bateau(Echantillonage horizontal)');                       
                  $ligne++;
                  $ligne_entete= $this->insertion_entete($styleEntete,$ligne,$objPHPExcel,$id_region,$id_district,$id_site_embarquement,$id_unite_peche,$id_espece,$annee,$mois);
                  if ($ligne_entete!=$ligne)
                  {
                      $ligne=$ligne_entete+1;
                  }

                  $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":H".$ligne)->applyFromArray($stylesousTitre);
                  $objPHPExcel-> getActiveSheet()->getStyle("A".$ligne.":H".$ligne)->getNumberFormat()->setFormatCode('00');
                  $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":H".$ligne)->getAlignment()->setWrapText(true);
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, 'Code unique en en-tête/code unique echantillon');
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, 'Site de débarquement');
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, 'Unité de pêche');
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'Date');
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, 'Pêche hier');
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, 'Pêche avant hier');
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ligne, 'Nombre de jours de peche la semaine dernière');
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, 'PAB');
                  
                  
                  $ligne++;
                  foreach ($data as $key => $value)
                  {
                      $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":H".$ligne)->applyFromArray($stylecontenu);

                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne,$value->code_unique_fiche);
                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne,$value->libelle);
                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, $value->libelle_unite_peche);
                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, $value->date);
                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, $value->peche_hier);
                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, $value->peche_avant_hier);
                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ligne, $value->nbr_jrs_peche_dernier_sem);
                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, number_format($value->pab, 2,","," "));
                      $ligne++;
                  }  
              }

            if ($pivot=="req_5_2_unite_pabmoy_stdevpab_nbrechantilonpab_nbrjrpeche_sqr_degre")
              {
                  $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                  $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":H".$ligne);
                  $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":H".$ligne)->applyFromArray($styleTitre);            
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, 'Req 5_2 : PABmoy par unite de peche /strate majeure/strate mineur/Mois/Annee)');                       
                  $ligne++;
                  $ligne_entete= $this->insertion_entete($styleEntete,$ligne,$objPHPExcel,$id_region,$id_district,$id_site_embarquement,$id_unite_peche,$id_espece,$annee,$mois);
                  if ($ligne_entete!=$ligne)
                  {
                      $ligne=$ligne_entete+1;
                  }

                  $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":G".$ligne)->applyFromArray($stylesousTitre);
                  $objPHPExcel-> getActiveSheet()->getStyle("A".$ligne.":G".$ligne)->getNumberFormat()->setFormatCode('00');
                  $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":G".$ligne)->getAlignment()->setWrapText(true);
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, 'Unité de pêche');
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, 'PABmoy');
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, 'StdevPAB');
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'Nbr echantillon PAB');
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, 'Nbr jour peche');
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, 'SQR(n)');
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ligne, 'Degrees libertes');
                  
                  
                  $ligne++;
                  foreach ($data as $key => $value)
                  {
                      $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":G".$ligne)->applyFromArray($stylecontenu);

                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne,$value->libelle_unite_peche);
                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne,number_format($value->pab_moy), 2,","," ");
                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, $value->ecart_type);
                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, $value->nombre_echantillon);
                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, number_format($value->nbr_jrs_peche_mois), 0,","," ");
                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, $value->sqrt);
                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ligne, $value->degree);
                      $ligne++;
                  }  
              }
        
               if ($pivot=="req_5_3_unite_pabmoy_maxpab_stdevpab_sqr_fractil90_erelative_clpab")
              {
                  $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                  $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":H".$ligne);
                  $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":H".$ligne)->applyFromArray($styleTitre);            
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, 'Req 5_3 : Erreur relative PABmoy par unite de peche par strate mineure/Mois/Année');                       
                  $ligne++;
                  $ligne_entete= $this->insertion_entete($styleEntete,$ligne,$objPHPExcel,$id_region,$id_district,$id_site_embarquement,$id_unite_peche,$id_espece,$annee,$mois);
                  if ($ligne_entete!=$ligne)
                  {
                      $ligne=$ligne_entete+1;
                  }

                  $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":H".$ligne)->applyFromArray($stylesousTitre);
                  $objPHPExcel-> getActiveSheet()->getStyle("A".$ligne.":H".$ligne)->getNumberFormat()->setFormatCode('00');
                  $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":H".$ligne)->getAlignment()->setWrapText(true);
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, 'Unite de peche');
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, 'PABmoy');
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, 'Max PAB');
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'StdevPAB');
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, 'SQR(n)');
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, '90% Fractile');
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ligne, 'Erreur relative 90%');
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, 'CLPAB');
                  
                  
                  $ligne++;
                  foreach ($data as $key => $value)
                  {
                      $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":H".$ligne)->applyFromArray($stylecontenu);

                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne,$value['libelle_unite_peche']);
                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne,number_format($value['pab_moy']), 2,","," ");
                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne,number_format($value['max_pab']), 2,","," ");
                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, $value['ecart_type']);
                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, number_format($value['sqrt']), 2,","," ");
                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, number_format($value['distribution_90']), 2,","," ");
                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ligne, $value['erreur_relative_90']." %");
                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, number_format($value['clpab']), 2,","," ");
                      $ligne++;
                  }  
              } 
     
        if ($pivot=="req_5_4_moi_jrmensuel_unite_pabmoy_jrpechemenspab_moymaxxpab_moynmaxxpabcor_maxjrpechemensupab")
              {
                  $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                  $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":H".$ligne);
                  $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":H".$ligne)->applyFromArray($styleTitre);            
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, 'Req 5_4 : Nombre de jour peche PAB par unite de peche/strate majeur/strate mineure/Mois/Année');                       
                  $ligne++;
                  $ligne_entete= $this->insertion_entete($styleEntete,$ligne,$objPHPExcel,$id_region,$id_district,$id_site_embarquement,$id_unite_peche,$id_espece,$annee,$mois);
                  if ($ligne_entete!=$ligne)
                  {
                      $ligne=$ligne_entete+1;
                  }

                  $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":H".$ligne)->applyFromArray($stylesousTitre);
                  $objPHPExcel-> getActiveSheet()->getStyle("A".$ligne.":H".$ligne)->getNumberFormat()->setFormatCode('00');
                  $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":H".$ligne)->getAlignment()->setWrapText(true);
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, 'Mois');
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, 'Jours mensuels');
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, 'Unite de peche');
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'PABmoy');
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, 'Nbr jours peche mensuel PAB');
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, 'Moyenne Max PAB');
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ligne, 'Moyenne Max PAB correct');
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, 'Max nbrs jours peche mensuel PAB');
                  
                  
                  $ligne++;
                  foreach ($data as $key => $value)
                  {
                      $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":H".$ligne)->applyFromArray($stylecontenu);

                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne,$value['mois']);
                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne,$value['nbr_jour_mois']);
                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, $value['libelle_unite_peche']);
                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, number_format($value['pab_moy']), 2,","," ");
                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, number_format($value['nbr_jrs_peche_mensuel_pab']), 0,","," ");
                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, number_format($value['max_pab']), 2,","," ");
                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ligne, number_format($value['moy_pax_pab_correcte']), 2,","," ");
                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, number_format($value['max_nbr_jrs_peche_mensuel_pab']), 2,","," ");
                      $ligne++;
                  }  
              }

              if ($pivot=="req_6_1_annee_unite_totalannuel")
              {
                  $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
                  $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);
                  $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":C".$ligne)->applyFromArray($styleTitre);            
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, 'Req 6_1: Total jours de peche annuelle par Unité de peche avec PAB');                       
                  $ligne++;
                  $ligne_entete= $this->insertion_entete($styleEntete,$ligne,$objPHPExcel,$id_region,$id_district,$id_site_embarquement,$id_unite_peche,$id_espece,$annee,$mois);
                  if ($ligne_entete!=$ligne)
                  {
                      $ligne=$ligne_entete+1;
                  }

                  $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":C".$ligne)->applyFromArray($stylesousTitre);
                  $objPHPExcel-> getActiveSheet()->getStyle("A".$ligne.":C".$ligne)->getNumberFormat()->setFormatCode('00');
                  $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":C".$ligne)->getAlignment()->setWrapText(true);
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, 'Année');
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, 'Unité de peche');
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, 'Total annuelle');                  
                  
                  
                  $ligne++;
                  foreach ($data as $key => $value)
                  {
                      $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":C".$ligne)->applyFromArray($stylecontenu);

                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne,$value['annee']);
                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne,$value['libelle_unite_peche']);
                      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, number_format($value['somme'], 2,","," "));
                      $ligne++;
                  }  
              } 

        if ($pivot=="req_6_2_moi_jrmensuel_site_unite_nbrunite_jrpechemenspab_jrtotalmensuel_cpuemoy_capturtotal_erelative_nbrechantillon_moymaxxpab_maxcpue_maxcapttot_clcapt_erelcapttot")
        {
            $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
            $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":P".$ligne);
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":P".$ligne)->applyFromArray($styleTitre);            
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, 'Req 6.2 : PrixPAB par especes par l’unité de pêche/Strate majeure/Strate mineure/Année/Mois');                       
            $ligne++;
            $ligne_entete= $this->insertion_entete($styleEntete,$ligne,$objPHPExcel,$id_region,$id_district,$id_site_embarquement,$id_unite_peche,$id_espece,$annee,$mois);
            if ($ligne_entete!=$ligne)
            {
                $ligne=$ligne_entete+1;
            }

            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":P".$ligne)->applyFromArray($stylesousTitre);
            $objPHPExcel-> getActiveSheet()->getStyle("A".$ligne.":P".$ligne)->getNumberFormat()->setFormatCode('00');
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":P".$ligne)->getAlignment()->setWrapText(true);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, ' Mois'.'        ');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, 'Jours mensuels');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, 'Site de debarquement');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'Unité de peche');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, 'Nombre unité de peche');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, 'Nombre de jours de peche'."\n".'mensuel PAB');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ligne, 'Nombre de jours de peche'."\n".'total mensuel PAB');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, 'CPUE moyenne');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ligne, 'Erreur relative 90%');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ligne, 'Erreur relative '."\n".'capture totales 90%');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$ligne, 'Nombre d\'echantillon CPUE');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$ligne, 'Captures totales(t)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$ligne, 'Moyenne max PAB');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$ligne, 'MAX CPUE');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$ligne, 'Max capture totale');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$ligne, 'CL capture totale');
            $ligne++;
            foreach ($data as $key => $value)
            {
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":P".$ligne)->applyFromArray($stylecontenu);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne,$value['mois']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, $value['nbr_jour_mois']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, $value['libelle']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, $value['libelle_unite_peche']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, $value['nbr_unite_peche']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, number_format($value['nbr_jrs_peche_mensuel_pab']), 2,","," ");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ligne, number_format($value['nbr_total_jrs_peche_mensuel']), 2,","," ");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne,$value['moyenne']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ligne, $value['erreur_relative_90']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ligne, $value['erreur_relative_capture_total_90']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$ligne, $value['nombre']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$ligne, $value['captures_totales_t']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$ligne, number_format($value['max_pab']), 2,","," ");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$ligne, $value['max_cpue']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$ligne, number_format($value['max_captures_totales']), 2,","," ");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$ligne, $value['cl_captures_totales']);
                $ligne++;
            }
          
        }
      if ($pivot=="req_7_1_moi_unite_code3Al_caapture_prixunitmoy")
        {
            $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
            $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":G".$ligne);
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":G".$ligne)->applyFromArray($styleTitre);            
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, 'Req 7.1 : Capture par especes par l\'unité de peche par strate majeure/strate mineure/Année/mois');                       
            $ligne++;
            $ligne_entete= $this->insertion_entete($styleEntete,$ligne,$objPHPExcel,$id_region,$id_district,$id_site_embarquement,$id_unite_peche,$id_espece,$annee,$mois);
            if ($ligne_entete!=$ligne)
            {
                $ligne=$ligne_entete+1;
            }

            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":E".$ligne)->applyFromArray($stylesousTitre);
            $objPHPExcel-> getActiveSheet()->getStyle("A".$ligne.":E".$ligne)->getNumberFormat()->setFormatCode('00');
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":E".$ligne)->getAlignment()->setWrapText(true);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, ' Mois'.'        ');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, 'Unité de pêche');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, 'Code3Alpha');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'Capture(kg)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, 'Prix moyen');
            $ligne++;
            foreach ($data as $key => $value)
            {
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":E".$ligne)->applyFromArray($stylecontenu);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne,$value->mois);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, $value->libelle_unite_peche);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, $value->code);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, number_format($value->capture_par_espece, 2,","," ").' Kg');              
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, number_format($value->prix_unitaire_moyenne, 2,","," "));

                $ligne++;
            }
          
        }

         if ($pivot=="req_7_2_moi_unite_caapture")
        {
            $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
            $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":C".$ligne);
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":C".$ligne)->applyFromArray($styleTitre);            
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, 'Req 7.2 : Total capture par especes par l\'unité de peche strate majeure/strate mineure/Année/mois');                       
            $ligne++;
            $ligne_entete= $this->insertion_entete($styleEntete,$ligne,$objPHPExcel,$id_region,$id_district,$id_site_embarquement,$id_unite_peche,$id_espece,$annee,$mois);
            if ($ligne_entete!=$ligne)
            {
                $ligne=$ligne_entete+1;
            }

            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":C".$ligne)->applyFromArray($stylesousTitre);
            $objPHPExcel-> getActiveSheet()->getStyle("A".$ligne.":C".$ligne)->getNumberFormat()->setFormatCode('00');
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":C".$ligne)->getAlignment()->setWrapText(true);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, ' Mois'.'        ');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, 'Unité de pêche');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, 'Capture(kg)');
            $ligne++;
            foreach ($data as $key => $value)
            {
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":C".$ligne)->applyFromArray($stylecontenu);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne,$value->mois);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, $value->libelle_unite_peche);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, number_format($value->capture_par_espece, 2,","," ").' Kg');              

                $ligne++;
            }
          
        }

        if ($pivot=="req_7_3_moi_unite_code3Al_captureespece_captureunite_composi_prixmoy")
        {
            $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
            $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":G".$ligne);
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":G".$ligne)->applyFromArray($styleTitre);            
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, 'Req 7.3 : Composition d’espèce par l’unité de pêche');                       
            $ligne++;
            $ligne_entete= $this->insertion_entete($styleEntete,$ligne,$objPHPExcel,$id_region,$id_district,$id_site_embarquement,$id_unite_peche,$id_espece,$annee,$mois);
            if ($ligne_entete!=$ligne)
            {
                $ligne=$ligne_entete+1;
            }

            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":G".$ligne)->applyFromArray($stylesousTitre);
            $objPHPExcel-> getActiveSheet()->getStyle("A".$ligne.":G".$ligne)->getNumberFormat()->setFormatCode('00');
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":G".$ligne)->getAlignment()->setWrapText(true);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, ' Mois'.'        ');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, 'Unité de pêche');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, 'Code3Alpha');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'Capture total');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, 'Composition d\'espèce');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, 'Capture total par espèce');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ligne, 'Prix moyen');
            $ligne++;
            foreach ($data as $key => $value)
            {
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":G".$ligne)->applyFromArray($stylecontenu);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne,$value->mois);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, $value->libelle_unite_peche);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, $value->code);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, number_format($value->somme_capture_par_unite_peche, 2,","," ").' Kg');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne,number_format($value->composition_espece, 2,","," ").' %');
               
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, number_format($value->somme_capture_par_espece, 2,","," ").' Kg');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ligne, number_format($value->prix_moyenne, 2,","," "));

                $ligne++;
            }
          
        }

        if ($pivot=="req_8_moi_site_unite_jrpechemenspab_jrtotalmensuel_code3Al_erelative_erelcapttot_nbrechantillon_capturtotal_composi_captureespece_prixmoy_pprix")
        {
            $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
            $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":N".$ligne);
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":N".$ligne)->applyFromArray($styleTitre);            
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, 'Req 8 : Prix PAB par espèces par l’unité de pêche/Strate majeure/Strate mineure/Année/Mois');                       
            $ligne++;
            $ligne_entete= $this->insertion_entete($styleEntete,$ligne,$objPHPExcel,$id_region,$id_district,$id_site_embarquement,$id_unite_peche,$id_espece,$annee,$mois);
            if ($ligne_entete!=$ligne)
            {
                $ligne=$ligne_entete+1;
            }

            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":N".$ligne)->applyFromArray($stylesousTitre);
            $objPHPExcel-> getActiveSheet()->getStyle("A".$ligne.":N".$ligne)->getNumberFormat()->setFormatCode('00');
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":N".$ligne)->getAlignment()->setWrapText(true);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, 'Mois');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, 'Site de débarquement');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, 'Unite de pêche');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'Nombre de jours de'."\n".'pêche mensuel PAB');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, 'Nombre de jours total'."\n".'de pêche mensuel PAB');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, 'Code3Alpha');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ligne, 'Erreur Relative 90%');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, 'Erreur Relative'."\n".'capture tolales 90%');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ligne, 'Nombre echantillon CPUE');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ligne, 'Captures totales (t)');
             $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$ligne, 'Composition d\'espèce');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$ligne, 'Capture total '."\n".'par espèce');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$ligne, 'Prix moyen');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$ligne, 'Prix');
            $ligne++;
            foreach ($data as $key => $value)
            {
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":N".$ligne)->applyFromArray($stylecontenu);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne,$value['mois']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne,$value['libelle']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, $value['libelle_unite_peche']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, number_format($value['nbr_jrs_peche_mensuel_pab'], 0,","," "));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne,$value['nbr_total_jrs_peche_mensuel']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, $value['code']);
               
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ligne, $value['erreur_relative_90']." %");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, number_format($value['erreur_relative_capture_total_90'], 2,","," ")." %");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ligne, $value['nombre']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ligne, number_format($value['captures_totales_t'], 2,","," "));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$ligne, number_format($value['composition_espece'], 2,","," ")." %");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$ligne, number_format($value['somme_capture_par_espece'], 2,","," ")." Kg");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$ligne, number_format($value['prix_moyenne'], 2,","," "));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$ligne, number_format($value['prix'], 2,","," "));
                $ligne++;
            }
          
        }

        if ($pivot=='req_9_annee_site_unite_nbrunite')
        {

            $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
            $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":D".$ligne);
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":D".$ligne)->applyFromArray($styleTitre);            
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, 'Req 10 : Targeted Unité de pêche par strate mineure par Année / Mois');                       
            $ligne++;
            $ligne_entete= $this->insertion_entete($styleEntete,$ligne,$objPHPExcel,$id_region,$id_district,$id_site_embarquement,$id_unite_peche,$id_espece,$annee,$mois);
            if ($ligne_entete!=$ligne)
            {
                $ligne=$ligne_entete+1;
            }

            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":D".$ligne)->applyFromArray($stylesousTitre);
            $objPHPExcel-> getActiveSheet()->getStyle("A".$ligne.":D".$ligne)->getNumberFormat()->setFormatCode('00');
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":D".$ligne)->getAlignment()->setWrapText(true);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, '  Année      ');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, 'Site d\'embarquement');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, 'Unité de pêche');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'Nombre d\'unité de pêche');
            $ligne++;
            foreach ($data as $key => $value)
            {
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":D".$ligne)->applyFromArray($stylecontenu);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne,$value->annee);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, $value->libelle);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, $value->libelle_unite_peche);
               
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, $value->nbr_unite_peche);

                $ligne++;
            }
        }

        if ($pivot=='req_10_annee_region_unite_nbrunite')
        {

            $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
            $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":D".$ligne);
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":D".$ligne)->applyFromArray($styleTitre);            
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, 'Req 10 : Targeted Unité de pêche par strate mineure par Année / Mois');                       
            $ligne++;
            $ligne_entete= $this->insertion_entete($styleEntete,$ligne,$objPHPExcel,$id_region,$id_district,$id_site_embarquement,$id_unite_peche,$id_espece,$annee,$mois);
            if ($ligne_entete!=$ligne)
            {
                $ligne=$ligne_entete+1;
            }

            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":D".$ligne)->applyFromArray($stylesousTitre);
            $objPHPExcel-> getActiveSheet()->getStyle("A".$ligne.":D".$ligne)->getNumberFormat()->setFormatCode('00');
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":D".$ligne)->getAlignment()->setWrapText(true);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, '  Année      ');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, '  Région      ');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, 'Unité de pêche');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, 'Nombre d\'unité de pêche');
            $ligne++;
            foreach ($data as $key => $value)
            {
                $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":D".$ligne)->applyFromArray($stylecontenu);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne,$value->annee);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, $value->libelle_region);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, $value->libelle_unite_peche);
               
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, $value->nbr_unite_peche);

                $ligne++;
            }
        }



        try
        {
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save(dirname(__FILE__) . "/../../../../../../assets/excel/requetes/".$nom_file.".xlsx");
            
            $this->response([
                'status' => TRUE,
                'nom_file' =>$nom_file.".xlsx",
                'message' => 'Get file success',
            ], REST_Controller::HTTP_OK);
          
        } 
        catch (PHPExcel_Writer_Exception $e)
        {
            $this->response([
                  'status' => FALSE,
                   'nom_file' => array(),
                   'message' => "Something went wrong: ". $e->getMessage(),
                ], REST_Controller::HTTP_OK);
        }
    }

   public function insertion_entete($style,$ligne,$objPHPExcel,$id_region,$id_district,$id_site_embarquement,$id_unite_peche,$id_espece,$annee,$mois)
    {

        if($id_region!='*' && $id_region!="undefined")
        {
            $tmp= $this->RegionManager->findById($id_region);

            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne)->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":B".$ligne);
               
            $objRichText = new PHPExcel_RichText();

            $titre = $objRichText->createTextRun('Region : ');
            $titre->getFont()->applyFromArray(array( "bold" => true, "size" => 11, "name" => "Calibri"));

            $contenu = $objRichText->createTextRun($tmp->nom);
            $contenu->getFont()->applyFromArray(array("size" => 11, "name" => "Calibri"));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne,$objRichText);
            $ligne++;
        }
        if($id_district!='*' && $id_district!="undefined")
        {
            $tmp= $this->DistrictManager->findById($id_district);
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne)->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":B".$ligne);
               
            $objRichText = new PHPExcel_RichText();

            $titre = $objRichText->createTextRun('District : ');
            $titre->getFont()->applyFromArray(array( "bold" => true, "size" => 11, "name" => "Calibri"));

            $contenu = $objRichText->createTextRun($tmp->nom);
            $contenu->getFont()->applyFromArray(array("size" => 11, "name" => "Calibri"));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne,$objRichText);
            $ligne++;
        }
        if($id_site_embarquement!='*' && $id_site_embarquement!="undefined")
        {
            $tmp= $this->Site_embarquementManager->findById($id_site_embarquement);

            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne)->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":B".$ligne);
            $objRichText = new PHPExcel_RichText();

            $titre = $objRichText->createTextRun('Site de débarquement : ');
            $titre->getFont()->applyFromArray(array( "bold" => true, "size" => 11, "name" => "Calibri"));

            $contenu = $objRichText->createTextRun($tmp->libelle);
            $contenu->getFont()->applyFromArray(array("size" => 11, "name" => "Calibri"));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne,$objRichText);

            $ligne++;
        }
        if($id_unite_peche!='*' && $id_unite_peche!="undefined")
        {
            $tmp= $this->Unite_pecheManager->findById($id_unite_peche);

            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne)->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":B".$ligne);
             $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":B".$ligne)->getAlignment()->setWrapText(false);  
            $objRichText = new PHPExcel_RichText();

            $titre = $objRichText->createTextRun('Unite de pêche : ');
            $titre->getFont()->applyFromArray(array( "bold" => true, "size" => 11, "name" => "Calibri"));

            $contenu = $objRichText->createTextRun($tmp->libelle);
            $contenu->getFont()->applyFromArray(array("size" => 11, "name" => "Calibri"));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne,$objRichText);
            $ligne++;
        }
        if($id_espece!='*' && $id_espece!="undefined")
        {
            $tmp= $this->EspeceManager->findById($id_espece);

            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne)->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":B".$ligne);
             $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":B".$ligne)->getAlignment()->setWrapText(false);  
            $objRichText = new PHPExcel_RichText();

            $titre = $objRichText->createTextRun('Espece : ');
            $titre->getFont()->applyFromArray(array( "bold" => true, "size" => 11, "name" => "Calibri"));

            $contenu = $objRichText->createTextRun($tmp->nom_scientifique." (".$tmp->nom_local.")");
            $contenu->getFont()->applyFromArray(array("size" => 11, "name" => "Calibri"));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne,$objRichText);
            $ligne++;
        }

        if($annee!='*' && $annee!="undefined")
        {
           $month='';
           if ($mois!='*' && $mois!="undefined") {
             $month = $this->affichage_mois($mois);
           }

            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne)->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":B".$ligne);
             $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":B".$ligne)->getAlignment()->setWrapText(false);  
            $objRichText = new PHPExcel_RichText();

            $titre = $objRichText->createTextRun('Date : ');
            $titre->getFont()->applyFromArray(array( "bold" => true, "size" => 11, "name" => "Calibri"));

            $contenu = $objRichText->createTextRun($month." ".$annee);
            $contenu->getFont()->applyFromArray(array("size" => 11, "name" => "Calibri"));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne,$objRichText);
            $ligne++;
        }

        return $ligne;
    }





}
/* End of file controllername.php */
/* Location: ./application/controllers/controllername.php */
?>