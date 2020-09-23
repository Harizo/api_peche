<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_donnees_cadre_enquete_marche extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_donnees_cadre_enquete_marche_model', 'SIP_donnees_cadre_enquete_marcheManager');
    }

    public function index_get() 
    {
        $id = $this->get('id');
        $id_district = $this->get('id_district');
            $data = array();
            if ($id) 
            {
                
                $data = $this->SIP_donnees_cadre_enquete_marcheManager->findById($id);
                
            } 
            else 
            {
                if ($id_district) 
                {
                   $response = $this->SIP_donnees_cadre_enquete_marcheManager->find_all_by_district($id_district);
                    if ($response) 
                    {
                        $data = $response ;
                    }
                }
                else
                {
                    $response = $this->SIP_donnees_cadre_enquete_marcheManager->findAll();
                    if ($response) 
                    {
                        $data = $response ;
                    }
                }

            }
        if (count($data)>0) 
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
    public function index_post() 
    {
        $id = $this->post('id') ;
        $supprimer = $this->post('supprimer') ;
        if ($supprimer == 0) 
        {
            if ($id == 0) 
            {
                $data = array(
                 
                    'id_commune'                    =>      $this->post('id_commune'),
                    'nom_marche'                    =>      $this->post('nom_marche'),      
                    'nom_ville'                     =>      $this->post('nom_ville'),      
                    'nbr_jour_ouvrable_mois'        =>      $this->post('nbr_jour_ouvrable_mois') ,
                    'affluence_max'                 =>      $this->post('affluence_max') ,
                    'nom_vendeur_spec_frais'        =>      $this->post('nom_vendeur_spec_frais') ,
                    'nom_vendeur_spec_transforme'   =>      $this->post('nom_vendeur_spec_transforme') ,
                    'id_espece'                     =>      $this->post('id_espece') ,
                    'nbr_tot_etal'                  =>      $this->post('nbr_tot_etal') ,
                    'nbr_etal_pdt_frais'            =>      $this->post('nbr_etal_pdt_frais') ,
                    'nbr_etal_pdt_transforme'       =>      $this->post('nbr_etal_pdt_transforme') 
                );
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->SIP_donnees_cadre_enquete_marcheManager->add($data);
                if (!is_null($dataId)) {
                    $this->response([
                        'status' => TRUE,
                        'response' => $dataId,
                        'message' => 'Data insert success'
                            ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'insertion annuler'
                            ], REST_Controller::HTTP_OK);
                }
            } 
            else 
            {
                $data = array(
                    'id_commune'                    =>      $this->post('id_commune'),
                    'nom_marche'                    =>      $this->post('nom_marche'),      
                    'nom_ville'                     =>      $this->post('nom_ville'),      
                    'nbr_jour_ouvrable_mois'        =>      $this->post('nbr_jour_ouvrable_mois') ,
                    'affluence_max'                 =>      $this->post('affluence_max') ,
                    'nom_vendeur_spec_frais'        =>      $this->post('nom_vendeur_spec_frais') ,
                    'nom_vendeur_spec_transforme'   =>      $this->post('nom_vendeur_spec_transforme') ,
                    'id_espece'                     =>      $this->post('id_espece') ,
                    'nbr_tot_etal'                  =>      $this->post('nbr_tot_etal') ,
                    'nbr_etal_pdt_frais'            =>      $this->post('nbr_etal_pdt_frais') ,
                    'nbr_etal_pdt_transforme'       =>      $this->post('nbr_etal_pdt_transforme') 
                );

                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->SIP_donnees_cadre_enquete_marcheManager->update($id, $data);
                if(!is_null($update)) {
                    $this->response([
                        'status' => TRUE,
                        'response' => 1,
                        'message' => 'Update data success'
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Mis à jour annuler'
                    ], REST_Controller::HTTP_OK);
                }
            }
        } 
        else 
        {
            if (!$id) {
                $this->response([
                    'status' => FALSE,
                    'response' => 0,
                    'message' => 'No request found'
                        ], REST_Controller::HTTP_BAD_REQUEST);
            }
            $delete = $this->SIP_donnees_cadre_enquete_marcheManager->delete($id);         
            if (!is_null($delete)) {
                $this->response([
                    'status' => TRUE,
                    'response' => 1,
                    'message' => "Delete data success"
                        ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => FALSE,
                    'response' => 0,
                    'message' => 'No request found'
                        ], REST_Controller::HTTP_OK);
            }
        }        
    }
}
/* End of file controllername.php */
/* Location: ./application/controllers/controllername.php */
