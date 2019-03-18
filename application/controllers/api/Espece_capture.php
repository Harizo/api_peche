<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//harizo
// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Espece_capture extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Espece_capture_model', 'Espece_captureManager');
        $this->load->model('fiche_echantillonnage_capture_model', 'Fiche_echantillonnage_captureManager');
        $this->load->model('echantillon_model', 'EchantillonManager');
        $this->load->model('utilisateurs_model', 'UserManager');
        $this->load->model('Espece_model', 'EspeceManager');
    }

    public function index_get() {
        $id = $this->get('id');
        $cle_etrangere = $this->get('cle_etrangere');

        if ($cle_etrangere) 
        {
            $taiza="findcle_etrangere no nataony";
            $menu = $this->Espece_captureManager->findAllByEchantillon($cle_etrangere);
             if ($menu) 
             {
                    foreach ($menu as $key => $value) 
                    {
                        $fiche_echantillonnage_capture = array();
                        $echantillon = array();
                        $espece = array();
                        $user = array();

                        $fiche_echantillonnage_capture = $this->Fiche_echantillonnage_captureManager->findById($value->id_fiche_echantillonnage_capture);
                        $echantillon = $this->EchantillonManager->findById($value->id_echantillon);
                        $espece = $this->EspeceManager->findById($value->id_espece);
                        $user = $this->UserManager->findById($value->id_user);
                        $data[$key]['id'] = $value->id;
                        $data[$key]['fiche_echantillonnage_capture'] = $fiche_echantillonnage_capture;
                        $data[$key]['echantillon'] = $echantillon;

                        $data[$key]['capture'] = $value->capture;
                        $data[$key]['prix'] = $value->prix;

                        $data[$key]['espece'] = $espece;
                        
                        $data[$key]['user'] = $user;
                        
                        $data[$key]['date_creation'] = $value->date_creation;
                        $data[$key]['date_modification'] = $value->date_modification;

                    }
                } 
                else
                    $data = array();
            
        } 
        else 
        {
            if ($id) 
            {
                $data = array();
                $espece_capture= $this->Espece_captureManager->findById($id);
                $data['id'] = $espece_capture->id;
                $data['id_fiche_echantillonnage_capture'] = $espece_capture->id_fiche_echantillonnage_capture;
                $data['id_echantillon'] = $espece_capture->id_echantillon;
                $data['id_espece'] = $espece_capture->id_espece;
                $data['capture'] = $espece_capture->capture;
                $data['prix'] = $espece_capture->prix;
                $data['id_user'] = $espece_capture->id_user;
                $data['date_creation'] = $espece_capture->date_creation;
                $data['date_modification'] = $espece_capture->date_modification;
                
            } 
            else 
            {
                $espece_capture= $this->Espece_captureManager->findAll();

                if ($espece_capture) {
                    foreach ($espece_capture as $key => $value) {
                $espece = $this->EspeceManager->findById($value->id_espece);        
                
                $data['id'] = $espece_capture->id;
                $data[$key]['id_fiche_echantillonnage_capture'] = $espece_capture->id_fiche_echantillonnage_capture;
                $data[$key]['id_echantillon'] = $value->id_echantillon;
                $data[$key]['espece_id'] = $value->id_espece ;
                $data[$key]['espece_nom'] = $espece ->nom_local;
                $data[$key]['capture'] = $value->capture;
                $data[$key]['prix'] = $value->prix;
                $data[$key]['id_user'] = $value->id_user;
                $data[$key]['date_creation'] = $value->date_creation;
                $data[$key]['date_modification'] = $value->date_modification;
                        
                    };
                } else
                    $data = array();
            }
        }
        if (count($data)>0) {
            $this->response([
                'status' => TRUE,
                'response' => $data,
                'message' => 'Get data success',
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => FALSE,
                'response' => array(),
                'message' => 'No data were found'
            ], REST_Controller::HTTP_OK);
        }
    }
    public function index_post() {
        $id = $this->post('id') ;
        $supprimer = $this->post('supprimer') ;
        if ($supprimer == 0) {
            if ($id == 0) {
                $data = array(
                    'id_fiche_echantillonnage_capture' => $this->post('fiche_echantillonnage_capture_id'),
                    'id_echantillon' => $this->post('echantillon_id'),
                    'id_espece' => $this->post('espece_id'),
                    'capture' => $this->post('capture'),
                    'prix' => $this->post('prix'),
                    'id_user' => $this->post('user_id')
                );               
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->Espece_captureManager->add($data);              
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
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $data = array(
                    'id_fiche_echantillonnage_capture' => $this->post('fiche_echantillonnage_capture_id'),
                    'id_echantillon' => $this->post('echantillon_id'),
                    'id_espece' => $this->post('espece_id'),
                    'capture' => $this->post('capture'),
                    'prix' => $this->post('prix'),
                    'id_user' => $this->post('user_id')
                );              
                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->Espece_captureManager->update($id, $data);              
                if(!is_null($update)){
                    $this->response([
                        'status' => TRUE, 
                        'response' => 1,
                        'message' => 'Update data success'
                            ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_OK);
                }
            }
        } else {
            if (!$id) {
            $this->response([
            'status' => FALSE,
            'response' => 0,
            'message' => 'No request found'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
            $delete = $this->Espece_captureManager->delete($id);          
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