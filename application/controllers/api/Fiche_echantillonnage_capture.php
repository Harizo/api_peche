<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Fiche_echantillonnage_capture extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('fiche_echantillonnage_capture_model', 'Fiche_echantillonnage_captureManager');
        $this->load->model('district_model', 'DistrictManager');
        $this->load->model('region_model', 'RegionManager');
        $this->load->model('site_embarquement_model', 'Site_embarquementManager');
        $this->load->model('utilisateurs_model', 'UserManager');
        $this->load->model('enqueteur_model', 'EnqueteurManager');

    }
    public function index_get() {
        $id = $this->get('id');
        //$id_district = $this->get('id_district');
       //$id_region = $this->get('id_region');
        $date_debut = $this->get('date_debut');
        $date_fin = $this->get('date_fin');
        $taiza="";

        if($date_debut || $date_fin)
        {

            $data = array();
            $menu = $this->Fiche_echantillonnage_captureManager->findByDate($date_debut ,$date_fin);

            if ($menu)
            {
                foreach ($menu as $key => $value)
                {
                    $district = array();
                    $district = $this->DistrictManager->findById($value->id_district);
                    $region = $this->RegionManager->findById($value->id_region);
                    $site_embarquement = $this->Site_embarquementManager->findById($value->id_site_embarquement);
                    $enqueteur = $this->EnqueteurManager->findById($value->id_enqueteur);
                    $user = $this->UserManager->findById($value->id_user);
                    $data[$key]['id'] = $value->id;
                    $data[$key]['code_unique'] = $value->code_unique;
                    $data[$key]['date'] = $value->date;
                    $data[$key]['date_creation'] = $value->date_creation;
                    $data[$key]['date_modification'] = $value->date_modification;
                    $data[$key]['latitude'] = $value->latitude;
                    $data[$key]['longitude'] = $value->longitude;
                    $data[$key]['altitude'] = $value->altitude;
                    $data[$key]['district'] = $district;
                    $data[$key]['region'] = $region;
                    $data[$key]['site_embarquement'] = $site_embarquement;
                    $data[$key]['enqueteur'] = $enqueteur;
                    $data[$key]['user'] = $user;

                }
            } else
                    $data = array();

        }
        else
        { 
            if ($id)  
            {
                $data = array();
                $fiche_echantillonnage_capture = $this->Fiche_echantillonnage_captureManager->findById($id);
                $district = $this->DistrictManager->findById($fiche_echantillonnage_capture->district_id);
                $data['id'] = $fiche_echantillonnage_capture->id;
                $data['code_unique'] = $fiche_echantillonnage_capture->code_unique;
                $data['date'] = $fiche_echantillonnage_capture->date;
                $data['date_creation'] = $fiche_echantillonnage_capture->date_creation;
                $data['date_modification'] = $fiche_echantillonnage_capture->date_modification;
                $data['latitude'] = $fiche_echantillonnage_capture->latitude;
                $data['longitude'] = $fiche_echantillonnage_capture->longitude;
                $data['altitude'] = $fiche_echantillonnage_capture->altitude;
                $data['site_embarquement'] = $site_embarquement;
                $data['district'] = $district;
                $data['enqueteur'] = $enqueteur;                
                $data['region'] = $region;
                $data['user'] = $user;

            } 
            else 
            {
                $taiza="findAll no nataony";
                $menu = $this->Fiche_echantillonnage_captureManager->findAll();
                if ($menu) {
                    foreach ($menu as $key => $value) {
                        $district = array();
                        $district = $this->DistrictManager->findById($value->id_district);
                        $region = $this->RegionManager->findById($value->id_region);
                        $site_embarquement = $this->Site_embarquementManager->findById($value->id_site_embarquement);
                        $enqueteur = $this->EnqueteurManager->findById($value->id_enqueteur);
                        $user = $this->UserManager->findById($value->id_user);
                        $data[$key]['id'] = $value->id;
                        $data[$key]['code_unique'] = $value->code_unique;
                        $data[$key]['date'] = $value->date;
                        $data[$key]['date_creation'] = $value->date_creation;
                        $data[$key]['date_modification'] = $value->date_modification;
                        $data[$key]['latitude'] = $value->latitude;
                        $data[$key]['longitude'] = $value->longitude;
                        $data[$key]['altitude'] = $value->altitude;
                        $data[$key]['district'] = $district;
                        $data[$key]['region'] = $region;
                        $data[$key]['site_embarquement'] = $site_embarquement;
                        $data[$key]['enqueteur'] = $enqueteur;
                        $data[$key]['user'] = $user;

                    }
                } else
                    $data = array();
            }
        }
        if (count($data)>0) {
            $this->response([
                'status' => TRUE,
                'response' => $data,
                'message' => $taiza,
                // 'message' => 'Get data success',
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
                    'code_unique' => $this->post('code_unique'),
                    'date' => $this->post('date'),
                    'latitude' => $this->post('latitude'),
                    'longitude' => $this->post('longitude'),
                    'altitude' => $this->post('altitude'),
                    'id_region' => $this->post('region_id'),
                    'id_district' => $this->post('district_id'),
                    'id_site_embarquement' => $this->post('site_embarquement_id'),
                    'id_enqueteur' => $this->post('enqueteur_id'),
                    'id_user' => $this->post('user_id')
                );
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->Fiche_echantillonnage_captureManager->add($data);
                if (!is_null($dataId))  {
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
                    'code_unique' => $this->post('code_unique'),
                    'date' => $this->post('date'),
                    'latitude' => $this->post('latitude'),
                    'longitude' => $this->post('longitude'),
                    'altitude' => $this->post('altitude'),
                    'id_region' => $this->post('region_id'),
                    'id_district' => $this->post('district_id'),
                    'id_site_embarquement' => $this->post('site_embarquement_id'),
                    'id_enqueteur' => $this->post('enqueteur_id'),
                    'id_user' => $this->post('user_id')
                );
                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->Fiche_echantillonnage_captureManager->update($id, $data);
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
            $delete = $this->Fiche_echantillonnage_captureManager->delete($id);
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
