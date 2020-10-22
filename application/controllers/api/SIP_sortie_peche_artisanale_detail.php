<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_sortie_peche_artisanale_detail extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_sortie_peche_artisanale_detail_model', 'SIP_sortie_peche_artisanaledetailManager');
    }

    public function index_get() 
    {
        $id = $this->get('id');
        $id_sip_sortie_peche_artisanale = $this->get('id_sip_sortie_peche_artisanale');
            $data = array();
            if ($id) 
            {               
                $data = $this->SIP_sortie_peche_artisanaledetailManager->findById($id);               
            } 
            else if($id_sip_sortie_peche_artisanale)
            {
                $response = $this->SIP_sortie_peche_artisanaledetailManager->findById_sortie_peche_artisanale($id_sip_sortie_peche_artisanale);
                if ($response) 
                {
                    $data = $response ;
                }
            }
			else {
                $response = $this->SIP_sortie_peche_artisanaledetailManager->findAll();
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
				'id_sip_sortie_peche_artisanale' =>  $this->post('id_sip_sortie_peche_artisanale'),              
				'id_espece'                      =>  $this->post('id_espece'),              
				'quantite'                       =>  $this->post('quantite'),              
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
                $dataId = $this->SIP_sortie_peche_artisanaledetailManager->add($data);
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
                $update = $this->SIP_sortie_peche_artisanaledetailManager->update($id, $data);
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
            $delete = $this->SIP_sortie_peche_artisanaledetailManager->delete($id);         
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
