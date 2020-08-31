<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_peche_thoniere_malagasy extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_peche_thoniere_malagasy_model', 'SIP_peche_thoniere_malagasyManager');
    }

    public function index_get() 
    {
        $id = $this->get('id');
        $id_navire       = $this->get('id_navire');
        $data = array();      
        if($id_navire)
        {
            $data = $this->SIP_peche_thoniere_malagasyManager->findCleNavire($id_navire);
           
        }
       
        else
        {
            if ($id) 
            {               
                $data = $this->SIP_peche_thoniere_malagasyManager->findById($id);               
            } 
            else 
            {
                $response = $this->SIP_peche_thoniere_malagasyManager->findAll();
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
                    'id_navire'         =>  $this->post('id_navire'),              
                    'numfp'             =>  $this->post('numfp'),              
                    'nom_capitaine'     =>  $this->post('nom_capitaine'),              
                    'nbr_equipage'      =>  $this->post('nbr_equipage'),              
                    'date_rapport'      =>  $this->post('date_rapport'),              
                    'nom_declarant'     =>  $this->post('nom_declarant'),              
                    'date_depart'       =>  $this->post('date_depart'),              
                    'date_arrive'       =>  $this->post('date_arrive'),              
                    'port'              =>  $this->post('port'),              
                    'nbr_jour_en_mer'   =>  $this->post('nbr_jour_en_mer'),              
                    'nbr_peche'         =>  $this->post('nbr_peche'),              
                    'nbr_peche_zee_mdg' =>  $this->post('nbr_peche_zee_mdg'),              
                );
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->SIP_peche_thoniere_malagasyManager->add($data);
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
                    'numfp'             =>  $this->post('numfp'),              
                    'nom_capitaine'     =>  $this->post('nom_capitaine'),              
                    'nbr_equipage'      =>  $this->post('nbr_equipage'),              
                    'date_rapport'      =>  $this->post('date_rapport'),              
                    'nom_declarant'     =>  $this->post('nom_declarant'),              
                    'date_depart'       =>  $this->post('date_depart'),              
                    'date_arrive'       =>  $this->post('date_arrive'),              
                    'port'              =>  $this->post('port'),              
                    'nbr_jour_en_mer'   =>  $this->post('nbr_jour_en_mer'),              
                    'nbr_peche'         =>  $this->post('nbr_peche'),              
                    'nbr_peche_zee_mdg' =>  $this->post('nbr_peche_zee_mdg'),              
                );
                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->SIP_peche_thoniere_malagasyManager->update($id, $data);
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
            $delete = $this->SIP_peche_thoniere_malagasyManager->delete($id);         
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
