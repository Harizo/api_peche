<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_reporting_peche_thon_malagasy extends REST_Controller
{
    public function __construct()
    { parent::__construct();
        $this->load->model('SIP_reporting_peche_thon_malagasy_model', 'ReportingpechethonmalagasyManager');
    }

    public function index_get() {	
		set_time_limit(0);
    	$menu = $this->get('menu');
    	$annee_debut = $this->get('annee_debut');
    	$annee_fin = $this->get('annee_fin');
    	$export_excel = $this->get('export_excel');   
		$repertoire="peche_thon_malagasy/";
    	$data=array();
		// AFFICHAGE RESULTAT REQUETE
		if($menu =="menu_12_1") {
			// Qté par espèce annuel
			// Reporting ACCESS 12.1
			$data=$this->ReportingpechethonmalagasyManager->reporting_peche_thon_malagasy_qte_espece_annuel($annee_debut,$annee_fin);
			$titre_etat="Quantité thon malagasy par espèces";
		} else if($menu =="menu_12_2") {
			 // PAR ESPECE PAR MOIS ET ANNUEL
			 // Reporting ACCESS 12.2
			$data=$this->ReportingpechethonmalagasyManager->reporting_thoniere_malagasy_etranger_par_espece_par_mois($annee_debut,$annee_fin);
			$titre_etat="Quantité thon malagasy par espèces mois";
		} else if($menu =="menu_12_3") {
			// PAR MOIS PAR NAVIRE ET PAR ESPECE
			// Reporting ACCESS 12.3
			 $data=$this->ReportingpechethonmalagasyManager->reporting_thoniere_malagasy_par_mois_navire_espece($annee_debut,$annee_fin);
			$titre_etat="Quantité thon malagasy par navire mois";
		} else if($menu =="menu_12_4") {
			// Qté annuel par espèce et par navire
			// Reporting ACCESS 12.4
			 $data=$this->ReportingpechethonmalagasyManager->reporting_peche_thon_malagasy_par_navire_espece($annee_debut,$annee_fin);
			 $titre_etat="Quantité thon malagasy par espèces navire";
		} else if($menu =="menu_12_5") {
			// Qté hamecon mensuel et annuel
			// Reporting ACCESS 12.5
			$data=$this->ReportingpechethonmalagasyManager->reporting_peche_thon_malagasy_hamecon_mensuel($annee_debut,$annee_fin);
			$titre_etat="Nombre hamecon utilisé par mois thon malagasy";
		} else if($menu =="menu_12_6"){
			// PAR SOCIETE MENSUEL ET ANNUEL
			// Reporting ACCESS 12.6
			$data=$this->ReportingpechethonmalagasyManager->reporting_peche_thon_malagasy_par_societe_mensuel_annuel($annee_debut,$annee_fin);
			$titre_etat="Quantité thon malagasy par société";
		} else if($menu =="menu_12_7") {
			$data=array();
		} else if($menu =="menu_12_8") {
			// NOMBRE JOUR EN MER PAR NAVIRE, MENSUEL ET ANNUEL
			// Reporting ACCESS 12.8					
			$data=$this->ReportingpechethonmalagasyManager->reporting_thoniere_malagasy_etranger_jour_en_mer_navire_mensuel_annuel($annee_debut,$annee_fin);
			$titre_etat="Nombre jour de mer thon malagasy";
		} else if($menu =="menu_12_9") {
			// NOMBRE JOUR DE PECHE PAR NAVIRE, MENSUEL ET ANNUEL
			// Reporting ACCESS 12.9				
			$data=$this->ReportingpechethonmalagasyManager->reporting_thoniere_malagasy_etranger_jour_peche_navire_mensuel_annuel($annee_debut,$annee_fin);
			$titre_etat="Nombre jour de peche thon malagasy";
		} else {
			$data=array();
		}
        //EXPORT EXCEL
            if ($export_excel == 'oui') 
            {

                require_once 'Classes/PHPExcel.php';
                require_once 'Classes/PHPExcel/IOFactory.php';

                $directoryName = dirname(__FILE__) ."/../../../../export_excel/".$repertoire;
                //Check if the directory already exists.
                if(!is_dir($directoryName))
                {
                    mkdir($directoryName, 0777,true);
                }
                
                $objPHPExcel = new PHPExcel();
                $objPHPExcel->getProperties()->setCreator("S.I.P")
                            ->setLastModifiedBy("S.I.P")
                            ->setTitle("reporting peche thon malagasy")
                            ->setSubject("reporting peche thon malagasy")
                            ->setDescription("reporting peche thon malagasy")
                            ->setKeywords("reporting peche thon malagasy")
                            ->setCategory("reporting peche thon malagasy");

                $ligne=1; 

                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
                $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
                $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
                $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
                $objPHPExcel->getActiveSheet()->getPageMargins()->SetLeft(0.64); //***pour marge gauche
                $objPHPExcel->getActiveSheet()->getPageMargins()->SetRight(0.64); //*** pour marge droite

                
                $entete = array_keys((array)$data[0]) ;
                $nbr_cell = count($entete) - 1;
                $alphabet = 65 ;

                // foreach ($entete as $key => $value) {
                    // $objPHPExcel->getActiveSheet()->getColumnDimension(chr($alphabet))->setautoSize(true) ;
                    // $alphabet++ ;
                // }

                $objPHPExcel->getActiveSheet()->setTitle("Feuille 1");
                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&11&B Page &P / &N');
                $objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&R&11&B Page &P / &N');


                $styleTitre = array
                (
                    'alignment' => array
                    (
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        
                    ),
                    
                    'font' => array
                    (
                        //'name'  => 'Times New Roman',
                        'bold'  => true,
                        'size'  => 14
                    ),
                );

                $stylesousTitre = array
		        ('borders' => array
		            (
		                'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
		            ),
		        'alignment' => array
		            (
		                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
		                
		            ),
		        'font' => array
		            (
		                //'name'  => 'Times New Roman',
		                'bold'  => true,
		                'size'  => 12
		            ),
		        );

                $stylecontenu = array
                (
                    'borders' => array
                    (
                        'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
                    ),
                    
                    'alignment' => array
                    (
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    )
                );
            //TITRE
				$objPHPExcel->getActiveSheet()->getColumnDimension(chr(65))->setautoSize(true) ;
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(40);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr(65).$ligne, "Etat : ".$titre_etat);
				if($nbr_cell >25) {
					// MergeCells jusquà la cellule Z seulement
					$objPHPExcel->getActiveSheet()->mergeCells(chr(65).$ligne.":".chr(90).$ligne);
					 $objPHPExcel->getActiveSheet()->getStyle(chr(65).$ligne.":".chr(90).$ligne)->applyFromArray($styleTitre);
				} else {
					$objPHPExcel->getActiveSheet()->mergeCells(chr(65).$ligne.":".chr((65+$nbr_cell)).$ligne);
					$objPHPExcel->getActiveSheet()->getStyle(chr(65).$ligne.":".chr((65+$nbr_cell)).$ligne)->applyFromArray($styleTitre);
				}
            //FIN TITRE
                $ligne = $ligne + 2 ;
            //ENTETE
				$lettre_avant=0;
                $alphabet = 65 ;
                foreach ($entete as $key => $value) 
                {
					$objPHPExcel->getActiveSheet()->getStyle(($lettre_avant >0 ? chr($lettre_avant).chr($alphabet).$ligne  : chr($alphabet).$ligne))->applyFromArray($stylesousTitre);
					$objPHPExcel->getActiveSheet()->getStyle(($lettre_avant >0 ? chr($lettre_avant).chr($alphabet).$ligne  : chr($alphabet).$ligne))->getAlignment()->setWrapText(true);
                	$objPHPExcel->setActiveSheetIndex(0)->setCellValue(($lettre_avant >0 ? chr($lettre_avant).chr($alphabet).$ligne  : chr($alphabet).$ligne), (string)$value);
                	$alphabet++;
					if($alphabet ==91) {
						// $alphabet > 'Z' ==> retour vers 'A' = 65
						$alphabet = 65 ;
						if($lettre_avant==0) {
							$lettre_avant=65; // 'A' ==> commence par 'AA'
						} else {
							$lettre_avant=$lettre_avant +1;
						}
					}
                }
                $ligne++;
            //FIN ENTETE
            //CONTENU  
                for ($i=0; $i < count($data); $i++) 
                {
					$lettre_avant=0;
                	$alphabet = 65 ;
	                foreach ($entete as $key => $value) 
	                {
						$objPHPExcel->getActiveSheet()->getStyle(chr(65).$ligne.":".($lettre_avant >0 ? chr($lettre_avant).chr($alphabet).$ligne  : chr($alphabet).$ligne))->applyFromArray($stylecontenu);
						$objPHPExcel->getActiveSheet()->getStyle(chr(65).$ligne.":".($lettre_avant >0 ? chr($lettre_avant).chr($alphabet).$ligne  : chr($alphabet).$ligne))->getAlignment()->setWrapText(true);
						
	                	$objPHPExcel->setActiveSheetIndex(0)->setCellValue(($lettre_avant >0 ? chr($lettre_avant).chr($alphabet).$ligne  : chr($alphabet).$ligne), $data[$i]->$value);
						$objPHPExcel->getActiveSheet()->getColumnDimension(($lettre_avant >0 ? chr($lettre_avant).chr($alphabet)  : chr($alphabet)))->setautoSize(true) ;
	                	$alphabet++;
						if($alphabet ==91) {
							// $alphabet > 'Z' ==> retour vers 'A' = 65
							$alphabet = 65 ;
							if($lettre_avant==0) {
								$lettre_avant=65; // 'A' ==> commence par 'AA'
							} else {
								$lettre_avant=$lettre_avant +1;
							}
						}
	                }
	                $ligne++;               	
                }
            //FIN CONTENU
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save(dirname(__FILE__) . "/../../../../export_excel/".$repertoire."/".$titre_etat.".xlsx");
            }
        //FIN EXPORT EXCEL        
        if ($export_excel == 'oui') 
        {

            if ($data) 
            {
                $this->response([
                    'status' => TRUE,
                    'nom_file' => $titre_etat.".xlsx",
                    'repertoire' =>  $repertoire,
                    'entete' =>  $entete,
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
            
        } else if (count($data)>0)	{    		
            $this->response([
                'status' => TRUE,
                'response' => $data,
                'message' => 'Get file success',
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