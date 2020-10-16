<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_reporting_crevette extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_reporting_crevette_model', 'crevManager');
    }

    public function index_get() 
    {
        $etat = $this->get('etat');

        switch ($etat) 
        {
            //RELEVE CAPTURE
                    case 'tableau_de_bord_maree':
                    {

                        $data = $this->crevManager->tableau_de_bord_maree();
                        break;
                    }
                    case 'nombre_fiche_par_maree':
                    {

                        $data = $this->crevManager->nombre_fiche_par_maree();
                        break;
                    }
                    case 'qte_crevette_par_maree':
                    {

                        $data = $this->crevManager->qte_par_maree('qte_crevette');
                        break;
                    }

                    case 'qte_poisson_par_maree':
                    {

                        $data = $this->crevManager->qte_par_maree('qte_poisson');
                        break;
                    }

                    case 'qte_crevette_par_bateau':
                    {

                        $data = $this->crevManager->sip_qte_par_bateau_peche_crevette('qte_crevette');
                        break;
                    }

                    case 'qte_poisson_par_bateau':
                    {

                        $data = $this->crevManager->sip_qte_par_bateau_peche_crevette('qte_poisson');
                        break;
                    }

                    case 'qte_crevette_par_societe':
                    {

                        $data = $this->crevManager->sip_qte_par_societe_peche_crevette('qte_crevette');
                        break;
                    }

                    case 'qte_poisson_par_societe':
                    {

                        $data = $this->crevManager->sip_qte_par_societe_peche_crevette('qte_poisson');
                        break;
                    }

                //COMMERCE

                    case 'qte_vente_locale_commerce':
                    {

                        $data = $this->crevManager->sip_qte_commercialise_crevette('qte_vl');
                        break;
                    }

                    case 'qte_exportation_commerce':
                    {

                        $data = $this->crevManager->sip_qte_commercialise_crevette('qte_export');
                        break;
                    }

                    case 'qte_vente_locale_par_mois_commerce':
                    {

                        $data = $this->crevManager->sip_qte_par_mois_commercialise_crevette('qte_vl');
                        break;
                    }

                    case 'qte_exportation_par_mois_commerce':
                    {

                        $data = $this->crevManager->sip_qte_par_mois_commercialise_crevette('qte_export');
                        break;
                    }


                    case 'qte_vente_locale_par_societe_commerce':
                    {

                        $data = $this->crevManager->sip_qte_par_societe_commercialise_crevette('qte_vl');
                        break;
                    }

                    case 'qte_exportation_par_societe_commerce':
                    {

                        $data = $this->crevManager->sip_qte_par_societe_commercialise_crevette('qte_export');
                        break;
                    }



                    case 'prix_moy_vente_locale_mois_commerce':
                    {

                        $data = $this->crevManager->sip_prix_moy_par_mois_commercialise_crevette('pum_vl');
                        break;
                    }

                    case 'prix_moy_exportation_mois_commerce':
                    {

                        $data = $this->crevManager->sip_prix_moy_par_mois_commercialise_crevette('pum_export');
                        break;
                    }

                    case 'sip_qte_exporte_par_dest_commerce_crevette':
                    {

                        $data = $this->crevManager->sip_qte_exporte_par_dest_commerce_crevette();
                        break;
                    }

                //FIN COMMERCE

            //FIN RELEVE CAPTURE

          
            
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
