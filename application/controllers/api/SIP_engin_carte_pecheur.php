<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_engin_carte_pecheur extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_engin_carte_pecheur_model', 'SIP_engin_carte_pecheurManager');
    }

    public function index_get() 
    {
        $id = $this->get('id');
        $id_carte_pecheur = $this->get('id_carte_pecheur');
        
            $data = array();
            if ($id) 
            {
                
                $data = $this->SIP_engin_carte_pecheurManager->findById($id);
                
            } 
            else 
            {
                
                $response = $this->SIP_engin_carte_pecheurManager->find_all_by_carte($id_carte_pecheur);
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
                 
                    'id_carte_pecheur'          =>      $this->post('id_carte_pecheur'),
                    'id_type_engin'             =>      $this->post('id_type_engin'),
                    'nbr_engin'                 =>      $this->post('nbr_engin'),      
                    'utilisation_engin'         =>      $this->post('utilisation_engin'),      
                    'longueur'                  =>      $this->post('longueur'),      
                    'largeur'                   =>      $this->post('largeur'),
                    'hauteur'                   =>      $this->post('hauteur'),
                    'maille'                    =>      $this->post('maille'),
                    'hamecon'                   =>      $this->post('hamecon'),
                    'etat_principale'           =>      $this->post('etat_principale')
                );

                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->SIP_engin_carte_pecheurManager->add($data);
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
                    'id_carte_pecheur'          =>      $this->post('id_carte_pecheur'),
                    'id_type_engin'             =>      $this->post('id_type_engin'),
                    'nbr_engin'                 =>      $this->post('nbr_engin'),      
                    'utilisation_engin'         =>      $this->post('utilisation_engin'),      
                    'longueur'                  =>      $this->post('longueur'),      
                    'largeur'                   =>      $this->post('largeur'),
                    'hauteur'                   =>      $this->post('hauteur'),
                    'maille'                    =>      $this->post('maille'),
                    'hamecon'                   =>      $this->post('hamecon'),
                    'etat_principale'           =>      $this->post('etat_principale')
                );

                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->SIP_engin_carte_pecheurManager->update($id, $data);
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
            $delete = $this->SIP_engin_carte_pecheurManager->delete($id);         
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
