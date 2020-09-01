 <?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_saisie_vente_poissonnerie extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_saisie_vente_poissonnerie_model', 'SIP_saisie_vente_poissonnerieManager');
    }

    public function index_get() 
    {
        $id                 = $this->get('id');
        $id_poissonnerie    = $this->get('id_poissonnerie');
        $id_presentation    = $this->get('id_presentation');
        $id_conservation    = $this->get('id_conservation');
        $famille_rh         = $this->get('famille_rh');
        $data   =   array();

        if (($id_presentation)||($id_conservation)||($id_poissonnerie)||($famille_rh))
        {
            if ($id_presentation) 
            {
                $data = $this->SIP_saisie_vente_poissonnerieManager->findClePresentation($id_presentation);               
            }

            if ($id_conservation)
            {
                $data = $this->SIP_saisie_vente_poissonnerieManager->findCleConservation($id_conservation);
            }

            if ($id_poissonnerie)
            {
                $data = $this->SIP_saisie_vente_poissonnerieManager->findClePoissonnerie($id_poissonnerie);
            }

            if ($famille_rh)
            {
                $data = $this->SIP_saisie_vente_poissonnerieManager->findCleFamille($famille_rh);
            }
        }
        else
        {
            if ($id) 
            {
                $data = $this->SIP_saisie_vente_poissonnerieManager->findByid($id);
            } 
           
            else 
            {
               $response = $this->SIP_saisie_vente_poissonnerieManager->findAll();
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
                'id_poissonnerie'           =>$this->post('id_poissonnerie'),
                'reference_fournisseur'     =>$this->post('reference_fournisseur'),
                'famille_rh'                =>$this->post('famille_rh'), 
                'origine_produits'          =>$this->post('origine_produits'),
                'id_conservation'           =>$this->post('id_conservation'),
                'designation_article'       =>$this->post('designation_article'),
                'quantite_vendu'            =>$this->post('quantite_vendu'),
                'id_presentation'           =>$this->post('id_presentation'),
                'chiffre_affaire'           =>$this->post('chiffre_affaire'),
                'prix_kg'                   =>$this->post('prix_kg') ,   
                'observations'              =>$this->post('observations')          
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
                $dataId = $this->SIP_saisie_vente_poissonnerieManager->add($data);
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
                $update = $this->SIP_saisie_vente_poissonnerieManager->update($id, $data);
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
            $delete = $this->SIP_saisie_vente_poissonnerieManager->delete($id);         
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
