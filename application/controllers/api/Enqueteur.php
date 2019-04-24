<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Enqueteur extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('enqueteur_model', 'EnqueteurManager');
        $this->load->model('unite_peche_site_model', 'Unite_peche_siteManager');
        $this->load->model('region_model', 'RegionManager');
    }

    public function index_get() {
        $id = $this->get('id');
        $cle_etrangere = $this->get('cle_etrangere');
        $site = array();
            if ($id) 
            {
                $data = array();
                $enqueteur = $this->EnqueteurManager->findById($id);
                $data['id'] = $enqueteur->id;
                $data['prenom'] = $enqueteur->prenom;
                $data['nom'] = $enqueteur->nom;
                $data['telephone'] = $enqueteur->telephone;
            }
            elseif($cle_etrangere)
            {
                $data = array();
               $site_embarquement = $this->EnqueteurManager->findSiteByEnqueteur($cle_etrangere);
               $site['id_site'] = $site_embarquement[0]->id_site;
               $site['libelle'] = $site_embarquement[0]->libelle;
               $site['region'] = $site_embarquement[0]->region;
               $unite_peche=$this->EnqueteurManager->findUniteBySite_embarquement($site_embarquement[0]->id_site);
               foreach ($unite_peche as $key => $value)
               {
                   $data[$key]['id']=$value->id;
                   $data[$key]['libelle']=$value->libelle;
               }
            } 
            else 
            {
                $menu = $this->EnqueteurManager->findAll();
                if ($menu) 
                {
                    foreach ($menu as $key => $value) 
                    {
                        $data[$key]['id'] = $value->id;
                        $data[$key]['prenom'] = $value->prenom;
                        $data[$key]['nom'] = $value->nom;
                        $data[$key]['telephone'] = $value->telephone;
                    }
                } 
                else
                    $data = array();

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
        if (count($site)>0) {
            $this->response([
                'status' => TRUE,
                'site' => $site,
                'message' => 'Get site success',
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => FALSE,
                'response' => array(),
                'message' => 'No site were found'
            ], REST_Controller::HTTP_OK);
        }

    }
    public function index_post() {
        $id = $this->post('id') ;
        $supprimer = $this->post('supprimer') ;
        if ($supprimer == 0) {
            if ($id == 0) {
                $data = array(
                    'nom' => $this->post('nom'),
                    'prenom' => $this->post('prenom'),
                    'telephone' => $this->post('telephone'),
                    'id_region' => $this->post('region_id')
                );
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->EnqueteurManager->add($data);
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
                    'prenom' => $this->post('prenom'),
                    'nom' => $this->post('nom'),
                    'telephone' => $this->post('telephone'),
                    'id_region' => $this->post('region_id')
                );
                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->EnqueteurManager->update($id, $data);
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
            $delete = $this->EnqueteurManager->delete($id);         
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
