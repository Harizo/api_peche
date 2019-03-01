<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Unite_peche extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('unite_peche_model', 'Unite_pecheManager');
        $this->load->model('site_embarquement_model', 'Site_embarquementManager');
        $this->load->model('type_canoe_model', 'Type_canoeManager');
        $this->load->model('type_engin_model', 'Type_enginManager');
    }
    public function index_get()
    {
        $id = $this->get('id');
        $cle_etrangere = $this->get('cle_etrangere');
		$taiza="";
        if($cle_etrangere)
        {
            $taiza="findcle_etrangere no nataony";
            $menu = $this->Unite_pecheManager->findAllBySite_embarquement($cle_etrangere);
            if ($menu)
                {   foreach ($menu as $key => $value)
                    {   $site_embarquement = array();
                        $type_canoe = array();
                        $type_engin = array();

                        $site_embarquement = $this->Site_embarquementManager->findById($value->id_site_embarquement);
                        $type_canoe = $this->Type_canoeManager->findById($value->id_type_canoe);
                        $type_engin = $this->Type_enginManager->findById($value->id_type_engin);

                        $data[$key]['id'] = $value->id;
                        $data[$key]['libelle'] = $value->libelle;

                        $data[$key]['type_canoe'] = $type_canoe;
                        $data[$key]['type_engin'] = $type_engin;
                     //   $data[$key]['site_embarquement'] = $site_embarquement;
                            
                        /*$data[$key]['site_embarquement_id'] = $value->id_site_embarquement;
                        $data[$key]['site_embarquement_nom'] = $site_embarquement->libelle;
                        
                           
                        $data[$key]['type_canoe_id'] = $value->id_type_canoe;
                        $data[$key]['type_canoe_nom'] = $type_canoe ->nom;
                            
                        $data[$key]['type_engin_id'] = $value->id_type_engin;
                        $data[$key]['type_engin_nom'] = $type_engin->libelle;     */                   
                            
                        
                    }
                } else
                        $data = array();
        }
        else
        {
            if ($id)
            {   $data = array();
                $unite_peche = $this->Unite_pecheManager->findById($id);
                $data['id'] = $unite_peche->id;
                $data['libelle'] = $unite_peche->libelle;
              //  $data['id_site_embarquement'] =$unite_peche->id_site_embarquement;
                $data['id_type_canoe'] =$unite_peche->id_type_canoe;
                $data['id_type_engin'] =$unite_peche->id_type_engin;
                
            } 
            else
            {	$taiza="findAll no nataony";
                $menu = $this->Unite_pecheManager->findAll();
                if ($menu)
                {   foreach ($menu as $key => $value)
                    {   $site_embarquement = array();
                        $type_canoe = array();
                        $type_engin = array();

                      //  $site_embarquement = $this->Site_embarquementManager->findById($value->id_site_embarquement);
                        $type_canoe = $this->Type_canoeManager->findById($value->id_type_canoe);
                        $type_engin = $this->Type_enginManager->findById($value->id_type_engin);

                        
                        $data[$key]['id'] = $value->id;
                        $data[$key]['libelle'] = $value->libelle;
                            
                        /*$data[$key]['site_embarquement_id'] = $value->id_site_embarquement;
                        $data[$key]['site_embarquement_nom'] = $site_embarquement->libelle;*/
                     //   $data[$key]['site_embarquement'] = $site_embarquement;
                        $data[$key]['type_canoe'] = $type_canoe;
                        $data[$key]['type_engin'] = $type_engin;
                           
                        /*$data[$key]['type_canoe_id'] = $value->id_type_canoe;
                        $data[$key]['type_canoe_nom'] = $type_canoe ->nom;
                            
                        $data[$key]['type_engin_id'] = $value->id_type_engin;
                        $data[$key]['type_engin_nom'] = $type_engin->libelle;        */                
                            
                        
                    }
                } else
                        $data = array();
                
            }
        }
        if (count($data)>0)
        {   $this->response([
                'status' => TRUE,
                'response' => $data,
                'message' => $taiza,
                // 'message' => 'Get data success',
            ], REST_Controller::HTTP_OK);
        }
        else
        {  $this->response([
                'status' => FALSE,
                'response' => array(),
                'message' => 'No data were found'
            ], REST_Controller::HTTP_OK);
        }
    }
    public function index_post()
    {   $id = $this->post('id') ;
        $supprimer = $this->post('supprimer') ;
        if ($supprimer == 0)
        {   if ($id == 0)
            {   $data = array(
                   // 'site_embarquement_id' => $this->post('site_embarquement_id'),
                    'type_canoe_id' => $this->post('type_canoe_id'),
                    'type_engin_id' => $this->post('type_engin_id'),
                    'libelle' => $this->post('libelle')
                );
                if (!$data)
                {   $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                        ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->Unite_pecheManager->add($data);
                if (!is_null($dataId)) 
                {   $this->response([
                        'status' => TRUE,
                        'response' => $dataId,
                        'message' => 'Data insert success'
                        ], REST_Controller::HTTP_OK);
                }
                else
                {   $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                        ], REST_Controller::HTTP_BAD_REQUEST);
                }         
            }
            else
            {   $data = array(
                   // 'site_embarquement_id' => $this->post('site_embarquement_id'),
                    'type_canoe_id' => $this->post('type_canoe_id'),
                    'type_engin_id' => $this->post('type_engin_id'),
                    'libelle' => $this->post('libelle')
                );
                
                if (!$data || !$id)
                {   $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                        ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->Unite_pecheManager->update($id, $data);
                if(!is_null($update))
                {   $this->response([
                        'status' => TRUE,
                        'response' => 1,
                        'message' => 'Update data success'
                        ], REST_Controller::HTTP_OK);
                } 
                else
                {   $this->response([
                        'status' => FALSE,
                        'message' => 'No request found'
                        ], REST_Controller::HTTP_OK);
                }
            }
        }
        else
        {   if (!$id)
            {   $this->response([
                    'status' => FALSE,
                    'response' => 0,
                    'message' => 'No request found'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
            $delete = $this->Unite_pecheManager->delete($id);
            if (!is_null($delete))
            {   $this->response([
                    'status' => TRUE,
                    'response' => 1,
                    'message' => "Delete data success"
                    ], REST_Controller::HTTP_OK);
            } 
            else
            {   $this->response([
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
