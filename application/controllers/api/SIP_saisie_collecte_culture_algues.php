 <?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_saisie_collecte_culture_algues extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_saisie_collecte_culture_algues_model', 'SIP_saisie_collecte_culture_alguesManager');

    }

    public function index_get() 
    {
        $id = $this->get('id');
        $id_commune   = $this->get('id_commune');
        $id_fokontany = $this->get('id_fokontany');
        $annee        = $this->get('annee');
        $mois         = $this->get('mois');
        $data = array();
        
        if ($id_commune)
        { 
            $requete= "AND alg.id_commune=".$id_commune."" ; 
            if ($id_fokontany!='-' && $id_fokontany!='null' && $id_fokontany!='undefined') 
                $requete= $requete." AND alg.id_fokontany=".$id_fokontany."" ;
            
            if ($mois!='null' && $mois!='-' && $annee!='null' && $annee!='undefined' && $annee!='') 
                $requete= $requete." AND alg.annee=".$annee." AND alg.mois=".$mois."" ; 
            else {
                if ($mois!='null' && $mois!='-') 
                    $requete= $requete." AND alg.mois=".$mois."" ;
                if ($annee!='null' && $annee!='undefined' && $annee!='') 
                    $requete= $requete." AND alg.annee=".$annee."" ;
            }
            
            $data = $this->SIP_saisie_collecte_culture_alguesManager->findByFiltre($requete);
        }

        else {
            if ($id) 
            { 
                 $data = $this->SIP_saisie_collecte_culture_alguesManager->findById($id);
            } 
            else 
            { 
                $response = $this->SIP_saisie_collecte_culture_alguesManager->findAll();
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
        $id             = $this->post('id') ;
        $supprimer      = $this->post('supprimer') ;
        if ($supprimer == 0) 
        {
            $data = array(
                'id_commune'    =>      $this->post('id_commune'),
                'id_fokontany'  =>      $this->post('id_fokontany'),              
                'annee'         =>      $this->post('annee'),                 
                'mois'          =>      $this->post('mois'),                 
                'village'       =>      $this->post('village'),                 
                'quantite'      =>      $this->post('quantite'),                 
                'montant'       =>      $this->post('montant')         
            );
            if ($id == 0) 
            {
                
                if (!$data) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $dataId = $this->SIP_saisie_collecte_culture_alguesManager->add($data);
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

                if (!$data || !$id) {
                    $this->response([
                        'status' => FALSE,
                        'response' => 0,
                        'message' => 'No request found'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
                $update = $this->SIP_saisie_collecte_culture_alguesManager->update($id, $data);
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
            $delete = $this->SIP_saisie_collecte_culture_alguesManager->delete($id);         
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
