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
