<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_carte_pecheur extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_carte_pecheur_model', 'SIP_carte_pecheurManager');
    }

    public function index_get() 
    {
        $id = $this->get('id');
        $id_district = $this->get('id_district');
            $data = array();
            if ($id) 
            {
                
                $data = $this->SIP_carte_pecheurManager->findById($id);
                
            } 
            else 
            {
                if ($id_district) 
                {
                   $response = $this->SIP_carte_pecheurManager->find_all_by_district($id_district);
                    if ($response) 
                    {
                        $data = $response ;
                    }
                }
                else
                {
                    $response = $this->SIP_carte_pecheurManager->findAll();
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
                    'numero'                =>      $this->post('numero'),
                    'date'                  =>      $this->post('date'),      
                    'id_fokontany'          =>      $this->post('id_fokontany'),      
                    'village'               =>      $this->post('village') ,
                    'association'           =>      $this->post('association') ,
                    'nom'                   =>      $this->post('nom') ,
                    'prenom'                =>      $this->post('prenom') ,
                    'cin'                   =>      $this->post('cin') ,
                    'date_cin'              =>      $this->post('date_cin') ,
                    'date_naissance'        =>      $this->post('date_naissance') ,
                    'lieu_cin'              =>      $this->post('lieu_cin') ,
                    'nbr_pirogue'           =>      $this->post('nbr_pirogue') 
                );
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->SIP_carte_pecheurManager->add($data);
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
                    'numero'                =>      $this->post('numero'),
                    'date'                  =>      $this->post('date'),      
                    'id_fokontany'          =>      $this->post('id_fokontany'),      
                    'village'               =>      $this->post('village') ,
                    'association'           =>      $this->post('association') ,
                    'nom'                   =>      $this->post('nom') ,
                    'prenom'                =>      $this->post('prenom') ,
                    'cin'                   =>      $this->post('cin') ,
                    'date_cin'              =>      $this->post('date_cin') ,
                    'date_naissance'        =>      $this->post('date_naissance') ,
                    'lieu_cin'              =>      $this->post('lieu_cin') ,
                    'nbr_pirogue'           =>      $this->post('nbr_pirogue') 
                );

                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->SIP_carte_pecheurManager->update($id, $data);
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
            $delete = $this->SIP_carte_pecheurManager->delete($id);         
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
