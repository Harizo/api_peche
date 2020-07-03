<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_bateau_crevette extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_bateau_crevette_model', 'SIP_bateau_crevetteManager');
    }

    public function index_get() 
    {
        $id = $this->get('id');
        $id_societe_crevette = $this->get('id_societe_crevette');
            $data = array();
            if ($id) 
            {
                
                $SIP_bateau_crevette = $this->SIP_bateau_crevetteManager->findById($id);
                $data['id'] = $SIP_bateau_crevette->id;
                $data['code'] = $SIP_bateau_crevette->code;
                $data['nom'] = $SIP_bateau_crevette->nom;
            } 
            else 
            {
                $response = $this->SIP_bateau_crevetteManager->findAllbysociete($id_societe_crevette);
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
                 
                    'id_societe_crevette'           =>      $this->post('id_societe_crevette'),
                    'immatriculation'               =>      $this->post('immatriculation'),      
                    'deb_validite'                  =>      $this->post('deb_validite'),      
                    'fin_validite'                  =>      $this->post('fin_validite'),      
                    'nom'                           =>      $this->post('nom'),      
                    'segment'                       =>      $this->post('segment'),      
                    'type'                          =>      $this->post('type'),      
                    'numero_license'                =>      $this->post('numero_license'),      
                    'license_1'                     =>      $this->post('license_1'),      
                    'license_2'                     =>      $this->post('license_2'),      
                    'an_acquis'                     =>      $this->post('an_acquis'),      
                    'cout'                          =>      $this->post('cout')
                );
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->SIP_bateau_crevetteManager->add($data);
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
                    'id_societe_crevette'           =>      $this->post('id_societe_crevette'),
                    'immatriculation'               =>      $this->post('immatriculation'),      
                    'deb_validite'                  =>      $this->post('deb_validite'),      
                    'fin_validite'                  =>      $this->post('fin_validite'),      
                    'nom'                           =>      $this->post('nom'),      
                    'segment'                       =>      $this->post('segment'),      
                    'type'                          =>      $this->post('type'),      
                    'numero_license'                =>      $this->post('numero_license'),      
                    'license_1'                     =>      $this->post('license_1'),      
                    'license_2'                     =>      $this->post('license_2'),      
                    'an_acquis'                     =>      $this->post('an_acquis'),      
                    'cout'                          =>      $this->post('cout')
                );

                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->SIP_bateau_crevetteManager->update($id, $data);
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
            $delete = $this->SIP_bateau_crevetteManager->delete($id);         
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
