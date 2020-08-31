<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_conservation extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_conservation_model', 'SIP_conservationManager');
    }

    public function index_get() 
    {
        $id = $this->get('id');
        $data = array();
       
       /*     if ($id) 
            {
                
                $SIP_conservation = $this->SIP_conservationManager->findById($id);
                $data['id'] = $SIP_conservation->id;
                $data['code'] = $SIP_conservation->code;
                $data['nom'] = $SIP_conservation->nom;
            } 
            else 
            {
                $response = $this->SIP_conservationManager->findAll();
                if ($response) 
                {
                    $data = $response ;
                }

            }
        */
       
        if ($id) 
        {
            $data = $this->SIP_conservationManager->findById($id);
        } 
        else 
        {
            $response = $this->SIP_conservationManager->findAll();
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
     
             $data = array(
                'libelle'       => $this->post('libelle')
            );
                
            if ($id == 0) 
            {
               /*
                $data = array(
                    'id_collecteurs'                    => $this->post('id_collecteurs'),
                    'id_espece'                         => $this->post('id_espece'),
                    'id_district'                       => $this->post('id_district'),
                    'annee'                             => $this->post('annee'),
                    'mois'                              => $this->post('mois'),
                    'id_conservation'                   => $this->post('id_conservation'),
                    'quantite'                          => $this->post('quantite'),
                    'prix'                              => $this->post('prix'),
                    'id_presentation'                   => $this->post('id_presentation'),
                    'coefficiant_conservation'          => $this->post('coefficiant_conservation'),
                    'valeur'                            => $this->post('valeur')
                );
                */
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->SIP_conservationManager->add($data);
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
                /*
                $data = array(
                    'id_collecteurs'                    => $this->post('id_collecteurs'),
                    'id_espece'                         => $this->post('id_espece'),
                    'id_district'                       => $this->post('id_district'),
                    'annee'                             => $this->post('annee'),
                    'mois'                              => $this->post('mois'),
                    'id_conservation'                   => $this->post('id_conservation'),
                    'quantite'                          => $this->post('quantite'),
                    'prix'                              => $this->post('prix'),
                    'id_presentation'                   => $this->post('id_presentation'),
                    'coefficiant_conservation'          => $this->post('coefficiant_conservation'),
                    'valeur'                            => $this->post('valeur')
                );
                */

                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->SIP_conservationManager->update($id, $data);
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
            $delete = $this->SIP_conservationManager->delete($id);         
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
