<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_production_commercialisation_region extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_production_commercialisation_region_model', 'SIP_production_commercialisation_regionManager');
    }

    public function index_get() 
    {
        $id = $this->get('id');
        $id_region = $this->get('id_region');
        $annee = $this->get('annee');
        $get_annee = $this->get('get_annee');
            $data = array();
            if ($id) 
            {
                
                $data = $this->SIP_production_commercialisation_regionManager->findById($id);
                
            } 
            
            if($id_region) 
            {
                
                $data = $this->SIP_production_commercialisation_regionManager->findAll($id_region, $annee);
                

            }

            if($get_annee) 
            {
                
                $data = $this->SIP_production_commercialisation_regionManager->get_annee();
                

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
                    'code_activ'                         => $this->post('code_activ'),
                    'code_dom'                           => $this->post('code_dom'),
                    'code_act_dom'                       => $this->post('code_act_dom'),
                    'annee'                              => $this->post('annee'),
                    'mois'                               => $this->post('mois'),
                    'id_espece'                          => $this->post('id_espece'),
                    'quantite'                           => $this->post('quantite'),
                    'quantite_en_nbre'                   => $this->post('quantite_en_nbre'),
                    'code_comm'                          => $this->post('code_comm'),
                    'quantite_comm'                      => $this->post('quantite_comm'),
                    'id_region'                          => $this->post('id_region'),
                    'id_district'                          => $this->post('id_district')
                );
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->SIP_production_commercialisation_regionManager->add($data);
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
                    'code_activ'                         => $this->post('code_activ'),
                    'code_dom'                           => $this->post('code_dom'),
                    'code_act_dom'                       => $this->post('code_act_dom'),
                    'annee'                              => $this->post('annee'),
                    'mois'                               => $this->post('mois'),
                    'id_espece'                          => $this->post('id_espece'),
                    'quantite'                           => $this->post('quantite'),
                    'quantite_en_nbre'                   => $this->post('quantite_en_nbre'),
                    'code_comm'                          => $this->post('code_comm'),
                    'quantite_comm'                      => $this->post('quantite_comm'),
                    'id_region'                          => $this->post('id_region'),
                    'id_district'                          => $this->post('id_district')
                );

                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->SIP_production_commercialisation_regionManager->update($id, $data);
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
            $delete = $this->SIP_production_commercialisation_regionManager->delete($id);         
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
