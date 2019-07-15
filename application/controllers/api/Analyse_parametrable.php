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
        $this->load->model('distribution_fractile_model', 'Distribution_fractileManager'); 
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
        $result = array() ;

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


                
                
             
//********Debut Requete L1.1 Annee Strate Majeur Capture Valeur********// 
            if ($pivot == "*") 
            {
                $indice = 0 ;
                $total_prix = 0 ;
                $total_capture = 0 ;
                $erreur_rel_capture=0;
                $erreur_relative=0;
            //-talou                 
                  /* $donnees=$this->Fiche_echantillonnage_captureManager->som_capture_totales($this->generer_requete_analyse($annee,$mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece));
                    if ($donnees[0]->capture != null )
                    {
                        $total_prix = $total_prix + $donnees[0]->prix ;
                        $total_capture = $total_capture + $donnees[0]->capture ;
                       

                        $donnees[0]->region = "-" ;
                        $donnees[0]->site_embarquement = "-" ;
                        $donnees[0]->unite_peche = "-" ;
                        $data[$indice] = $donnees[0] ;
                        $indice++ ;
                    }*/

                    /*$data['total_prix'] = $total_prix ;
                    $data['total_capture'] = $total_capture ;*/
                // talou-/

                // /-vaovao
                 $result =   $this->Fiche_echantillonnage_captureManager->erreur_relativepivotl1(
                            $this->generer_requete_analyse($annee,$mois,$id_region,$id_district,$id_site_embarquement, $id_unite_peche, $id_espece),
                            $this->generer_requete_analyse($annee,$mois,$id_region,$id_district,'*', $id_unite_peche, '*'));

                    if ($result != null )
                    {                        
                        $i=1;
                        $donnees=array();

                        foreach ($result as $key => $value)
                        {
                            $tab_capture_par_espece =   $this->Fiche_echantillonnage_captureManager->capture_total_par_espece(
                                                        $this->generer_requete_analyse($annee,$value->mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece),$value->id_reg,$value->id_unite,$value->mois);
                            
                            /*$value->capture_total_par_unite=$this->Fiche_echantillonnage_captureManager->capture_total_par_unite($this->generer_requete_analyse($annee,$value->mois, $id_region, $id_district, '*', $id_unite_peche, $id_espece),$value->id_reg,$value->id_unite,$value->mois);*/

                            $ecart_type = $this->Fiche_echantillonnage_captureManager->ecartypeAnalyse(
                                          $this->generer_requete_analyse($annee,$value->mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece),$value->id_reg,$value->id_unite,$value->mois,$value->id_site);

                            $date_t     = explode('-', $value->date) ;
                            $res        = mktime( 0, 0, 0, $date_t[1], 1, $date_t[0] );
                            $nbr_jour   = intval(date("t",$res));

                            $nbr_jrs_peche_mensuel_pab = $value->pab_moy * $nbr_jour;
                            
                            $captures_t = ($value->nbr_unit_peche * $nbr_jrs_peche_mensuel_pab * $value->cpue_moyenne);                            
                            
                            $distribution    = $this->Distribution_fractileManager->findByDegree($value->degree);
                            $tdistriburion90 = $distribution[0]->PercentFractile90 ;
                            $clcpue          = ($tdistriburion90 * $ecart_type) / $value->sqrt ;
                            $erreur_relative = ($clcpue / $value->cpue_moyenne ) * 100;

                            $distributionpab = $this->Distribution_fractileManager->findByDegree($value->degreepab);
                            $clpab           = ($distributionpab[0]->PercentFractile90 * $value->ecart_typepab) / $value->sqrtpab ;
                           $max_pab          = ($clpab + $value->pab_moy) ;
                            
                            if ($max_pab > 1 ) 
                            {$moy_pax_pab = 1 ;}
                            else{$moy_pax_pab = $max_pab ;}
                            
                            $max_cpue = $clcpue + $value->cpue_moyenne;

                            $nbr_total_jrs_peche_mensuel = $value->nbr_unit_peche * $nbr_jrs_peche_mensuel_pab;
                            $max_captures_totales = ($value->nbr_unit_peche * $moy_pax_pab * $nbr_jour * $max_cpue)/1000;

                            $cl_captures_totales = $max_captures_totales - $captures_t;
                            $erreur_relative_capture_total_90=1;
                            if ($captures_t)
                            {
                                $erreur_relative_capture_total_90 = ($cl_captures_totales / $captures_t) * 100;
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
                            $donnees[$i]['mois'] = $value->mois;
                            
                            $donnees[$i]['captures_t'] = $captures_t;
                            $donnees[$i]['tab_composition'] = $tab_composition;
                            $donnees[$i]['site'] = $value->id_site;
                            //$donnees[$i]['nbr_jrs_peche_mensuel_pab'] = $value->pab_moy;
                            
                            //$donnees[$i]['nbr_jrs_peche_mensuel_pab2'] = $pab[0]->pab_moy;         
                            /*$donnees[$i]['sqrt'] = $value->sqrt;
                            $donnees[$i]['sqrtpab'] = $value->sqrtpab;
                            $donnees[$i]['degree'] = $value->degree;
                            $donnees[$i]['degreepab'] = $value->degreepab;
                            $donnees[$i]['ecart_typepab'] = $value->ecart_typepab;*/
                            //$donnees[$i]['tab_capture_par_unit'] = $tab_capture_par_unit[0]->capture_total_par_espece;
                            $donnees[$i]['tab_capture_par_unit2'] = $value->capture_total_par_unite;
                            
                            $donnees[$i]['capture_total_par_espec'] = $tab_capture_par_espece;
                            $donnees[$i]['tab_capture_espece_totaldetai'] = $tab_capture_espece_total;


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
                // vaovao-/    
                    //$r=$this->generer_requete_analyse($annee,$mois,$id_region,$id_district,$id_site_embarquement, $id_unite_peche, $id_espece);
                    //$donnees['requet'] = $r ;
                   // $data[$indice] = $r; 
                }

            //Pivot * 
//********Fin Requete L1.1 Annee Strate Majeur Capture Valeur********//
 
//********Debut Requete L1.2 Annee Strate Mineur Capture Valeur********//         
            //Pivot region 
                if ($pivot == "id_region") 
                {
                    $indice = 0 ;
                    $total_prix = 0 ;
                    $total_capture = 0 ;
                    $erreur_rel_capture=0;
                    $erreur_relative=0;
                    $datadetail=array();
                    ///-debut talou
                   /* foreach ($all_region as $key_region => $value_region) 
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
                        
                    }*/
                    /*$data['total_prix'] = $total_prix ;
                    $data['total_capture'] = $total_capture ;*/

                    //fin talou-/
                     // /-vaovao
              $result =   $this->Fiche_echantillonnage_captureManager->erreur_relativepivotl1(
                            $this->generer_requete_analyse($annee,$mois,$id_region,$id_district,$id_site_embarquement, $id_unite_peche, $id_espece),
                            $this->generer_requete_analyse($annee,$mois,$id_region,$id_district,'*', $id_unite_peche, '*'));

                    if ($result != null )
                    {                        
                        $i=1;
                        $donnees=array();
                        $tab_region= array();
                        
                        foreach ($result as $key => $value)
                        {
                            $tab_capture_par_espece =   $this->Fiche_echantillonnage_captureManager->capture_total_par_espece(
                                                        $this->generer_requete_analyse($annee,$value->mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece),$value->id_reg,$value->id_unite,$value->mois);
                            
                            /*$value->capture_total_par_unite=$this->Fiche_echantillonnage_captureManager->capture_total_par_unite($this->generer_requete_analyse($annee,$value->mois, $id_region, $id_district, '*', $id_unite_peche, $id_espece),$value->id_reg,$value->id_unite,$value->mois);*/

                            $ecart_type = $this->Fiche_echantillonnage_captureManager->ecartypeAnalyse(
                                          $this->generer_requete_analyse($annee,$value->mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece),$value->id_reg,$value->id_unite,$value->mois,$value->id_site);

                            $date_t     = explode('-', $value->date) ;
                            $res        = mktime( 0, 0, 0, $date_t[1], 1, $date_t[0] );
                            $nbr_jour   = intval(date("t",$res));

                            $nbr_jrs_peche_mensuel_pab = $value->pab_moy * $nbr_jour;
                            
                            $captures_t = ($value->nbr_unit_peche * $nbr_jrs_peche_mensuel_pab * $value->cpue_moyenne);                            
                            
                            $distribution    = $this->Distribution_fractileManager->findByDegree($value->degree);
                            $tdistriburion90 = $distribution[0]->PercentFractile90 ;
                            $clcpue          = ($tdistriburion90 * $ecart_type) / $value->sqrt ;
                            $erreur_relative = ($clcpue / $value->cpue_moyenne ) * 100;

                            $distributionpab = $this->Distribution_fractileManager->findByDegree($value->degreepab);
                            $clpab           = ($distributionpab[0]->PercentFractile90 * $value->ecart_typepab) / $value->sqrtpab ;
                           $max_pab          = ($clpab + $value->pab_moy) ;
                            
                            if ($max_pab > 1 ) 
                            {$moy_pax_pab = 1 ;}
                            else{$moy_pax_pab = $max_pab ;}
                            
                            $max_cpue = $clcpue + $value->cpue_moyenne;

                            $nbr_total_jrs_peche_mensuel = $value->nbr_unit_peche * $nbr_jrs_peche_mensuel_pab;
                            $max_captures_totales = ($value->nbr_unit_peche * $moy_pax_pab * $nbr_jour * $max_cpue)/1000;

                            $cl_captures_totales = $max_captures_totales - $captures_t;
                            $erreur_relative_capture_total_90=1;
                            if ($captures_t)
                            {
                                $erreur_relative_capture_total_90 = ($cl_captures_totales / $captures_t) * 100;
                            }

                            $j=0;
                            $tab_capture_espece_total = array();
                            $prix_espece              = array();
                            $tab_composition          = array();
                            $exist=false;
                            
                          
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
                            $donnees['capture'] = $som_tab_capture_par_espece;

                            $total_prix_unite=array_sum($prix_espece);
                            $donnees['prix'] = $total_prix_unite/1000;

                            $donnees['erreur_rel_capture'] = $erreur_relative_capture_total_90;
                            $donnees['erreur_relative'] = $erreur_relative;
                            $donnees['nom_region'] = $value->nom_region;
                            //$donnees['mois'] = $value->mois;
                            
                            $donnees['captures_t'] = $captures_t;
                            $donnees['tab_composition'] = $tab_composition;
                            //$donnees['site'] = $value->id_site;
                            //$donnees['nbr_jrs_peche_mensuel_pab'] = $value->pab_moy;
                            
                            //$donnees['nbr_jrs_peche_mensuel_pab2'] = $pab[0]->pab_moy;         
                            $donnees['sqrt'] = $value->sqrt;
                            $donnees['sqrtpab'] = $value->sqrtpab;
                            $donnees['degree'] = $value->degree;
                            $donnees['degreepab'] = $value->degreepab;
                            $donnees['ecart_typepab'] = $value->ecart_typepab;
                            //$donnees['tab_capture_par_unit'] = $tab_capture_par_unit[0]->capture_total_par_espece;
                            $donnees['tab_capture_par_unit2'] = $value->capture_total_par_unite;
                            
                            $donnees['capture_total_par_espec'] = $tab_capture_par_espece;
                            $donnees['tab_capture_espece_totaldetai'] = $tab_capture_espece_total;

                            $donnees['total_espece'] = $tab_capture_par_espece;
                            
                                      
                                        foreach ($tab_region as $k => $valueRegion)
                                        {
                                           if ($valueRegion['nom_region']==$value->nom_region)
                                                {                                                    
                                                    $exist=true;
                                                }
                                        }
                         
                            if($exist==false)
                            { 
                                array_push($tab_region, array('nom_region'=>$value->nom_region));  
                            }
                            $donnees['region'] = $tab_region;
                            $datadetail[$i] = $donnees;
                            
                          $i++;  
                        }
                       
                        $n=0;
                        
                        foreach ($tab_region as $keyunite => $valueunite) {
                            $valparregion=array();
                            $capture=0;
                            $prix=0;
                            $erreur_rel=0;
                            $erreur_rel_capt=0;
                            $c=0;
                            foreach ($datadetail as $keyd => $valued) {
                                if ($valued['nom_region']==$valueunite['nom_region']) {
                                   $valparregion['region']=$valueunite['nom_region'];
                                   $capture+=$valued['capture'];
                                   $prix+=$valued['prix'];
                                   $erreur_rel+=$valued['erreur_relative'];
                                   $erreur_rel_capt+=$valued['erreur_rel_capture'];
                                   $valparregion['capture']=$capture;
                                   $valparregion['prix']=$prix;
                                   $c++;
                                }                                
                            }

                         $valparregion['erreur_relative'] = number_format ( $erreur_rel/$c,0);
                         $valparregion['erreur_rel_capture'] = number_format ( $erreur_rel_capt/$c,0);
                         $data[$n] = $valparregion;
                         $n++;
                        }
                        
                        $Total_captur  = array_column($data, 'capture');
                        $total_capture = array_sum($Total_captur);

                        $Total_pri     = array_column($data, 'prix');
                        $total_prix    = array_sum($Total_pri);

                      $erreur_capture = array_column($datadetail, 'erreur_rel_capture');
                        $n_capture    = count($erreur_capture);
                        $erreur_rel_capture= number_format ( array_sum($erreur_capture)/$n_capture,0);

                        $erreur = array_column($datadetail, 'erreur_relative');
                        $n      = count($erreur);
                        $erreur_relative = number_format ( array_sum($erreur)/$n,0);
                    }
                // vaovao-/ 
                }
            //Pivot region

//********Fin Requete L1.2 Annee Strate Mineur Capture Valeur********//
           
//********Debut Requete L2.1&L2.2 Annee Strate Capture Valeur********//
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
//********Fin Requete L2.1&L2.2 Annee Strate Capture Valeur********//

//********Debut Requete L2.3 Annee Strate Unité de pêche Capture Valeur********//           
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

//********Fin Requete L2.3 Annee Strate Unité de pêche Capture Valeur********//
            
//********Debut Requete L4.1 Annee Strate Espèce Capture Valeur********//
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

//********Fin Requete L4.1 Annee Strate Espèce Capture Valeur********// 

//********Debut Requete L2.4 Annee Strate Mineur Unité de pêche Capture Valeur********//
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

//********Fin Requete L2.4 Annee Strate Mineur Unité de pêche Capture Valeur********//


//****Debut Requete L2.5 Annee mois Site de débarquement Unité de pêche Capture Valeur******//

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

//****Fin Requete L2.5 Annee mois Site de débarquement Unité de pêche Capture Valeur******//


//****Debut Requete L1.3&L1.6 Annee Unité de pêche Capture Valeur******//             
            
            //Pivot unite peche 
                if ($pivot == "id_unite_peche") 
                {
                    $indice = 0 ;
                    $total_prix = 0 ;
                    $total_capture = 0 ;
                    $erreur_rel_capture=0;
                    $erreur_relative=0;
                    $datadetail=array();
                    ///-debut talou
                   /* foreach ($all_unite_peche as $key => $value) 
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
                        
                    }*/
                   /* $data['total_prix'] = $total_prix ;
                    $data['total_capture'] = $total_capture ;*/

                    //fin talou-/
                     // /-vaovao
                $result =   $this->Fiche_echantillonnage_captureManager->erreur_relativepivotl1(
                            $this->generer_requete_analyse($annee,$mois,$id_region,$id_district,$id_site_embarquement, $id_unite_peche, $id_espece),
                            $this->generer_requete_analyse($annee,$mois,$id_region,$id_district,'*', $id_unite_peche, '*'));

                    if ($result != null )
                    {                        
                        $i=1;
                        $donnees=array();
                        $tab_unite = array();
                        
                        foreach ($result as $key => $value)
                        {
                            $tab_capture_par_espece =   $this->Fiche_echantillonnage_captureManager->capture_total_par_espece(
                                                        $this->generer_requete_analyse($annee,$value->mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece),$value->id_reg,$value->id_unite,$value->mois);
                            
                            /*$value->capture_total_par_unite=$this->Fiche_echantillonnage_captureManager->capture_total_par_unite($this->generer_requete_analyse($annee,$value->mois, $id_region, $id_district, '*', $id_unite_peche, $id_espece),$value->id_reg,$value->id_unite,$value->mois);*/

                            $ecart_type = $this->Fiche_echantillonnage_captureManager->ecartypeAnalyse(
                                          $this->generer_requete_analyse($annee,$value->mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece),$value->id_reg,$value->id_unite,$value->mois,$value->id_site);

                            $date_t     = explode('-', $value->date) ;
                            $res        = mktime( 0, 0, 0, $date_t[1], 1, $date_t[0] );
                            $nbr_jour   = intval(date("t",$res));

                            $nbr_jrs_peche_mensuel_pab = $value->pab_moy * $nbr_jour;
                            
                            $captures_t = ($value->nbr_unit_peche * $nbr_jrs_peche_mensuel_pab * $value->cpue_moyenne);                            
                            
                            $distribution    = $this->Distribution_fractileManager->findByDegree($value->degree);
                            $tdistriburion90 = $distribution[0]->PercentFractile90 ;
                            $clcpue          = ($tdistriburion90 * $ecart_type) / $value->sqrt ;
                            $erreur_relative = ($clcpue / $value->cpue_moyenne ) * 100;

                            $distributionpab = $this->Distribution_fractileManager->findByDegree($value->degreepab);
                            $clpab           = ($distributionpab[0]->PercentFractile90 * $value->ecart_typepab) / $value->sqrtpab ;
                           $max_pab          = ($clpab + $value->pab_moy) ;
                            
                            if ($max_pab > 1 ) 
                            {$moy_pax_pab = 1 ;}
                            else{$moy_pax_pab = $max_pab ;}
                            
                            $max_cpue = $clcpue + $value->cpue_moyenne;

                            $nbr_total_jrs_peche_mensuel = $value->nbr_unit_peche * $nbr_jrs_peche_mensuel_pab;
                            $max_captures_totales = ($value->nbr_unit_peche * $moy_pax_pab * $nbr_jour * $max_cpue)/1000;

                            $cl_captures_totales = $max_captures_totales - $captures_t;
                            $erreur_relative_capture_total_90=1;
                            if ($captures_t)
                            {
                                $erreur_relative_capture_total_90 = ($cl_captures_totales / $captures_t) * 100;
                            }

                            $j=0;
                            $tab_capture_espece_total = array();
                            $prix_espece              = array();
                            $tab_composition          = array();
                            $ex=false;
                            
                          
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
                            $donnees['capture'] = $som_tab_capture_par_espece;

                            $total_prix_unite=array_sum($prix_espece);
                            $donnees['prix'] = $total_prix_unite/1000;

                            $donnees['erreur_rel_capture'] = $erreur_relative_capture_total_90;
                            $donnees['erreur_relative'] = $erreur_relative;
                            $donnees['unite_peche'] = $value->libelle;
                            //$donnees['mois'] = $value->mois;
                            
                            $donnees['captures_t'] = $captures_t;
                            $donnees['tab_composition'] = $tab_composition;
                            //$donnees['site'] = $value->id_site;
                            //$donnees['nbr_jrs_peche_mensuel_pab'] = $value->pab_moy;
                            
                            //$donnees['nbr_jrs_peche_mensuel_pab2'] = $pab[0]->pab_moy;         
                            $donnees['sqrt'] = $value->sqrt;
                            $donnees['sqrtpab'] = $value->sqrtpab;
                            $donnees['degree'] = $value->degree;
                            $donnees['degreepab'] = $value->degreepab;
                            $donnees['ecart_typepab'] = $value->ecart_typepab;
                            //$donnees['tab_capture_par_unit'] = $tab_capture_par_unit[0]->capture_total_par_espece;
                            $donnees['tab_capture_par_unit2'] = $value->capture_total_par_unite;
                            
                            $donnees['capture_total_par_espec'] = $tab_capture_par_espece;
                            $donnees['tab_capture_espece_totaldetai'] = $tab_capture_espece_total;

                            $donnees['total_espece'] = $tab_capture_par_espece;
                            
                                      
                                        foreach ($tab_unite as $k => $valueUnite)
                                        {
                                           if ($valueUnite['unite_peche']==$value->libelle)
                                                {                                                    
                                                    $ex=true;
                                                }
                                        }
                         
                            if($ex==false)
                            { 
                                array_push($tab_unite, array('unite_peche'=>$value->libelle));  
                            }
                            $donnees['unit'] = $tab_unite;
                            $datadetail[$i] = $donnees;
                            
                          $i++;  
                        }
                        $valu=array();
                        $n=0;
                        
                        foreach ($tab_unite as $keyunite => $valueunite) {
                            $valparunite=array();
                            $capture=0;
                            $prix=0;
                            $erreur_rel=0;
                            $erreur_rel_capt=0;
                            $c=0;
                            foreach ($datadetail as $keyd => $valued) {
                                if ($valued['unite_peche']==$valueunite['unite_peche']) {
                                   $valparunite['unite_peche']=$valueunite['unite_peche'];
                                   $capture+=$valued['capture'];
                                   $prix+=$valued['prix'];
                                   $erreur_rel+=$valued['erreur_relative'];
                                   $erreur_rel_capt+=$valued['erreur_rel_capture'];
                                   $valparunite['capture']=$capture;
                                   $valparunite['prix']=$prix;
                                   $c++;
                                }                                
                            }

                         $valparunite['erreur_relative'] = number_format ( $erreur_rel/$c,0);
                         $valparunite['erreur_rel_capture'] = number_format ( $erreur_rel_capt/$c,0);
                         $data[$n] = $valparunite;
                         $n++;
                        }
                        
                        $Total_captur  = array_column($data, 'capture');
                        $total_capture = array_sum($Total_captur);

                        $Total_pri     = array_column($data, 'prix');
                        $total_prix    = array_sum($Total_pri);

                      $erreur_capture = array_column($datadetail, 'erreur_rel_capture');
                        $n_capture    = count($erreur_capture);
                        $erreur_rel_capture= number_format ( array_sum($erreur_capture)/$n_capture,0);

                        $erreur = array_column($datadetail, 'erreur_relative');
                        $n      = count($erreur);
                        $erreur_relative = number_format ( array_sum($erreur)/$n,0);
                    }
                // vaovao-/ 
                }
            //Pivot unite peche 

//****Fin Requete L1.3&L1.6 Annee Unité de pêche Capture Valeur******//

//****Debut Requete Site de débarquemet******//
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

//****Fin Requete Site de débarquemet******//

//****Debut Requete L1.4 Annee Strate Mineur Unité de pêche Capture Valeur******//

            //Pivot region  and unite peche
                if ($pivot == "id_region_and_id_unite_peche") 
                {
                    $indice = 0 ;
                    $total_prix = 0 ;
                    $total_capture = 0 ;
                    $erreur_rel_capture=0;
                    $erreur_relative=0;
                    $datadetail=array();

                    ///-debut talou
                   /* foreach ($all_region as $key_region => $value_region) 
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
                        
                    }*/
                    //fin talou-/
                    
                     // /-vaovao
                $result =   $this->Fiche_echantillonnage_captureManager->erreur_relativepivotl1(
                            $this->generer_requete_analyse($annee,$mois,$id_region,$id_district,$id_site_embarquement, $id_unite_peche, $id_espece),
                            $this->generer_requete_analyse($annee,$mois,$id_region,$id_district,'*', $id_unite_peche, '*'));

                    if ($result != null )
                    {                        
                        $i=1;
                        $donnees=array();
                        $tab_unite = array();
                        $tab_region = array();
                        foreach ($result as $key => $value)
                        {
                            $tab_capture_par_espece =   $this->Fiche_echantillonnage_captureManager->capture_total_par_espece(
                                                        $this->generer_requete_analyse($annee,$value->mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece),$value->id_reg,$value->id_unite,$value->mois);
                            
                            /*$value->capture_total_par_unite=$this->Fiche_echantillonnage_captureManager->capture_total_par_unite($this->generer_requete_analyse($annee,$value->mois, $id_region, $id_district, '*', $id_unite_peche, $id_espece),$value->id_reg,$value->id_unite,$value->mois);*/

                            $ecart_type = $this->Fiche_echantillonnage_captureManager->ecartypeAnalyse(
                                          $this->generer_requete_analyse($annee,$value->mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece),$value->id_reg,$value->id_unite,$value->mois,$value->id_site);

                            $date_t     = explode('-', $value->date) ;
                            $res        = mktime( 0, 0, 0, $date_t[1], 1, $date_t[0] );
                            $nbr_jour   = intval(date("t",$res));

                            $nbr_jrs_peche_mensuel_pab = $value->pab_moy * $nbr_jour;
                            
                            $captures_t = ($value->nbr_unit_peche * $nbr_jrs_peche_mensuel_pab * $value->cpue_moyenne);                            
                            
                            $distribution    = $this->Distribution_fractileManager->findByDegree($value->degree);
                            $tdistriburion90 = $distribution[0]->PercentFractile90 ;
                            $clcpue          = ($tdistriburion90 * $ecart_type) / $value->sqrt ;
                            $erreur_relative = ($clcpue / $value->cpue_moyenne ) * 100;

                            $distributionpab = $this->Distribution_fractileManager->findByDegree($value->degreepab);
                            $clpab           = ($distributionpab[0]->PercentFractile90 * $value->ecart_typepab) / $value->sqrtpab ;
                           $max_pab          = ($clpab + $value->pab_moy) ;
                            
                            if ($max_pab > 1 ) 
                            {$moy_pax_pab = 1 ;}
                            else{$moy_pax_pab = $max_pab ;}
                            
                            $max_cpue = $clcpue + $value->cpue_moyenne;

                            $nbr_total_jrs_peche_mensuel = $value->nbr_unit_peche * $nbr_jrs_peche_mensuel_pab;
                            $max_captures_totales = ($value->nbr_unit_peche * $moy_pax_pab * $nbr_jour * $max_cpue)/1000;

                            $cl_captures_totales = $max_captures_totales - $captures_t;
                            $erreur_relative_capture_total_90=1;
                            if ($captures_t)
                            {
                                $erreur_relative_capture_total_90 = ($cl_captures_totales / $captures_t) * 100;
                            }

                            $j=0;
                            $tab_capture_espece_total = array();
                            $prix_espece              = array();
                            $tab_composition          = array();
                            $existunite=false;
                            $existregion=false;
                          
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
                            $donnees['capture'] = $som_tab_capture_par_espece;

                            $total_prix_unite=array_sum($prix_espece);
                            $donnees['prix'] = $total_prix_unite/1000;

                            $donnees['erreur_rel_capture'] = $erreur_relative_capture_total_90;
                            $donnees['erreur_relative'] = $erreur_relative;
                            $donnees['unite_peche'] = $value->libelle;
                            $donnees['nom_region'] = $value->nom_region;
                            $donnees['capture_total_par_espec'] = $tab_capture_par_espece;
                            $donnees['tab_capture_espece_totaldetai'] = $tab_capture_espece_total;

                            $donnees['total_espece'] = $tab_capture_par_espece;
                            
                                      
                                        foreach ($tab_unite as $k => $valueUnite)
                                        {
                                           if ($valueUnite['unite_peche']==$value->libelle)
                                                {                                                    
                                                    $existunite=true;
                                                }
                                        }
                         
                            if($existunite==false)
                            { 
                                array_push($tab_unite, array('unite_peche'=>$value->libelle));  
                            }
                                        foreach ($tab_region as $k => $valueRegion)
                                        {
                                           if ($valueRegion['nom_region']==$value->nom_region)
                                                {                                                    
                                                    $existregion=true;
                                                }
                                        }
                         
                            if($existregion==false)
                            { 
                                array_push($tab_region, array('nom_region'=>$value->nom_region));  
                            }
                            $donnees['region'] = $tab_region;
                            $donnees['unit'] = $tab_unite;
                            $datadetail[$i] = $donnees;
                            
                          $i++;  
                        }

                        $n=0;
                        foreach ($tab_region as $keyregion => $valueregion)
                        {
                            foreach ($tab_unite as $keyunite => $valueunite)
                            {
                                $valparuniteregion=array();
                                $capture=0;
                                $prix=0;
                                $erreur_rel=0;
                                $erreur_rel_capt=0;
                                $c=0;
                                foreach ($datadetail as $keyd => $valued)
                                {
                                    if ($valued['unite_peche']==$valueunite['unite_peche'] && $valued['nom_region']==$valueregion['nom_region'])
                                    {
                                       $valparuniteregion['unite_peche']=$valueunite['unite_peche'];
                                       $valparuniteregion['region']=$valueregion['nom_region'];
                                       $capture+=$valued['capture'];
                                       $prix+=$valued['prix'];
                                       $erreur_rel+=$valued['erreur_relative'];
                                       $erreur_rel_capt+=$valued['erreur_rel_capture'];
                                       $valparuniteregion['capture']=$capture;
                                       $valparuniteregion['prix']=$prix;
                                       $c++;
                                    }                                
                                }

                             $valparuniteregion['erreur_relative'] = number_format ( $erreur_rel/$c,0);
                             $valparuniteregion['erreur_rel_capture'] = number_format ( $erreur_rel_capt/$c,0);
                             $data[$n] = $valparuniteregion;
                             $n++;
                            }
                        }
                        
                        
                        $Total_captur  = array_column($data, 'capture');
                        $total_capture = array_sum($Total_captur);

                        $Total_pri     = array_column($data, 'prix');
                        $total_prix    = array_sum($Total_pri);

                      $erreur_capture = array_column($datadetail, 'erreur_rel_capture');
                        $n_capture    = count($erreur_capture);
                        $erreur_rel_capture= number_format ( array_sum($erreur_capture)/$n_capture,0);

                        $erreur = array_column($datadetail, 'erreur_relative');
                        $n      = count($erreur);
                        $erreur_relative = number_format ( array_sum($erreur)/$n,0);
                    }
                // vaovao-/ 
                }
            //Pivot region and unite peche

//****Fin Requete L1.4 Annee Strate Mineur Unité de pêche Capture Valeur******//

//****Debut Requete L1.5 Annee Site de débarquement Unité de pêche Capture Valeur******//
            //Pivot site  and unite peche
                if ($pivot == "id_site_embarquement_and_id_unite_peche") 
                {
                    $indice = 0 ;
                    $total_prix = 0 ;
                    $total_capture = 0 ;
                    $erreur_rel_capture=0;
                    $erreur_relative=0;

                    //-talou
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
                    $data['total_prix'] = $total_prix ;
                    $data['total_capture'] = $total_capture ;
                // talou-/

                // /-vaovao
               /*  $result =   $this->Fiche_echantillonnage_captureManager->erreur_relativepivotl1(
                            $this->generer_requete_analyse($annee,$mois,$id_region,$id_district,$id_site_embarquement, $id_unite_peche, $id_espece),
                            $this->generer_requete_analyse($annee,$mois,$id_region,$id_district,'*', $id_unite_peche, '*'));

                    if ($result != null )
                    {                        
                        $i=1;
                        $data=array();

                        foreach ($result as $key => $value)
                        {
                            $tab_capture_par_espece =   $this->Fiche_echantillonnage_captureManager->capture_total_par_espece(
                                                        $this->generer_requete_analyse($annee,$value->mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece),$value->id_reg,$value->id_unite,$value->mois);
                            $ecart_type = $this->Fiche_echantillonnage_captureManager->ecartypeAnalyse(
                                          $this->generer_requete_analyse($annee,$value->mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece),$value->id_reg,$value->id_unite,$value->mois,$value->id_site);

                            $date_t     = explode('-', $value->date) ;
                            $res        = mktime( 0, 0, 0, $date_t[1], 1, $date_t[0] );
                            $nbr_jour   = intval(date("t",$res));

                            $nbr_jrs_peche_mensuel_pab = $value->pab_moy * $nbr_jour;
                            
                            $captures_t = ($value->nbr_unit_peche * $nbr_jrs_peche_mensuel_pab * $value->cpue_moyenne)/1000;                            
                            
                            $distribution    = $this->Distribution_fractileManager->findByDegree($value->degree);
                            $tdistriburion90 = $distribution[0]->PercentFractile90 ;
                            $clcpue          = ($tdistriburion90 * $ecart_type) / $value->sqrt ;
                            $erreur_relative = ($clcpue / $value->cpue_moyenne ) * 100;

                            $distributionpab = $this->Distribution_fractileManager->findByDegree($value->degreepab);
                            $clpab           = ($distributionpab[0]->PercentFractile90 * $value->ecart_typepab) / $value->sqrtpab ;
                           $max_pab          = ($clpab + $value->pab_moy) ;
                            
                            if ($max_pab > 1 ) 
                            {$moy_pax_pab = 1 ;}
                            else{$moy_pax_pab = $max_pab ;}
                            
                            $max_cpue = $clcpue + $value->cpue_moyenne;

                            $nbr_total_jrs_peche_mensuel = $value->nbr_unit_peche * $nbr_jrs_peche_mensuel_pab;
                            $max_captures_totales = ($value->nbr_unit_peche * $moy_pax_pab * $nbr_jour * $max_cpue)/1000;

                            $cl_captures_totales = $max_captures_totales - $captures_t;
                            $erreur_relative_capture_total_90=1;
                            if ($captures_t)
                            {
                                $erreur_relative_capture_total_90 = ($cl_captures_totales / $captures_t) * 100;
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
                            $data[$i]['capture'] = $som_tab_capture_par_espece;

                            $total_prix_unite=array_sum($prix_espece);
                            $data[$i]['prix'] = $total_prix_unite;

                            $data[$i]['erreur_relative_capture_total_90'] = $erreur_relative_capture_total_90;
                            $data[$i]['erreur_relative'] = $erreur_relative;
                            $data[$i]['unite_peche'] = $value->libelle;
                            $data[$i]['site_embarquement'] = $value->site_embarquement;
                            
                            $data[$i]['captures_t'] = $captures_t;
                            $data[$i]['tab_composition'] = $tab_composition;
                            $data[$i]['site'] = $value->id_site;
                            $data[$i]['tab_capture_par_unit2'] = $value->capture_total_par_unite;
                            
                            $data[$i]['capture_total_par_espec'] = $tab_capture_par_espece;
                            $data[$i]['tab_capture_espece_totaldetai'] = $tab_capture_espece_total;


                          $i++;  
                        }
                        $Total_captur  = array_column($data, 'capture');
                        $total_capture = array_sum($Total_captur);

                        $Total_pri     = array_column($data, 'prix');
                        $total_prix    = array_sum($Total_pri);

                        $erreur_capture = array_column($data, 'erreur_relative_capture_total_90');
                        $n_capture      = count($erreur_capture);
                        $erreur_rel_capture = number_format ( array_sum($erreur_capture)/$n_capture ,0);

                        $erreur = array_column($data, 'erreur_relative');
                        $n      = count($erreur);
                        $erreur_relative = number_format ( array_sum($erreur)/$n,0);
                        
                      $indice++ ; 
                      } */
                }
            //Pivot site and unite peche

//****Debut Requete L1.5 Annee Site de débarquement Unité de pêche Capture Valeur******//

//****Debut Requete L3.1&L3.2 Annee Strate Espèce Capture Valeur******//
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
//****Debut Requete L3.1&L3.2 Annee Strate Espèce Capture Valeur******//

                $total['total_prix'] = $total_prix ;
                    $total['total_capture'] = $total_capture ;
                   // $total['erreur_relative_capture_total'] = $erreur_rel_capture ;
                   // $total['erreur_relative_total'] = $erreur_relative ;

        }
        
        
        //********************************* fin Nombre echantillon *****************************
        if (count($data)>0) {
            $this->response([
                'status' => TRUE,
                'response' => $data,
                'total' => $total,
               // 'd' => $d,
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