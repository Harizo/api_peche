<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_production_crevette extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_production_crevette_model', 'SIP_production_crevetteManager');
    }

    public function index_get() 
    {
        $id = $this->get('id');
        $id_bateau_crevette = $this->get('id_bateau_crevette');
            $data = array();
            if ($id) 
            {
                
                $data = $this->SIP_production_crevetteManager->findById($id);
                
            } 
            else 
            {
                $response = $this->SIP_production_crevetteManager->findAllbybateau($id_bateau_crevette);
                if ($response) 
                {
                    $data = $response ;
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
                 
                    'id_bateau_crevette'     =>      $this->post('id_bateau_crevette'),
                    'zone_peche'             =>      $this->post('zone_peche'),      
                    'annee'                  =>      $this->post('annee'),      
                    'num_maree'              =>      $this->post('num_maree'),      
                    'maree'                  =>      $this->post('maree'),      
                    'qte_crevette'           =>      $this->post('qte_crevette'),      
                    'qte_poisson'           =>      $this->post('qte_poisson'),      
                    'nbr_fiche'              =>      $this->post('nbr_fiche')
                );

                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->SIP_production_crevetteManager->add($data);
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
                    'id_bateau_crevette'     =>      $this->post('id_bateau_crevette'),
                    'zone_peche'             =>      $this->post('zone_peche'),      
                    'annee'                  =>      $this->post('annee'),      
                    'num_maree'              =>      $this->post('num_maree'),      
                    'maree'                  =>      $this->post('maree'),      
                    'qte_crevette'           =>      $this->post('qte_crevette'),      
                    'qte_poisson'           =>      $this->post('qte_poisson'),      
                    'nbr_fiche'              =>      $this->post('nbr_fiche')
                );

                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->SIP_production_crevetteManager->update($id, $data);
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
            $delete = $this->SIP_production_crevetteManager->delete($id);         
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
