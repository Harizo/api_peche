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
            $data = array();
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
                    'annee'                    =>      $this->post('annee'),      
                    'mois'                     =>      $this->post('mois'),      
                    'produit'                  =>      $this->post('produit'),      
                    'id_presentation'          =>      $this->post('id_presentation'),      
                    'id_conservation'          =>      $this->post('id_conservation'),      
                    'qte_vl'                   =>      $this->post('qte_vl'),      
                    'pum_vl'                   =>      $this->post('pum_vl'),      
                    'val_vl'                   =>      $this->post('val_vl'),      
                    'qte_exp'                  =>      $this->post('qte_exp'),      
                    'pum_exp'                  =>      $this->post('pum_exp'),      
                    'val_exp'                  =>      $this->post('val_exp'),      
                    'dest_exp'                 =>      $this->post('dest_exp')
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
                    'annee'                    =>      $this->post('annee'),      
                    'mois'                     =>      $this->post('mois'),      
                    'produit'                  =>      $this->post('produit'),      
                    'id_presentation'          =>      $this->post('id_presentation'),      
                    'id_conservation'          =>      $this->post('id_conservation'),      
                    'qte_vl'                   =>      $this->post('qte_vl'),      
                    'pum_vl'                   =>      $this->post('pum_vl'),      
                    'val_vl'                   =>      $this->post('val_vl'),      
                    'qte_exp'                  =>      $this->post('qte_exp'),      
                    'pum_exp'                  =>      $this->post('pum_exp'),      
                    'val_exp'                  =>      $this->post('val_exp'),      
                    'dest_exp'                 =>      $this->post('dest_exp') 
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
