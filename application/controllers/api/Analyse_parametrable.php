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
               // $all_region = $this->RegionManager->findAll();
                $all_region = $this->RegionManager->findAllInTable($annee);
            }

            if(($id_unite_peche!='*')&&($id_unite_peche!='undefined'))
            {
                $all_unite_peche = $this->Unite_pecheManager->findByIdtab($id_unite_peche);
            }
            else
            {
                //$all_unite_peche=$this->Unite_pecheManager->findAll();
                $all_unite_peche=$this->Unite_pecheManager->findAllInTable($annee);
            }

            if(($id_site_embarquement!='*')&&($id_site_embarquement!='undefined'))
            {
                $all_site_embarquement = $this->Site_embarquementManager->findByIdtab($id_site_embarquement);
            }
            else
            {
                //$all_site_embarquement=$this->Site_embarquementManager->findAllByFiche($annee);

                $all_site_embarquement=$this->Site_embarquementManager->findAllInTable($annee);
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
                                                       
                            
                            
                            $som_tab_capture_par_espece =array_sum($tab_capture_espece_total);
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
                foreach ($all_region as $kRegion => $vRegion) {
                   $result =   $this->Fiche_echantillonnage_captureManager->erreur_relativepivotl1(
                            $this->generer_requete_analyse($annee,$mois,$vRegion->id,$id_district,$id_site_embarquement, $id_unite_peche, $id_espece),
                            $this->generer_requete_analyse($annee,$mois,$vRegion->id,$id_district,'*', $id_unite_peche, '*'));

                    if ($result != null )
                    {                        
                        $i=1;
                        $donnees=array();

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
                                                       
                            
                            
                            $som_tab_capture_par_espece =array_sum($tab_capture_espece_total);
                            $donnees[$i]['Total_capture_unite'] = $som_tab_capture_par_espece;

                            $total_prix_unite=array_sum($prix_espece);
                            $donnees[$i]['total_prix_unite'] = $total_prix_unite;
                            $donnees[$i]['erreur_relative_capture_total_90'] = $erreur_relative_capture_total_90;
                            $donnees[$i]['erreur_relative'] = $erreur_relative;
                            $donnees[$i]['unite_peche'] = $value->libelle;

                            $donnees[$i]['espece'] = $tab_capture_espece_total;
                          $i++;  
                        }
                        $Total_captureRegion  = array_column($donnees, 'Total_capture_unite');
                        $total_captureRegion = array_sum($Total_captureRegion);

                        $Total_prixRegion     = array_column($donnees, 'total_prix_unite');
                        $total_prixRegion    = array_sum($Total_prixRegion)/1000;

                        $erreur_captureRegion = array_column($donnees, 'erreur_relative_capture_total_90');
                        $n_captureRegion      = count($erreur_captureRegion);
                        $erreur_rel_captureRegion = number_format ( array_sum($erreur_captureRegion)/$n_captureRegion ,0);

                        $erreurRegion = array_column($donnees, 'erreur_relative');
                        $nRegion      = count($erreurRegion);
                        $erreur_relativeRegion = number_format ( array_sum($erreurRegion)/$nRegion,0);

                        $data[$indice]['region'] = $vRegion->nom;
                        $data[$indice]['capture'] = $total_captureRegion;
                        $data[$indice]['prix']    = $total_prixRegion;
                        $data[$indice]['erreur_relative']    = $erreur_relativeRegion;
                        $data[$indice]['erreur_rel_capture'] = $erreur_rel_captureRegion;
                        $data[$indice]['Donnee'] = $donnees;                        
                      $indice++ ;  
                    }
                }
                $Total_capture  = array_column($data, 'capture');
                $total_capture = array_sum($Total_capture);

                $Total_prix     = array_column($data, 'prix');
                $total_prix    = array_sum($Total_prix);

                $erreur_capture = array_column($data, 'erreur_rel_capture');
                $n_capture      = count($erreur_capture);
                $erreur_rel_capture = number_format ( array_sum($erreur_capture)/$n_capture ,0);

                $erreur = array_column($data, 'erreur_relative');
                $n      = count($erreur);
                $erreur_relative = number_format ( array_sum($erreur)/$n,0);
                // vaovao-/ 
                }
            //Pivot region

//********Fin Requete L1.2 Annee Strate Mineur Capture Valeur********//

//****Debut Requete L1.3&L1.6 Annee Unité de pêche Capture Valeur******//             
            
            //Pivot unite peche 
             if ($pivot == "id_unite_peche") 
            {
                $indice = 0 ;
                $total_prix = 0 ;
                $total_capture = 0 ;
                $erreur_rel_capture=0;
                $erreur_relative=0;
                $n_erreur_relative=0;
                $n_erreur_capture=0;
                foreach ($all_unite_peche as $kUnite_peche => $vUnite_peche) {
                   $result =   $this->Fiche_echantillonnage_captureManager->erreur_relativepivotl1(
                            $this->generer_requete_analyse($annee,$mois,$id_region,$id_district,$id_site_embarquement, $vUnite_peche->id, $id_espece),
                            $this->generer_requete_analyse($annee,$mois,$id_region,$id_district,'*', $vUnite_peche->id, '*'));

                   
                    if ($result != null )
                    {                        
                        $i=1;
                        $donnees=array();
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
                                                       
                            
                            
                            $som_tab_capture_par_espece =array_sum($tab_capture_espece_total);
                            $donnees[$i]['Total_capture_unite'] = $som_tab_capture_par_espece;

                            $total_prix_unite=array_sum($prix_espece);
                            $donnees[$i]['total_prix_unite'] = $total_prix_unite;
                            $donnees[$i]['erreur_relative_capture_total_90'] = $erreur_relative_capture_total_90;
                            $donnees[$i]['erreur_relative'] = $erreur_relative;
                            $donnees[$i]['unite_peche'] = $value->libelle;
                            $donnees[$i]['unite_pech2'] = $vUnite_peche->libelle;
                            $donnees[$i]['espece'] = $tab_capture_espece_total;

                          $i++;  
                        }
                        $Total_captureUnite_peche  = array_column($donnees, 'Total_capture_unite');
                        $total_captureUnite_peche = array_sum($Total_captureUnite_peche);

                        $Total_prixUnite_peche     = array_column($donnees, 'total_prix_unite');
                        $total_prixUnite_peche    = array_sum($Total_prixUnite_peche)/1000;

                        $erreur_captureUnite_peche = array_column($donnees, 'erreur_relative_capture_total_90');
                        $n_captureUnite_peche      = count($erreur_captureUnite_peche);
                        $erreur_rel_captureUnite_peche = number_format ( array_sum($erreur_captureUnite_peche)/$n_captureUnite_peche ,0);

                        $erreurUnite_peche = array_column($donnees, 'erreur_relative');
                        $nUnite_peche      = count($erreurUnite_peche);
                        $erreur_relativeUnite_peche = number_format ( array_sum($erreurUnite_peche)/$nUnite_peche,0);
                       
                        $data[$indice]['unite_peche'] = $vUnite_peche->libelle;
                        $data[$indice]['capture'] = $total_captureUnite_peche;
                        $data[$indice]['prix']    = $total_prixUnite_peche;
                        $data[$indice]['erreur_relative']    = $erreur_relativeUnite_peche;
                        $data[$indice]['erreur_rel_capture'] = $erreur_rel_captureUnite_peche;
                        $data[$indice]['Donnee'] = $donnees;
                        if($erreur_relativeUnite_peche)
                        {
                            $n_erreur_relative++;
                        } 
                        if($erreur_rel_captureUnite_peche)
                        {
                            $n_erreur_capture++;
                        }                       
                      $indice++ ;  
                    }
                }
                $Total_capture  = array_column($data, 'capture');
                $total_capture = array_sum($Total_capture);

                $Total_prix     = array_column($data, 'prix');
                $total_prix    = array_sum($Total_prix);

                $erreur_capture = array_column($data, 'erreur_rel_capture');
                //$n_capture      = count($erreur_capture);
                $erreur_rel_capture = number_format ( array_sum($erreur_capture)/$n_erreur_capture ,0);

                $erreur = array_column($data, 'erreur_relative');
                //$n      = count($erreur);
                $erreur_relative = number_format ( array_sum($erreur)/$n_erreur_relative,0);
                // vaovao-/ 
                }
            //Pivot unite peche 

