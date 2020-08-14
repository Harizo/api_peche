<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_sequence_peche extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_sequence_peche_model', 'SIP_sequence_pecheManager');
    }

    public function index_get() 
    {
        $id = $this->get('id');
        $annee = $this->get('annee');
        $get_nbr_sequence_peche = $this->get('get_nbr_sequence_peche');
        $id_fiche_peche_crevette = $this->get('id_fiche_peche_crevette');
            $data = array();
            if ($id) 
            {
                
                $data = $this->SIP_sequence_pecheManager->findById($id);
                
            } 
            else 
            {
                if ($id_fiche_peche_crevette) 
                {
                    $response = $this->SIP_sequence_pecheManager->findAll_by_fiche_peche_crevette($id_fiche_peche_crevette);
                    if ($response) 
                    {
                        $data = $response ;
                    }
                }
                else
                {

                    if ($get_nbr_sequence_peche == 1) 
                    {
                        $response = $this->SIP_sequence_pecheManager->get_nbr_sequence_peche($annee);
                        if ($response) 
                        {
                            $data = $response ;
                        }
                    }
                    else

                        $data = $this->SIP_sequence_pecheManager->findAll();
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
                    'id_fiche_peche_crevette'       => $this->post('id_fiche_peche_crevette'),
                    'numseqpeche'                          => $this->post('numseqpeche')
                );
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->SIP_sequence_pecheManager->add($data);
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
                    'id_fiche_peche_crevette'       => $this->post('id_fiche_peche_crevette'),
                    'numseqpeche'                          => $this->post('numseqpeche')
                );
                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->SIP_sequence_pecheManager->update($id, $data);
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
            $delete = $this->SIP_sequence_pecheManager->delete($id);         
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
