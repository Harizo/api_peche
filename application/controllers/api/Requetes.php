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
                    $data = $this->requete_8($sites_par_region,$all_unite_peche,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece) ;
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


    public function requete_8($site_embarquements, $unite_peches,$pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche,$id_espece)
    {
      $pab = $this->Fiche_echantillonnage_captureManager->req_8($this->generer_requete($pivot, $date, $annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece));

      if ($pab) 
      {
        /*foreach ($pab as $key => $value) 
        {
          $data[$key]['capture_par_espece'] = $value->capture_par_espece ;
          $data[$key]['libelle_unite_peche'] = $value->libelle_unite_peche ;
          $data[$key]['id_unite_peche'] = $value->id_unite_peche ;
          $data[$key]['id_site'] = $value->id_site ;
          $data[$key]['code'] = $value->code ;
          $data[$key]['prix_unitaire_total'] = $value->prix_unitaire_total ;
          $data[$key]['prix_unitaire_moyenne'] = $value->prix_unitaire_moyenne ;
          $data[$key]['mois'] = $value->mois ;
          $data[$key]['date'] = $value->date ;
          $data[$key]['annee'] = $value->annee ;


          $data[$key]['sqrt'] = $value->sqrt ;
          $data[$key]['degree'] = $value->degree ;
          $data[$key]['cpue_moyenne'] = $value->cpue_moyenne ;
          $data[$key]['libelle_site'] = $value->libelle_site ;
          $pab_moy = $value->pab_moy ;
          $data[$key]['pab_moy'] = $pab_moy ;
          $data[$key]['nbr_echantillon'] = $value->nbr_echantillon ;
         



          $data[$key]['id_region'] = $value->id_region ;
          $obj= $this->Fiche_echantillonnage_captureManager->pour_7_3($value->mois, $value->annee, $value->id_unite_peche, $value->id_region) ;
          $capture_total = $obj[0]->capture_total ;
          $data[$key]['capture_total'] = $capture_total ;
          $data[$key]['composition_espece'] = ($value->capture_par_espece / $capture_total) * 100 ;

          $res = mktime( 0, 0, 0, $value->mois, 1, $value->annee ); 
          $nbr_jour = intval(date("t",$res)) ;

          $nbr_jrs_peche_mens = $pab_moy * $nbr_jour ;
          $data[$key]['nbr_jrs_peche_mens'] = $nbr_jrs_peche_mens ;

          $enquete_cadre = $this->Enquete_cadreManager->findBy_id_site_id_unite_peche_annee($value->id_site, $value->id_unite_peche, $value->annee);
          $nbr_unite_peche = $enquete_cadre->nbr_unite_peche ;

          $data[$key]['nbr_total_jrs_peche_mens'] = $nbr_jrs_peche_mens * $nbr_unite_peche ;

          $distribution = $this->Distribution_fractileManager->findByDegree($value->degree);
          $tdistriburion90 = $distribution[0]->PercentFractile90 ;
          $data[$key]['distribution_90'] = $tdistriburion90 ;
          
        }*/
      }
      else
      {
        $data = array();
      }

      return $pab ;
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



    


    

}
/* End of file controllername.php */
/* Location: ./application/controllers/controllername.php */
?>