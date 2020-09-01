<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class SIP_type_espece extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_type_espece_model', 'SIP_type_especeManager');
    }
    public function index_get() {

        $id = $this->get('id');
        $data = array() ;
        if ($id) 
            {
                $data = $this->SIP_type_especeManager->findById($id);
            } 
            else 
            {
                $response = $this->SIP_type_especeManager->findAll();
                if ($response) 
                {
                    $data = $response ;
                }

            }
        if (count($data)>0) {
            $this->response([
                'status' => TRUE,
                'response' => $data,
                'message' => 'Get data success'
            ], REST_Controller::HTTP_OK);
        } 
        else {
            $this->response([
                'status' => FALSE,
                'response' => array(),
                'message' => 'No data were found'
            ], REST_Controller::HTTP_OK);
        }
    }
    public function index_post() {

        $id = $this->post('id') ;
        $supprimer = $this->post('supprimer') ;
        if ($supprimer == 0) {
            $data = array(
                    'libelle' => $this->post('libelle')
                );  
            if ($id == 0) {
                          
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->SIP_type_especeManager->add($data);              
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
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
           } 
           else {
                            
                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->SIP_type_especeManager->update($id, $data);              
                if(!is_null($update)){
                    $this->response([
                        'status' => TRUE, 
                        'response' => 1,
                        'message' => 'Update data success'
                    ], REST_Controller::HTTP_OK);
                }
                else {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_OK);
                }
            }
        } 
        else {
            if (!$id) {
                $this->response([
                'status' => FALSE,
                'response' => 0,
                'message' => 'No request found'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
            $delete = $this->SIP_type_especeManager->delete($id);          
            if (!is_null($delete)) {
                $this->response([
                    'status' => TRUE,
                    'response' => 1,
                    'message' => "Delete data success"
                ], REST_Controller::HTTP_OK);
            } 
            else {
                $this->response([
                    'status' => FALSE,
                    'response' => 0,
                    'message' => 'No request found'
                ], REST_Controller::HTTP_OK);
            }
        }

    }
}
