<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_collecteur_mareyeur extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_collecteur_mareyeur_model', 'SIP_collecteur_mareyeurManager');
    }

    public function index_get() 
    {
        $id = $this->get('id');
            $data = array();
            if ($id) 
            {
                
                $SIP_collecteur_mareyeur = $this->SIP_collecteur_mareyeurManager->findById($id);
                $data['id'] = $SIP_collecteur_mareyeur->id;
                $data['code'] = $SIP_collecteur_mareyeur->code;
                $data['nom'] = $SIP_collecteur_mareyeur->nom;
            } 
            else 
            {
                $response = $this->SIP_collecteur_mareyeurManager->findAll();
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
                    'code'                  => $this->post('code'),
                    'nom'                   => $this->post('nom'),
                    'type_genre'            => $this->post('type_genre'),
                    'adresse'               => $this->post('adresse'),
                    'ref_autorisation'      => $this->post('ref_autorisation'),
                    'is_coll_eau_douce'     => $this->post('is_coll_eau_douce'),
                    'is_coll_marine'        => $this->post('is_coll_marine'),
                    'is_mareyeur'           => $this->post('is_mareyeur')
                );
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->SIP_collecteur_mareyeurManager->add($data);
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
                    'code'                  => $this->post('code'),
                    'nom'                   => $this->post('nom'),
                    'type_genre'            => $this->post('type_genre'),
                    'adresse'               => $this->post('adresse'),
                    'ref_autorisation'      => $this->post('ref_autorisation'),
                    'is_coll_eau_douce'     => $this->post('is_coll_eau_douce'),
                    'is_coll_marine'        => $this->post('is_coll_marine'),
                    'is_mareyeur'           => $this->post('is_mareyeur')
                );

                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->SIP_collecteur_mareyeurManager->update($id, $data);
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
            $delete = $this->SIP_collecteur_mareyeurManager->delete($id);         
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
