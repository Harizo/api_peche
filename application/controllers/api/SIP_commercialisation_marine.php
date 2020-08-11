<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_commercialisation_marine extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_commercialisation_marine_model', 'SIP_commercialisation_marineManager');
    }

    public function index_get() 
    {
        $id = $this->get('id');
        $compte_nbr_fiche = $this->get('compte_nbr_fiche');
        $id_permis = $this->get('id_permis');
            $data = array();
            

            if ($compte_nbr_fiche) 
            {
                $data = $this->SIP_commercialisation_marineManager->compte_nbr_fiche($id_permis);
            }
            else
            {
                if ($id) 
                {
                    
                    $data = $this->SIP_commercialisation_marineManager->findById($id);
                   
                } 
                else 
                {
                    $response = $this->SIP_commercialisation_marineManager->find_all_join($id_permis);
                    if ($response) 
                    {
                        $data = $response ;
                    }

                }
            }
        if (count($data)>0) 
        {
            $this->response([
                'status' => TRUE,
                'response' => $data,
                'message' => 'Get data success',
            ], REST_Controller::HTTP_OK);
        } 
        else 
        {
            $this->response([
                'status' => FALSE,
                'response' => array(),
                'message' => 'No data were found'
            ], REST_Controller::HTTP_OK);
        }
    }
    public function index_post() 
    {
        $id = $this->post('id') ;
        $supprimer = $this->post('supprimer') ;
        if ($supprimer == 0) 
        {
            if ($id == 0) 
            {
                $data = array(
                   
                    'id_permis'                         =>      $this->post('id_permis'),             
                    'id_espece'                         =>      $this->post('id_espece'),                
                    'numero_visa'                       =>      $this->post('numero_visa'),              
                    'numero_cos'                        =>      $this->post('numero_cos'),              
                    'annee'                             =>      $this->post('annee'),                 
                    'mois'                              =>      $this->post('mois'),                 
                    'id_conservation'                   =>      $this->post('id_conservation'),                
                    'id_presentation'                   =>      $this->post('id_presentation'),                 
                    'coefficiant_conservation'          =>      $this->post('coefficiant_conservation'),

                    'vl_qte'                            =>      $this->post('vl_qte'),
                    'vl_prix_par_kg'                    =>      $this->post('vl_prix_par_kg'),
                    'vl_poids_vif'                      =>      $this->post('vl_poids_vif'),

                    'exp_qte'                           =>      $this->post('exp_qte'),
                    'exp_prix_par_kg'                   =>      $this->post('exp_prix_par_kg'),
                    'exp_poids_vif'                     =>      $this->post('exp_poids_vif'),
                    'exp_destination'                   =>      $this->post('exp_destination'),

                    'export_qte'                           =>      $this->post('export_qte'),
                    'export_prix_par_kg'                   =>      $this->post('export_prix_par_kg'),
                    'export_poids_vif'                     =>      $this->post('export_poids_vif'),
                    'export_destination'                   =>      $this->post('export_destination'),

                    'date_expedition'                   =>      $this->post('date_expedition'),
                    
                    'nbr_colis'                         =>      $this->post('nbr_colis'),
                    'nom_dest'                          =>      $this->post('nom_dest'),
                    'adresse_dest'                      =>      $this->post('adresse_dest'),
                    'lieu_exped'                        =>      $this->post('lieu_exped'),
                    'moyen_transport'                   =>      $this->post('moyen_transport')
                );
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->SIP_commercialisation_marineManager->add($data);
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
                        'message' => 'insertion annuler'
                            ], REST_Controller::HTTP_OK);
                }
            } 
            else 
            {
                $data = array(
                    'id_permis'                         =>      $this->post('id_permis'),              
                    'id_espece'                         =>      $this->post('id_espece'),               
                    'numero_visa'                       =>      $this->post('numero_visa'),              
                    'numero_cos'                        =>      $this->post('numero_cos'),              
                    'annee'                             =>      $this->post('annee'),                 
                    'mois'                              =>      $this->post('mois'),                 
                    'id_conservation'                   =>      $this->post('id_conservation'),                
                    'id_presentation'                   =>      $this->post('id_presentation'),                 
                    'coefficiant_conservation'          =>      $this->post('coefficiant_conservation'),

                    'vl_qte'                            =>      $this->post('vl_qte'),
                    'vl_prix_par_kg'                    =>      $this->post('vl_prix_par_kg'),
                    'vl_poids_vif'                      =>      $this->post('vl_poids_vif'),

                    'exp_qte'                           =>      $this->post('exp_qte'),
                    'exp_prix_par_kg'                   =>      $this->post('exp_prix_par_kg'),
                    'exp_poids_vif'                     =>      $this->post('exp_poids_vif'),
                    'exp_destination'                   =>      $this->post('exp_destination'),

                    'export_qte'                           =>      $this->post('export_qte'),
                    'export_prix_par_kg'                   =>      $this->post('export_prix_par_kg'),
                    'export_poids_vif'                     =>      $this->post('export_poids_vif'),
                    'export_destination'                   =>      $this->post('export_destination'),
                    
                    'date_expedition'                   =>      $this->post('date_expedition'),
                    
                    'nbr_colis'                         =>      $this->post('nbr_colis'),
                    'nom_dest'                          =>      $this->post('nom_dest'),
                    'adresse_dest'                      =>      $this->post('adresse_dest'),
                    'lieu_exped'                        =>      $this->post('lieu_exped'),
                    'moyen_transport'                   =>      $this->post('moyen_transport')
                );

                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->SIP_commercialisation_marineManager->update($id, $data);
                if(!is_null($update)) {
                    $this->response([
                        'status' => TRUE,
                        'response' => 1,
                        'message' => 'Update data success'
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Mis à jour annuler'
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
            $delete = $this->SIP_commercialisation_marineManager->delete($id);         
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
