<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_saisie_collecte_halieutique extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_saisie_collecte_halieutique_model', 'SIP_saisie_collecte_halieutiqueManager');
    }

    public function index_get() 
    {
        $id = $this->get('id');
        $id_permis = $this->get('id_permis');
            $data = array();
            if ($id) 
            {
                
                $SIP_saisie_collecte_halieutique = $this->SIP_saisie_collecte_halieutiqueManager->findById($id);
                $data['id'] = $SIP_saisie_collecte_halieutique->id;
                $data['code'] = $SIP_saisie_collecte_halieutique->code;
                $data['nom'] = $SIP_saisie_collecte_halieutique->nom;
            } 
            else 
            {
                $response = $this->SIP_saisie_collecte_halieutiqueManager->find_all_join($id_permis);
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
                    'id_permis'                    => $this->post('id_permis'),
                    'annee'                             => $this->post('annee'),
                    'mois'                              => $this->post('mois'),
                    'id_conservation'                   => $this->post('id_conservation'),
                    'quantite'                          => $this->post('quantite'),
                    'prix'                              => $this->post('prix'),
                    'id_presentation'                   => $this->post('id_presentation'),
                    'coefficiant_conservation'          => $this->post('coefficiant_conservation'),
                    'valeur'                            => $this->post('valeur')
                );
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->SIP_saisie_collecte_halieutiqueManager->add($data);
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
                    'id_permis'                    => $this->post('id_permis'),
                    'annee'                             => $this->post('annee'),
                    'mois'                              => $this->post('mois'),
                    'id_conservation'                   => $this->post('id_conservation'),
                    'quantite'                          => $this->post('quantite'),
                    'prix'                              => $this->post('prix'),
                    'id_presentation'                   => $this->post('id_presentation'),
                    'coefficiant_conservation'          => $this->post('coefficiant_conservation'),
                    'valeur'                            => $this->post('valeur')
                );

                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->SIP_saisie_collecte_halieutiqueManager->update($id, $data);
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
            $delete = $this->SIP_saisie_collecte_halieutiqueManager->delete($id);         
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
