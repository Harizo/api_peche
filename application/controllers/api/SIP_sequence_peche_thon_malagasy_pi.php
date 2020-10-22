<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_sequence_peche_thon_malagasy_pi extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_sequence_peche_thon_malagasy_pi_model', 'SIP_sequence_peche_thon_malagasyManager');
    }

    public function index_get() 
    {
        $id = $this->get('id');
        $id_sequence_peche_thon_malagasy = $this->get('id_sequence_peche_thon_malagasy');
            $data = array();
            if ($id) 
            {
                
                $data = $this->SIP_sequence_peche_thon_malagasyManager->findById($id);
                
            } 
            else 
            {
                if ($id_sequence_peche_thon_malagasy) 
                {
                    $response = $this->SIP_sequence_peche_thon_malagasyManager->findAll_by_sequence_peche_thon_malagasy_pi($id_sequence_peche_thon_malagasy);
                    if ($response) 
                    {
                        $data = $response ;
                    }
                }
                else
                {

                    if ($get_nbr_sequence_peche == 1) 
                    {
                        $response = $this->SIP_sequence_peche_thon_malagasyManager->get_nbr_sequence_peche($annee);
                        if ($response) 
                        {
                            $data = $response ;
                        }
                    }
                    else

                        $data = $this->SIP_sequence_peche_thon_malagasyManager->findAll();
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
				'id_sequence_peche_thon_malagasy' => $this->post('id_sequence_peche_thon_malagasy'),
				'date_pi'                         => $this->post('date_pi'),
				// 'annee'                           => $this->post('annee'),
				// 'jour'                            => $this->post('jour'),
				// 'mois'                            => $this->post('mois'),
				'heuret'                          => $this->post('heuret'),
				'minutet'                         => $this->post('minutet'),
				'postlatitude'                    => $this->post('postlatitude'),
				'postlongitude'                   => $this->post('postlongitude'),
				'temperature'                     => $this->post('temperature'),
				'nb_ham_entrflot'                 => $this->post('nb_ham_entrflot'),
				'nb_ham_util'                     => $this->post('nb_ham_util'),
				'appats_util'                     => $this->post('appats_util'),
			);
            if ($id == 0) 
            {
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->SIP_sequence_peche_thon_malagasyManager->add($data);
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
                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->SIP_sequence_peche_thon_malagasyManager->update($id, $data);
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
            $delete = $this->SIP_sequence_peche_thon_malagasyManager->delete($id);         
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
