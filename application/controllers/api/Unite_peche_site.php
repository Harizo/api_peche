<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Unite_peche_site extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('unite_peche_site_model', 'Unite_peche_site_Manager');
        $this->load->model('unite_peche_model', 'Unite_pecheManager');
        $this->load->model('site_embarquement_model', 'Site_embarquementManager');
        
    }
    public function index_get()
    {
        $id = $this->get('id');
        $cle_etrangere = $this->get('cle_etrangere');
	
        if($cle_etrangere)
        {
            /*$taiza="findcle_etrangere no nataony";
            $menu = $this->Unite_peche_site_Manager->findAllBySite_embarquement($cle_etrangere);
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

                }
            } 
            else
                    $data = array();*/
        }
        else
        {
            if ($id)
            {   $data = array();
                $unite_peche = $this->Unite_peche_site_Manager->findById($id);
                $data['id'] = $unite_peche->id;
            
                $unite_peche = $this->Unite_pecheManager->findById($unite_peche->id_unite_peche);
                $site_embarquement = $this->Site_embarquementManager->findById($unite_peche->id_site_embarquement);
                $data['unite_peche'] =$unite_peche;
                $data['site_embarquement'] =$site_embarquement;
                
            } 
            else
            {	
                $menu = $this->Unite_peche_site_Manager->findAll();
                if ($menu)
                {   foreach ($menu as $key => $value)
                    {   
                        $unite_peche = $this->Unite_pecheManager->findById($value->id_unite_peche);
                        $site_embarquement = $this->Site_embarquementManager->findById($value->id_site_embarquement);

                        $data[$key]['id'] =$value->id;
                        $data[$key]['unite_peche'] =$unite_peche;
                        $data[$key]['site_embarquement'] =$site_embarquement;    
                        
                    }
                } else
                        $data = array();
                
            }
        }
        if (count($data)>0)
        {   $this->response([
                'status' => TRUE,
                'response' => $data,
                'message' => 'Get data success',
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
                
                    'id_unite_peche' => $this->post('unite_peche_id'),
                    'id_site_embarquement' => $this->post('site_embarquement_id')
                );
                if (!$data)
                {   $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                        ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->Unite_peche_site_Manager->add($data);
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
                   'id_unite_peche' => $this->post('unite_peche_id'),
                    'id_site_embarquement' => $this->post('site_embarquement_id')
                );
                
                if (!$data || !$id)
                {   $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                        ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->Unite_peche_site_Manager->update($id, $data);
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
            $delete = $this->Unite_peche_site_Manager->delete($id);
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