//****Fin Requete L1.3&L1.6 Annee Unité de pêche Capture Valeur******//

//****Debut Requete L1.4 Annee Strate Mineur Unité de pêche Capture Valeur******//

            //Pivot region  and unite peche
            if ($pivot == "id_region_and_id_unite_peche") 
            {
                $indice = 0 ;
                $total_prix = 0 ;
                $total_capture = 0 ;
                $erreur_rel_capture=0;
                $erreur_relative=0;
                $n_erreur_relative=0;
                $n_erreur_capture=0;
                foreach ($all_region as $kRegion => $vRegion )
                {
                    foreach ($all_unite_peche as $kUnite_peche => $vUnite_peche)
                    {
                       $result =   $this->Fiche_echantillonnage_captureManager->erreur_relativepivotl1(
                                $this->generer_requete_analyse($annee,$mois,$vRegion->id,$id_district,$id_site_embarquement, $vUnite_peche->id, $id_espece),
                                $this->generer_requete_analyse($annee,$mois,$vRegion->id,$id_district,'*', $vUnite_peche->id, '*'));

                       
                        if ($result != null )
                        {                        
                            $i=1;
                            $donnees=array();
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
                                                           
                                
                                
                                $som_tab_capture_par_espece =array_sum($tab_capture_espece_total);
                                $donnees[$i]['Total_capture_unite'] = $som_tab_capture_par_espece;

                                $total_prix_unite=array_sum($prix_espece);
                                $donnees[$i]['total_prix_unite'] = $total_prix_unite;
                                $donnees[$i]['erreur_relative_capture_total_90'] = $erreur_relative_capture_total_90;
                                $donnees[$i]['erreur_relative'] = $erreur_relative;
                                $donnees[$i]['unite_peche'] = $value->libelle;
                                $donnees[$i]['unite_pech2'] = $vUnite_peche->libelle;
                                $donnees[$i]['espece'] = $tab_capture_espece_total;

                              $i++;  
                            }
                            $Total_captureUnite_peche  = array_column($donnees, 'Total_capture_unite');
                            $total_captureUnite_peche = array_sum($Total_captureUnite_peche);

                            $Total_prixUnite_peche     = array_column($donnees, 'total_prix_unite');
                            $total_prixUnite_peche    = array_sum($Total_prixUnite_peche)/1000;

                            $erreur_captureUnite_peche = array_column($donnees, 'erreur_relative_capture_total_90');
                            $n_captureUnite_peche      = count($erreur_captureUnite_peche);
                            $erreur_rel_captureUnite_peche = number_format ( array_sum($erreur_captureUnite_peche)/$n_captureUnite_peche ,0);

                            $erreurUnite_peche = array_column($donnees, 'erreur_relative');
                            $nUnite_peche      = count($erreurUnite_peche);
                            $erreur_relativeUnite_peche = number_format ( array_sum($erreurUnite_peche)/$nUnite_peche,0);
                           $data[$indice]['region'] = $vRegion->nom;
                            $data[$indice]['unite_peche'] = $vUnite_peche->libelle;
                            $data[$indice]['capture'] = $total_captureUnite_peche;
                            $data[$indice]['prix']    = $total_prixUnite_peche;
                            $data[$indice]['erreur_relative']    = $erreur_relativeUnite_peche;
                            $data[$indice]['erreur_rel_capture'] = $erreur_rel_captureUnite_peche;
                            $data[$indice]['Donnee'] = $donnees;
                            if($erreur_relativeUnite_peche)
                            {
                                $n_erreur_relative++;
                            } 
                            if($erreur_rel_captureUnite_peche)
                            {
                                $n_erreur_capture++;
                            }                       
                          $indice++ ;  
                        }
                    }
                }

                $Total_capture  = array_column($data, 'capture');
                $total_capture = array_sum($Total_capture);

                $Total_prix     = array_column($data, 'prix');
                $total_prix    = array_sum($Total_prix);

                $erreur_capture = array_column($data, 'erreur_rel_capture');
                //$n_capture      = count($erreur_capture);
                $erreur_rel_capture = number_format ( array_sum($erreur_capture)/$n_erreur_capture ,0);

                $erreur = array_column($data, 'erreur_relative');
                //$n      = count($erreur);
                $erreur_relative = number_format ( array_sum($erreur)/$n_erreur_relative,0);
               
                }            

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

                $n_erreur_relative=0;
                $n_erreur_capture=0;

                foreach ($all_site_embarquement as $kSite => $vSite )
                {
                    foreach ($all_unite_peche as $kUnite_peche => $vUnite_peche)
                    {
                       $result =   $this->Fiche_echantillonnage_captureManager->erreur_relativepivotl1(
                                $this->generer_requete_analyse($annee,$mois,$id_region,$id_district,$vSite->id, $vUnite_peche->id, $id_espece),
                                $this->generer_requete_analyse($annee,$mois,$id_region,$id_district,'*', $vUnite_peche->id, '*'));

                       
                        if ($result != null )
                        {                        
                            $i=1;
                            $donnees=array();
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
                                                           
                                
                                
                                $som_tab_capture_par_espece =array_sum($tab_capture_espece_total);
                                $donnees[$i]['Total_capture_unite'] = $som_tab_capture_par_espece;

                                $total_prix_unite=array_sum($prix_espece);
                                $donnees[$i]['total_prix_unite'] = $total_prix_unite;
                                $donnees[$i]['erreur_relative_capture_total_90'] = $erreur_relative_capture_total_90;
                                $donnees[$i]['erreur_relative'] = $erreur_relative;
                                $donnees[$i]['unite_peche'] = $value->libelle;
                                $donnees[$i]['unite_pech2'] = $vUnite_peche->libelle;
                                $donnees[$i]['espece'] = $tab_capture_espece_total;

                              $i++;  
                            }
                            $Total_captureUnite_peche  = array_column($donnees, 'Total_capture_unite');
                            $total_captureUnite_peche = array_sum($Total_captureUnite_peche);

                            $Total_prixUnite_peche     = array_column($donnees, 'total_prix_unite');
                            $total_prixUnite_peche    = array_sum($Total_prixUnite_peche)/1000;

                            $erreur_captureUnite_peche = array_column($donnees, 'erreur_relative_capture_total_90');
                            $n_captureUnite_peche      = count($erreur_captureUnite_peche);
                            $erreur_rel_captureUnite_peche = number_format ( array_sum($erreur_captureUnite_peche)/$n_captureUnite_peche ,0);

                            $erreurUnite_peche = array_column($donnees, 'erreur_relative');
                            $nUnite_peche      = count($erreurUnite_peche);
                            $erreur_relativeUnite_peche = number_format ( array_sum($erreurUnite_peche)/$nUnite_peche,0);
                           $data[$indice]['site_embarquement'] = $vSite->libelle;
                            $data[$indice]['unite_peche'] = $vUnite_peche->libelle;
                            $data[$indice]['capture'] = $total_captureUnite_peche;
                            $data[$indice]['prix']    = $total_prixUnite_peche;
                            $data[$indice]['erreur_relative']    = $erreur_relativeUnite_peche;
                            $data[$indice]['erreur_rel_capture'] = $erreur_rel_captureUnite_peche;
                            $data[$indice]['Donnee'] = $donnees;
                            if($erreur_relativeUnite_peche)
                            {
                                $n_erreur_relative++;
                            } 
                            if($erreur_rel_captureUnite_peche)
                            {
                                $n_erreur_capture++;
                            }                       
                          $indice++ ;  
                        }
                    }
                }

                $Total_capture  = array_column($data, 'capture');
                $total_capture = array_sum($Total_capture);

                $Total_prix     = array_column($data, 'prix');
                $total_prix    = array_sum($Total_prix);

                $erreur_capture = array_column($data, 'erreur_rel_capture');
                //$n_capture      = count($erreur_capture);
                $erreur_rel_capture = number_format ( array_sum($erreur_capture)/$n_erreur_capture ,0);

                $erreur = array_column($data, 'erreur_relative');
               // $n      = count($erreur);
                $erreur_relative = number_format ( array_sum($erreur)/$n_erreur_relative,0);
               
                }
              
            //Pivot site and unite peche

