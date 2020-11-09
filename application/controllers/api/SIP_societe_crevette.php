<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_societe_crevette extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_societe_crevette_model', 'SIP_societe_crevetteManager');
    }

    public function index_get() 
    {
        $id = $this->get('id');
        $id_base_cote = $this->get('id_base_cote');
        $id_base_geo = $this->get('id_base_geo');
        $data = array();
        if (($id_base_geo) || ($id_base_cote)) {
            if ($id_base_geo) {
                $data = $this->SIP_societe_crevetteManager->findBaseGeo($id_base_geo);
            }

            if ($id_base_cote) {
               $data = $this->SIP_societe_crevetteManager->findBaseCote($id_base_cote);
            }
        } else {
            if ($id) 
            {
                
                $SIP_societe_crevette = $this->SIP_societe_crevetteManager->findById($id);
                $data['id'] = $SIP_societe_crevette->id;
                $data['code'] = $SIP_societe_crevette->code;
                $data['nom'] = $SIP_societe_crevette->nom;
            } 
            else 
            {
                $response = $this->SIP_societe_crevetteManager->findAll();
                if ($response) 
                {
                    $data = $response ;
                }

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
                    'code'                  =>      $this->post('code'),
		            'nom'                   =>      $this->post('nom'),                 
		            'deb_validite'          =>      $this->post('deb_validite'),                 
		            'fin_validite'          =>      $this->post('fin_validite'),                 
		            'base_geo'      		=>      $this->post('base_geo'),                 
		            'base_cote'     		=>      $this->post('base_cote'),                 
		            'an_creation'        	=>      $this->post('an_creation'),                 
		            'type'           		=>      $this->post('type')  
                );
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->SIP_societe_crevetteManager->add($data);
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
                    'code'                  =>      $this->post('code'),
		            'nom'                   =>      $this->post('nom'),                 
		            'deb_validite'          =>      $this->post('deb_validite'),                 
		            'fin_validite'          =>      $this->post('fin_validite'),                 
		            'base_geo'      		=>      $this->post('base_geo'),                 
		            'base_cote'     		=>      $this->post('base_cote'),                 
		            'an_creation'        	=>      $this->post('an_creation'),                 
		            'type'           		=>      $this->post('type') 
                );

                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->SIP_societe_crevetteManager->update($id, $data);
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
            $delete = $this->SIP_societe_crevetteManager->delete($id);         
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
