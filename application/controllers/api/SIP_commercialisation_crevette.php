<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_commercialisation_crevette extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_commercialisation_crevette_model', 'SIP_commercialisation_crevetteManager');
    }

    public function index_get() 
    {
        $id = $this->get('id');
        $id_societe_crevette = $this->get('id_societe_crevette');
        $id_conservation = $this->get('id_conservation');
        $id_presentation = $this->get('id_presentation');
        $id_espece = $this->get('id_espece');
        $data = array();
         if (($id_presentation)||($id_conservation)||($id_espece)) 
        {
           if($id_conservation)
                $x = $this->SIP_commercialisation_crevetteManager->findCleConservation($id_conservation);

            if($id_presentation)
                $x = $this->SIP_commercialisation_crevetteManager->findClePresentation($id_presentation);

            if($id_espece)
                $x = $this->SIP_commercialisation_crevetteManager->findEspece($id_espece);

            if ($x) 
                $data = $x ;
        }
        else
        {
            if ($id) 
            {
                
                $SIP_commercialisation_crevette = $this->SIP_commercialisation_crevetteManager->findById($id);
                $data['id'] = $SIP_commercialisation_crevette->id;
                $data['code'] = $SIP_commercialisation_crevette->code;
                $data['nom'] = $SIP_commercialisation_crevette->nom;
            } 
            else 
            {
                $response = $this->SIP_commercialisation_crevetteManager->find_all_join($id_societe_crevette);
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
                    'dest_exp'                 =>      $this->post('dest_exp'),

                    'qte_export'               =>      $this->post('qte_export'),      
                    'pum_export'               =>      $this->post('pum_export'),      
                    'val_export'               =>      $this->post('val_export'),      
                    'dest_export'              =>      $this->post('dest_export')
                );
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->SIP_commercialisation_crevetteManager->add($data);
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
                    'dest_exp'                 =>      $this->post('dest_exp') ,

                    'qte_export'               =>      $this->post('qte_export'),      
                    'pum_export'               =>      $this->post('pum_export'),      
                    'val_export'               =>      $this->post('val_export'),      
                    'dest_export'              =>      $this->post('dest_export')
                );

                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->SIP_commercialisation_crevetteManager->update($id, $data);
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
            $delete = $this->SIP_commercialisation_crevetteManager->delete($id);         
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