//****Debut Requete L1.5 Annee Site de débarquement Unité de pêche Capture Valeur******//
           
//********Debut Requete L2.1&L2.2 Annee Strate Capture Valeur********//
            //Pivot mois
             if ($pivot == "mois_strate_majeur") 
            {
                $indice = 0 ;
                $total_prix = 0 ;
                $total_capture = 0 ;
                $erreur_rel_capture=0;
                $erreur_relative=0;

                for ($moi=1; $moi <=12 ; $moi++)
                {
                   $result =   $this->Fiche_echantillonnage_captureManager->erreur_relativepivotl1(
                            $this->generer_requete_analyse($annee,$moi,$id_region,$id_district,$id_site_embarquement, $id_unite_peche, $id_espece),
                            $this->generer_requete_analyse($annee,$moi,$id_region,$id_district,'*', $id_unite_peche, '*'));

                   
                    if ($result != null )
                    {                        
                        $i=1;
                        $donnees=array();
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
                            {
                                $moy_pax_pab = 1 ;
                            }
                            else
                                {
                                    $moy_pax_pab = $max_pab ;
                                }
                            
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
                            $donnees[$i]['Total_capture_unite'] = $som_tab_capture_par_espece;

                            $total_prix_unite=array_sum($prix_espece);
                            $donnees[$i]['total_prix_unite'] = $total_prix_unite;
                            $donnees[$i]['erreur_relative_capture_total_90'] = $erreur_relative_capture_total_90;
                            $donnees[$i]['erreur_relative'] = $erreur_relative;
                            $donnees[$i]['unite_peche'] = $value->libelle;                            
                            $donnees[$i]['espece'] = $tab_capture_espece_total;

                          $i++;  
                        }
                        $Total_captureUnite_peche  = array_column($donnees, 'Total_capture_unite');
                        $total_captureUnite_peche = array_sum($Total_captureUnite_peche);

                        $Total_prixUnite_peche     = array_column($donnees, 'total_prix_unite');
                        $total_prixUnite_peche    = array_sum($Total_prixUnite_peche)/1000;

                        $erreur_captureUnite_peche = array_column($donnees, 'erreur_relative_capture_total_90');
                        $n_captureUnite_peche      = count($erreur_captureUnite_peche);
                        $erreur_rel_captureUnite_peche = number_format ( array_sum($erreur_captureUnite_peche)/$n_captureUnite_peche ,0);

                        $erreurUnite_peche = array_column($donnees, 'erreur_relative');
                        $nUnite_peche      = count($erreurUnite_peche);
                        $erreur_relativeUnite_peche = number_format ( array_sum($erreurUnite_peche)/$nUnite_peche,0);
                       
                        $data[$indice]['mois'] = $moi;
                        $data[$indice]['capture'] = $total_captureUnite_peche;
                        $data[$indice]['prix']    = $total_prixUnite_peche;
                        $data[$indice]['erreur_relative']    = $erreur_relativeUnite_peche;
                        $data[$indice]['erreur_rel_capture'] = $erreur_rel_captureUnite_peche;
                        $data[$indice]['Donnee'] = $donnees;                       
                      $indice++ ;  
                    }
                }
                $Total_capture  = array_column($data, 'capture');
                $total_capture = array_sum($Total_capture);

                $Total_prix     = array_column($data, 'prix');
                $total_prix    = array_sum($Total_prix);

                $erreur_capture = array_column($data, 'erreur_rel_capture');
                $n_capture      = count($erreur_capture);
                $erreur_rel_capture = number_format ( array_sum($erreur_capture)/$n_capture ,0);

                $erreur = array_column($data, 'erreur_relative');
                $n      = count($erreur);
                $erreur_relative = number_format ( array_sum($erreur)/$n,0);
               
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
                $erreur_rel_capture=0;
                $erreur_relative=0;
                $n_erreur_relative=0;
                $n_erreur_capture=0;
                for ($moi=1; $moi <=12 ; $moi++)
                {
                    foreach ($all_unite_peche as $kUnite_peche => $vUnite_peche)
                    {
                       $result =   $this->Fiche_echantillonnage_captureManager->erreur_relativepivotl1(
                                $this->generer_requete_analyse($annee,$moi,$id_region,$id_district,$id_site_embarquement, $vUnite_peche->id, $id_espece),
                                $this->generer_requete_analyse($annee,$moi,$id_region,$id_district,'*', $vUnite_peche->id, '*'));

                       
                        if ($result != null )
                        {                        
                            $i=1;
                            $donnees=array();
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
                                                           
                                
                                
                                $som_tab_capture_par_espece =array_sum($tab_capture_espece_total);
                                $donnees[$i]['Total_capture_unite'] = $som_tab_capture_par_espece;

                                $total_prix_unite=array_sum($prix_espece);
                                $donnees[$i]['total_prix_unite'] = $total_prix_unite;
                                $donnees[$i]['erreur_relative_capture_total_90'] = $erreur_relative_capture_total_90;
                                $donnees[$i]['erreur_relative'] = $erreur_relative;
                                $donnees[$i]['unite_peche'] = $value->libelle;
                                $donnees[$i]['unite_pech2'] = $vUnite_peche->libelle;
                                $donnees[$i]['espece'] = $tab_capture_espece_total;

                              $i++;  
                            }
                            $Total_captureUnite_peche  = array_column($donnees, 'Total_capture_unite');
                            $total_captureUnite_peche = array_sum($Total_captureUnite_peche);

                            $Total_prixUnite_peche     = array_column($donnees, 'total_prix_unite');
                            $total_prixUnite_peche    = array_sum($Total_prixUnite_peche)/1000;

                            $erreur_captureUnite_peche = array_column($donnees, 'erreur_relative_capture_total_90');
                            $n_captureUnite_peche      = count($erreur_captureUnite_peche);
                            $erreur_rel_captureUnite_peche = number_format ( array_sum($erreur_captureUnite_peche)/$n_captureUnite_peche ,0);

                            $erreurUnite_peche = array_column($donnees, 'erreur_relative');
                            $nUnite_peche      = count($erreurUnite_peche);
                            $erreur_relativeUnite_peche = number_format ( array_sum($erreurUnite_peche)/$nUnite_peche,0);
                           
                            $data[$indice]['mois'] = $moi;
                            $data[$indice]['unite_peche'] = $vUnite_peche->libelle;
                            $data[$indice]['capture'] = $total_captureUnite_peche;
                            $data[$indice]['prix']    = $total_prixUnite_peche;
                            $data[$indice]['erreur_relative']    = $erreur_relativeUnite_peche;
                            $data[$indice]['erreur_rel_capture'] = $erreur_rel_captureUnite_peche;
                            $data[$indice]['Donnee'] = $donnees;
                            if($erreur_relativeUnite_peche)
                            {
                                $n_erreur_relative++;
                            } 
                            if($erreur_rel_captureUnite_peche)
                            {
                                $n_erreur_capture++;
                            }                       
                          $indice++ ;  
                        }
                    }
                }
                $Total_capture  = array_column($data, 'capture');
                $total_capture = array_sum($Total_capture);

                $Total_prix     = array_column($data, 'prix');
                $total_prix    = array_sum($Total_prix);

                $erreur_capture = array_column($data, 'erreur_rel_capture');
                //$n_capture      = count($erreur_capture);
                $erreur_rel_capture = number_format ( array_sum($erreur_capture)/$n_erreur_capture ,0);

                $erreur = array_column($data, 'erreur_relative');
                //$n      = count($erreur);
                $erreur_relative = number_format ( array_sum($erreur)/$n_erreur_relative,0);
                
                }
              
            //Pivot mois unité de peche

