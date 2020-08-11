<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_permis extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_permis_model', 'SIP_permisManager');
    }

    public function index_get() 
    {
        $id = $this->get('id');
        $id_collecteurs = $this->get('id_collecteurs');
            $data = array();
            if ($id) 
            {
                
                $SIP_permis = $this->SIP_permisManager->findById($id);
                $data['id'] = $SIP_permis->id;
                $data['code'] = $SIP_permis->code;
                $data['nom'] = $SIP_permis->nom;
            } 
            else 
            {
                $response = $this->SIP_permisManager->find_all_join($id_collecteurs);
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
                    'id_collecteur_mareyeur'            => $this->post('id_collecteur_mareyeur'),
                    //'id_espece'                         => $this->post('id_espece'),
                    'id_district'                       => $this->post('id_district'),
                    'numero_permis'                     => $this->post('numero_permis'),
                    'date_quittance'                    => $this->post('date_quittance')
                );
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->SIP_permisManager->add($data);
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
                    'id_collecteur_mareyeur'            => $this->post('id_collecteur_mareyeur'),
                    //'id_espece'                         => $this->post('id_espece'),
                    'id_district'                       => $this->post('id_district'),
                    'numero_permis'                     => $this->post('numero_permis'),
                    'date_quittance'                    => $this->post('date_quittance')
                );

                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->SIP_permisManager->update($id, $data);
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
            $delete = $this->SIP_permisManager->delete($id);         
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
