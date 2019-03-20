<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Nbr_jrs_mois_unite_peche extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('nbr_jrs_mois_unite_peche_model', 'Nbr_jrs_mois_unite_peche_Manager');
        $this->load->model('unite_peche_model', 'Unite_pecheManager');
        
    }
    public function index_get()
    {
        $id = $this->get('id');

            if ($id)
            {   $data = array();
                $unite_peche = $this->Nbr_jrs_mois_unite_peche_Manager->findById($id);
                $data['id'] = $unite_peche->id;
                $data['max_jrs_peche'] = $unite_peche->max_jrs_peche;
            
                $unite_peche = $this->Unite_pecheManager->findById($unite_peche->id_unite_peche);
                $data['unite_peche'] =$unite_peche;
                
            } 
            else
            {	
                $menu = $this->Nbr_jrs_mois_unite_peche_Manager->findAll();
                if ($menu)
                {   foreach ($menu as $key => $value)
                    {   
                        $unite_peche = $this->Unite_pecheManager->findById($value->id_unite_peche);

                        $data[$key]['id'] =$value->id;
                        $data[$key]['unite_peche'] =$unite_peche;
                        $data[$key]['max_jrs_peche'] =$value->max_jrs_peche;    
                        
                    }
                } else
                        $data = array();
                
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
                    'max_jrs_peche' => $this->post('max_jrs_peche')
                );
                if (!$data)
                {   $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                        ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->Nbr_jrs_mois_unite_peche_Manager->add($data);
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
                    'max_jrs_peche' => $this->post('max_jrs_peche')
                );
                
                if (!$data || !$id)
                {   $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                        ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->Nbr_jrs_mois_unite_peche_Manager->update($id, $data);
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
            $delete = $this->Nbr_jrs_mois_unite_peche_Manager->delete($id);
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
