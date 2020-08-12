<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_navire extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_navire_model', 'SIP_navireManager');
    }

    public function index_get() 
    {
        $id = $this->get('id');
            $data = array();
            if ($id) 
            {               
                $data = $this->SIP_navireManager->findById($id);               
            } 
            else 
            {
                $response = $this->SIP_navireManager->findAll();
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
                    'immatricule'       =>  $this->post('immatricule'),              
                    'nom'               =>  $this->post('nom'),              
                    'pavillon'          =>  $this->post('pavillon'),              
                    'armateur'          =>  $this->post('armateur'),              
                    'adresse'           =>  $this->post('adresse'),              
                    'tonnage_brute'     =>  $this->post('tonnage_brute'),              
                    'lht'               =>  $this->post('lht'),              
                    'capacite_cale'     =>  $this->post('capacite_cale'),              
                    'indication_ratio'  =>  $this->post('indication_ratio'),              
                    'type_navire'       =>  $this->post('type_navire'),              
                );
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->SIP_navireManager->add($data);
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
                    'immatricule'       =>  $this->post('immatricule'),              
                    'nom'               =>  $this->post('nom'),              
                    'pavillon'          =>  $this->post('pavillon'),              
                    'armateur'          =>  $this->post('armateur'),              
                    'adresse'           =>  $this->post('adresse'),              
                    'tonnage_brute'     =>  $this->post('tonnage_brute'),              
                    'lht'               =>  $this->post('lht'),              
                    'capacite_cale'     =>  $this->post('capacite_cale'),              
                    'indication_ratio'  =>  $this->post('indication_ratio'),              
                    'type_navire'       =>  $this->post('type_navire'),              
                );
                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->SIP_navireManager->update($id, $data);
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
            $delete = $this->SIP_navireManager->delete($id);         
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
