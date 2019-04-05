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

        $this->load->model('nbr_echantillon_enqueteur_model', 'Nbr_echantillon_enqueteurManager');
	
    }

    public function index_post() { 

    	$message_erreur = "" ;
        $date_envoi = date('Y/m/d');
    	$date_code_unique = date('d/m/Y');

        

    	$numero_fiche = $this->Fiche_echantillonnage_captureManager->numero($date_envoi);
    	$site_embarquement = $this->Site_embarquementManager->findById($this->post('id_site_embarquement'));
    	$district = $this->DistrictManager->findById($site_embarquement->id_district);
        $region = $this->RegionManager->findById($site_embarquement->id_region);
        $id_enqueteur = $this->post('id_enqueteur' );
        $sequence = intval($numero_fiche[0]->nombre) + 1;
		if($sequence < 10) {
			$sequence = '0'.$sequence;
		}

        $code_unique = $region->nom."-".$site_embarquement->libelle."-".$date_code_unique."-".$sequence ;


        $id_serveur = $this->post('id_serveur') ;
        $date = $this->post('date') ;

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
                    'altitude' => $this->post('altitude'),
                    'validation' => 0
                );


			if($id_serveur == 0)//envoi avy any @mobile na web site tsy app centrale
			{	
                
                foreach (json_decode($echantillons) as $key => $value) 
                {
                    //get max echantillon possible
                        $nbr_echantillon_enqueteur = $this->Nbr_echantillon_enqueteurManager->findByenqueteur_unite_peche_site($id_enqueteur, $value->id_unite_peche ,$site_embarquement->id);
                    //get max echantillon possible

                    if (!$nbr_echantillon_enqueteur) 
                    {
                        $message_erreur = "Limite non définie pour /".$value->id_unite_peche ;

                        break ;
                    }
                    else
                    {
                        $limite_echantillon = $nbr_echantillon_enqueteur->nbr_max_echantillon ;
                        //nbr echantillon efa vita
                        

                            $echantillon_max = $this->Fiche_echantillonnage_captureManager->get_nbr_max_echantillon($date,$id_enqueteur,$value->id_unite_peche);

                            if (!$echantillon_max) 
                            {
                                $nbr_echantillon_bdd = 0 ;
                                $nbr_echantillon_bdd++;
                            }
                            else
                            {
                                $nbr_echantillon_bdd = $echantillon_max[0]->nombre ;
                                $nbr_echantillon_bdd++;
                            }
                            //bloackage ajout si limite inferieur
                            if ( $nbr_echantillon_bdd > $limite_echantillon) 
                            {
                                $tab_date = explode('-', $date) ;
                                $message_erreur ="Maximum atteint pour le Mois de ".$this->affichage_mois($tab_date[1])."Envoyé:".$nbr_echantillon_bdd." Max:".$limite_echantillon." Unité de pêche: /".$value->id_unite_peche ;
                                break;
                            }
                            
                            //fin bloackage ajout si limite inferieur
                        
                        //nbr echantillon efa vita
                    }

                    
                }

                if ($message_erreur == "") 
                {
                    $dataId_fiche = $this->Fiche_echantillonnage_captureManager->add($data);
                }
				else 
                {
                    $dataId_fiche = false ;
                }




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
            $obj['message_erreur'] = $message_erreur ;
			
						
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

    public function affichage_mois($mois_int)
    {
        switch ($mois_int) {
            case '1':
                return "Janvier";
                break;
            case '2':
                return "Février";
                break;
            case '3':
                return "Mars";
                break;
            case '4':
                return "Avril";
                break;
            case '5':
                return "Mai";
                break;
            case '6':
                return "Juin";
                break;
            case '7':
                return "Juillet";
                break;
            case '8':
                return "Août";
                break;
            case '9':
                return "Septembre";
                break;
            case '10':
                return "Octobre";
                break;
            case '11':
                return "Novembre";
                break;
            case '12':
                return "Décembre";
                break;
            
            default:
                return "";
                break;
        }
    }
}
/* End of file controllername.php */
/* Location: ./application/controllers/controllername.php */
?>
