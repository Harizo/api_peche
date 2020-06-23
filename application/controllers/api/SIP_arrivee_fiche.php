<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_arrivee_fiche extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_arrivee_fiche_model', 'SIP_arrivee_ficheManager');
    }

    public function index_get() 
    {
        $id = $this->get('id');
        $id_permis = $this->get('id_permis');
            $data = array();
            if ($id) 
            {
                
                $data = $this->SIP_arrivee_ficheManager->findById($id);
               
            } 
            else 
            {
                $response = $this->SIP_arrivee_ficheManager->finda_ll_By_permis($id_permis);
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
                   

                    'id_permis'                             =>      $this->post('id_permis'),              
                    'annee'                                 =>      $this->post('annee'),              
                    'janvier'                               =>      $this->post('janvier'),
                    'fevrier'                               =>      $this->post('fevrier'),
                    'mars'                                  =>      $this->post('mars'),
                    'avril'                                 =>      $this->post('avril'),
                    'mai'                                   =>      $this->post('mai'),
                    'juin'                                  =>      $this->post('juin'),
                    'juillet'                               =>      $this->post('juillet'),
                    'aout'                                  =>      $this->post('aout'),
                    'septembre'                             =>      $this->post('septembre'),
                    'octobre'                               =>      $this->post('octobre'),
                    'novembre'                              =>      $this->post('novembre'),
                    'decembre'                              =>      $this->post('decembre')
                );
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->SIP_arrivee_ficheManager->add($data);
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
                    'id_permis'                             =>      $this->post('id_permis'),  
                    'annee'                                 =>      $this->post('annee'),             
                    'janvier'                               =>      $this->post('janvier'),
                    'fevrier'                               =>      $this->post('fevrier'),
                    'mars'                                  =>      $this->post('mars'),
                    'avril'                                 =>      $this->post('avril'),
                    'mai'                                   =>      $this->post('mai'),
                    'juin'                                  =>      $this->post('juin'),
                    'juillet'                               =>      $this->post('juillet'),
                    'aout'                                  =>      $this->post('aout'),
                    'septembre'                             =>      $this->post('septembre'),
                    'octobre'                               =>      $this->post('octobre'),
                    'novembre'                              =>      $this->post('novembre'),
                    'decembre'                              =>      $this->post('decembre')
                );

                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->SIP_arrivee_ficheManager->update($id, $data);
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
            $delete = $this->SIP_arrivee_ficheManager->delete($id);         
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
