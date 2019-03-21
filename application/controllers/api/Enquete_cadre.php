<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Enquete_cadre extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('enquete_cadre_model', 'Enquete_cadreManager');        
        $this->load->model('region_model', 'RegionManager');
        $this->load->model('district_model', 'DistrictManager');
        $this->load->model('site_embarquement_model', 'Site_embarquementManager');
        $this->load->model('unite_peche_model', 'Unite_pecheManager');

    }
    public function index_get() {
        $id = $this->get('id');
        $annee = $this->get('annee');
 
            if ($id)  
            {
                $data = array();
                $enquete_cadre = $this->Enquete_cadreManager->findById($id);
                $district = $this->DistrictManager->findById($enquete_cadre->district_id);
                $region = $this->RegionManager->findById($enquete_cadre->region_id);
                $site_embarquement = $this->site_embarquementManager->findById($enquete_cadre->site_embarquement_id);
                $unite_peche = $this->unite_pecheManager->findById($enquete_cadre->unite_peche_id);
                $data['id'] = $enquete_cadre->id;                
                $data['annee'] = $enquete_cadre->annee;
                $data['region'] = $region;
                $data['district'] = $district;
                $data['site_embarquement'] = $site_embarquement;
                $data['nbr_unite_peche'] = $enquete_cadre->nbr_unite_pecher;

            } 
            else 
            {
                if ($annee) 
                {
                    $taiza="findAll by annee";
                    $menu = $this->Enquete_cadreManager->findAllbyannee($annee);
                    if ($menu) {
                        foreach ($menu as $key => $value) {
                            $district = array();
                            $region = array();
                            $site_embarquement = array();
                            $unite_peche = array();
                            $district = $this->DistrictManager->findById($value->id_district);
                            $region = $this->RegionManager->findById($value->id_region);
                            $site_embarquement = $this->Site_embarquementManager->findById($value->id_site_embarquement);
                            $unite_peche = $this->Unite_pecheManager->findById($value->id_unite_peche);
                            $data[$key]['id'] = $value->id;
                            $data[$key]['district'] = $district;
                            $data[$key]['annee'] = $value->annee;
                            $data[$key]['region'] = $region;
                            $data[$key]['site_embarquement'] = $site_embarquement;
                            $data[$key]['unite_peche'] = $unite_peche;
                            $data[$key]['nbr_unite_peche'] = $value->nbr_unite_peche;
                        }
                    } else
                        $data = array();
                }
                else 
                {
                    $taiza="findAll no nataony";
                    $menu = $this->Enquete_cadreManager->findAll();
                    if ($menu) {
                        foreach ($menu as $key => $value) {
                            $district = array();
                            $region = array();
                            $site_embarquement = array();
                            $unite_peche = array();
                            $district = $this->DistrictManager->findById($value->id_district);
                            $region = $this->RegionManager->findById($value->id_region);
                            $site_embarquement = $this->Site_embarquementManager->findById($value->id_site_embarquement);
                            $unite_peche = $this->Unite_pecheManager->findById($value->id_unite_peche);
                            $data[$key]['id'] = $value->id;
                            $data[$key]['district'] = $district;
                            $data[$key]['annee'] = $value->annee;
                            $data[$key]['region'] = $region;
                            $data[$key]['site_embarquement'] = $site_embarquement;
                            $data[$key]['unite_peche'] = $unite_peche;
                            $data[$key]['nbr_unite_peche'] = $value->nbr_unite_peche;
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
        $duplication = $this->post('duplication') ;
        if ($duplication) 
        {
            $last_year = $this->Enquete_cadreManager->get_last_year();
            $now_year = date("Y");
            $etat = $this->Enquete_cadreManager->duplication($last_year->annee, $now_year);
            if (!is_null($etat))  {
                        $this->response([
                            'status' => TRUE,
                            'response' => $etat,
                            'message' => 'Data duplicated success'
                                ], REST_Controller::HTTP_OK);
                    } else {
                        $this->response([
                            'status' => FALSE,
                            'response' => 0,
                            'message' => 'No request found'
                                ], REST_Controller::HTTP_BAD_REQUEST);
                    }   
        }
        else 
        {
            if ($supprimer == 0) {
                if ($id == 0) {
                    $data = array(
                        'annee' => $this->post('annee'),
                        'id_region' => $this->post('id_region'),
                        'id_district' => $this->post('id_district'),
                        'id_site_embarquement' => $this->post('id_site_embarquement'),
                        'id_unite_peche' => $this->post('id_unite_peche'),
                        'nbr_unite_peche' => $this->post('nbr_unite_peche')
                    );
                    if (!$data) {
                        $this->response([
                            'status' => FALSE,
                            'response' => 0,
                            'message' => 'No request found'
                                ], REST_Controller::HTTP_BAD_REQUEST);
                    }
                    $dataId = $this->Enquete_cadreManager->add($data);
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
                } 
                else 
                {
                    $data = array(
                        'annee' => $this->post('annee'),
                        'id_region' => $this->post('id_region'),
                        'id_district' => $this->post('id_district'),
                        'id_site_embarquement' => $this->post('id_site_embarquement'),
                        'id_unite_peche' => $this->post('id_unite_peche'),
                        'nbr_unite_peche' => $this->post('nbr_unite_peche')
                    );
                    if (!$data || !$id) {
                        $this->response([
                            'status' => FALSE,
                            'response' => 0,
                            'message' => 'No request found'
                                ], REST_Controller::HTTP_BAD_REQUEST);
                    }
                    $update = $this->Enquete_cadreManager->update($id, $data);
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
                $delete = $this->Enquete_cadreManager->delete($id);
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
}
/* End of file controllername.php */
/* Location: ./application/controllers/controllername.php */
