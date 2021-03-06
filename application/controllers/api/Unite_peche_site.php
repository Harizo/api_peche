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
        $this->load->model('type_canoe_model', 'Type_canoeManager');
        $this->load->model('type_engin_model', 'Type_enginManager');
        $this->load->model('nbr_echantillon_enqueteur_model', 'Nbr_echantillon_enqueteurManager');
        
    }
    public function index_get()
    {
        $id = $this->get('id');
        $cle_etrangere = $this->get('cle_etrangere');
        $cle_site = $this->get('cle_site');
        $menus = $this->get('menus');       
	
        if($cle_etrangere)
        {
            $taiza="findcle_etrangere no nataony";
            $menu = $this->Unite_peche_site_Manager->findAllBySite_embarquement($cle_etrangere);
            if ($menu)
            {   foreach ($menu as $key => $value)
                {   
                    $unite_peche = $this->Unite_pecheManager->findById($value->id_unite_peche);
                    //$site_embarquement = $this->Site_embarquementManager->findById($value->id_site_embarquement);

                    $data[$key]['id'] =$value->id;
                    $data[$key]['unite_peche'] =$unite_peche;
                    $data[$key]['libelle'] =$unite_peche->libelle;
                    //$data[$key]['site_embarquement'] =$site_embarquement;
                    //$data[$key]['nbr_echantillon'] =$value->nbr_echantillon; 
                }
            } 
            else
                    $data = array();
        }
        elseif ($id)
        {   $data = array();
            $unite_peche_site = $this->Unite_peche_site_Manager->findById($id);
            $data['id'] = $unite_peche_site->id;
            $data['nbr_echantillon'] = $unite_peche->nbr_echantillon;
            
            $unite_peche = $this->Unite_pecheManager->findById($unite_peche_site->id_unite_peche);
            $site_embarquement = $this->Site_embarquementManager->findById($unite_peche_site->id_site_embarquement);
            $data['unite_peche'] =$unite_peche;
            $data['site_embarquement'] =$site_embarquement;
                
        }
        elseif($cle_site)
        {
            $taiza="findclesite no nataony";
            $menu = $this->Unite_peche_site_Manager->findAllBySite_embarquementCanoeEngin($cle_site);
            if ($menu)
            {   
                foreach ($menu as $key => $value)
                {   $site_embarquement = array();
                    $type_canoe = array();
                    $type_engin = array();
                    $unite_peche = array();

                    $type_canoe = $this->Type_canoeManager->findById($value->id_type_canoe);
                    $type_engin = $this->Type_enginManager->findById($value->id_type_engin);
                    $unite_peche = $this->Unite_pecheManager->findById($value->id_unite_peche);
                        
                    $data[$key]['id'] = $value->id;
                    $data[$key]['nbr_echantillon'] = $value->nbr_echantillon;
                    $data[$key]['type_canoe'] = $type_canoe;
                    $data[$key]['type_engin'] = $type_engin;
                    $data[$key]['unite_peche'] = $unite_peche;
                          
                }
            } else
                $data = array();

        }
        elseif ($menus=='nbr_echantillon')
        {   
            $annee               = $this->get('annee');
            $mois                = $this->get('mois');
            $id_enqueteur        = $this->get('id_enqueteur');
            $id_unite_pech       = $this->get('id_unite_peche');
            $id_site_embarquemen = $this->get('id_site_embarquement');
            
            $data['nbr_echantillon_actuel']              = 0; 
            $data['nbr_echantillon_enqueteur_actuel']    = 0;
            $data['nbr_echantillon_enqueteur_predefini'] = 0;             
               
            $nbr_echantillon_predefini = $this->Unite_peche_site_Manager->findnbrechantillonBypecheandsite($id_unite_pech,$id_site_embarquemen);

            $nbr_echantillon_actuel = $this->Unite_peche_site_Manager->nbrechantillontotal($this->generer_requete($annee,$id_site_embarquemen,$id_unite_pech));
            
            $data['nbr_echantillon_predefini'] = $nbr_echantillon_predefini[0]->nbr_echantillon;
             
            if ($nbr_echantillon_actuel)
            {
                $data['nbr_echantillon_actuel'] = $nbr_echantillon_actuel[0]->nombre;
            }

            
            $nbr_echantillon_enqueteur_predefini = $this->Nbr_echantillon_enqueteurManager->max_echantillon_enqueteur($id_enqueteur,$id_unite_pech,$id_site_embarquemen);
            $nbr_echantillon_enqueteur_actuel = $this->Unite_peche_site_Manager->nbrechantillontotal($this->generer_requeteenqueteur($annee, $mois, $id_site_embarquemen, $id_unite_pech, $id_enqueteur));
                
            if ($nbr_echantillon_enqueteur_predefini)
            {
                $data['nbr_echantillon_enqueteur_predefini']=$nbr_echantillon_enqueteur_predefini[0]->max;
            }
            if ($nbr_echantillon_enqueteur_actuel)
            {
                $data['nbr_echantillon_enqueteur_actuel']=$nbr_echantillon_enqueteur_actuel[0]->nombre;
            }
        }
            
        else
        {	
            $menu = $this->Unite_peche_site_Manager->findAll();
            if ($menu)
            {   
                foreach ($menu as $key => $value)
                {   
                    $unite_peche = $this->Unite_pecheManager->findById($value->id_unite_peche);
                    $site_embarquement = $this->Site_embarquementManager->findById($value->id_site_embarquement);

                    $data[$key]['id'] =$value->id;
                    $data[$key]['unite_peche'] =$unite_peche;
                    $data[$key]['site_embarquement'] =$site_embarquement;
                    $data[$key]['nbr_echantillon'] =$value->nbr_echantillon;    
                        
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
                    'id_site_embarquement' => $this->post('site_embarquement_id'),
                    'nbr_echantillon' => $this->post('nbr_echantillon')
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
                    'id_site_embarquement' => $this->post('site_embarquement_id'),
                    'nbr_echantillon' => $this->post('nbr_echantillon')
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

     public function generer_requete($annee,$id_site_embarquement, $id_unite_peche)
    {
        $requete = "date BETWEEN '".$annee."-01-01' AND '".$annee."-12-31' " ;
          
            if ($id_site_embarquement) 
            {
                $requete = $requete." AND id_site_embarquement='".$id_site_embarquement."'" ;
            }

            if ($id_unite_peche) 
            {
                $requete = $requete." AND id_unite_peche='".$id_unite_peche."'" ;
            }

        return $requete ;
    }
    public function generer_requeteenqueteur($annee, $mois, $id_site_embarquement, $id_unite_peche,$id_enqueteur)
    {

        $j = cal_days_in_month(CAL_GREGORIAN, $mois, $annee);
        $requete = "date BETWEEN '".$annee."-".$mois."-01' AND '".$annee."-".$mois."-".$j."' " ;
          
            if ($id_site_embarquement) 
            {
                $requete = $requete." AND id_site_embarquement='".$id_site_embarquement."'" ;
            }

            if ($id_unite_peche) 
            {
                $requete = $requete." AND id_unite_peche='".$id_unite_peche."'" ;
            }
            if ($id_enqueteur) 
            {
                $requete = $requete." AND id_enqueteur='".$id_enqueteur."'" ;
            }

        return $requete ;
    }
}
/* End of file controllername.php */
/* Location: ./application/controllers/controllername.php */