//********Fin Requete L2.3 Annee Strate Unité de pêche Capture Valeur********//

//****Debut Requete L2.5 Annee mois Site de débarquement Unité de pêche Capture Valeur******//

            //Pivot mois  unité de peche site
            if ($pivot == "mois_and_id_unite_peche_and_id_site_embarquement") 
            {
                $indice = 0 ;
                $total_prix = 0 ;
                $total_capture = 0 ;
                $erreur_rel_capture=0;
                $erreur_relative=0;

                $n_erreur_relative=0;
                $n_erreur_capture=0;
                for ($moi=1; $moi <=12 ; $moi++)
                { 
                    foreach ($all_site_embarquement as $kSite => $vSite )
                    {
                        foreach ($all_unite_peche as $kUnite_peche => $vUnite_peche)
                        {
                           $result =   $this->Fiche_echantillonnage_captureManager->erreur_relativepivotl1(
                                    $this->generer_requete_analyse($annee,$moi,$id_region,$id_district,$vSite->id, $vUnite_peche->id, $id_espece),
                                    $this->generer_requete_analyse($annee,$moi,$id_region,$id_district,'*', $vUnite_peche->id, '*'));

                           
                            if ($result != null )
                            {                        
                                $i=1;
                                $donnees=array();
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
                                                               
                                    
                                    
                                    $som_tab_capture_par_espece =array_sum($tab_capture_espece_total);
                                    $donnees[$i]['Total_capture_unite'] = $som_tab_capture_par_espece;

                                    $total_prix_unite=array_sum($prix_espece);
                                    $donnees[$i]['total_prix_unite'] = $total_prix_unite;
                                    $donnees[$i]['erreur_relative_capture_total_90'] = $erreur_relative_capture_total_90;
                                    $donnees[$i]['erreur_relative'] = $erreur_relative;
                                    $donnees[$i]['unite_peche'] = $value->libelle;
                                    $donnees[$i]['espece'] = $tab_capture_espece_total;

                                  $i++;  
                                }
                                $Total_captureUnite_peche  = array_column($donnees, 'Total_capture_unite');
                                $total_captureUnite_peche = array_sum($Total_captureUnite_peche);

                                $Total_prixUnite_peche     = array_column($donnees, 'total_prix_unite');
                                $total_prixUnite_peche    = array_sum($Total_prixUnite_peche)/1000;

                                $erreur_captureUnite_peche = array_column($donnees, 'erreur_relative_capture_total_90');
                                $n_captureUnite_peche      = count($erreur_captureUnite_peche);
                                $erreur_rel_captureUnite_peche = number_format ( array_sum($erreur_captureUnite_peche)/$n_captureUnite_peche ,0);

                                $erreurUnite_peche = array_column($donnees, 'erreur_relative');
                                $nUnite_peche      = count($erreurUnite_peche);
                                $erreur_relativeUnite_peche = number_format ( array_sum($erreurUnite_peche)/$nUnite_peche,0);
                                $data[$indice]['mois'] = $moi;
                                $data[$indice]['site_embarquement'] = $vSite->libelle;
                                $data[$indice]['unite_peche'] = $vUnite_peche->libelle;
                                $data[$indice]['capture'] = $total_captureUnite_peche;
                                $data[$indice]['prix']    = $total_prixUnite_peche;
                                $data[$indice]['erreur_relative']    = $erreur_relativeUnite_peche;
                                $data[$indice]['erreur_rel_capture'] = $erreur_rel_captureUnite_peche;
                                $data[$indice]['Donnee'] = $donnees;
                                if($erreur_relativeUnite_peche)
                                {
                                    $n_erreur_relative++;
                                } 
                                if($erreur_rel_captureUnite_peche)
                                {
                                    $n_erreur_capture++;
                                }                       
                              $indice++ ;  
                            }
                        }
                    }
                }
                

                $Total_capture  = array_column($data, 'capture');
                $total_capture = array_sum($Total_capture);

                $Total_prix     = array_column($data, 'prix');
                $total_prix    = array_sum($Total_prix);

                $erreur_capture = array_column($data, 'erreur_rel_capture');
                //$n_capture      = count($erreur_capture);
                $erreur_rel_capture = number_format ( array_sum($erreur_capture)/$n_erreur_capture ,0);

                $erreur = array_column($data, 'erreur_relative');
               // $n      = count($erreur);
                $erreur_relative = number_format ( array_sum($erreur)/$n_erreur_relative,0);
               
                }
               /* if ($pivot == "mois_and_id_unite_peche_and_id_site_embarquement") 
                {
                    $indice = 0 ;
                    $total_prix = 0 ;
                    $total_capture = 0 ;
                    $erreur_rel_capture=0;
                    $erreur_relative=0;

                $result =   $this->Fiche_echantillonnage_captureManager->erreur_relativepivotl1(
                            $this->generer_requete_analyse($annee,$mois,$id_region,$id_district,$id_site_embarquement, $id_unite_peche, $id_espece),
                            $this->generer_requete_analyse($annee,$mois,$id_region,$id_district,'*', $id_unite_peche, '*'));

                    if ($result != null )
                    {                        
                        $i=1;
                        $donnees=array();
                        $tab_site = array();
                        $tab_unite = array();
                        $tab_mois = array();
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
                            $existunite=false;
                            $existsite=false;
                            $existmois=false;
                          
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
                            $donnees['site_embarquement'] = $value->site_embarquement;
                            $donnees['mois'] = $value->mois;
                            
                            $donnees['captures_t'] = $captures_t;
                            $donnees['tab_composition'] = $tab_composition;
                            
                            $donnees['capture_total_par_espec'] = $tab_capture_par_espece;
                            $donnees['tab_capture_espece_totaldetai'] = $tab_capture_espece_total;

                            $donnees['total_espece'] = $tab_capture_par_espece;

                            foreach ($tab_mois as $k => $valueMois)
                            {
                                if ($valueMois['mois']==$value->mois)
                                {                                                    
                                    $existmois=true;
                                }
                            }
                         
                            if($existmois==false)
                            { 
                                array_push($tab_mois, array('mois'=>$value->mois));  
                            }
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
                                        foreach ($tab_site as $k => $valueSite)
                                        {
                                           if ($valueSite['site_embarquement']==$value->site_embarquement)
                                                {                                                    
                                                    $existsite=true;
                                                }
                                        }
                         
                            if($existsite==false)
                            { 
                                array_push($tab_site, array('site_embarquement'=>$value->site_embarquement));  
                            }
                            $donnees['site'] = $tab_site;
                            $donnees['unit'] = $tab_unite;
                            $datadetail[$i] = $donnees;
                            
                          $i++;  
                        }

                        $n=0;
                        foreach ($tab_mois as $keymois => $valuemois)
                        {
                            foreach ($tab_site as $keysite => $valuesit)
                            {
                                foreach ($tab_unite as $keyunite => $valueunit)
                                {
                                    $valparunitesite=array();
                                    $capture=0;
                                    $prix=0;
                                    $erreur_rel=0;
                                    $erreur_rel_capt=0;
                                    $c=0;
                                    foreach ($datadetail as $keyd => $valued)
                                    {
                                        if ($valued['mois']==$valuemois['mois'] && $valued['unite_peche']==$valueunit['unite_peche'] && $valued['site_embarquement']==$valuesit['site_embarquement'])
                                        {
                                           $valparunitesite['unite_peche']=$valueunit['unite_peche'];
                                           $valparunitesite['site_embarquement']=$valuesit['site_embarquement'];
                                           $valparunitesite['mois']=$valuemois['mois'];

                                           $capture+=$valued['capture'];
                                           $prix+=$valued['prix'];
                                           $erreur_rel+=$valued['erreur_relative'];
                                           $erreur_rel_capt+=$valued['erreur_rel_capture'];
                                           $valparunitesite['capture']=$capture;
                                           $valparunitesite['prix']=$prix;
                                            $valparunitesite['donee']=$datadetail;
                                           $c++;
                                        }                                
                                    }

                                    if ($valparunitesite)
                                    {   
                                        $valparunitesite['erreur_relative'] = number_format ( $erreur_rel/$c,0);
                                        $valparunitesite['erreur_rel_capture'] = number_format ( $erreur_rel_capt/$c,0);
                                        $data[$n] = $valparunitesite;
                                        $n++;
                                    }
                                 
                                }
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
                
                }*/
            //Pivot mois unité de peche site

