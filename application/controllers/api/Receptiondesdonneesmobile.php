<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//harizo
// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Receptiondesdonneesmobile extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('fiche_echatillonnage_capture_model', 'Fiche_echatillonnage_captureManager');
        $this->load->model('Espece_capture_model', 'Espece_captureManager');
        $this->load->model('echantillon_model', 'EchantillonManager');
		
		// DDB        
        $this->load->model('user_model', 'UserManager');
	
    }

    public function index_post() { 

    	$id = $this->post('id');
    	$id_user = $this->post('id_user');
    	$id_serveur = $this->post('id_serveur');
    	$date = $this->post('date');
    	$id_site_embarquement = $this->post('id_site_embarquement');
    	$id_district = $this->post('id_district');
    	$id_enqueteur = $this->post('id_enqueteur');
    	$id_region = $this->post('id_region');
    	$latitude = $this->post('latitude');
    	$longitude = $this->post('longitude');
    	
			if($id_serveur == 0)//envoi avy any @mobile
			{		
				
			}
			else
			{
				//validation @web na modification
			}
			
						
			if(!is_null($IdsauvegardeFiche_echantillonnge_capture)) {
				$this->response([
					'status' => TRUE,
					'response' => $IdsauvegardeFiche_echantillonnge_capture,
					'message' => "Mise à jour ok"
						], REST_Controller::HTTP_OK);
			} else {
				$this->response([
					'status' => TRUE,
					'response' => 'ECHEC',
					'message' => "Transaction non fait"
						], REST_Controller::HTTP_OK);				
			}		
				
    }
}
/* End of file controllername.php */
/* Location: ./application/controllers/controllername.php */
?>
