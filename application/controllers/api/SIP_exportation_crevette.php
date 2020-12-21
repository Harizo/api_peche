<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_exportation_crevette extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_exportation_crevette_model', 'SIP_exportation_crevetteManager');
    }

    public function index_get() 
    {
        $id = $this->get('id');
        $id_societe_crevette = $this->get('id_societe_crevette');
        $id_conservation = $this->get('id_conservation');
        $id_presentation = $this->get('id_presentation');
        $data = array();
        if (($id_presentation)||($id_conservation)) 
        {
           if($id_conservation)
                $x = $this->SIP_exportation_crevetteManager->findCleConservation($id_conservation);

            if($id_presentation)
                $x = $this->SIP_exportation_crevetteManager->findClePresentation($id_presentation);
            if ($x) 
                $data = $x;
        }
        
        else
        {  
            if ($id) 
            {
                
                $SIP_exportation_crevette = $this->SIP_exportation_crevetteManager->findById($id);
                $data['id'] = $SIP_exportation_crevette->id;
                $data['code'] = $SIP_exportation_crevette->code;
                $data['nom'] = $SIP_exportation_crevette->nom;
            } 
            else 
            {
                $response = $this->SIP_exportation_crevetteManager->find_all_join($id_societe_crevette);
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
                 
                    'id_societe_crevette'      =>      $this->post('id_societe_crevette'),
                    'id_espece'                =>      $this->post('id_espece'),
                    'annee'                    =>      $this->post('annee'),      
                    'mois'                     =>      $this->post('mois'),      
                    'date_visa'                =>      $this->post('date_visa'),      
                    'numero_visa'              =>      $this->post('numero_visa'),      
                    'date_cos'                 =>      $this->post('date_cos'),      
                    'numero_cos'               =>      $this->post('numero_cos'),      
                    'date_edrd'                =>      $this->post('date_edrd'),      
                    'id_presentation'          =>      $this->post('id_presentation'),      
                    'id_conservation'          =>      $this->post('id_conservation'),      
                    'quantite'                 =>      $this->post('quantite'),      
                    'valeur_ar'                =>      $this->post('valeur_ar'),      
                    'valeur_euro'              =>      $this->post('valeur_euro'),  
                    'valeur_usd'               =>      $this->post('valeur_usd'),  
                    'destination'              =>      $this->post('destination')  
                );
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->SIP_exportation_crevetteManager->add($data);
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
                    'id_societe_crevette'      =>      $this->post('id_societe_crevette'),
                    'id_espece'                =>      $this->post('id_espece'),
                    'annee'                    =>      $this->post('annee'),      
                    'mois'                     =>      $this->post('mois'),      
                    'date_visa'                =>      $this->post('date_visa'),      
                    'numero_visa'              =>      $this->post('numero_visa'),      
                    'date_cos'                 =>      $this->post('date_cos'),      
                    'numero_cos'               =>      $this->post('numero_cos'),      
                    'date_edrd'                =>      $this->post('date_edrd'),      
                    'id_presentation'          =>      $this->post('id_presentation'),      
                    'id_conservation'          =>      $this->post('id_conservation'),      
                    'quantite'                 =>      $this->post('quantite'),      
                    'valeur_ar'                =>      $this->post('valeur_ar'),      
                    'valeur_euro'              =>      $this->post('valeur_euro'),  
                    'valeur_usd'               =>      $this->post('valeur_usd'),  
                    'destination'              =>      $this->post('destination')
                );

                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->SIP_exportation_crevetteManager->update($id, $data);
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
            $delete = $this->SIP_exportation_crevetteManager->delete($id);         
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
