<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_reporting_halieutique extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_reporting_halieutique_model', 'halManager');
    }

    public function index_get() 
    {
        $id = $this->get('id');

         $data = $this->halManager->get_somme_capture_all_espece_by_dist();
    
         
        if ($data) 
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
   
}
/* End of file controllername.php */
/* Location: ./application/controllers/controllername.php */
