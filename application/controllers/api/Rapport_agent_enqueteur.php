<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Rapport_agent_enqueteur extends REST_Controller
{
    public function __construct()
    { parent::__construct();
        $this->load->model('enqueteur_model', 'EnqueteurManager');
        $this->load->model('echantillon_model', 'EchantillonManager');
    }

    public function index_get()
    {	
    	$menu = $this->get('menu');
    	$id_enqueteur = $this->get('id_enqueteur');
    	
    	//$site_embarquement=array();
    	//$nomEnqueteur=array();
    	//$region=array();
    	//$nomSite=array();
    	$data=array();
    	$enqueteur=array();
    	$daty=array();
    	$site=array();
    	$num_contrat = $this->get('num_contrat');
    	$date_fin = $this->get('date_fin');
    	$date_debut = $this->get('date_debut');
    	$repertoire= $this->get('repertoire');
    	if($menu=='rapportagent')
    	{
    		if($id_enqueteur)
	        {
	            $site_embarquement = $this->EnqueteurManager->findSiteByEnqueteur($id_enqueteur);
	            $enquet = $this->EnqueteurManager->findById($id_enqueteur);
	           // $data["site"]= $site_embarquement;
	            $nom=strtoupper($enquet->nom);
	            $prenom=strtoupper($enquet->prenom);
	        	if($prenom)
	        	{
	        		$nom= $nom." ".$prenom;  
	        	}
	        	$nomEnqueteur=$nom;
	        	$enqueteur["nomEnqueteur"]=$nomEnqueteur;
	        	if($num_contrat!='undefined')
	        	{
	        		$enqueteur["num_contrat"]=$num_contrat;
	        	}else
	        	{
	        		$enqueteur["num_contrat"]='';
	        	}	        	
	        	
	        }

	        if($site_embarquement)
	        {	
	        	$all_unite_peche=$this->EnqueteurManager->findUniteBySite_embarquement($site_embarquement[0]->id_site);
	        	//$data=$all_unite_peche;
	        	$region=strtoupper($site_embarquement[0]->region);
	        	$nomSite=strtoupper($site_embarquement[0]->libelle);
	        	$site["region"]=$region;
	        	$site["sitenom"]=$nomSite;
	        }
	        foreach ($all_unite_peche as $key => $value)
		    { 
    			$nbrechant= $this->EchantillonManager->nbrechantilonpartiel($date_debut,$date_fin,$id_enqueteur,$value->id);
    			$data[$key]['unite_peche']=$value->libelle;
    			$data[$key]['nombre']=$nbrechant[0]->nombre;
    		}
	        
	        
	        
	       /* foreach (json_decode($echantillon_unite) as $key => $value) {
	        	$data[$key]=$value;
	        }*/

	        $daty['date_debut']=date("d-m-Y", strtotime($date_debut));;
	        $daty['date_fin'] = date("d-m-Y", strtotime($date_fin));
	       $exclel_agent=$this->excel_agent($enqueteur,$site,$daty,$repertoire,$data); 
    	}
    	if (count($data)>0)
    	{    		
            $this->response([
                'status' => TRUE,
                'response' => $data,
                'message' => 'Get file success',
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