//****Fin Requete L2.5 Annee mois Site de débarquement Unité de pêche Capture Valeur******//

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

//****Debut Requete L3.1&L3.2 Annee Strate Espèce Capture Valeur******//
            //Pivot espece 
                if ($pivot == "id_espece") 
                {   
                    $indice = 0 ;
                    $total_prix = 0 ;
                    $total_capture = 0 ;
                    $erreur_rel_capture=0;
                    $erreur_relative=0;

                    //-talou                   
                
                  /*  if ($all_espece) {
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
                    }*/
                    // talou-/    

                // /-vaovao
               
                  $result =   $this->Fiche_echantillonnage_captureManager->erreur_relativepivotl1(
                            $this->generer_requete_analyse($annee,$mois,$id_region,$id_district,$id_site_embarquement, $id_unite_peche, $id_espece),
                            $this->generer_requete_analyse($annee,$mois,$id_region,$id_district,'*', $id_unite_peche, '*'));

                    if ($result != null )
                    {                        
                        $i=1;
                        $donnees=array();
                        $tab_code=array();
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
                                $tab_espece               = array();
                                    if ($tab_capture_par_espece) 
                                    {
                                        foreach ($tab_capture_par_espece as $val)
                                        {
                                            $tab_capture_espece_total['capture']=($val->capture_total_par_espece/$value->capture_total_par_unite)*($captures_t);
                                            $tab_capture_espece_total['prix']=(($val->capture_total_par_espece/$value->capture_total_par_unite)*($captures_t/1000))*$val->prix;
                                            $tab_capture_espece_total['nom_local']=$val->nom_local;
                                            $tab_capture_espece_total['nom_scientifique']=$val->nom_scientifique;
                                            $tab_capture_espece_total['code']=$val->coda;
                                            $tab_capture_espece_total['erreur_relative']=$erreur_relative;
                                            $tab_capture_espece_total['erreur_relative_capture']=$erreur_relative_capture_total_90;

                                            $tab_espece[$j]=$tab_capture_espece_total;


                                            if(!in_array($val->coda, $tab_code))
                                            {
                                                array_push($tab_code, $val->coda);
                                            }
                                            $j++;
                                        }
                                    }
                                
                                $datadetail[$i] =$tab_espece;
                              $i++;
                              
                        }
                        
                        $final=array();
                        $y=0;
                        foreach ($tab_code as $keyCode => $valueCode) {
                            $capture=0;
                            $prix=0;
                            $nom_scientifique='';
                            $nom_local='';
                            $tb_erreur_rel=array();
                            $tb_erreur_rel_capture=array();
                            foreach ($datadetail as $key => $value)
                            {
                                foreach ($value as $keyv => $valuev)
                                {   
                                    if ($valueCode==$valuev['code']) {
                                        $capture+=$valuev['capture'];
                                        $prix+=$valuev['prix'];
                                        $nom_scientifique=$valuev['nom_scientifique'];
                                        $nom_local=$valuev['nom_local'];
                                        array_push($tb_erreur_rel, $valuev['erreur_relative']);
                                        array_push($tb_erreur_rel_capture, $valuev['erreur_relative_capture']);
                                    }
                                    
                                    
                                }                            
                            }
                            $final['erreur_relative']=number_format ( array_sum($tb_erreur_rel)/count($tb_erreur_rel),0);
                            $final['erreur_rel_capture']=number_format ( array_sum($tb_erreur_rel_capture)/count($tb_erreur_rel_capture),0);
                            //$final['tab']=$tb_erreur_rel;
                            $final['capture']=$capture;
                            $final['prix']=$prix;
                            $final['code']=$valueCode;
                            $final['espece_nom_scientifique']=$nom_scientifique;
                            $final['espece_nom_local']=$nom_local;
                            $data[$y]=$final;
                            $y++;
                        }
                        
                        
                        $indice++;
                       
                        $Total_captur  = array_column($data, 'capture');
                        $total_capture = array_sum($Total_captur);

                        $Total_pri     = array_column($data, 'prix');
                        $total_prix    = array_sum($Total_pri);

                      $erreur_capture = array_column($data, 'erreur_rel_capture');
                        $n_capture    = count($erreur_capture);
                        $erreur_rel_capture= number_format ( array_sum($erreur_capture)/$n_capture,0);

                        $erreur = array_column($data, 'erreur_relative');
                        $n      = count($erreur);
                        $erreur_relative = number_format ( array_sum($erreur)/$n,0);
                    }
                

                // vaovao-/
                    

                    /*$data['total_prix'] = $total_prix ;
                    $data['total_capture'] = $total_capture ;*/
            }
            //Pivot espece 
//****Debut Requete L3.1&L3.2 Annee Strate Espèce Capture Valeur******//

