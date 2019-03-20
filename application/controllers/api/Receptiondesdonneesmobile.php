<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//harizo
// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Receptiondesdonneesmobile extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Fiche_echantillonnage_capture_model', 'Fiche_echantillonnage_captureManager');
        $this->load->model('Espece_capture_model', 'Espece_captureManager');
        $this->load->model('echantillon_model', 'EchantillonManager');

        $this->load->model('site_embarquement_model', 'Site_embarquementManager');
        $this->load->model('district_model', 'DistrictManager');
        $this->load->model('region_model', 'RegionManager');
		
		// DDB        
        $this->load->model('Utilisateurs_model', 'UserManager');
	
    }

    public function index_post() { 

    	
        $date_envoi = date('Y/m/d');
    	$date_code_unique = date('d/m/Y');

    	$numero_fiche = $this->Fiche_echantillonnage_captureManager->numero($date_envoi);
    	$site_embarquement = $this->Site_embarquementManager->findById($this->post('id_site_embarquement'));
    	$district = $this->DistrictManager->findById($site_embarquement->id_district);
        $region = $this->RegionManager->findById($site_embarquement->id_region);

        $sequence = intval($numero_fiche[0]->nombre) + 1;
		if($sequence < 10) {
			$sequence = '0'.$sequence;
		}

        $code_unique = $region->nom."-".$site_embarquement->libelle."-".$date_code_unique."-".$sequence ;


        $id_serveur = $this->post('id_serveur') ;

        $echantillons = $this->post('echantillons') ;
       // $especes_captures = $this->post('especes_captures') ;

    	$data = array(

                    'code_unique'    => $code_unique,
                    'date' => $this->post('date'),
                    'id_user'    => $this->post('id_user'),
                    'id_serveur' => $this->post('id_serveur'),
                    'id_site_embarquement' => $this->post('id_site_embarquement'),
                    'id_district' => $this->post('id_district'),
                    'id_enqueteur' => $this->post('id_enqueteur'),
                    'id_region' => $this->post('id_region'),
                    'latitude' => $this->post('latitude'),
                    'longitude' => $this->post('longitude'),
                    'altitude' => $this->post('altitude')
                );


			if($id_serveur == 0)//envoi avy any @mobile na web site tsy app centrale
			{		
				$dataId_fiche = $this->Fiche_echantillonnage_captureManager->add($data);
			}
			else
			{
				//$dataId_fiche = $id_serveur ;
                
			}

            if ($dataId_fiche) //insertion zanany
            {
                if (count($echantillons) > 0 ) 
                {
                    /*foreach ($echantillons as $key_ech => $ech) 
                    {
                        
                    }*/

                    $etat_echantillon = $this->EchantillonManager->save_all($dataId_fiche, json_decode($echantillons));
                }
            }

            $obj = array() ;

            $obj['id_serveur'] = $dataId_fiche ;
            $obj['code_unique'] = $code_unique ;
           // $obj['etat_echantillon'] = $etat_echantillon ;
			
						
			if(!is_null($obj)) {
				$this->response([
					'status' => TRUE,
					'response' => $obj,
					'message' => "Inserer avec succès"
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
