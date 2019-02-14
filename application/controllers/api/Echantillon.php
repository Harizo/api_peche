<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Echantillon extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('echantillon_model', 'EchantillonManager');
        $this->load->model('fiche_echantillonnage_capture_model', 'Fiche_echantillonnage_captureManager');
        $this->load->model('type_canoe_model', 'Type_canoeManager');
        $this->load->model('type_engin_model', 'Type_enginManager');
        $this->load->model('utilisateurs_model', 'UserManager');
        $this->load->model('data_collect_model', 'Data_collectManager');

    }
    public function index_get() {
        $id = $this->get('id');
        $cle_etrangere = $this->get('cle_etrangere');
        $id_district = $this->get('id_district');
        $id_region = $this->get('id_region');
		$taiza="";
        if ($cle_etrangere) {
            $taiza="findcle_etrangere no nataony";
            $menu = $this->EchantillonManager->findAllByFiche_echantillonnnage_capture($cle_etrangere);
            if ($menu) {
                    foreach ($menu as $key => $value) {
                        $fiche_echantillonnage_capture = array();
                        $type_canoe = array();
                        $type_engin = array();
                        $data_collect = array();
                        $user = array();

                        $fiche_echantillonnage_capture = $this->Fiche_echantillonnage_captureManager->findById($value->id_fiche_echantillonnage_capture);
                        $type_canoe = $this->Type_canoeManager->findById($value->id_type_canoe);
                        $type_engin = $this->Type_enginManager->findById($value->id_type_engin);
                        $data_collect = $this->Data_collectManager->findById($value->id_data_collect);
                        $user = $this->UserManager->findById($value->id_user);
                        $data[$key]['id'] = $value->id;
                        
                        $data[$key]['fiche_echantillonnage_capture_id'] = $value->id_fiche_echantillonnage_capture;
                        //$data[$key]['fiche_echantillonnage_capture_nom'] = $fiche_echantillonnage_capture->code_unique;
                       
                        $data[$key]['type_canoe_id'] = $value->id_type_canoe;
                        $data[$key]['type_canoe_nom'] = $type_canoe ->nom;
                        
                        $data[$key]['type_engin_id'] = $value->id_type_engin;
                        $data[$key]['type_engin_nom'] = $type_engin->libelle;
                        
                        $data[$key]['peche_hier'] = $value->peche_hier;
                        $data[$key]['peche_avant_hier'] = $value->peche_avant_hier;
                        $data[$key]['nbr_jrs_peche_dernier_sem'] = $value->nbr_jrs_peche_dernier_sem;
                        $data[$key]['total_capture'] = $value->total_capture;
                        $data[$key]['unique_code'] = $value->unique_code;
                        
                        $data[$key]['data_collect_id'] = $value->id_data_collect;
                        $data[$key]['data_collect_nom'] = $data_collect->libelle;
                        
                        $data[$key]['nbr_bateau_actif'] = $value->nbr_bateau_actif;
                        $data[$key]['total_bateau_ecn'] = $value->total_bateau_ecn;
                        
                        $data[$key]['user_id'] = $value->id_user;
                        $data[$key]['user_nom'] = $user->nom;
                        
                        $data[$key]['date_creation'] = $value->date_creation;
                        $data[$key]['date_modification'] = $value->date_modification;

                    }
                } else{
                    $data = array();         
                }
        } else {
            if ($id)  {
                $data = array();
                $echantillon = $this->EchantillonManager->findById($id);
                //$data_collect = $this->Data_collectManager->findById($echantillon->district_id);
               // $user = $this->Data_collectManager->findById($echantillon->district_id);
                $data['id'] = $echantillon->id;
                $data['id_fiche_echantillonage_capture'] =$echantillon->id_fiche_echantillonnage_capture;
                $data['id_type_canoe'] =$echantillon->id_type_canoe;
                $data['id_type_engin'] =$echantillon->id_type_engin;
                $data['peche_hier'] = $echantillon->peche_hier;
                $data['peche_avant_hier'] = $echantillon->peche_avant_hier;
                $data['nbr_jrs_peche_dernier_sem'] = $echantillon->nbr_jrs_peche_dernier_sem;
                $data['total_capture'] = $echantillon->total_capture;                
                $data['unique_code'] = $echantillon->unique_code;
                $data['id_data_collect'] = $echantillon->id_data_collect;
                $data['nbr_bateau_actif'] = $echantillon->nbr_bateau_actif;
                $data['total_bateau_ecn'] = $echantillon->total_bateau_ecn;
                $data['id_user'] =$echantillon->id_user;
                
                $data['date_creation'] = $echantillon->date_creation;
                $data['date_modification'] = $echantillon->date_modification;
                

            } else if($id_district && $id_region) {
				$taiza="Ato ambony ary id_district=".$id_district."  ary id_region=".$id_region; 
				$menu = $this->EchantillonManager->find_Echantillon_avec_District_et_Region();
                if ($menu) {
					$data=$menu;
                } else
                    $data = array();
			} else {
				$taiza="findAll no nataony";
                $menu = $this->EchantillonManager->findAll();
                if ($menu) {
                    foreach ($menu as $key => $value) {
                        $fiche_echantillonnage_capture = array();
                        $type_canoe = array();
                        $type_engin = array();
                        $data_collect = array();
                        $user = array();

                        $fiche_echantillonnage_capture = $this->Fiche_echantillonnage_captureManager->findById($value->id_fiche_echantillonnage_capture);
                        $type_canoe = $this->Type_canoeManager->findById($value->id_type_canoe);
                        $type_engin = $this->Type_enginManager->findById($value->id_type_engin);
                        $data_collect = $this->Data_collectManager->findById($value->id_data_collect);
                        $user = $this->UserManager->findById($value->id_user);
                        $data[$key]['id'] = $value->id;
                        
                        $data[$key]['fiche_echantillonnage_capture_id'] = $value->id_fiche_echantillonnage_capture;
                       // $data[$key]['fiche_echantillonnage_capture_nom'] = $fiche_echantillonnage_capture->code_unique;
                       
                        $data[$key]['type_canoe_id'] = $value->id_type_canoe;
                        $data[$key]['type_canoe_nom'] = $type_canoe ->nom;
                        
                        $data[$key]['type_engin_id'] = $value->id_type_engin;
                        $data[$key]['type_engin_nom'] = $type_engin->libelle;
                        
                        $data[$key]['peche_hier'] = $value->peche_hier;
                        $data[$key]['peche_avant_hier'] = $value->peche_avant_hier;
                        $data[$key]['nbr_jrs_peche_dernier_sem'] = $value->nbr_jrs_peche_dernier_sem;
                        $data[$key]['total_capture'] = $value->total_capture;
                        $data[$key]['unique_code'] = $value->unique_code;
                        
                        $data[$key]['data_collect_id'] = $value->id_data_collect;
                        $data[$key]['data_collect_nom'] = $data_collect->libelle;
                        
                        $data[$key]['nbr_bateau_actif'] = $value->nbr_bateau_actif;
                        $data[$key]['total_bateau_ecn'] = $value->total_bateau_ecn;
                        
                        $data[$key]['user_id'] = $value->id_user;
                        $data[$key]['user_nom'] = $user->nom;
                        
                        $data[$key]['date_creation'] = $value->date_creation;
                        $data[$key]['date_modification'] = $value->date_modification;

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
                    'fiche_echantillonnage_capture_id' => $this->post('fiche_echantillonnage_capture_id'),
                    'type_canoe_id' => $this->post('type_canoe_id'),
                    'type_engin_id' => $this->post('type_engin_id'),
                    'peche_hier' => $this->post('peche_hier'),
                    'peche_avant_hier' => $this->post('peche_avant_hier'),
                    'nbr_jrs_peche_dernier_sem' => $this->post('nbr_jrs_peche_dernier_sem'),
                    'total_capture' => $this->post('total_capture'),
                    'unique_code' => $this->post('unique_code'),
                    'data_collect_id' => $this->post('data_collect_id'),
                    'nbr_bateau_actif' => $this->post('nbr_bateau_actif'),
                    'total_bateau_ecn' => $this->post('total_bateau_ecn'),
                    'user_id' => $this->post('user_id')
                );
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->EchantillonManager->add($data);
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
                    'fiche_echantillonnage_capture_id' => $this->post('fiche_echantillonnage_capture_id'),
                    'type_canoe_id' => $this->post('type_canoe_id'),
                    'type_engin_id' => $this->post('type_engin_id'),
                    'peche_hier' => $this->post('peche_hier'),
                    'peche_avant_hier' => $this->post('peche_avant_hier'),
                    'nbr_jrs_peche_dernier_sem' => $this->post('nbr_jrs_peche_dernier_sem'),
                    'total_capture' => $this->post('total_capture'),
                    'unique_code' => $this->post('unique_code'),
                    'data_collect_id' => $this->post('data_collect_id'),
                    'nbr_bateau_actif' => $this->post('nbr_bateau_actif'),
                    'total_bateau_ecn' => $this->post('total_bateau_ecn'),
                    'user_id' => $this->post('user_id')
                );
                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->EchantillonManager->update($id, $data);
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
            $delete = $this->EchantillonManager->delete($id);
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
