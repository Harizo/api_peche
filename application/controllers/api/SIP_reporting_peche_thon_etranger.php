<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_reporting_peche_thon_etranger extends REST_Controller
{
    public function __construct()
    { parent::__construct();
        $this->load->model('SIP_reporting_peche_thon_malagasy_model', 'ReportingpechethonmalagasyManager');
        $this->load->model('SIP_reporting_peche_thon_etranger_model', 'ReportingpechethonetrangerManager');
    }

    public function index_get() {	
    	$menu = $this->get('menu');
    	$annee_debut = $this->get('annee_debut');
    	$annee_fin = $this->get('annee_fin');
		$table_en_tete=	$this->get('table_en_tete');   
		$table_sequence =$this->get('table_sequence');   
		$cle_etranger_sequence=$this->get('cle_etranger_sequence');   
		$table_sequence_capture=$this->get('table_sequence_capture');   
		$cle_etranger_sequence_capture=$this->get('cle_etranger_sequence_capture');   
    	$export_excel = $this->get('export_excel');   
    	$data=array();
    	if($export_excel) {
			// EXPORT EXCEL
     	} else {
			// AFFICHAGE RESULTAT REQUETE
			if($menu =="menu_13_1") {
				// Qté par espèce annuel
				// Reporting ACCESS 13.1
				$data=$this->ReportingpechethonetrangerManager->reporting_peche_thon_etranger_qte_espece_annuel($annee_debut,$annee_fin);
			} else if($menu =="menu_13_2") {
				 // Nombre par espèce annuel
				 // Reporting ACCESS 13.2
				$data=$this->ReportingpechethonetrangerManager->reporting_peche_thon_etranger_nombre_espece_annuel($annee_debut,$annee_fin,$table_en_tete,$table_sequence,$cle_etranger_sequence,$table_sequence_capture,$cle_etranger_sequence_capture);
			} else if($menu =="menu_13_3") {
				 // PAR ESPECE PAR MOIS ET ANNUEL
				 // Reporting ACCESS 13.3
				$data=$this->ReportingpechethonmalagasyManager->reporting_thoniere_malagasy_etranger_par_espece_par_mois($annee_debut,$annee_fin,$table_en_tete,$table_sequence,$cle_etranger_sequence,$table_sequence_capture,$cle_etranger_sequence_capture);
			}  else if($menu =="menu_13_5") {
				// PAR MOIS PAR NAVIRE ET PAR ESPECE
				// Reporting ACCESS 13.
				 $data=$this->ReportingpechethonmalagasyManager->reporting_thoniere_malagasy_etranger_par_mois_navire_espece($annee_debut,$annee_fin,$table_en_tete,$table_sequence,$cle_etranger_sequence,$table_sequence_capture,$cle_etranger_sequence_capture);
			} else if($menu =="menu_13_6") {
				// Qté annuel par espèce et par navire
				// Reporting ACCESS 13.6
				 $data=$this->ReportingpechethonetrangerManager->reporting_peche_thon_etranger_par_navire_espece($annee_debut,$annee_fin);
			} else if($menu =="menu_13_8") {
				// Qté hamecon mensuel et annuel
				// Reporting ACCESS 13.8
				$data=$this->ReportingpechethonetrangerManager->reporting_peche_thon_etranger_hamecon_mensuel($annee_debut,$annee_fin);
			} else if($menu =="menu_13_"){
				// PAR SOCIETE MENSUEL ET ANNUEL
				// Reporting ACCESS 13.
				$data=$this->ReportingpechethonmalagasyManager->reporting_thoniere_malagasy_etranger_par_societe_mensuel_annuel($annee_debut,$annee_fin,$table_en_tete,$table_sequence,$cle_etranger_sequence,$table_sequence_capture,$cle_etranger_sequence_capture);
			} else if($menu =="menu_13_7") {
				$data=array();
			} else if($menu =="menu_13_9") {
				// NOMBRE JOUR EN MER PAR NAVIRE, MENSUEL ET ANNUEL
				// Reporting ACCESS 13.9				
				$data=$this->ReportingpechethonmalagasyManager->reporting_thoniere_malagasy_etranger_jour_en_mer_navire_mensuel_annuel($annee_debut,$annee_fin,$table_en_tete);
			} else if($menu =="menu_13_20") {
				// NOMBRE JOUR DE PECHE PAR NAVIRE, MENSUEL ET ANNUEL
				// Reporting ACCESS 13.20				
				$data=$this->ReportingpechethonmalagasyManager->reporting_thoniere_malagasy_etranger_jour_peche_navire_mensuel_annuel($annee_debut,$annee_fin,$table_en_tete);
			}else if($menu =="menu_13_21") {
				// Qté estimé débarqué
				// Reporting ACCESS 13.21				
				$data=$this->ReportingpechethonetrangerManager->reporting_thoniere_etranger_estime_debarque($annee_debut,$annee_fin);
			}else if($menu =="menu_13_29") {
				// NOMBRE JOUR DE PECHE PAR NAVIRE, MENSUEL ET ANNUEL
				// Reporting ACCESS 13.29				
				$data=$this->ReportingpechethonetrangerManager->reporting_thoniere_etranger_par_position($annee_debut,$annee_fin);
			} else {
				$data=array();
			}		
		}
    	if (count($data)>0)	{    		
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

    public function excel_agent($enqueteur,$site,$daty,$repertoire,$data)
    {
    	require_once 'Classes/PHPExcel.php';
		require_once 'Classes/PHPExcel/IOFactory.php';

		$nom_file='Rapport_agent_enqueteur';
    	$directoryName = dirname(__FILE__) ."/../../../../../../assets/excel/".$repertoire;

    	//Check if the directory already exists.
		if(!is_dir($directoryName))
		{
			mkdir($directoryName, 0777,true);
		}
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("Myexcel")
					->setLastModifiedBy("Me")
					->setTitle("RAPPORT AGENT")
					->setSubject("RAPPORT AGENT")
					->setDescription("RAPPORT AGENT")
					->setKeywords("RAPPORT AGENT")
					->setCategory("RAPPORT AGENT");

		$ligne=1;

		// Set Orientation, size and scaling
		// Set Orientation, size and scaling
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
		$objPHPExcel->getActiveSheet()->getPageMargins()->SetLeft(0.64); //***pour marge gauche
		$objPHPExcel->getActiveSheet()->getPageMargins()->SetRight(0.64); //*** pour marge droite 

		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);

		$objPHPExcel->getActiveSheet()->setTitle("Rapport_agent_enqueteur");
		$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&11&B Page &P / &N');
		$objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&R&11&B Page &P / &N');
		$objPHPExcel->getActiveSheet()->setShowGridlines(false);

		$styleTitre = array
		(
		'alignment' => array
			(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				
			),
		'font' => array
			(
				//'name'  => 'Times New Roman',
				'bold'  => true,
				'size'  => 14
			),
		);

		$styleTitrenumprojet= array
		(
		'alignment' => array
			(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				
			),
		'font' => array
			(
				//'name'  => 'Times New Roman',
				'bold'  => true,
				'size'  => 12
			),
		);

		$stylesousTitre = array
		(
		'alignment' => array
			(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				
			),
		'font' => array
			(
				//'name'  => 'Times New Roman',
				'bold'  => true,
				'size'  => 16
			),
		);

		$styletabletitre= array
		(
			'borders' => array
			(
				'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
			),
		'alignment' => array
			(
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			),
		'font' => array
			(
				//'name'  => 'Times New Roman',
				'bold'  => true
			)
		);

		$styletable= array
		(
			'borders' => array
			(
				'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
			),
		'alignment' => array
			(
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			)
		);

		//$objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
		$objPHPExcel->getActiveSheet()->mergeCells("B".$ligne.":C".$ligne);
		
		

		$objDrawingpeche = new PHPExcel_Worksheet_Drawing();
		$objDrawingpeche->setName('PHPExcel logopeche');
		$objDrawingpeche->setDescription('PHPExcel logopeche');
		$objDrawingpeche->setPath(dirname(__FILE__)."/../../../../../../assets/excel/".$repertoire."logo_peche.png");		
		$objDrawingpeche->setHeight(60);
		$objDrawingpeche->setCoordinates("A".$ligne);
		$objDrawingpeche->setOffsetY(50);
		
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('PHPExcel logorepublic');
		$objDrawing->setDescription('PHPExcel logorepublic');
		$objDrawing->setPath(dirname(__FILE__)."/../../../../../../assets/excel/".$repertoire."republic_madagascar.png");
		
		/*$colWidth = $objPHPExcel->getActiveSheet()->getColumnDimension('B')->getWidth();
		 $colWidthPixels = $colWidth * 7.0017094;
		 $offsetX = $colWidthPixels - $objDrawing->getWidth();*/
		
		$objDrawing->setHeight(120);
		$objDrawing->setCoordinates("B".$ligne);
		$objDrawing->setOffsetX(120);
		$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());


		$heit=$objDrawing->getHeight();
		$objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight($heit);
		$objDrawingpeche->setWorksheet($objPHPExcel->getActiveSheet());

		$ligne++;
		$objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":D".$ligne);
		$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":D".$ligne)->applyFromArray($styleTitre);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "MINISTERE DES RESSOURCES HALIEUTIQUES ET DE LA PECHE");

		$ligne=$ligne+2;
		$objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":D".$ligne);
		$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":D".$ligne)->applyFromArray($styleTitre);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "*********************************");

		$ligne=$ligne+2;
		$objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":D".$ligne);
		$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":D".$ligne)->applyFromArray($styleTitrenumprojet);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Deuxième Projet de Gouvernance des Pêches et de Croissance Partagée dans le Sud-Ouest de l'Océan Indien(SWIOFish2)");
		$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":D".$ligne)->getAlignment()->setWrapText(true);

		$ligne=$ligne+2;
		$objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":D".$ligne);
		$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":D".$ligne)->applyFromArray($stylesousTitre);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "RAPPORT DE L'AGENT ENQUETEUR");

		$ligne=$ligne+2;
		//$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":D".$ligne)->applyFromArray($stylesousTitre);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Noms des agents:");

		$ligne++;
		$objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":B".$ligne);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $enqueteur["nomEnqueteur"]);

		$objPHPExcel->getActiveSheet()->mergeCells("C".$ligne.":D".$ligne);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, "Contrat n°: ".$enqueteur["num_contrat"]);
		$objPHPExcel->getActiveSheet()->getStyle("C".$ligne.":D".$ligne)->getAlignment()->setWrapText(true);

		$ligne=$ligne+2;
		$objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":B".$ligne);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Période du: ".$daty['date_debut']." au ".$daty['date_fin']);

		$ligne=$ligne+2;
		$objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":B".$ligne);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Région: ".$site["region"]);

		$ligne++;
		$objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":B".$ligne);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Site d'enquête: ".$site["sitenom"]);

		$ligne=$ligne+2;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Travaux effectués:");
		$ligne++;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Noms des villages");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, "Questionnaires remplis");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, "Questionnaires validés par le superviseur");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, "Observations");
		$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":D".$ligne)->applyFromArray($styletabletitre);
		$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":D".$ligne)->getAlignment()->setWrapText(true);
		$ligne++;		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $site["sitenom"]);
		$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":D".$ligne)->applyFromArray($styletable);
		$ligne++;
		foreach ($data as $k => $val) {
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, $val['unite_peche']." (".$val['nombre'].")");
		$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":D".$ligne)->applyFromArray($styletable);
		$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":D".$ligne)->getAlignment()->setWrapText(true);
		$ligne++;	
		}		
		
		$ligne++;
		$objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":B".$ligne);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Problèmes rencontrés et solutions adoptées: ");
		
		$ligne=$ligne+4;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Visa du superviseur");
		
		$ligne++;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Date:");
		try
		{
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		    $objWriter->save(dirname(__FILE__) . "/../../../../../../assets/excel/rapport_agent/".$nom_file.".xlsx");
		    
		    $this->response([
                'status' => TRUE,
                'response' => $nom_file.".xlsx",
                'message' => 'Get file success',
            ], REST_Controller::HTTP_OK);
		  
		} 
		catch (PHPExcel_Writer_Exception $e)
		{
		    $this->response([
	              'status' => FALSE,
	               'response' => array(),
	               'message' => "Something went wrong: ". $e->getMessage(),
	            ], REST_Controller::HTTP_OK);
		}
    }
    
}