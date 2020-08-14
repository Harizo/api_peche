<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_sortie_peche_artisanale extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_sortie_peche_artisanale_model', 'SIP_sortie_peche_artisanaleManager');
    }

    public function index_get() 
    {
        $id = $this->get('id');
            $data = array();
            if ($id) 
            {               
                $data = $this->SIP_sortie_peche_artisanaleManager->findById($id);               
            } 
            else 
            {
                $response = $this->SIP_sortie_peche_artisanaleManager->findAll();
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
                    'id_navire'         =>  $this->post('id_navire'),              
                    'nom_capitaine'     =>  $this->post('nom_capitaine'),              
                    'port'              =>  $this->post('port'),              
                    'num_maree'         =>  $this->post('num_maree'),              
                    'date_depart'       =>  $this->post('date_depart'),              
                    'date_arrive'       =>  $this->post('date_arrive'),              
                    'annee'             =>  $this->post('annee'),              
                    'mois'              =>  $this->post('mois'),              
                    'id_espece'         =>  $this->post('id_espece'),              
                    'quantite'          =>  $this->post('quantite'),              
                );
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->SIP_sortie_peche_artisanaleManager->add($data);
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
                    'id_navire'         =>  $this->post('id_navire'),              
                    'nom_capitaine'     =>  $this->post('nom_capitaine'),              
                    'port'              =>  $this->post('port'),              
                    'num_maree'         =>  $this->post('num_maree'),              
                    'date_depart'       =>  $this->post('date_depart'),              
                    'date_arrive'       =>  $this->post('date_arrive'),              
                    'annee'             =>  $this->post('annee'),              
                    'mois'              =>  $this->post('mois'),              
                    'id_espece'         =>  $this->post('id_espece'),              
                    'quantite'          =>  $this->post('quantite'),              
                );
                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->SIP_sortie_peche_artisanaleManager->update($id, $data);
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
            $delete = $this->SIP_sortie_peche_artisanaleManager->delete($id);         
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
