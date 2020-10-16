<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_reporting_halieutique extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_reporting_halieutique_model', 'halManager');
    }

    public function index_get() 
    {
        $etat = $this->get('etat');

        switch ($etat) 
        {
            //COLLECTE
                case 'sip_get_somme_capture_all_espece_by_dist':
                {

                    $data = $this->halManager->get_somme_capture_all_espece_by_dist();
                    break;
                }

                case 'sip_quantite_collecte_region':
                {

                    $data = $this->halManager->sip_quantite_collecte_region();
                    break;
                }

                case 'sip_quantite_collecte_mois':
                {

                    $data = $this->halManager->sip_quantite_collecte_mois();
                    break;
                }

                case 'sip_quantite_collecte_operateur':
                {

                    $data = $this->halManager->sip_quantite_collecte_operateur();
                    break;
                }
                case 'sip_quantite_collecte_espece':
                {

                    $data = $this->halManager->sip_quantite_collecte_espece();
                    break;
                }

                case 'sip_prix_moyenne_mois':
                {

                    $data = $this->halManager->sip_prix_moyenne_mois();
                    break;
                }

                case 'sip_prix_moyenne_district':
                {

                    $data = $this->halManager->sip_prix_moyenne_district();
                    break;
                }

                case 'sip_prix_moyenne_region':
                {

                    $data = $this->halManager->sip_prix_moyenne_region();
                    break;
                }
            //FIN COLLECTE

            //COMMERCE MARINE
                //VENTE LOCALE
                    case 'sip_qte_par_espece_vente_local':
                    {

                        $data = $this->halManager->sip_qte_par_espece_vente_local();
                        break;
                    }

                    case 'sip_qte_par_district_espece_vente_local':
                    {

                        $data = $this->halManager->sip_qte_par_district_espece_vente_local();
                        break;
                    }

                    case 'sip_qte_par_region_espece_vente_local':
                    {

                        $data = $this->halManager->sip_qte_par_region_espece_vente_local();
                        break;
                    }

                    case 'sip_prix_moyenne_par_espece_vente_local':
                    {

                        $data = $this->halManager->sip_prix_moyenne_par_espece_vente_local();
                        break;
                    }

                    case 'sip_prix_moyenne_par_district_vente_local':
                    {

                        $data = $this->halManager->sip_prix_moyenne_par_district_vente_local();
                        break;
                    }

                    case 'sip_prix_moyenne_par_region_vente_local':
                    {

                        $data = $this->halManager->sip_prix_moyenne_par_region_vente_local();
                        break;
                    }

                    case 'sip_qte_par_operateur_vente_local':
                    {

                        $data = $this->halManager->sip_qte_par_operateur_vente_local();
                        break;
                    }

                //FIN VENTE LOCALE 
                //EXPEDITION INERNE

                    case 'sip_qte_par_espece_expedition_interne':
                    {

                        $data = $this->halManager->sip_qte_par_espece_expedition_interne();
                        break;
                    }

                    case 'sip_qte_par_district_espece_expedition_interne':
                    {

                        $data = $this->halManager->sip_qte_par_district_espece_expedition_interne();
                        break;
                    }

                    case 'sip_qte_par_region_espece_expedition_interne':
                    {

                        $data = $this->halManager->sip_qte_par_region_espece_expedition_interne();
                        break;
                    }

                    case 'sip_prix_moyenne_par_espece_expedition_interne':
                    {

                        $data = $this->halManager->sip_prix_moyenne_par_espece_expedition_interne();
                        break;
                    }

                    case 'sip_prix_moyenne_par_district_expedition_interne':
                    {

                        $data = $this->halManager->sip_prix_moyenne_par_district_expedition_interne();
                        break;
                    }

                    case 'sip_prix_moyenne_par_region_expedition_interne':
                    {

                        $data = $this->halManager->sip_prix_moyenne_par_region_expedition_interne();
                        break;
                    }

                    case 'sip_qte_par_operateur_expedition_interne':
                    {

                        $data = $this->halManager->sip_qte_par_operateur_expedition_interne();
                        break;
                    }


                //FIN EXPEDITION INTERNE
                //EXPORTATION
                    case 'sip_qte_par_espece_exportation':
                    {

                        $data = $this->halManager->sip_qte_par_espece_exportation();
                        break;
                    }

                    case 'sip_qte_par_district_espece_exportation':
                    {

                        $data = $this->halManager->sip_qte_par_district_espece_exportation();
                        break;
                    }

                    case 'sip_qte_par_region_espece_exportation':
                    {

                        $data = $this->halManager->sip_qte_par_region_espece_exportation();
                        break;
                    }

                    case 'sip_prix_moyenne_par_espece_exportation':
                    {

                        $data = $this->halManager->sip_prix_moyenne_par_espece_exportation();
                        break;
                    }

                    case 'sip_prix_moyenne_par_district_exportation':
                    {

                        $data = $this->halManager->sip_prix_moyenne_par_district_exportation();
                        break;
                    }

                    case 'sip_prix_moyenne_par_region_exportation':
                    {

                        $data = $this->halManager->sip_prix_moyenne_par_region_exportation();
                        break;
                    }
                //FIN EXPORTATION


                //QTE POIDS VIF
                    case 'sip_qte_vente_locale_qte_poids_vif':
                    {

                        $data = $this->halManager->sip_qte_vente_locale_qte_poids_vif();
                        break;
                    }
                    case 'sip_qte_expedition_interne_qte_poids_vif':
                    {

                        $data = $this->halManager->sip_qte_expedition_interne_qte_poids_vif();
                        break;
                    }
                    case 'sip_qte_exportation_qte_poids_vif':
                    {

                        $data = $this->halManager->sip_qte_exportation_qte_poids_vif();
                        break;
                    }

                    case 'sip_qte_expedie_par_desination':
                    {

                        $data = $this->halManager->sip_qte_expedie_par_desination();
                        break;
                    }
                //FIN QTE POIDS VIF
            //FIN COMMERCE MARINE

            //COMMERCE EAU DOUCE
                //VENTE LOCALE
                    case 'sip_qte_par_espece_vente_local_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_par_espece_comm_eau_douce('vl_qte');
                        break;
                    }

                    case 'sip_qte_par_district_espece_vente_local_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_par_district_comm_eau_douce('vl_qte');
                        break;
                    }

                    case 'sip_qte_par_region_espece_vente_local_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_par_region_comm_eau_douce('vl_qte');
                        break;
                    }

                    case 'sip_prix_moyenne_par_espece_vente_local_eau_douce':
                    {

                        $data = $this->halManager->sip_prix_moy_espece_comm_eau_douce('vl_prix_par_kg');
                        break;
                    }

                    case 'sip_prix_moyenne_par_district_vente_local_eau_douce':
                    {

                        $data = $this->halManager->sip_prix_moy_district_comm_eau_douce('vl_prix_par_kg');
                        break;
                    }

                    case 'sip_prix_moyenne_par_region_vente_local_eau_douce':
                    {

                        $data = $this->halManager->sip_prix_moy_region_comm_eau_douce('vl_prix_par_kg');
                        break;
                    }

                    case 'sip_qte_par_operateur_vente_local_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_par_operateur_comm_eau_douce('vl_qte');
                        break;
                    }

                //FIN VENTE LOCALE 
                //EXPEDITION INERNE

                    case 'sip_qte_par_espece_expedition_interne_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_par_espece_comm_eau_douce('exp_qte');
                        break;
                    }

                    case 'sip_qte_par_district_espece_expedition_interne_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_par_district_comm_eau_douce('exp_qte');
                        break;
                    }

                    case 'sip_qte_par_region_espece_expedition_interne_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_par_region_comm_eau_douce('exp_qte');
                        break;
                    }

                    case 'sip_prix_moyenne_par_espece_expedition_interne_eau_douce':
                    {

                        $data = $this->halManager->sip_prix_moy_espece_comm_eau_douce('exp_prix_par_kg');
                        break;
                    }

                    case 'sip_prix_moyenne_par_district_expedition_interne_eau_douce':
                    {

                        $data = $this->halManager->sip_prix_moy_district_comm_eau_douce('exp_prix_par_kg');
                        break;
                    }

                    case 'sip_prix_moyenne_par_region_expedition_interne_eau_douce':
                    {

                        $data = $this->halManager->sip_prix_moy_region_comm_eau_douce('exp_prix_par_kg');
                        break;
                    }

                    case 'sip_qte_par_operateur_expedition_interne_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_par_operateur_comm_eau_douce('exp_qte');
                        break;
                    }


                //FIN EXPEDITION INTERNE
                //EXPORTATION
                    case 'sip_qte_par_espece_exportation_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_par_espece_comm_eau_douce('export_qte');
                        break;
                    }

                    case 'sip_qte_par_district_espece_exportation_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_par_district_comm_eau_douce('export_qte');
                        break;
                    }

                    case 'sip_qte_par_region_espece_exportation_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_par_region_comm_eau_douce('export_qte');
                        break;
                    }

                    case 'sip_prix_moyenne_par_espece_exportation_eau_douce':
                    {

                        $data = $this->halManager->sip_prix_moy_espece_comm_eau_douce('export_prix_par_kg');
                        break;
                    }

                    case 'sip_prix_moyenne_par_district_exportation_eau_douce':
                    {

                        $data = $this->halManager->sip_prix_moy_district_comm_eau_douce('export_prix_par_kg');
                        break;
                    }

                    case 'sip_prix_moyenne_par_region_exportation_eau_douce':
                    {

                        $data = $this->halManager->sip_prix_moy_region_comm_eau_douce('export_prix_par_kg');
                        break;
                    }

                    case 'sip_qte_par_operateur_exportation_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_par_operateur_comm_eau_douce('export_qte');
                        break;
                    }
                //FIN EXPORTATION


                //QTE POIDS VIF
                    case 'sip_qte_vente_locale_qte_poids_vif_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_par_espece_comm_eau_douce('vl_poids_vif');
                        break;
                    }
                    case 'sip_qte_expedition_interne_qte_poids_vif_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_par_espece_comm_eau_douce('exp_poids_vif');
                        break;
                    }
                    case 'sip_qte_exportation_qte_poids_vif_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_par_espece_comm_eau_douce('export_poids_vif');
                        break;
                    }

                    case 'sip_qte_expedie_par_desination_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_expedie_par_desination_eau_douce();//mbolamila amboarina
                        break;
                    }
                //FIN QTE POIDS VIF
            //FIN COMMERCE EAU DOUCE


            
            default:
               
                break;
        }

         
    
         
        if ($data) 
        {
            $this->response([
                'status' => TRUE,
                'response' => $data,
                'message' => 'Get data success',
            ], REST_Controller::HTTP_OK);
        } 
        else 
        {
            $this->response([
                'status' => FALSE,
                'response' => array(),
                'message' => 'No data were found'
            ], REST_Controller::HTTP_OK);
        }
    }
   
}
/* End of file controllername.php */
/* Location: ./application/controllers/controllername.php */
