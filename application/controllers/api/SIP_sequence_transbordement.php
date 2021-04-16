<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_sequence_transbordement extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_sequence_transbordement_model', 'SIP_sequence_transbordementManager');
    }

    public function index_get() 
    {
        $id = $this->get('id');
        $id_sequence_peche = $this->get('id_sequence_peche');
            $data = array();
            if ($id) 
            {
                
                $data = $this->SIP_sequence_transbordementManager->findById($id);
                
            } 
            else 
            {
                if ($id_sequence_peche) 
                {
                    $response = $this->SIP_sequence_transbordementManager->findAll_by_fiche_peche_crevette($id_sequence_peche);
                    if ($response) 
                    {
                        $data = $response ;
                    }
                }
                else
                {
                    $data = $this->SIP_sequence_transbordementManager->findAll();
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
                    'id_sequence_peche'             => $this->post('id_sequence_peche'),
                    'date'                          => $this->post('date'),
                    'heurep'                        => $this->post('heurep'),
                    'minutep'                       => $this->post('minutep'),
                    'heuret'                        => $this->post('heuret'),
                    'minutet'                       => $this->post('minutet'),
                    'postlatitude'                  => $this->post('postlatitude'),
                    'postlongitude'                 => $this->post('postlongitude'),
                    'id_navire'                     => $this->post('id_navire')
                );
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->SIP_sequence_transbordementManager->add($data);
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
                    'id_sequence_peche'             => $this->post('id_sequence_peche'),
                    'date'                          => $this->post('date'),
                    'heurep'                        => $this->post('heurep'),
                    'minutep'                       => $this->post('minutep'),
                    'heuret'                        => $this->post('heuret'),
                    'minutet'                       => $this->post('minutet'),
                    'postlatitude'                  => $this->post('postlatitude'),
                    'postlongitude'                 => $this->post('postlongitude'),
                    'id_navire'                     => $this->post('id_navire')
                );

                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->SIP_sequence_transbordementManager->update($id, $data);
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
            $delete = $this->SIP_sequence_transbordementManager->delete($id);         
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
