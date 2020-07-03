<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Update_id_commune_in_fokontany extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Update_id_commune_in_fokontany_model', 'Upm');
        
    }
    public function index_get() 
    {
        set_time_limit(0);
        ini_set ('memory_limit', '2048M');
       
        $dist_tmp = $this->Upm->findAlldistrict();

        $nbr = 0 ;

        foreach ($dist_tmp as $key => $value) 
        {
            $dist = $this->Upm->findBy_dist_by_nom(($value->nom));

            if ($dist) 
            {
                $datas = array(
                    'id' => $dist->id ,
                    'id_region' => $dist->id_region 
                ); 

               // $data[$nbr] = $this->Upm->update_district_tmp($value->id_pgsql, $dist->id_region);

              //  $data[$nbr] = $this->Upm->update_commune_tmp($value->id_pgsql, $dist->id);
                $nbr++;
                   
                
                
            }



        }

        if (count($data)>0) {
            $this->response([
                'status' => TRUE,
                'response' => $data,
                'message' => 'uodate ok',
                // 'message' => 'Get data success',
            ], REST_Controller::HTTP_OK);
        } else {
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