//****Debut Requete L3.3&L3.4 Annee Unite Espèce Capture Valeur******//
            //Pivot espece 
                if ($pivot == "id_unite_peche_and_id_espece") 
                {   
                    $indice = 0 ;
                    $total_prix = 0 ;
                    $total_capture = 0 ;
                    $erreur_rel_capture=0;
                    $erreur_relative=0;

                  $result =   $this->Fiche_echantillonnage_captureManager->erreur_relativepivotl1(
                            $this->generer_requete_analyse($annee,$mois,$id_region,$id_district,$id_site_embarquement, $id_unite_peche, $id_espece),
                            $this->generer_requete_analyse($annee,$mois,$id_region,$id_district,'*', $id_unite_peche, '*'));

                    if ($result != null )
                    {                        
                        $i=1;
                        $donnees=array();
                        $tab_code=array();
                        $tab_unite= array();
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
                                $tab_espece               = array();
                                    if ($tab_capture_par_espece) 
                                    {
                                        foreach ($tab_capture_par_espece as $val)
                                        {
                                            $tab_capture_espece_total['capture']=($val->capture_total_par_espece/$value->capture_total_par_unite)*($captures_t);
                                            $tab_capture_espece_total['prix']=(($val->capture_total_par_espece/$value->capture_total_par_unite)*($captures_t/1000))*$val->prix;
                                            $tab_capture_espece_total['nom_local']=$val->nom_local;
                                            $tab_capture_espece_total['nom_scientifique']=$val->nom_scientifique;
                                            $tab_capture_espece_total['code']=$val->coda;
                                            $tab_capture_espece_total['erreur_relative']=$erreur_relative;
                                            $tab_capture_espece_total['erreur_relative_capture']=$erreur_relative_capture_total_90;
                                            $tab_capture_espece_total['unite_peche']=$value->libelle;
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
                                $datadetail[$i] =$tab_espece;
                              $i++;
                              
                        }
                        
                        $final=array();
                        $y=0;
                        foreach ($tab_unite as $keyUnite => $valueUnite)
                        {
                            foreach ($tab_code as $keyCode => $valueCode)
                            {
                                $capture=0;
                                $prix=0;
                                $nom_scientifique='';
                                $nom_local='';
                                $tb_erreur_rel=array();
                                $tb_erreur_rel_capture=array();
                                $existe=false;
                               foreach ($datadetail as $key => $value)
                                {
                                    foreach ($value as $keyv => $valuev)
                                    {   
                                        if ($valueCode==$valuev['code'] && $valueUnite==$valuev['unite_peche']) 
                                        {
                                            $capture+=$valuev['capture'];
                                            $prix+=$valuev['prix'];
                                            $nom_scientifique=$valuev['nom_scientifique'];
                                            $nom_local=$valuev['nom_local'];
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
                                    //$final['tabuni']=$tab_unite;
                                    $final['capture']=$capture;
                                    $final['prix']=$prix;
                                    $final['code']=$valueCode;
                                    $final['espece_nom_scientifique']=$nom_scientifique;
                                    $final['espece_nom_local']=$nom_local;
                                    $data[$y]=$final;
                                    $y++;
                                }
                               
                            }
                            
                            
                        }
                        
                       
                        $Total_captur  = array_column($data, 'capture');
                        $total_capture = array_sum($Total_captur);

                        $Total_pri     = array_column($data, 'prix');
                        $total_prix    = array_sum($Total_pri);

                      $erreur_capture = array_column($data, 'erreur_rel_capture');
                        $n_capture    = count($erreur_capture);
                        $erreur_rel_capture= number_format ( array_sum($erreur_capture)/$n_capture,0);

                        $erreur = array_column($data, 'erreur_relative');
                        $n      = count($erreur);
                        $erreur_relative = number_format ( array_sum($erreur)/$n,0);
                    }
            }
            //Pivot espece 
//****Debut Requete L3.3&L3.4 Annee Unite Espèce Capture Valeur******//

//****Debut Requete L3.5 Annee Site Espèce Capture Valeur******//
            //Pivot espece 
                if ($pivot == "id_site_embarquement_id_unite_peche_and_id_espece") 
                {   
                    $indice = 0 ;
                    $total_prix = 0 ;
                    $total_capture = 0 ;
                    $erreur_rel_capture=0;
                    $erreur_relative=0;

                  $result =   $this->Fiche_echantillonnage_captureManager->erreur_relativepivotl1(
                            $this->generer_requete_analyse($annee,$mois,$id_region,$id_district,$id_site_embarquement, $id_unite_peche, $id_espece),
                            $this->generer_requete_analyse($annee,$mois,$id_region,$id_district,'*', $id_unite_peche, '*'));

                    if ($result != null )
                    {                        
                        $i=1;
                        $donnees=array();
                        $tab_code=array();
                        $tab_unite= array();
                        $tab_site= array();
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
                                $tab_espece               = array();
                                    if ($tab_capture_par_espece) 
                                    {
                                        foreach ($tab_capture_par_espece as $val)
                                        {
                                            $tab_capture_espece_total['capture']=($val->capture_total_par_espece/$value->capture_total_par_unite)*($captures_t);
                                            $tab_capture_espece_total['prix']=(($val->capture_total_par_espece/$value->capture_total_par_unite)*($captures_t/1000))*$val->prix;
                                            $tab_capture_espece_total['nom_local']=$val->nom_local;
                                            $tab_capture_espece_total['nom_scientifique']=$val->nom_scientifique;
                                            $tab_capture_espece_total['code']=$val->coda;
                                            $tab_capture_espece_total['erreur_relative']=$erreur_relative;
                                            $tab_capture_espece_total['erreur_relative_capture']=$erreur_relative_capture_total_90;
                                            $tab_capture_espece_total['unite_peche']=$value->libelle;
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
                                $datadetail[$i] =$tab_espece;
                              $i++;
                              
                        }
                        
                        $final=array();
                        $y=0;
                        foreach ($tab_site as $keySite => $valueSite)
                        {
                            foreach ($tab_unite as $keyUnite => $valueUnite)
                            {
                                foreach ($tab_code as $keyCode => $valueCode)
                                {
                                    $capture=0;
                                    $prix=0;
                                    $nom_scientifique='';
                                    $nom_local='';
                                    $tb_erreur_rel=array();
                                    $tb_erreur_rel_capture=array();
                                    $existe=false;
                                   foreach ($datadetail as $key => $value)
                                    {
                                        foreach ($value as $keyv => $valuev)
                                        {   
                                            if ($valueCode==$valuev['code'] && $valueUnite==$valuev['unite_peche'] && $valueSite==$valuev['site_embarquement']) 
                                            {
                                                $capture+=$valuev['capture'];
                                                $prix+=$valuev['prix'];
                                                $nom_scientifique=$valuev['nom_scientifique'];
                                                $nom_local=$valuev['nom_local'];
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
                                        $final['tabsite']=$tab_site;
                                        $final['capture']=$capture;
                                        $final['prix']=$prix;
                                        $final['code']=$valueCode;
                                        $final['espece_nom_scientifique']=$nom_scientifique;
                                        $final['espece_nom_local']=$nom_local;
                                        $data[$y]=$final;
                                        $y++;
                                    }
                                   
                                }
                                
                                
                            }
                        }
                        
                        
                       
                        $Total_captur  = array_column($data, 'capture');
                        $total_capture = array_sum($Total_captur);

                        $Total_pri     = array_column($data, 'prix');
                        $total_prix    = array_sum($Total_pri);

                      $erreur_capture = array_column($data, 'erreur_rel_capture');
                        $n_capture    = count($erreur_capture);
                        $erreur_rel_capture= number_format ( array_sum($erreur_capture)/$n_capture,0);

                        $erreur = array_column($data, 'erreur_relative');
                        $n      = count($erreur);
                        $erreur_relative = number_format ( array_sum($erreur)/$n,0);
                    }
            }
            //Pivot espece 
//****Fin Requete L3.5 Annee Site Espèce Capture Valeur******//

//****Debut Requete L3.6 Annee Unite Espèce Capture Valeur******//
            //Pivot espece 
                if ($pivot == "id_unite_peche_and_id_espece_and_cpue_effort") 
                {   
                    $indice = 0 ;
                    $total_prix = 0 ;
                    $total_capture = 0 ;
                    $erreur_rel_capture=0;
                    $erreur_relative=0;
                    $nbr_jrs_peche_annuel_pabs= array();
                  $result =   $this->Fiche_echantillonnage_captureManager->erreur_relativepivotl1(
                            $this->generer_requete_analyse($annee,$mois,$id_region,$id_district,$id_site_embarquement, $id_unite_peche, $id_espece),
                            $this->generer_requete_analyse($annee,$mois,$id_region,$id_district,'*', $id_unite_peche, '*'));

                    if ($result != null )
                    {                        
                        $i=1;
                        $donnees=array();
                        $tab_code=array();
                        $tab_unite= array();
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
                                $tab_espece               = array();
                                    if ($tab_capture_par_espece) 
                                    {
                                        foreach ($tab_capture_par_espece as $val)
                                        {
                                            $tab_capture_espece_total['capture']=($val->capture_total_par_espece/$value->capture_total_par_unite)*($captures_t);
                                            $tab_capture_espece_total['prix']=(($val->capture_total_par_espece/$value->capture_total_par_unite)*($captures_t/1000))*$val->prix;
                                            $tab_capture_espece_total['nbr_total_jrs_peche_mensuel']=$nbr_total_jrs_peche_mensuel;
                                            $tab_capture_espece_total['nom_local']=$val->nom_local;
                                            $tab_capture_espece_total['nom_scientifique']=$val->nom_scientifique;
                                            $tab_capture_espece_total['code']=$val->coda;
                                            $tab_capture_espece_total['erreur_relative']=$erreur_relative;
                                            $tab_capture_espece_total['erreur_relative_capture']=$erreur_relative_capture_total_90;
                                            $tab_capture_espece_total['unite_peche']=$value->libelle;
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
                                $datadetail[$i] =$tab_espece;
                              $i++;
                              
                        }
                        
                        $final=array();
                        $y=0;
                        foreach ($tab_unite as $keyUnite => $valueUnite)
                        {
                            foreach ($tab_code as $keyCode => $valueCode)
                            {
                                $capture=0;
                                $prix=0;
                                $nom_scientifique='';
                                $nom_local='';
                                $tb_erreur_rel=array();
                                $tb_erreur_rel_capture=array();
                                $tb_nbr_total_jrs_peche_mensuel=array();
                                $existe=false;
                               foreach ($datadetail as $key => $value)
                                {
                                    foreach ($value as $keyv => $valuev)
                                    {   
                                        if ($valueCode==$valuev['code'] && $valueUnite==$valuev['unite_peche']) 
                                        {
                                            $capture+=$valuev['capture'];
                                            $prix+=$valuev['prix'];
                                            $nom_scientifique=$valuev['nom_scientifique'];
                                            $nom_local=$valuev['nom_local'];
                                            array_push($tb_erreur_rel, $valuev['erreur_relative']);
                                            array_push($tb_erreur_rel_capture, $valuev['erreur_relative_capture']);
                                            array_push($tb_nbr_total_jrs_peche_mensuel, $valuev['nbr_total_jrs_peche_mensuel']);
                                            $existe=true;
                                        }
                                        
                                        
                                    }                            
                                }
                             
                                if ($existe)
                                {   
                                    $final['erreur_relative']=number_format ( array_sum($tb_erreur_rel)/count($tb_erreur_rel),0);
                                   $final['erreur_rel_capture']=number_format ( array_sum($tb_erreur_rel_capture)/count($tb_erreur_rel_capture),0);
                                   $final['nbr_total_jrs_peche_annuel_moy']=number_format ( array_sum($tb_nbr_total_jrs_peche_mensuel)/count($tb_nbr_total_jrs_peche_mensuel),0);
                                    $final['unite_peche']=$valueUnite;
                                    //$final['tabuni']=$tab_unite;
                                    $final['capture']=$capture;
                                    $final['cpue_effort']=number_format ( $capture/(array_sum($tb_nbr_total_jrs_peche_mensuel)/count($tb_nbr_total_jrs_peche_mensuel)),3);
                                    $final['prix']=$prix;
                                    $final['code']=$valueCode;
                                    $final['espece_nom_scientifique']=$nom_scientifique;
                                    $final['espece_nom_local']=$nom_local;
                                   // $final['dat']=$nbr_total_jrs_peche_annuel;
                                    $data[$y]=$final;
                                    $y++;
                                }
                               
                            }
                            
                            
                        }
                        
                       
                        $Total_captur  = array_column($data, 'capture');
                        $total_capture = array_sum($Total_captur);

                        $Total_pri     = array_column($data, 'prix');
                        $total_prix    = array_sum($Total_pri);

                      $erreur_capture = array_column($data, 'erreur_rel_capture');
                        $n_capture    = count($erreur_capture);
                        $erreur_rel_capture= number_format ( array_sum($erreur_capture)/$n_capture,0);

                        $erreur = array_column($data, 'erreur_relative');
                        $n      = count($erreur);
                        $erreur_relative = number_format ( array_sum($erreur)/$n,0);
                    }
            }
            //Pivot espece 
//****Fin Requete L3.6 Annee Unite Espèce Capture Valeur******//

//********Debut Requete L4.1 Annee Mois Espèce Capture Valeur********//
            //Pivot mois  espece
                if ($pivot == "mois_and_id_espece") 
                {
                    /*$indice = 0 ;
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
                        
                    }*/

                    $indice = 0 ;
                    $total_prix = 0 ;
                    $total_capture = 0 ;
                    $erreur_rel_capture=0;
                    $erreur_relative=0;

                  $result =   $this->Fiche_echantillonnage_captureManager->erreur_relativepivotl1(
                            $this->generer_requete_analyse($annee,$mois,$id_region,$id_district,$id_site_embarquement, $id_unite_peche, $id_espece),
                            $this->generer_requete_analyse($annee,$mois,$id_region,$id_district,'*', $id_unite_peche, '*'));

                    if ($result != null )
                    {                        
                        $i=1;
                        $donnees=array();
                        $tab_code=array();
                        $tab_mois= array();
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
                                $tab_espece               = array();
                                    if ($tab_capture_par_espece) 
                                    {
                                        foreach ($tab_capture_par_espece as $val)
                                        {
                                            $tab_capture_espece_total['capture']=($val->capture_total_par_espece/$value->capture_total_par_unite)*($captures_t);
                                            $tab_capture_espece_total['prix']=(($val->capture_total_par_espece/$value->capture_total_par_unite)*($captures_t/1000))*$val->prix;
                                            $tab_capture_espece_total['nom_local']=$val->nom_local;
                                            $tab_capture_espece_total['nom_scientifique']=$val->nom_scientifique;
                                            $tab_capture_espece_total['code']=$val->coda;
                                            $tab_capture_espece_total['erreur_relative']=$erreur_relative;
                                            $tab_capture_espece_total['erreur_relative_capture']=$erreur_relative_capture_total_90;
                                            $tab_capture_espece_total['mois']=$value->mois;
                                            $tab_espece[$j]=$tab_capture_espece_total;

                                            if(!in_array($val->coda, $tab_code))
                                            {
                                                array_push($tab_code, $val->coda);
                                            }
                                            $j++;
                                        }
                                    }

                                    if(!in_array($value->mois, $tab_mois))
                                    {
                                        array_push($tab_mois, $value->mois);
                                    }
                                $datadetail[$i] =$tab_espece;
                              $i++;
                              
                        }
                        
                        $final=array();
                        $y=0;
                        foreach ($tab_mois as $keyMois => $valueMois)
                        {
                            foreach ($tab_code as $keyCode => $valueCode)
                            {
                                $capture=0;
                                $prix=0;
                                $nom_scientifique='';
                                $nom_local='';
                                $tb_erreur_rel=array();
                                $tb_erreur_rel_capture=array();
                                $existe=false;
                                foreach ($datadetail as $key => $value)
                                {
                                    foreach ($value as $keyv => $valuev)
                                    {   
                                        if ($valueCode==$valuev['code'] && $valueMois==$valuev['mois']) 
                                        {
                                            $capture+=$valuev['capture'];
                                            $prix+=$valuev['prix'];
                                            $nom_scientifique=$valuev['nom_scientifique'];
                                            $nom_local=$valuev['nom_local'];
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
                                    
                                    $final['mois']=$valueMois;
                                    $final['tabmois']=$tab_mois;
                                    $final['capture']=$capture;
                                    $final['prix']=$prix;
                                    $final['code']=$valueCode;
                                    $final['espece_nom_scientifique']=$nom_scientifique;
                                    $final['espece_nom_local']=$nom_local;
                                    $data[$y]=$final;
                                    $y++;
                                }
                                       
                            } 
                        }
                        

                        $Total_captur  = array_column($data, 'capture');
                        $total_capture = array_sum($Total_captur);

                        $Total_pri     = array_column($data, 'prix');
                        $total_prix    = array_sum($Total_pri);

                        $erreur_capture = array_column($data, 'erreur_rel_capture');
                        $n_capture    = count($erreur_capture);
                        $erreur_rel_capture= number_format ( array_sum($erreur_capture)/$n_capture,0);

                        $erreur = array_column($data, 'erreur_relative');
                        $n      = count($erreur);
                        $erreur_relative = number_format ( array_sum($erreur)/$n,0);
                    }
                
                }
            //Pivot mois espece

//********Fin Requete L4.1 Annee Mois Espèce Capture Valeur********//

//********Debut Requete L4.3 Annee Mois unite Espèce Capture Valeur********//
            //Pivot mois  espece
                if ($pivot == "mois_id_unite_peche_and_id_espece") 
                {
                    $indice = 0 ;
                    $total_prix = 0 ;
                    $total_capture = 0 ;
                    $erreur_rel_capture=0;
                    $erreur_relative=0;

                  $result =   $this->Fiche_echantillonnage_captureManager->erreur_relativepivotl1(
                            $this->generer_requete_analyse($annee,$mois,$id_region,$id_district,$id_site_embarquement, $id_unite_peche, $id_espece),
                            $this->generer_requete_analyse($annee,$mois,$id_region,$id_district,'*', $id_unite_peche, '*'));

                    if ($result != null )
                    {                        
                        $i=1;
                        $donnees=array();
                        $tab_code=array();
                        $tab_unite= array();
                        $tab_mois= array();
                        foreach ($result as $key => $value)
                        {    $tab_capture_par_espece =   $this->Fiche_echantillonnage_captureManager->capture_total_par_espece(
                                                            $this->generer_requete_analyse($annee,$value->mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece),$value->id_reg,$value->id_unite,$value->mois);
                              
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
                                $tab_espece               = array();
                                    if ($tab_capture_par_espece) 
                                    {
                                        foreach ($tab_capture_par_espece as $val)
                                        {
                                            $tab_capture_espece_total['capture']=($val->capture_total_par_espece/$value->capture_total_par_unite)*($captures_t);
                                            $tab_capture_espece_total['prix']=(($val->capture_total_par_espece/$value->capture_total_par_unite)*($captures_t/1000))*$val->prix;
                                            $tab_capture_espece_total['nom_local']=$val->nom_local;
                                            $tab_capture_espece_total['nom_scientifique']=$val->nom_scientifique;
                                            $tab_capture_espece_total['code']=$val->coda;
                                            $tab_capture_espece_total['erreur_relative']=$erreur_relative;
                                            $tab_capture_espece_total['erreur_relative_capture']=$erreur_relative_capture_total_90;
                                            $tab_capture_espece_total['unite_peche']=$value->libelle;
                                            $tab_capture_espece_total['mois']=$value->mois;
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

                                    if(!in_array($value->mois, $tab_mois))
                                    {
                                        array_push($tab_mois, $value->mois);
                                    }
                                $datadetail[$i] =$tab_espece;
                              $i++;
                              
                        }
                        
                        $final=array();
                        $y=0;
                        foreach ($tab_mois as $keyMois => $valueMois)
                        {
                            foreach ($tab_unite as $keyUnite => $valueUnite)
                            {
                                foreach ($tab_code as $keyCode => $valueCode)
                                {
                                    $capture=0;
                                    $prix=0;
                                    $nom_scientifique='';
                                    $nom_local='';
                                    $tb_erreur_rel=array();
                                    $tb_erreur_rel_capture=array();
                                    $existe=false;
                                    foreach ($datadetail as $key => $value)
                                    {
                                        foreach ($value as $keyv => $valuev)
                                        {   
                                            if ($valueCode==$valuev['code'] && $valueUnite==$valuev['unite_peche'] && $valueMois==$valuev['mois']) 
                                            {
                                                $capture+=$valuev['capture'];
                                                $prix+=$valuev['prix'];
                                                $nom_scientifique=$valuev['nom_scientifique'];
                                                $nom_local=$valuev['nom_local'];
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
                                        $final['mois']=$valueMois;                                       
                                        $final['capture']=$capture;
                                        $final['prix']=$prix;
                                        $final['code']=$valueCode;
                                        $final['espece_nom_scientifique']=$nom_scientifique;
                                        $final['espece_nom_local']=$nom_local;
                                        $data[$y]=$final;
                                        $y++;
                                    }
                                       
                                } 
                            }
                        }
                        

                        $Total_captur  = array_column($data, 'capture');
                        $total_capture = array_sum($Total_captur);

                        $Total_pri     = array_column($data, 'prix');
                        $total_prix    = array_sum($Total_pri);

                      $erreur_capture = array_column($data, 'erreur_rel_capture');
                        $n_capture    = count($erreur_capture);
                        $erreur_rel_capture= number_format ( array_sum($erreur_capture)/$n_capture,0);

                        $erreur = array_column($data, 'erreur_relative');
                        $n      = count($erreur);
                        $erreur_relative = number_format ( array_sum($erreur)/$n,0);
                    }
                
                }
            //Pivot mois espece

//********Fin Requete L4.3 Annee Mois unite Espèce Capture Valeur********// 
            
//********Debut Requete L4.5 Annee Mois Site unite Espèce Capture Valeur********//
            //Pivot mois  espece
                if ($pivot == "mois_id_site_embarquement_id_unite_peche_and_id_espece") 
                {
                    $indice = 0 ;
                    $total_prix = 0 ;
                    $total_capture = 0 ;
                    $erreur_rel_capture=0;
                    $erreur_relative=0;

                  $result =   $this->Fiche_echantillonnage_captureManager->erreur_relativepivotl1(
                            $this->generer_requete_analyse($annee,$mois,$id_region,$id_district,$id_site_embarquement, $id_unite_peche, $id_espece),
                            $this->generer_requete_analyse($annee,$mois,$id_region,$id_district,'*', $id_unite_peche, '*'));

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
                                $tab_capture_par_espece =   $this->Fiche_echantillonnage_captureManager->capture_total_par_espece(
                                                            $this->generer_requete_analyse($annee,$value->mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece),$value->id_reg,$value->id_unite,$value->mois);
                              
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
                                $tab_espece               = array();
                                    if ($tab_capture_par_espece) 
                                    {
                                        foreach ($tab_capture_par_espece as $val)
                                        {
                                            $tab_capture_espece_total['capture']=($val->capture_total_par_espece/$value->capture_total_par_unite)*($captures_t);
                                            $tab_capture_espece_total['prix']=(($val->capture_total_par_espece/$value->capture_total_par_unite)*($captures_t/1000))*$val->prix;
                                            $tab_capture_espece_total['nom_local']=$val->nom_local;
                                            $tab_capture_espece_total['nom_scientifique']=$val->nom_scientifique;
                                            $tab_capture_espece_total['code']=$val->coda;
                                            $tab_capture_espece_total['erreur_relative']=$erreur_relative;
                                            $tab_capture_espece_total['erreur_relative_capture']=$erreur_relative_capture_total_90;
                                            $tab_capture_espece_total['unite_peche']=$value->libelle;
                                            $tab_capture_espece_total['site_embarquement']=$value->site_embarquement;
                                            $tab_capture_espece_total['mois']=$value->mois;
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
                                        $nom_scientifique='';
                                        $nom_local='';
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
                                                    $nom_scientifique=$valuev['nom_scientifique'];
                                                    $nom_local=$valuev['nom_local'];
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
                                            $final['espece_nom_scientifique']=$nom_scientifique;
                                            $final['espece_nom_local']=$nom_local;
                                            $data[$y]=$final;
                                            $y++;
                                        }
                                       
                                    }
                                    
                                    
                                }
                            }
                        }
                        

                        $Total_captur  = array_column($data, 'capture');
                        $total_capture = array_sum($Total_captur);

                        $Total_pri     = array_column($data, 'prix');
                        $total_prix    = array_sum($Total_pri);

                      $erreur_capture = array_column($data, 'erreur_rel_capture');
                        $n_capture    = count($erreur_capture);
                        $erreur_rel_capture= number_format ( array_sum($erreur_capture)/$n_capture,0);

                        $erreur = array_column($data, 'erreur_relative');
                        $n      = count($erreur);
                        $erreur_relative = number_format ( array_sum($erreur)/$n,0);
                    }
                
                }
            //Pivot mois espece

//********Fin Requete L4.5 Annee Mois Site unite Espèce Capture Valeur********// 

                $total['total_prix'] = $total_prix ;
                    $total['total_capture'] = $total_capture ;
                    $total['erreur_relative_capture_total'] = $erreur_rel_capture ;
                   $total['erreur_relative_total'] = $erreur_relative ;

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