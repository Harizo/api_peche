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
    	'id_serveur' => $this->post('id_serveur'),

			$data = array(
                    'fiche_echantillonnage_capture' => $this->post('fiche_echantillonnage_capture'),                    
                    'user_id' => $this->post('user_id')
                );
			
			if($id_serveur == 0)
			{		
				if(count($echatillonnages_captures) >0)
				{
				$IdsauvegardeFiche_echantillonnge_capture=$this->Fiche_echantillonnge_captureManager->SauvegarderTout($data);

				$IdsauvegardeEchantillon = $this->EchantillonManager->SauvegarderTout($data);
				$IdsauvegardeEspece_capture = $this->Espece_captureManager->SauvegarderTout($data);

				}
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
