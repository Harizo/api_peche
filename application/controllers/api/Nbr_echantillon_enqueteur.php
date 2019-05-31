<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Nbr_echantillon_enqueteur extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('nbr_echantillon_enqueteur_model', 'Nbr_echantillon_enqueteurManager');
        $this->load->model('unite_peche_model', 'Unite_pecheManager');
        $this->load->model('site_embarquement_model', 'Site_embarquementManager');
        $this->load->model('enqueteur_model', 'EnqueteurManager');
        $this->load->model('unite_peche_site_model', 'Unite_peche_site_Manager');
    }
    public function index_get()
    {
        $id = $this->get('id');
        $menus = $this->get('menus');
	       if ($id)
            {   $data = array();
                $nbr_echantillon_enqueteur = $this->Nbr_echantillon_enqueteurManager->findById($id);
                $data['id']                = $nbr_echantillon_enqueteur->id;
                $data['nbr_max_echantillon']   = $nbr_echantillon_enqueteur->nbr_max_echantillon;
                
                $enqueteur = $this->EnqueteurManager->findById($nbr_echantillon_enqueteur->id_enqueur);
                $data['enqueteur'] =$enqueteur;

                $unite_peche = $this->Unite_pecheManager->findById($nbr_echantillon_enqueteur->id_unite_peche);
                $data['unite_peche'] =$unite_peche;
                
                $site_embarquement = $this->Site_embarquementManager->findById($nbr_echantillon_enqueteur->id_site_embarquement);                
                $data['site_embarquement'] =$site_embarquement;
                
            }
            elseif ($menus=='nbr_echantillon')
            {
                $id_unite_pech       = $this->get('id_unite_peche');
                $id_site_embarquemen = $this->get('id_site_embarquement');
                
                $data['nbr_echantillon_predefini'] =0;  
                $nbr_echantillon_predefini = $this->Unite_peche_site_Manager->findnbrechantillonBypecheandsite($id_unite_pech,$id_site_embarquemen);
                if ($nbr_echantillon_predefini)
                {
                   $data['nbr_echantillon_predefini'] = $nbr_echantillon_predefini[0]->nbr_echantillon;
                } 
            }            
            else
            {	
                $menu = $this->Nbr_echantillon_enqueteurManager->findAll();
                if ($menu)
                {   foreach ($menu as $key => $value)
                    {   $enqueteur = $this->EnqueteurManager->findById($value->id_enqueteur);
                        $unite_peche = $this->Unite_pecheManager->findById($value->id_unite_peche);
                        $site_embarquement = $this->Site_embarquementManager->findById($value->id_site_embarquement);

                        $data[$key]['id']                 =$value->id;
                        $data[$key]['unite_peche']        =$unite_peche;
                        $data[$key]['enqueteur']          =$enqueteur;
                        $data[$key]['nbr_max_echantillon']=$value->nbr_max_echantillon;
                        $data[$key]['site_embarquement']  =$site_embarquement;    
                        
                    }
                } else
                        $data = array();
                
            }
        
        if (count($data)>0)
        {   $this->response([
                'status'    => TRUE,
                'response'  => $data,
                'message'   => 'Get data success',
            ], REST_Controller::HTTP_OK);
        }
        else
        {  $this->response([
                'status'    => FALSE,
                'response'  => array(),
                'message'   => 'No data were found'
            ], REST_Controller::HTTP_OK);
        }
    }
    public function index_post()
    {   $id = $this->post('id') ;
        $supprimer = $this->post('supprimer') ;
        if ($supprimer == 0)
        {   if ($id == 0)
            {   $data = array(
                    'id_enqueteur'          => $this->post('enqueteur_id'),
                    'id_unite_peche'        => $this->post('unite_peche_id'),
                    'id_site_embarquement'  => $this->post('site_embarquement_id'),
                    'nbr_max_echantillon'   => $this->post('nbr_max_echantillon')
                );
                if (!$data)
                {   $this->response([
                        'status'    => FALSE,
                        'response'  => 0,
                        'message'   => 'No request found'
                        ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->Nbr_echantillon_enqueteurManager->add($data);
                if (!is_null($dataId)) 
                {   $this->response([
                        'status'    => TRUE,
                        'response'  => $dataId,
                        'message'   => 'Data insert success'
                        ], REST_Controller::HTTP_OK);
                }
                else
                {   $this->response([
                        'status'    => FALSE,
                        'response'  => 0,
                        'message'   => 'No request found'
                        ], REST_Controller::HTTP_BAD_REQUEST);
                }         
            }
            else
            {   $data = array(
                    'id_enqueteur'          => $this->post('enqueteur_id'),
                    'id_unite_peche'        => $this->post('unite_peche_id'),
                    'id_site_embarquement'  => $this->post('site_embarquement_id'),
                    'nbr_max_echantillon'   => $this->post('nbr_max_echantillon')
                );
                
                if (!$data || !$id)
                {   $this->response([
                        'status'    => FALSE,
                        'response'  => 0,
                        'message'   => 'No request found'
                        ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->Nbr_echantillon_enqueteurManager->update($id, $data);
                if(!is_null($update))
                {   $this->response([
                        'status'    => TRUE,
                        'response'  => 1,
                        'message'   => 'Update data success'
                        ], REST_Controller::HTTP_OK);
                } 
                else
                {   $this->response([
                        'status'    => FALSE,
                        'message'   => 'No request found'
                        ], REST_Controller::HTTP_OK);
                }
            }
        }
        else
        {   if (!$id)
            {   $this->response([
                    'status'    => FALSE,
                    'response'  => 0,
                    'message'   => 'No request found'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
            $delete = $this->Nbr_echantillon_enqueteurManager->delete($id);
            if (!is_null($delete))
            {   $this->response([
                    'status'    => TRUE,
                    'response'  => 1,
                    'message'   => "Delete data success"
                    ], REST_Controller::HTTP_OK);
            } 
            else
            {   $this->response([
                    'status'    => FALSE,
                    'response'  => 0,
                    'message'   => 'No request found'
                    ], REST_Controller::HTTP_OK);
            }
        }      
    }
}
/* End of file controllername.php */
/* Location: ./application/controllers/controllername.php */
