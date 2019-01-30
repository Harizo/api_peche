<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Site_embarquement extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('site_embarquement_model', 'Site_embarquementManager');
        $this->load->model('district_model', 'DistrictManager');
        $this->load->model('region_model', 'RegionManager');
    }
    public function index_get() {
        $id = $this->get('id');
        $cle_etrangere = $this->get('cle_etrangere');
        $id_district = $this->get('id_district');
        $id_region = $this->get('id_region');
		$taiza="";
        if ($cle_etrangere) {
            $data = $this->Site_embarquementManager->findAllByDistrict($cle_etrangere);           
        } else {
            if ($id)  {
                $data = array();
                $site_embarquement = $this->Site_embarquementManager->findById($id);
                $district = $this->DistrictManager->findById($site_embarquement->district_id);
                $data['id'] = $site_embarquement->id;
                $data['code'] = $site_embarquement->code;
                $data['nom'] = $site_embarquement->nom;
                $data['district_id'] =$site_embarquement->district_id;
                $data['district_nom'] = $district->nom;
            } else if($id_district && $id_region) {
				$taiza="Ato ambony ary id_district=".$id_district."  ary id_region=".$id_region; 
				$menu = $this->Site_embarquementManager->find_Site_embarquement_avec_District_et_Region();
                if ($menu) {
					$data=$menu;
                } else
                    $data = array();
			} else {
				$taiza="findAll no nataony";
                $menu = $this->Site_embarquementManager->findAll();
                if ($menu) {
                    foreach ($menu as $key => $value) {
                        $district = array();
                        $district = $this->DistrictManager->findById($value->id_district);
                        $region = $this->RegionManager->findById($value->id_region);
                        $data[$key]['id'] = $value->id;
                        $data[$key]['code'] = $value->code;
                        $data[$key]['libelle'] = $value->libelle;
                        $data[$key]['code_unique'] = $value->code_unique;
                        $data[$key]['latitude'] = $value->latitude;
                        $data[$key]['longitude'] = $value->longitude;
                        $data[$key]['altitude'] = $value->altitude;
                        $data[$key]['district_id'] = $value->id_district;
                        $data[$key]['district_nom'] = $district->nom;
                        $data[$key]['region_id'] = $value->id_region;
                        $data[$key]['region_nom'] = $region->nom;

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
                    'code' => $this->post('code'),
                    'libelle' => $this->post('libelle'),
                    'code_unique' => $this->post('code_unique'),
                    'latitude' => $this->post('latitude'),
                    'longitude' => $this->post('longitude'),
                    'altitude' => $this->post('altitude'),
                    'region_id' => $this->post('region_id'),
                    'district_id' => $this->post('district_id')
                );
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->Site_embarquementManager->add($data);
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
                    'code' => $this->post('code'),
                    'libelle' => $this->post('libelle'),
                    'code_unique' => $this->post('code_unique'),
                    'latitude' => $this->post('latitude'),
                    'longitude' => $this->post('longitude'),
                    'altitude' => $this->post('altitude'),
                    'region_id' => $this->post('region_id'),
                    'district_id' => $this->post('district_id')
                );
                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->Site_embarquementManager->update($id, $data);
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
            $delete = $this->Site_embarquementManager->delete($id);
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
