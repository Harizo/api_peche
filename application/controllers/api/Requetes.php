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
        ini_set ('memory_limit', '1024M');
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
                  if (($id_region!='*')&&($id_region!='undefined')) 
                  {
                      $all_regionReq_8 = $this->RegionManager->findByIdtab($id_region);

                  }
                  else 
                  {
                     // $all_region = $this->RegionManager->findAll();
                      $all_regionReq_8 = $this->RegionManager->findAllInTable($annee);
                  }

                  if(($id_unite_peche!='*')&&($id_unite_peche!='undefined'))
                  {
                      $all_unite_pecheReq_8 = $this->Unite_pecheManager->findByIdtab($id_unite_peche);
                  }
                  else
                  {
                      //$all_unite_peche=$this->Unite_pecheManager->findAll();
                      $all_unite_pecheReq_8=$this->Unite_pecheManager->findAllInTable($annee);
                  }

                  if(($id_site_embarquement!='*')&&($id_site_embarquement!='undefined'))
                  {
                      $all_site_embarquementReq_8 = $this->Site_embarquementManager->findByIdtab($id_site_embarquement);
                  }
                  else
                  {
                      //$all_site_embarquement=$this->Site_embarquementManager->findAllByFiche($annee);

                      $all_site_embarquementReq_8=$this->Site_embarquementManager->findAllInTable($annee);
                  }


                  if(($id_espece!='*')&&($id_espece!='undefined'))
                  {
                      $all_especeReq_8 = $this->EspeceManager->findByIdtab($id_espece);
                  }
                  else
                  {
                      $all_especeReq_8=$this->EspeceManager->findAllInTable($annee);
                  }

            //initialisation Requete_8
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

            //RQ4
                  if (($pivot == 'req_4_1') || ($pivot == 'req_4_2'))
                  {
                    $data = $this->nbr_unite_peche($sites_par_region,$all_unite_peche,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece) ;
                  }
            //Fin RQ4

            //RQ5
                  if (($pivot == 'req_5_1') )
                  {
                    $data = $this->requete_5_pab($sites_par_region,$all_unite_peche,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece) ;
                  }
            //Fin RQ5
            //RQ5.2
                  if (($pivot == 'req_5_2') )
                  {
                    $data = $this->requete_5_2($sites_par_region,$all_unite_peche,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece) ;
                  }
            //Fin RQ5.2

            //RQ5.2
                  if (($pivot == 'req_5_3') || ($pivot == 'req_5_4') )
                  {
                    $data = $this->requete_5_3_et_5_4($sites_par_region,$all_unite_peche,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece) ;
                  }
            //Fin RQ5.2

            //RQ5.2
                  if (($pivot == 'req_6_1')  )
                  {
                    $data = $this->requete_6_1_acces6_2($sites_par_region,$all_unite_peche,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece) ;
                  }
            //Fin RQ5.2
            //RQ6.2
                  if (($pivot == 'req_6_2')  )
                  {
                    $data = $this->requete_6_2($sites_par_region,$all_unite_peche,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece) ;
                  }
            //Fin RQ6.2

            //RQ7.1
                  if (($pivot == 'req_7_1')  )
                  {
                    $data = $this->requete_7_1($sites_par_region,$all_unite_peche,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece) ;
                  }
            //Fin RQ7.1

            //RQ7.2
                  if (($pivot == 'req_7_2')  )
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
                  if (($pivot == 'req_7_3')  )
                  {
                    $data = $this->requete_7_3_new($sites_par_region,$all_unite_peche,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece) ;
                  }
            //Fin RQ7.3

            //RQ8
                  if (($pivot == 'req_8')  )
                  {
                    $data = $this->requete_8($all_regionReq_8,$all_site_embarquementReq_8,$all_unite_pecheReq_8,$all_especeReq_8,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece);
                  }
            //Fin RQ8

            //RQ9
                  if (($pivot == 'req_9')  )
                  {
                    $data = $this->requete_9($sites_par_region,$all_unite_peche,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece) ;
                  }
            //Fin RQ9
            //RQ10
                  if (($pivot == 'req_10')  )
                  {
                    $data = $this->requete_10($sites_par_region,$all_unite_peche,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece) ;
                  }
            //Fin RQ10
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

        
        

        if (($pivot == 'req_4_1') || ($pivot == 'req_4_2')|| ($pivot == 'req_9')|| ($pivot == 'req_10')) 
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
              if (($pivot == 'req_4_1')||($pivot == 'req_4_2')|| ($pivot == 'req_9')|| ($pivot == 'req_10')) 
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
              
                $ecart_type = $this->Fiche_echantillonnage_captureManager->ecart_type($this->generer_requete($pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece),$value->id_fiche,$value->id_region,$value->id_unite_peche); 
                
                //$ecart_type = $ecart_type_obj[0]->ecart_type ;
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
               
           
            }
      }

      return $data ;
    }

    public function nbr_unite_peche($site_embarquements, $unite_peches,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece)
    {
      //$donnees = $this->Unite_peche_site_Manager->findAllByRequetes();
      

      if ($pivot == "req_4_1") 
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


   /* public function requete_8($site_embarquements, $unite_peches,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece)
    {
        $data = array();
        $indice = 0 ;
        $total_prix = 0 ;
        $total_capture = 0 ;
        $erreur_rel_capture=0;
        $erreur_relative=0;

      $result =   $this->Fiche_echantillonnage_captureManager->erreur_relativepivotl8(
                            $this->generer_requete($pivot,$date,$annee,$mois,$id_region,$id_district,$id_site_embarquement,$id_unite_peche, $id_espece),
                            $this->generer_requete($pivot, $date,$annee,$mois,$id_region,$id_district,'*', $id_unite_peche, '*'));
      if ($result != null )
      {
        $i=1;
                        $donnees=array();

                        foreach ($result as $key => $value)
                        {
                            $tab_capture_par_espece =   $this->Fiche_echantillonnage_captureManager->capture_total_par_espece(
                                                        $this->generer_requete($pivot,$date,$annee,$value->mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece),$value->id_reg,$value->id_unite,$value->mois);

                            $ecart_type = $this->Fiche_echantillonnage_captureManager->ecartypeAnalyse(
                                          $this->generer_requete($pivot,$date,$annee,$value->mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece),$value->id_reg,$value->id_unite,$value->mois,$value->id_site);

                            $date_t     = explode('-', $value->date) ;
                            $res        = mktime( 0, 0, 0, $date_t[1], 1, $date_t[0] );
                            $nbr_jour   = intval(date("t",$res));

                            $nbr_jrs_peche_mensuel_pab = $value->pab_moy * $nbr_jour;
                            
                            $captures_t = ($value->nbr_unit_peche * $nbr_jrs_peche_mensuel_pab * $value->cpue_moyenne);                            
                            
                            $distribution    = $this->Distribution_fractileManager->findByDegree($value->degree);
                            $tdistriburion90 = $distribution[0]->PercentFractile90 ;
                            $clcpue          = ($tdistriburion90 * $ecart_type) / $value->sqrt ;
                            if ($value->cpue_moyenne ) {
                                $erreur_relative = ($clcpue / $value->cpue_moyenne ) * 100;
                            }
                            

                            $distributionpab = $this->Distribution_fractileManager->findByDegree($value->degreepab);
                            $clpab           = ($distributionpab[0]->PercentFractile90 * $value->ecart_typepab) / $value->sqrtpab ;
                           $max_pab          = ($clpab + $value->pab_moy) ;
                            
                            if ($max_pab > 1 ) 
                            {$moy_pax_pab = 1 ;}
                            else{$moy_pax_pab = $max_pab ;}
                            
                            $max_cpue = $clcpue + $value->cpue_moyenne;

                            $nbr_total_jrs_peche_mensuel = $value->nbr_unit_peche * $nbr_jrs_peche_mensuel_pab;
                            $max_captures_totales = ($value->nbr_unit_peche * $moy_pax_pab * $nbr_jour * $max_cpue)/1000;

                            $cl_captures_totales = $max_captures_totales - ($captures_t/1000);
 // dependre capture_t mila averina                          
                            $erreur_relative_capture_total_90=1;
                            if ($captures_t)
                            {
                                $erreur_relative_capture_total_90 = ($cl_captures_totales / ($captures_t/1000)) * 100;
                            } 

                            $j=0;
                            $tab_capture_espece_total = array();
                            $prix_espece              = array();
                            $tab_composition          = array();
                          
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
                                                       
                            
                            
                            $som_tab_capture_par_espece=array_sum($tab_capture_espece_total);
                            $donnees[$i]['Total_capture_unite'] = $som_tab_capture_par_espece;

                            $total_prix_unite=array_sum($prix_espece);
                            $donnees[$i]['total_prix_unite'] = $total_prix_unite;

                            $donnees[$i]['erreur_relative_capture_total_90'] = $erreur_relative_capture_total_90;
                            $donnees[$i]['erreur_relative'] = $erreur_relative;
                            $donnees[$i]['unite_peche'] = $value->libelle;

                            $donnees[$i]['espece'] = $tab_capture_espece_total;
                          $i++;  
                        }
                        $Total_captur  = array_column($donnees, 'Total_capture_unite');
                        $total_capture = array_sum($Total_captur);

                        $Total_pri     = array_column($donnees, 'total_prix_unite');
                        $total_prix    = array_sum($Total_pri)/1000;

                        $erreur_capture = array_column($donnees, 'erreur_relative_capture_total_90');
                        $n_capture      = count($erreur_capture);
                        $erreur_rel_capture = number_format ( array_sum($erreur_capture)/$n_capture ,0);

                        $erreur = array_column($donnees, 'erreur_relative');
                        $n      = count($erreur);
                        $erreur_relative = number_format ( array_sum($erreur)/$n,0);

                        $data[$indice]['capture'] = $total_capture;
                        $data[$indice]['prix']    = $total_prix;
                        $data[$indice]['erreur_relative']    = $erreur_relative;
                        $data[$indice]['erreur_rel_capture'] = $erreur_rel_capture;
                        $data[$indice]['Donnee'] = $donnees;                        
                      $indice++ ; 
      }
      return $data;
    }*/
     public function requete_8($all_regionReq_8,$all_site_embarquementReq_8,$all_unite_pecheReq_8,$all_especeReq_8,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece)
    {
        /* $data = array();
        $indice = 0 ;
        $total_prix = 0 ;
        $total_capture = 0 ;
        $erreur_rel_capture=0;
        $erreur_relative=0;

      $result =   $this->Fiche_echantillonnage_captureManager->erreur_relativepivotl1(
                            $this->generer_requete($pivot,$date,$annee,$mois,$id_region,$id_district,$id_site_embarquement,$id_unite_peche, $id_espece),
                            $this->generer_requete($pivot, $date,$annee,$mois,$id_region,$id_district,'*', $id_unite_peche, '*'));
      if ($result != null )
      {
        $i=1;
        $donnees=array();
        $tab_code=array();
        $tab_unite= array();
        $tab_site= array();
        $tab_mois= array();
        foreach ($result as $key => $value)
        {    
          if($value->pab_moy && $value->cpue_moyenne && $value->sqrt && $value->sqrtpab && $value->pab_moy)
          {
            $tab_capture_par_espece =   $this->Fiche_echantillonnage_captureManager->capture_total_par_espece($this->generer_requete($pivot,$date,$annee,$value->mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece),$value->id_reg,$value->id_unite,$value->mois);
                              
            $ecart_type = $this->Fiche_echantillonnage_captureManager->ecartypeAnalyse(
                           $this->generer_requete($pivot,$date,$annee,$value->mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece),$value->id_reg,$value->id_unite,$value->mois,$value->id_site);

            $date_t     = explode('-', $value->date) ;
            $res        = mktime( 0, 0, 0, $date_t[1], 1, $date_t[0] );
            $nbr_jour   = intval(date("t",$res));

            $nbr_jrs_peche_mensuel_pab = $value->pab_moy * $nbr_jour;
                                
            $captures_t = ($value->nbr_unit_peche * $nbr_jrs_peche_mensuel_pab * $value->cpue_moyenne/1000);                            
            $distribution    = $this->Distribution_fractileManager->findByDegree($value->degree);
            $tdistriburion90 = $distribution[0]->PercentFractile90 ;
            $clcpue          = ($tdistriburion90 * $ecart_type) / $value->sqrt ;
            if ($value->cpue_moyenne )
            {
              $erreur_relative = ($clcpue / $value->cpue_moyenne ) * 100;
            }

            $distributionpab = $this->Distribution_fractileManager->findByDegree($value->degreepab);
            $clpab           = ($distributionpab[0]->PercentFractile90 * $value->ecart_typepab) / $value->sqrtpab ;
            $max_pab          = ($clpab + $value->pab_moy) ;
                                
            if ($max_pab > 1 ) 
            {$moy_pax_pab = 1 ;}
            else{$moy_pax_pab = $max_pab ;}
                                
            $max_cpue = $clcpue + $value->cpue_moyenne;

            $nbr_total_jrs_peche_mensuel = $value->nbr_unit_peche * $nbr_jrs_peche_mensuel_pab;
            $max_captures_totales = ($value->nbr_unit_peche * $moy_pax_pab * $nbr_jour * $max_cpue)/1000;

            $cl_captures_totales = $max_captures_totales - ($captures_t);
            $erreur_relative_capture_total_90=1;
            if ($captures_t)
            {
              $erreur_relative_capture_total_90 = ($cl_captures_totales / ($captures_t)) * 100;
            }

            $j=0;
            $tab_capture_espece_total = array();
            $prix_espece              = array();
            $tab_composition          = array();
            $tab_espece               = array();
            if ($tab_capture_par_espece) 
            {
              foreach ($tab_capture_par_espece as $val)
              {
                $tab_capture_espece_total['mois']=$value->mois;
                $tab_capture_espece_total['unite_peche']=$value->libelle;
                $tab_capture_espece_total['nbr_jrs_peche_mensuel_pab']=$nbr_jrs_peche_mensuel_pab;
                $tab_capture_espece_total['nbr_total_jrs_peche_mensuel']=$nbr_total_jrs_peche_mensuel;
                $tab_capture_espece_total['code']=$val->coda;
                $tab_capture_espece_total['erreur_relative']=$erreur_relative;
                $tab_capture_espece_total['erreur_relative_capture']=$erreur_relative_capture_total_90;
                $tab_capture_espece_total['nombre_echantillon']=$value->nombre_echantillon;
                $tab_capture_espece_total['capture']=($val->capture_total_par_espece/$value->capture_total_par_unite)*($captures_t);
                $tab_capture_espece_total['composition_espece']=($val->capture_total_par_espece/$value->capture_total_par_unite);
                $tab_capture_espece_total['capture_espece']=$val->capture_total_par_espece;
                $tab_capture_espece_total['prix']=(($val->capture_total_par_espece/$value->capture_total_par_unite)*($captures_t))*$val->prix;
               // $tab_capture_espece_total['nom_local']=$val->nom_local;
                //$tab_capture_espece_total['nom_scientifique']=$val->nom_scientifique;
                
                
                
                
                $tab_capture_espece_total['site_embarquement']=$value->site_embarquement;
                
                $tab_espece[$j]=$tab_capture_espece_total;

                if(!in_array($val->coda, $tab_code))
                {
                  array_push($tab_code, $val->coda);
                }
                $j++;
              }
            }
            if(!in_array($value->libelle, $tab_unite))
            {
              array_push($tab_unite, $value->libelle);
            }

            if(!in_array($value->site_embarquement, $tab_site))
            {
              array_push($tab_site, $value->site_embarquement);
            }

            if(!in_array($value->mois, $tab_mois))
            {
              array_push($tab_mois, $value->mois);
            }
            $datadetail[$i] =$tab_espece;
            $i++;
          }  
        }
                        
        $final=array();
        $y=0;
        foreach ($tab_mois as $keyMois => $valueMois)
        {
          foreach ($tab_site as $keySite => $valueSite)
          {
            foreach ($tab_unite as $keyUnite => $valueUnite)
            {
              foreach ($tab_code as $keyCode => $valueCode)
              {
                $capture=0;
                $prix=0;
                $nbr_jrs_peche_mensuel_pab=0;
                $nbr_total_jrs_peche_mensuel=0;
                $nombre_echantillon=0;
                $composition_espece=0;
                $capture_espece=0;

                $tb_erreur_rel=array();
                $tb_erreur_rel_capture=array();
                $existe=false;
                foreach ($datadetail as $key => $value)
                {
                  foreach ($value as $keyv => $valuev)
                  {   
                    if ($valueCode==$valuev['code'] && $valueUnite==$valuev['unite_peche'] && $valueSite==$valuev['site_embarquement'] && $valueMois==$valuev['mois']) 
                    {
                        $capture+=$valuev['capture'];
                        $prix+=$valuev['prix'];
                        $nbr_jrs_peche_mensuel_pab=$valuev['nbr_jrs_peche_mensuel_pab'];
                        $nbr_total_jrs_peche_mensuel=$valuev['nbr_total_jrs_peche_mensuel'];
                        $nombre_echantillon=$valuev['nombre_echantillon'];
                        $composition_espece=$valuev['composition_espece'];
                        $capture_espece=$valuev['capture_espece'];
                        array_push($tb_erreur_rel, $valuev['erreur_relative']);
                        array_push($tb_erreur_rel_capture, $valuev['erreur_relative_capture']);
                                                    $existe=true;
                    }                                                
                                                
                  }                            
                }
                                     
                if ($existe)
                {   
                  $final['erreur_relative']=number_format ( array_sum($tb_erreur_rel)/count($tb_erreur_rel),0);
                  $final['erreur_rel_capture']=number_format ( array_sum($tb_erreur_rel_capture)/count($tb_erreur_rel_capture),0);
                  $final['unite_peche']=$valueUnite;
                  $final['site_embarquement']=$valueSite;
                  $final['mois']=$valueMois;
                  $final['tabsite']=$tab_site;
                  $final['capture']=$capture;
                  $final['prix']=$prix;
                  $final['code']=$valueCode;

                  $final['nbr_jrs_peche_mensuel_pab']=number_format ($nbr_jrs_peche_mensuel_pab,0,',','.');
                  $final['nbr_total_jrs_peche_mensuel']=number_format ($nbr_total_jrs_peche_mensuel,0,',','.');
                  $final['nombre_echantillon']=$nombre_echantillon;
                  $final['composition_espece']=$composition_espece;
                  $final['capture_espece']=$capture_espece;
                  //$final['espece_nom_scientifique']=$nom_scientifique;
                  //$final['espece_nom_local']=$nom_local;
                  $data[$y]=$final;
                  $y++;
                }
                                       
              }
                                    
            }
          }
        }
      }*/
      $indice = 0 ;
      $total_prix = 0 ;
      $total_capture = 0 ;
      $erreur_rel_capture=0;
      $erreur_relative=0;
      for ($moi=1; $moi <=12 ; $moi++)
       {
          foreach ($all_site_embarquementReq_8 as $kSite => $vSite)
          {
            foreach ($all_unite_pecheReq_8 as $kUnite_peche => $vUnite_peche)
            {
              foreach ($all_especeReq_8 as $kEspece => $vEspece)
              {
                $result =   $this->Fiche_echantillonnage_captureManager->essai(
                            $this->generer_requete_analyse($annee,$moi,$id_region,$id_district,$id_site_embarquement, $vUnite_peche->id,$vEspece->id),
                            $this->generer_requete_analyse($annee,$moi,$id_region,$id_district,'*', $vUnite_peche->id, '*'),$this->generer_requete_analyse_cadre($annee,$moi,$id_region,$id_district,$vSite->id, $vUnite_peche->id,$vEspece->id),$annee);

                               
                if ($result != null )
                {                        
                  $i=1;
                  $donnees=array();
                  foreach ($result as $key => $value)
                  {
                    $tab_capture_par_espece =   $this->Fiche_echantillonnage_captureManager->capture_total_par_espece($this->generer_requete_analyse($annee,$value->mois, $value->id_reg, $id_district, $id_site_embarquement,$value->id_unite, $vEspece->id));

                    $ecart_type = $this->Fiche_echantillonnage_captureManager->ecartypeAnalyse(
                                                  $this->generer_requete_analyse($annee,$value->mois, $value->id_reg, $id_district,'*',$value->id_unite, $id_espece));

                    $date_t     = explode('-', $value->date) ;
                    $res        = mktime( 0, 0, 0, $date_t[1], 1, $date_t[0] );
                    $nbr_jour   = intval(date("t",$res));

                    $nbr_jrs_peche_mensuel_pab = $value->pab_moy * $nbr_jour;
                                        
                    $captures_t = ($value->nbr_unit_peche * $nbr_jrs_peche_mensuel_pab * $value->cpue_moyenne);                            
                                        
                    $distribution    = $this->Distribution_fractileManager->findByDegree($value->degree);
                    $tdistriburion90 = $distribution[0]->PercentFractile90 ;
                    $clcpue          = ($tdistriburion90 * $ecart_type) / $value->sqrt ;
                    if ($value->cpue_moyenne )
                    {
                      $erreur_relative = ($clcpue / $value->cpue_moyenne ) * 100;
                    }
                    
                    $distributionpab = $this->Distribution_fractileManager->findByDegree($value->degreepab);
                    $clpab           = ($distributionpab[0]->PercentFractile90 * $value->ecart_typepab) / $value->sqrtpab ;
                    $max_pab          = ($clpab + $value->pab_moy) ;
                                        
                    if ($max_pab > 1 ) 
                    {$moy_pax_pab = 1 ;}
                    else{$moy_pax_pab = $max_pab ;}
                                        
                    $max_cpue = $clcpue + $value->cpue_moyenne;

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
                                                    $tab_capture_espece[$j]=$val->capture_total_par_espece;
                                                    $j++;
                                                }
                                            }
                                                                   
                                        
                                        
                                        $som_tab_capture_par_espece =array_sum($tab_capture_espece_total);
                                        $donnees[$i]['Total_capture_unite'] = $som_tab_capture_par_espece;

                                        $total_prix_unite=array_sum($prix_espece);

                                        $total_composition=array_sum($tab_composition);

                                        $capture_esp=array_sum($tab_capture_espece);

                                        $donnees[$i]['total_prix_unite'] = $total_prix_unite;
                                        $donnees[$i]['erreur_relative_capture_total_90'] = $erreur_relative_capture_total_90;
                                        $donnees[$i]['erreur_relative'] = $erreur_relative;
                                        $donnees[$i]['unite_peche'] = $value->libelle;
                                        $donnees[$i]['composition_espece'] = $total_composition;
                                        $donnees[$i]['capture_espece'] = $capture_esp;
                                        $donnees[$i]['espece'] = $tab_capture_espece_total;

                                      $i++;  
                                    }
                                    $Total_captureUnite_peche  = array_column($donnees, 'Total_capture_unite');
                                    $total_captureUnite_peche = array_sum($Total_captureUnite_peche);

                                    $Total_prixUnite_peche     = array_column($donnees, 'total_prix_unite');
                                    $total_prixUnite_peche    = array_sum($Total_prixUnite_peche)/1000;

                                    $composition_espece = array_column($donnees, 'composition_espece');

                                    $capture_espece = array_column($donnees, 'capture_espece');

                                    $erreur_captureUnite_peche = array_column($donnees, 'erreur_relative_capture_total_90');
                                    $n_captureUnite_peche      = count($erreur_captureUnite_peche);
                                    $erreur_rel_captureUnite_peche = number_format ( array_sum($erreur_captureUnite_peche)/$n_captureUnite_peche ,0);

                                    $erreurUnite_peche = array_column($donnees, 'erreur_relative');
                                    $nUnite_peche      = count($erreurUnite_peche);
                                    $erreur_relativeUnite_peche = number_format ( array_sum($erreurUnite_peche)/$nUnite_peche,0);
                                   
                                    $data[$indice]['mois'] = $moi;
                                    $data[$indice]['site_embarquement'] = $vSite->libelle;
                                    $data[$indice]['unite_peche'] = $vUnite_peche->libelle;
                                    $data[$indice]['code']=$vEspece->code;
                                    $data[$indice]['nbr_jrs_peche_mensuel_pab']=number_format ($nbr_jrs_peche_mensuel_pab,0,',','.');
                                    $data[$indice]['nbr_total_jrs_peche_mensuel']=number_format ($nbr_total_jrs_peche_mensuel,0,',','.');
                                    $data[$indice]['espece_nom_local']=$vEspece->nom_local;
                                    $data[$indice]['capture'] = $total_captureUnite_peche;
                                    $data[$indice]['prix']    = $total_prixUnite_peche;
                                    $data[$indice]['erreur_relative']    = $erreur_relativeUnite_peche;
                                    $data[$indice]['erreur_rel_capture'] = $erreur_rel_captureUnite_peche;
                                    $data[$indice]['composition_espece'] = $composition_espece;
                                    $data[$indice]['capture_espece'] = $capture_espece;
                                    $data[$indice]['nombre_echantillon'] = $value->nombre_echantillon;
                                    $data[$indice]['Donnee'] = $donnees;
                      
                                  $indice++ ;  
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
      $i=1;
      $result =   $this->Fiche_echantillonnage_captureManager->essai(
                            $this->generer_requete($pivot,$date,$annee,$mois,$id_region,$id_district,$id_site_embarquement,$id_unite_peche, $id_espece),
                            $this->generer_requete($pivot, $date,$annee,$mois,$id_region,$id_district,'*', $id_unite_peche, '*'),$this->generer_requete($pivot,$date,$annee,$mois,$id_region,$id_district,$id_site_embarquement,$id_unite_peche, $id_espece),$annee);
      if ($result != null )
      {
        for ($j=1; $j <13 ; $j++) { 
        //$donnees=array();
        foreach ($result as $key => $value)
        { if ($value->mois==$j) {

          $ecart_type = $this->Fiche_echantillonnage_captureManager->ecartypeAnalyse(
                        $this->generer_requete($pivot,$date,$annee,$value->mois,$value->id_reg,$id_district,'*',$value->id_unite,$id_espece));
          $date_t     = explode('-', $value->date) ;
          $res        = mktime( 0, 0, 0, $date_t[1], 1, $date_t[0] );
          $nbr_jour   = intval(date("t",$res));

          $nbr_jrs_peche_mensuel_pab = $value->pab_moy * $nbr_jour;
                            
         $captures_t = ($value->nbr_unit_peche * $nbr_jrs_peche_mensuel_pab * $value->cpue_moyenne/1000);                            
          $distribution    = $this->Distribution_fractileManager->findByDegree($value->degree);
          $tdistriburion90 = $distribution[0]->PercentFractile90 ;
          $clcpue          = ($tdistriburion90 * $ecart_type) / $value->sqrt ;
          if ($value->cpue_moyenne )
          {
             $erreur_relative = ($clcpue / $value->cpue_moyenne ) * 100;
          }
          
          $distributionpab = $this->Distribution_fractileManager->findByDegree($value->degreepab);
          $clpab = ($distributionpab[0]->PercentFractile90 * $value->ecart_typepab) / $value->sqrtpab ;
          $max_pab          = ($clpab + $value->pab_moy) ;
                            
          if ($max_pab > 1 ) 
          {$moy_pax_pab = 1 ;}
          else{$moy_pax_pab = $max_pab ;}
                            
          $max_cpue = $clcpue + $value->cpue_moyenne;

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
           $data[$i]['site_embarquement'] = $value->site_embarquement;
           $data[$i]['unite_peche_libelle']= $value->libelle;
           $data[$i]['nbr_unite_peche']= $value->nbr_unit_peche;
           $data[$i]['nbr_jrs_peche_mensuel_pab']=$nbr_jrs_peche_mensuel_pab;
           $data[$i]['cpue_moyenne']= $value->cpue_moyenne;
           $data[$i]['erreur_relative_90_cpue'] = $erreur_relative;
           $data[$i]['nombre_echantillon_cpue'] = $value->nombre_echantillon;
           $data[$i]['moy_pax_pab'] = $moy_pax_pab ;
           $data[$i]['max_cpue'] = $max_cpue;
           $data[$i]['jour_mensuel'] = $nbr_jour;
           $data[$i]['stDevCPUE'] = $ecart_type;
           $data[$i]['nbr_total_jrs_peche_mensuel']= $nbr_total_jrs_peche_mensuel;
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
                $requete = $requete." AND id_district='".$id_district."'" ;
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
                $requete = $requete." AND id_district='".$id_district."'" ;
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

    

}
/* End of file controllername.php */
/* Location: ./application/controllers/controllername.php */
?>