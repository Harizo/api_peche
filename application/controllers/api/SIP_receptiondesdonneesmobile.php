<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//harizo

require APPPATH . '/libraries/REST_Controller.php';

class SIP_receptiondesdonneesmobile extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_receptiondesdonneesmobile_model', 'SIP_recep_manager');
   
	
    }

   

    public function index_post() 
    { 
        $ok = 1 ;

        $carte_pecheur = array(

                    'id_utilisateur'    => $this->post('id_utilisateur'),
                    'id_serveur' => $this->post('id_serveur'),
                    'id_fokontany' => $this->post('id_fokontany'),
                    'village' => $this->post('village'),
                    'numero' => $this->post('numero'),
                    'date' => $this->post('date'),
                    'association' => $this->post('association'),
                    'nom' => $this->post('nom'),
                    'prenom' => $this->post('prenom'),
                    'date_naissance' => $this->post('date_naissance'),
                    'cin' => $this->post('cin'),
                    'date_cin' => $this->post('date_cin'),
                    'lieu_cin' => $this->post('lieu_cin'),
                    'nbr_pirogue' => $this->post('nbr_pirogue')
                    
                );

        $engins = $this->post('engins') ;
        $carte_pirogues = $this->post('carte_pirogues') ;

    	$dataId = $this->SIP_recep_manager->enregistrer_tout($carte_pecheur, json_decode($engins), json_decode($carte_pirogues));
			
						
		if($dataId) 
        {
			$this->response([
				'status' => TRUE,
				'response' => $dataId,
				'message' => "Inserer avec succès"
					], REST_Controller::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'response' => 'ECHEC',
				'message' => "Transaction non fait"
					], REST_Controller::HTTP_OK);				
		}		
				
    }

  
}
/* End of file controllername.php */

?>
