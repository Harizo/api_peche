<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_espece extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_espece_model', 'SIP_especeManager');
    }

    public function index_get() 
    {
        $id = $this->get('id');
        $id_type_espece = $this->get('id_type_espece');
        $id_famille = $this->get('id_famille');
        $id_navire = $this->get('id_navire');

        $eau_douce_marine =  $this->get('eau_douce_marine');
        $id_type_espece1 = $this->get('id_type_espece1');
        $id_type_espece2 = $this->get('id_type_espece2');
        $type_espece = $this->get('type_espece');
        $data = array();
        if ($type_espece)
		{
			$data = $this->SIP_especeManager->find_by_type_espece($type_espece);
		} 
		else 	
        if (($id_type_espece)||($id_navire)||($id_famille)) {
            
            if($id_famille) {
                $data = $this->SIP_especeManager->findFamille($id_famille);
            }

            if ($id_type_espece) 
            {
                $response = $this->SIP_especeManager->find_all_by_type($id_type_espece);
                if ($response) 
                {
                    $data = $response ;
                }
            }

            if($id_navire) {
                $data = $this->SIP_especeManager->find_all_by_navire($id_navire);
            }
        } 
        else {
            
            if ($id) 
            {
                
                $SIP_espece = $this->SIP_especeManager->findById($id);
                $data['id'] = $SIP_espece->id;
                $data['code'] = $SIP_espece->code;
                $data['nom'] = $SIP_espece->nom;
            }  
            else
            {

                if ($eau_douce_marine) 
                {
                    $data=$this->SIP_especeManager->find_all_eau_douce_marine($id_type_espece1, $id_type_espece2);
                }
                else
                    $data=$this->SIP_especeManager->findAll();

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
                    'code'              => $this->post('code'),
                    'nom'               => $this->post('nom'),
                    'nom_local'         => $this->post('nom_local'),
                    'nom_francaise'     => $this->post('nom_francaise'),
                    'nom_scientifique'  => $this->post('nom_scientifique'),
                    'typ_esp_id'        => $this->post('typ_esp_id'),
                    'id_famille'        => $this->post('id_famille')
                );
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->SIP_especeManager->add($data);
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
                    'code'              => $this->post('code'),
                    'nom'               => $this->post('nom'),
                    'nom_local'         => $this->post('nom_local'),
                    'nom_francaise'     => $this->post('nom_francaise'),
                    'nom_scientifique'  => $this->post('nom_scientifique'),
                    'typ_esp_id'        => $this->post('typ_esp_id'),
                    'id_famille'        => $this->post('id_famille')
                );

                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->SIP_especeManager->update($id, $data);
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
            $delete = $this->SIP_especeManager->delete($id);         
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
