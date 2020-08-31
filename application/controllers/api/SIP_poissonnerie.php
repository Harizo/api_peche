 <?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_poissonnerie extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_poissonnerie_model', 'SIP_poissonnerieManager');

    }

    public function index_get() 
    {
        $id = $this->get('id');
        $id_region    = $this->get('id_region');
        $data = array();
        
        if ($id_region)
        {
            $data = $this->SIP_poissonnerieManager->findCleRegion($id_region);
        }

        else {
            if ($id) 
            {
                 $data = $this->SIP_poissonnerieManager->findById($id);
            } 
            else 
            {
                $response = $this->SIP_poissonnerieManager->findAll();
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
                'id'            =>      $this->post('id'),
                'id_region'     =>      $this->post('id_region'),              
                'nom'           =>      $this->post('nom'),
                'localisation'  =>      $this->post('localisation'),                 
                'adresse'       =>      $this->post('adresse'),                 
                'rcs'           =>      $this->post('rcs'),                 
                'stat'          =>      $this->post('stat'),                 
                'nif'           =>      $this->post('nif'),                 
                'tel'           =>      $this->post('tel')         
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
                $dataId = $this->SIP_poissonnerieManager->add($data);
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
                $update = $this->SIP_poissonnerieManager->update($id, $data);
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
            $delete = $this->SIP_poissonnerieManager->delete($id);         
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
