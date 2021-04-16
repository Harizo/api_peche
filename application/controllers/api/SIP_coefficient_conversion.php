<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_coefficient_conversion extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_coefficient_conversion_model', 'SIP_coefficient_conversionManager');
    }

    public function index_get() 
    {
        $id = $this->get('id');
        $etat_get_by_pres_cons = $this->get('etat_get_by_pres_cons');

        $id_espece = $this->get('id_espece');
        $id_presentation = $this->get('id_presentation');
        $id_conservation = $this->get('id_conservation');

            $data = array();
            if ($id) 
            {
                
                $data = $this->SIP_coefficient_conversionManager->findById($id);
                
            } 
            else 
            {
                if ($etat_get_by_pres_cons) 
                {
                    $data = $this->SIP_coefficient_conversionManager->find_coeff($this->generer_requete($id_espece, $id_presentation, $id_conservation));
                }
                else
                {
                    $response = $this->SIP_coefficient_conversionManager->find_all_join();
                    if ($response) 
                    {
                        $data = $response ;
                    }
                }

            }
        if ($data) 
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
                    'id_espece'                    => $this->post('id_espece'),
                    'id_presentation'              => $this->post('id_presentation'),
                    'id_conservation'              => $this->post('id_conservation'),
                    'coefficient'                  => $this->post('coefficient')
                );
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->SIP_coefficient_conversionManager->add($data);
                if (!is_null($dataId)) {
                    $this->response([
                        'status' => TRUE,
                        'response' => $dataId,
                        'responsessss' => $data,
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
                    'id_espece'                    => $this->post('id_espece'),
                    'id_presentation'              => $this->post('id_presentation'),
                    'id_conservation'              => $this->post('id_conservation'),
                    'coefficient'                  => $this->post('coefficient')
                );

                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->SIP_coefficient_conversionManager->update($id, $data);
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
            $delete = $this->SIP_coefficient_conversionManager->delete($id);         
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


    public function generer_requete($id_espece, $id_presentation, $id_conservation)
    {
        $requete = "id_espece = ".$id_espece ;
            

            if (($id_presentation!='*')&&($id_presentation!='undefined')) 
            {
                $requete = $requete." AND id_presentation='".$id_presentation."'" ;
            }

            if (($id_conservation!='*')&&($id_conservation!='undefined')) 
            {
                $requete = $requete." AND id_conservation='".$id_conservation."'" ;
            }

        return $requete ;
    }
}
/* End of file controllername.php */
/* Location: ./application/controllers/controllername.php */
