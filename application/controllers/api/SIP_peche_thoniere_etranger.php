<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_peche_thoniere_etranger extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_peche_thoniere_etranger_model', 'SIP_peche_thoniere_etrangerManager');
    }

    public function index_get() 
    {
        $id = $this->get('id');
		$date_depart = $this->get('date_depart');
		$date_arrive = $this->get('date_arrive');
        $liste_annee = $this->get('liste_annee');		
        $id_navire = $this->get('id_navire');       
            $data = array();
            if ($id) 
            {               
                $data = $this->SIP_peche_thoniere_etrangerManager->findById($id);               
            } 
			else if($liste_annee) {
                $response = $this->SIP_peche_thoniere_etrangerManager->SelectAnnee();
                if ($response) 
                {
                    $data = $response ;
                }				
			} 			
            else if($date_depart && $date_arrive) {
				$response = $this->SIP_peche_thoniere_etrangerManager->SelectByDatedepart_Datearrivee($date_depart,$date_arrive);
                if ($response) 
                {
                    $data = $response ;
                }				
			}
			else
            {
                if ($id_navire) 
                    $response = $this->SIP_peche_thoniere_etrangerManager->findCleNavire($id_navire);

                else
                    $response = $this->SIP_peche_thoniere_etrangerManager->findAll();
                
                if ($response) 
                    $data = $response ;
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
				'nbr_lancers'       =>  $this->post('nbr_lancers'),              
				'num_sortie_peche'  =>  $this->post('num_sortie_peche'),              
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
                $dataId = $this->SIP_peche_thoniere_etrangerManager->add($data);
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
                $update = $this->SIP_peche_thoniere_etrangerManager->update($id, $data);
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
            $delete = $this->SIP_peche_thoniere_etrangerManager->delete($id);         
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
