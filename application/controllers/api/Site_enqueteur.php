<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Site_enqueteur extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('enqueteur_model', 'EnqueteurManager');
        $this->load->model('site_embarquement_model', 'Site_embarquementManager');
        $this->load->model('site_enqueteur_model', 'Site_enqueteurManager');
        $this->load->model('district_model', 'DistrictManager');
        $this->load->model('region_model', 'RegionManager');
    }

    public function index_get() {
        $id = $this->get('id');
        $cle_etrangere = $this->get('cle_etrangere');
        $cle_site = $this->get('cle_site');
        if($cle_etrangere)
        {   $data = array();
            $taiza="findcle_etrangere no nataony";
            $menu = $this->Site_enqueteurManager->findAllByEnqueteur($cle_etrangere);
            $si=$menu;
            if ($menu) 
            {
                foreach ($menu as $key => $value) 
                {   $district = array();
                    $region   = array();
                    
                    $district = $this->DistrictManager->findById($value->id_district);
                    $region   = $this->RegionManager->findById($value->id_region);                
                        
                    //$data[$key]['id']          = $value->id;
                    $data[$key]['id']          = $value->id_site;
                    $data[$key]['code']        = $value->code;
                    $data[$key]['libelle']     = $value->libelle;
                    $data[$key]['code_unique'] = $value->code_unique;
                    $data[$key]['latitude']    = $value->latitude;
                    $data[$key]['longitude']   = $value->longitude;
                    $data[$key]['altitude']    = $value->altitude;
                    $data[$key]['region']      = $region;
                    $data[$key]['district']    = $district;
                    //$data[$key]['si']    = $si;

                }
            } 
            else
                $data = array();
            
        }
        else
        {
            if ($id) {
                $data = array();
                $site_enqueteur = $this->Site_enqueteurManager->findById($id);
                $enqueteur = $this->EnqueteurManager->findById($site_enqueteur->id_enqueteur);
                $site_embarquement = $this->Site_embarquementManager->findById($site_enqueteur->id_site);
                $data['id'] = $Site_enqueteur->id;
                $data['site_embarquement'] = $site_embarquement;
                $data['enqueteur'] = $enqueteur;

            }
            else
            { 
                if($cle_site)
                {
                    $data = array();
                    $taiza="findcle_site no nataony";
                    $menu = $this->Site_enqueteurManager->findAllBySite($cle_etrangere);
                    $si=$menu;
                    if ($menu) 
                    {
                        foreach ($menu as $key => $value) 
                        {   
                            $data[$key]['id']          = $value->id_enqueteur;
                            //$data[$key]['nom']        = $value->enqueteur->nom;
                            //$data[$key]['prenom']     = $value->enqueteur->prenom;
                            //$data[$key]['telephone'] = $value->enqueteur->telephone;                     
                        }
                    } 
                    else
                        $data = array();
                    
                }else{
                    $menu = $this->Site_enqueteurManager->findAll();
                    if ($menu) {
                        foreach ($menu as $key => $value) {
                            $site_embarquement = array();
                            $enqueteur = array();
                            $site_embarquement = $this->Site_embarquementManager->findById($value->id_site);
                            $enqueteur = $this->EnqueteurManager->findById($value->id_enqueteur);
                            $data[$key]['id'] = $value->id;
                            $data[$key]['site_embarquement'] = $site_embarquement;
                            $data[$key]['enqueteur'] = $enqueteur;
                        }
                    } else
                        $data = array();
                }    
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
                    'id_site_embarquement' => $this->post('site_embarquement_id'),
                    'id_enqueteur' => $this->post('enqueteur_id')
                );
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->Site_enqueteurManager->add($data);
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
                    'id_site_embarquement' => $this->post('site_embarquement_id'),
                    'id_enqueteur' => $this->post('enqueteur_id')
                );
                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->Site_enqueteurManager->update($id, $data);
                if(!is_null($update)) {
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
            $delete = $this->Site_enqueteurManager->delete($id);         
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
