<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_base_menage extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_base_menage_model', 'crevManager');
    }

    public function index_get() 
    {
        $type_get = $this->get('type_get');

        $region = $this->get('region');
        $district = $this->get('district');
        $commune = $this->get('commune');
      

        switch ($type_get) 
        {
         
            case 'get_all_region':
            {

                $data = $this->crevManager->get_all_region();
                break;
            }
            case 'get_all_district_by_region':
            {

                $data = $this->crevManager->get_all_district_by_region($region);
                break;
            }


            case 'get_all_commune_by_district':
            {

                $data = $this->crevManager->get_all_commune_by_district($district);
                break;
            }

            case 'get_all':
            {

                $data = $this->crevManager->get_all($region, $district, $commune);
                break;
            }
            
            
            default:
               
                break;
        }

        
        
        
        
        if ($data) 
        {
            $this->response([
                'status' => TRUE,
                'response' => $data,
                'message' => 'Get data success'
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
