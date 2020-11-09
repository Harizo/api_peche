<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_export_vente_poissonnerie extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('SIP_poissonnerie_model', 'SIP_poissonnerieManager');
        $this->load->model('SIP_saisie_vente_poissonnerie_model', 'SIP_saisie_vente_poissonnerieManager');
        $this->load->model('region_model', 'RegionManager'); 

       
    }

    public function index_get()
    {	 

    	$id 				= $this->get('id');
    	$mois 				= $this->get('mois');
    	$annee 				= $this->get('annee');
    	$id_region 			= $this->get('id_region');
    	$nom_region 		= $this->get('nom_region');
    	$commune 			= $this->get('commune');
    	$district 			= $this->get('district');
    	$id_commune 		= $this->get('id_commune');
    	$id_district 		= $this->get('id_district');
    	$repertoire1 		= $this->get('repertoire1');
    	$repertoire2 		= $this->get('repertoire2');
    	$id_poissonnerie 	= $this->get('id_poissonnerie');
    	$nom_poissonnerie 	= $this->get('nom_poissonnerie');
    	$data = array();
        $nom_region =strtoupper($nom_region);
        $vente_poissonnerie=array();
        $colonne_vente_poissonnerie = array( );

		$test_an = false ;
		$test_mois = false ;
		$com = false ;
		$dist = false ;

        if ($repertoire1) 
        {
			$poissonnerie=array();
	        $poissonnerie['titre']='FICHE POISSONNERIE';
	        $poissonnerie['repertoire1']= $repertoire1 ;
	        $poissonnerie['nom_region']= $nom_region ;
	       
	        
	        if ($id_region)
	        { 
	            if ( ( ($id_commune!='-')&&($id_commune!='undefined')&&($id_commune!='null')&&($id_commune!='') ) && ( ($id_district!='-')&&($id_district!='undefined')&&($id_district!='null')&&($id_district!='') ) ) {
	                $data = $this->SIP_poissonnerieManager->findByRegionDistrictCommune($id_region,$id_district,$id_commune);
	                $poissonnerie['commune']= $commune;
	                $com = true ;
	                $poissonnerie['district']= $district ;
					$dist = true ;
	            } 
	            else
	            {
	                if ( ($id_district!='-')&&($id_district!='undefined')&&($id_district!='null')&&($id_district!='')&&( ($id_commune=='-')||($id_commune=='undefined')||($id_commune=='null')||($id_commune=='') ) ) {
	                $data = $this->SIP_poissonnerieManager->findByRegionDistrict($id_region,$id_district);
	                $poissonnerie['district']= $district ;
					$dist = true ;
	                }

	                if ( ($id_commune!='-')&&($id_commune!='undefined')&&($id_commune!='null')&&($id_commune!='')&&( ($id_district=='-')||($id_district=='')||($id_district=='undefined')||($id_district!='null') ) ) {
	                    $data = $this->SIP_poissonnerieManager->findByRegionCommune($id_region,$id_commune);
	                    $poissonnerie['commune']= $commune;
	                    $com = true ;
	                }

	                if ( (($id_commune=='-')||($id_commune=='undefined')||($id_commune=='')||($id_commune=='null')) &&( ($id_district=='-')||($id_district=='')||($id_district=='undefined')||($id_district=='null') ) ) {
	                    $data = $this->SIP_poissonnerieManager->findCleRegion($id_region);
	                   
	                 } 
	                    
	            }
	            
	        }
		            
	        else
	        { 
	            $data=$this->SIP_poissonnerieManager->findAll();
	        }

    		$this->genererexcelpoissonnerie($poissonnerie, $data,$id_district, $id_commune, $dist, $com);	
        	
        }

        if($repertoire2) 
        {	
        
			$nom_poissonnerie = strtoupper($nom_poissonnerie);

        	
	        $vente_poissonnerie['repertoire2']= $repertoire2 ;
	        $vente_poissonnerie['titre']='FICHE VENTE POISSONNERIE';
	        $vente_poissonnerie['nom_poissonnerie']=$nom_poissonnerie;
	        $vente_poissonnerie['nom_region']=$nom_region;
	        $vente_poissonnerie['district']=$district;
	        $vente_poissonnerie['commune']=$commune;

	        if ($id_poissonnerie)
	        {

	            if ( ((!$mois)&&(!$annee)) || (( ($mois=='')||($mois=='-')||($mois=='null')||($mois=='undefined') ) && (($annee=='')||($annee=='-')||($annee=='null')||($annee=='undefined') ) )) {
	                $data = $this->SIP_saisie_vente_poissonnerieManager->findClePoissonnerie($id_poissonnerie);

	            } 
	            else {
	                if (($annee== 'undefined')||($annee=='')||($annee=='-')||($annee=='null')) { 
	                        $data = $this->SIP_saisie_vente_poissonnerieManager->findPoissonnerieByMois($id_poissonnerie,$mois);
	                        $vente_poissonnerie['mois']=$mois;

							$test_mois = true ;
	                }
	                else
	                {
	                    if  (($mois=='undefined')||($mois=='-')||($mois=='null')||($mois=='')) { 
	                        $data = $this->SIP_saisie_vente_poissonnerieManager->findPoissonnerieByAnne($id_poissonnerie,$annee);  
	        				$vente_poissonnerie['annee']=$annee;

							$test_an = true ;
	                    }

	                    else {
	                    $data = $this->SIP_saisie_vente_poissonnerieManager->findPoissonnerieByAnneeMois($id_poissonnerie,$annee,$mois);
	                    $vente_poissonnerie['mois']=$mois;
	        			$vente_poissonnerie['annee']=$annee;

						$test_an = true ;
						$test_mois = true ;
	                    }  
	                }
	                
	            }
        	}
        	$this->genererexcelventepoissonnerie($vente_poissonnerie, $data, $id_poissonnerie,$nom_poissonnerie,$annee,$mois,$test_an, $test_mois);


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

    public function nombreFormat($res)
    {
        $data = str_ireplace('.', ',', $res) ; // remplace point en virgule
        $part = '' ;
        $val = '' ;
        for ($i=0; $i < strlen($data); $i++) { 
          if ($data[$i]==',') { // test si on a un virgule
            $part = substr($data, 0, $i); // découpe la chaine avant le virgule
            $val = substr($data, $i-strlen($data)); // découpe la chaine just au virgule
            $inverse = strrev($part); // renverse la chaine
            $part = chunk_split($inverse, 3, ' ') ; // ajout un espace à chaque 3 lettrede la chaine
            $part = strrev($part) ; // reenverse la chaine
          }
          if($part==''){
            $inverse = strrev($res); // renverse la chaine
            $part = chunk_split($inverse, 3, ' ') ; // ajout un espace à chaque 3 lettrede la chaine
            $part = strrev($part) ; // reenverse la chaine

          }
        }
        return $res = $part.$val ;
    }

    public function genererexcelpoissonnerie($poissonnerie, $data,$id_district, $id_commune, $dist, $com)
    {	require_once 'Classes/PHPExcel.php';
		require_once 'Classes/PHPExcel/IOFactory.php';

		$nom_file='fiche_poissonnerie';
    	$directoryName = dirname(__FILE__) ."/../../../../export_excel/".$poissonnerie['repertoire1'];
		//Check if the directory already exists.
		if(!is_dir($directoryName))
		{
			mkdir($directoryName, 0777,true);
		}
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("Myexcel")
					->setLastModifiedBy("Me")
					->setTitle("FICHE POISSONNERIE")
					->setSubject("FICHE POISSONNERIE")
					->setDescription("FICHE POISSONNERIE")
					->setKeywords("FICHE POISSONNERIE")
					->setCategory("FICHE POISSONNERIE");

		$ligne=1;            
		// Set Orientation, size and scaling
		// Set Orientation, size and scaling
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
		$objPHPExcel->getActiveSheet()->getPageMargins()->SetLeft(0.64); //***pour marge gauche
		$objPHPExcel->getActiveSheet()->getPageMargins()->SetRight(0.64); //*** pour marge droite

		for ($alphabet=65; $alphabet <74 ; $alphabet++) 
			$objPHPExcel->getActiveSheet()->getColumnDimension(chr($alphabet))->setWidth(20);
			

		$objPHPExcel->getActiveSheet()->setTitle("Fiche_poissonnerie");
		$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&11&B Page &P / &N');
		$objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&R&11&B Page &P / &N');

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
		$styleMenu = array
		(
			'alignment' => array
			(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				
			),
			
			'font' => array
			(
				'name'  => 'Times New Roman',
				'bold'  => false,
				'size'  => 11,
				'tex-decoration'=> 'underline'
			),
			'style' => array
			(
				'text_decoration' => 'underline'

			)

			
		);
		$styleSousTitre = array
		(
			'alignment' => array
			(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			),

			'borders' => array
			(
				'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
			)
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
		                        
		$objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
		$objPHPExcel->getActiveSheet()->mergeCells(chr(65).$ligne.":".chr(74).$ligne);
		$objPHPExcel->getActiveSheet()->getStyle(chr(65).$ligne.":".chr(74).$ligne)->applyFromArray($styleTitre);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr(65).$ligne, $poissonnerie['titre']);
		
		$ligne++;
			$objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":B".$ligne);
			$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleMenu);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "REGION: ".$poissonnerie['nom_region']);

		if ($dist) {
			$ligne++;
			$objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":B".$ligne);
			$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleMenu);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "DISTRICT: ".$poissonnerie['district']);
		}
		
		if ($com) {
			$ligne++;
			$objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":B".$ligne);
			$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleMenu);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "COMMUNE: ".$poissonnerie['commune']);
		}
			
		
		$ligne++;
		$alphabet = 65 ;

		$objPHPExcel->getActiveSheet()->getStyle(chr(65).$ligne.":G". $ligne)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getStyle(chr(65).$ligne.":G". $ligne)->applyFromArray($styleSousTitre);

		if (!$dist) {

			$objPHPExcel->getActiveSheet()->mergeCells(chr($alphabet).$ligne.":".chr($alphabet).$ligne);
			$objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet).$ligne)->applyFromArray($styleSousTitre);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, "DISTRICT");
			$alphabet ++ ;
		}
		
		if (!$com) {
			
			$objPHPExcel->getActiveSheet()->mergeCells(chr($alphabet).$ligne.":".chr($alphabet).$ligne);
			$objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet).$ligne)->applyFromArray($styleSousTitre);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, "COMMUNE");
			$alphabet ++ ;
			
		} 


		$objPHPExcel->getActiveSheet()->mergeCells(chr($alphabet).$ligne.":".chr($alphabet).$ligne);
		$objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet).$ligne)->applyFromArray($styleSousTitre);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, "NOM POISSONNERIE");
		$alphabet ++ ;

		$objPHPExcel->getActiveSheet()->mergeCells(chr($alphabet).$ligne.":".chr($alphabet).$ligne);
		$objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet).$ligne)->applyFromArray($styleSousTitre);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, "LOCALISATION");
		$alphabet ++ ;

		$objPHPExcel->getActiveSheet()->mergeCells(chr($alphabet).$ligne.":".chr($alphabet).$ligne);
		$objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet).$ligne)->applyFromArray($styleSousTitre);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, "ADRESSE");
		$alphabet ++ ;

		$objPHPExcel->getActiveSheet()->mergeCells(chr($alphabet).$ligne.":".chr($alphabet).$ligne);
		$objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet).$ligne)->applyFromArray($styleSousTitre);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, "RCS");
		$alphabet ++ ;

		$objPHPExcel->getActiveSheet()->mergeCells(chr($alphabet).$ligne.":".chr($alphabet).$ligne);
		$objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet).$ligne)->applyFromArray($styleSousTitre);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, "STAT");
		$alphabet ++ ;

		$objPHPExcel->getActiveSheet()->mergeCells(chr($alphabet).$ligne.":".chr($alphabet).$ligne);
		$objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet).$ligne)->applyFromArray($styleSousTitre);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, "NIF");
		$alphabet ++ ;

		$objPHPExcel->getActiveSheet()->mergeCells(chr($alphabet).$ligne.":".chr($alphabet).$ligne);
		$objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet).$ligne)->applyFromArray($styleSousTitre);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, "TELEPHONE");	

		$fin_col = $alphabet ;
		foreach ($data as $key => $value) {
			$ligne ++;
			$alphabet = 65 ;
			
			$objPHPExcel->getActiveSheet()->getStyle(chr(65).$ligne.":".chr($fin_col). $ligne)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle(chr(65).$ligne.":".chr($fin_col). $ligne)->applyFromArray($stylecontenu);
			if  (!$dist) {
				
				$objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet). $ligne)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet). $ligne)->applyFromArray($stylecontenu);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, $value->districts);
				$alphabet ++ ;
			}
			if (!$com) {
			
				$objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet). $ligne)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet). $ligne)->applyFromArray($stylecontenu);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, $value->communes);
				$alphabet ++ ;
			}
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, $value->nom);
			$alphabet ++ ;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, $value->localisation);
			$alphabet ++ ;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, $value->adresse);
			$alphabet ++ ;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, $value->rcs);
			$alphabet ++ ;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, $value->stat);
			$alphabet ++ ;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, $value->nif);
			$alphabet ++ ;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, $value->tel);
		}
		// ampiana we nom file + nom region
		
		try
		{
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		    $objWriter->save(dirname(__FILE__) . "/../../../../export_excel/fiche_poissonnerie/".$nom_file.".xlsx");
		    
		    $this->response([
                'status' => TRUE,
                'response' => $nom_file.".xlsx",
                'resp' => $data,
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

    public function genererexcelventepoissonnerie($vente_poissonnerie, $data, $id_poissonnerie,$nom_poissonnerie,$annee,$mois,$test_an, $test_mois)
    {	require_once 'Classes/PHPExcel.php';
		require_once 'Classes/PHPExcel/IOFactory.php';

		$nom_file='fiche_vente_poissonnerie';



    	$directoryName = dirname(__FILE__) ."/../../../../export_excel/".$vente_poissonnerie['repertoire2'];
		//Check if the directory already exists.
		if(!is_dir($directoryName))
		{
			mkdir($directoryName, 0777,true);
		}
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("Myexcel")
					->setLastModifiedBy("Me")
					->setTitle("FICHE VENTE POISSONNERIE")
					->setSubject("FICHE VENTE POISSONNERIE")
					->setDescription("FICHE VENTE POISSONNERIE")
					->setKeywords("FICHE VENTE POISSONNERIE")
					->setCategory("FICHE VENTE POISSONNERIE");

		// Set Orientation, size and scaling
		// Set Orientation, size and scaling
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
		$objPHPExcel->getActiveSheet()->getPageMargins()->SetLeft(0.64); //***pour marge gauche
		$objPHPExcel->getActiveSheet()->getPageMargins()->SetRight(0.64); //*** pour marge droite

		for ($alphabet=65; $alphabet <78 ; $alphabet++) 
			$objPHPExcel->getActiveSheet()->getColumnDimension(chr($alphabet))->setWidth(20);
			

		$objPHPExcel->getActiveSheet()->setTitle("Fiche_vente_poissonnerie");
		$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&11&B Page &P / &N');
		$objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&R&11&B Page &P / &N');

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
			)
		);
		$styleSousTitre = array
		(
			'alignment' => array
			(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			),

			'borders' => array
			(
				'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
			)
		);
		$styleMenu = array
		(
			'alignment' => array
			(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
			),
			'font' => array
			(
				'name'  => 'Times New Roman',
				'bold'  => false,
				'size'  => 11
			),
			'text_decoration' => array 
			(
				'underline' => true
			)
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



		$ligne=1;            
		$objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
		$objPHPExcel->getActiveSheet()->mergeCells(chr(65).$ligne.":".chr(73).$ligne);
		$objPHPExcel->getActiveSheet()->getStyle(chr(65).$ligne.":".chr(73).$ligne)->applyFromArray($styleTitre);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr(65).$ligne, $vente_poissonnerie['titre']);

		$ligne++;
			$objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":B".$ligne);
			$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleMenu);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Poissonnerie: ".$vente_poissonnerie['nom_poissonnerie']);

		$ligne++;
			$objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":B".$ligne);
			$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleMenu);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Région: ".$vente_poissonnerie['nom_region']);

		$ligne++;
			$objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":B".$ligne);
			$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleMenu);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "District: ".$vente_poissonnerie['district']);

		$ligne++;
			$objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":B".$ligne);
			$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleMenu);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Commune: ".$vente_poissonnerie['commune']);

			if (($mois!='undefined')&&($mois!='')&&($mois!='-')&&($mois!='null')) {
				$ligne++;
				$month = $this->affichage_mois($vente_poissonnerie['mois']);

				$objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":B".$ligne);
				$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleMenu);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Mois de: ".$month);
			}

			if (($annee!='undefined')&&($annee!='')&&($annee!='-')&&($annee!='null')) {
				$ligne++;
				
				$objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":B".$ligne);
				$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":B".$ligne)->applyFromArray($styleMenu);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Année: ".$vente_poissonnerie['annee']);
			}
		

			

		$ABC = 65 ; //ASCII code
		$ligne++;

			if ( !$test_mois && !$test_an ) {

				$objPHPExcel->getActiveSheet()->getStyle(chr($ABC).$ligne.":".chr($ABC+1). $ligne)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle(chr($ABC).$ligne.":".chr($ABC+1). $ligne)->applyFromArray($styleSousTitre);

				$objPHPExcel->getActiveSheet()->mergeCells(chr($ABC).$ligne.":".chr($ABC).$ligne);
				$objPHPExcel->getActiveSheet()->getStyle(chr($ABC).$ligne.":".chr($ABC).$ligne)->applyFromArray($styleSousTitre);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($ABC).$ligne, "ANNEE"); 
				$ABC++ ;

				$objPHPExcel->getActiveSheet()->mergeCells(chr($ABC).$ligne.":".chr($ABC).$ligne);
				$objPHPExcel->getActiveSheet()->getStyle(chr($ABC).$ligne.":".chr($ABC).$ligne)->applyFromArray($styleSousTitre);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($ABC).$ligne, "MOIS");
				$ABC++ ;

			} 
			else {
				if (!$test_an) {

					$objPHPExcel->getActiveSheet()->getStyle(chr($ABC).$ligne.":".chr($ABC). $ligne)->getAlignment()->setWrapText(true);
					$objPHPExcel->getActiveSheet()->getStyle(chr($ABC).$ligne.":".chr($ABC). $ligne)->applyFromArray($styleSousTitre);

					$objPHPExcel->getActiveSheet()->mergeCells(chr($ABC).$ligne.":".chr($ABC).$ligne);
					$objPHPExcel->getActiveSheet()->getStyle(chr($ABC).$ligne.":".chr($ABC).$ligne)->applyFromArray($styleSousTitre);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($ABC).$ligne, "ANNEE");
					$ABC++ ;
				}
				else {
					if (!$test_mois) {

						$objPHPExcel->getActiveSheet()->getStyle(chr($ABC).$ligne.":".chr($ABC). $ligne)->getAlignment()->setWrapText(true);
						$objPHPExcel->getActiveSheet()->getStyle(chr($ABC).$ligne.":".chr($ABC). $ligne)->applyFromArray($styleSousTitre);

						$objPHPExcel->getActiveSheet()->mergeCells(chr($ABC).$ligne.":".chr($ABC).$ligne);
						$objPHPExcel->getActiveSheet()->getStyle(chr($ABC).$ligne.":".chr($ABC).$ligne)->applyFromArray($styleSousTitre);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($ABC).$ligne, "MOIS");
						$ABC++ ;
					}
				
				}
			}

			$fin_col = $ABC + 10 ;

			$objPHPExcel->getActiveSheet()->getStyle(chr($ABC).$ligne.":".chr($fin_col). $ligne)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle(chr($ABC).$ligne.":".chr($fin_col). $ligne)->applyFromArray($styleSousTitre);

			$objPHPExcel->getActiveSheet()->mergeCells(chr($ABC).$ligne.":".chr($ABC).$ligne);
			$objPHPExcel->getActiveSheet()->getStyle(chr($ABC).$ligne.":".chr($ABC).$ligne)->applyFromArray($styleSousTitre);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($ABC).$ligne, "REFERENCE FOURNISSEUR"); $ABC++ ;



			$objPHPExcel->getActiveSheet()->mergeCells(chr($ABC).$ligne.":".chr($ABC).$ligne);
			$objPHPExcel->getActiveSheet()->getStyle(chr($ABC).$ligne.":".chr($ABC).$ligne)->applyFromArray($styleSousTitre);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($ABC).$ligne, "ORIGINE PRODUIT"); $ABC++ ; 

			$objPHPExcel->getActiveSheet()->mergeCells(chr($ABC).$ligne.":".chr($ABC).$ligne);
			$objPHPExcel->getActiveSheet()->getStyle(chr($ABC).$ligne.":".chr($ABC).$ligne)->applyFromArray($styleSousTitre);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($ABC).$ligne, "FAMILLE"); $ABC++ ; 


			$objPHPExcel->getActiveSheet()->mergeCells(chr($ABC).$ligne.":".chr($ABC).$ligne);
			$objPHPExcel->getActiveSheet()->getStyle(chr($ABC).$ligne.":".chr($ABC).$ligne)->applyFromArray($styleSousTitre);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($ABC).$ligne, "TYPE FAMILLE"); $ABC++ ; 

			$objPHPExcel->getActiveSheet()->mergeCells(chr($ABC).$ligne.":".chr($ABC).$ligne);
			$objPHPExcel->getActiveSheet()->getStyle(chr($ABC).$ligne.":".chr($ABC).$ligne)->applyFromArray($styleSousTitre);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($ABC).$ligne, "DESIGNATION ARTICLE"); $ABC++ ; 

			$objPHPExcel->getActiveSheet()->mergeCells(chr($ABC).$ligne.":".chr($ABC).$ligne);
			$objPHPExcel->getActiveSheet()->getStyle(chr($ABC).$ligne.":".chr($ABC).$ligne)->applyFromArray($styleSousTitre);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($ABC).$ligne, "PRESENTATION"); $ABC++ ; 


			$objPHPExcel->getActiveSheet()->mergeCells(chr($ABC).$ligne.":".chr($ABC).$ligne);
			$objPHPExcel->getActiveSheet()->getStyle(chr($ABC).$ligne.":".chr($ABC).$ligne)->applyFromArray($styleSousTitre);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($ABC).$ligne, "CONSERVATION");$ABC++ ;

			$objPHPExcel->getActiveSheet()->mergeCells(chr($ABC).$ligne.":".chr($ABC).$ligne);
			$objPHPExcel->getActiveSheet()->getStyle(chr($ABC).$ligne.":".chr($ABC).$ligne)->applyFromArray($styleSousTitre);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($ABC).$ligne, "QUANTITE VENDUE"); $ABC++ ; 

			$objPHPExcel->getActiveSheet()->mergeCells(chr($ABC).$ligne.":".chr($ABC).$ligne);
			$objPHPExcel->getActiveSheet()->getStyle(chr($ABC).$ligne.":".chr($ABC).$ligne)->applyFromArray($styleSousTitre);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($ABC).$ligne, "PRIX"); $ABC++ ; 

			$objPHPExcel->getActiveSheet()->mergeCells(chr($ABC).$ligne.":".chr($ABC).$ligne);
			$objPHPExcel->getActiveSheet()->getStyle(chr($ABC).$ligne.":".chr($ABC).$ligne)->applyFromArray($styleSousTitre);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($ABC).$ligne, "CHIFFRE D'AFFAIRE"); $ABC++ ; 

			$objPHPExcel->getActiveSheet()->mergeCells(chr($ABC).$ligne.":".chr($ABC).$ligne);
			$objPHPExcel->getActiveSheet()->getStyle(chr($ABC).$ligne.":".chr($ABC).$ligne)->applyFromArray($styleSousTitre);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($ABC).$ligne, "OBSERVATION");

		foreach ($data as $key => $value) {
			$ligne++;
			$ABC = 65 ;
			$objPHPExcel->getActiveSheet()->getStyle(chr($ABC).$ligne.":".chr($fin_col). $ligne)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle(chr($ABC).$ligne.":".chr($fin_col). $ligne)->applyFromArray($stylecontenu);
		 	if  (!$test_an ) 
		 	{
		 		$objPHPExcel->getActiveSheet()->getStyle(chr($ABC).$ligne.":".chr($ABC). $ligne)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle(chr($ABC).$ligne.":".chr($ABC). $ligne)->applyFromArray($stylecontenu);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($ABC).$ligne,$value->annee); $ABC++ ; 
		 	}
		 	if(!$test_mois) 
		 	{  
		 		$month = $this->affichage_mois($value->mois);
		 		
		 		$objPHPExcel->getActiveSheet()->getStyle(chr($ABC).$ligne.":".chr($ABC). $ligne)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle(chr($ABC).$ligne.":".chr($ABC). $ligne)->applyFromArray($stylecontenu);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($ABC).$ligne,$month); $ABC++ ; 
		 	}
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($ABC).$ligne,$value->reference_fournisseur); $ABC++ ; 
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($ABC).$ligne,$value->origine_produits); $ABC++ ; 
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($ABC).$ligne,$value->libelle_famille); $ABC++ ; 
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($ABC).$ligne,$value->type_famille); $ABC++ ;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($ABC).$ligne,$value->designation_article); $ABC++ ;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($ABC).$ligne,$value->libelle_presentation); $ABC++ ; 
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($ABC).$ligne,$value->libelle_conservation);$ABC++ ;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($ABC).$ligne,$this->nombreFormat($value->quantite_vendu)." Kg"); $ABC++ ; 
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($ABC).$ligne,$this->nombreFormat($value->prix_kg)." Ar/KG"); $ABC++ ; 
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($ABC).$ligne,$this->nombreFormat($value->chiffre_affaire)." Ar"); $ABC++ ; 
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($ABC).$ligne,$value->observations);
			
		}

		try
		{
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		    $objWriter->save(dirname(__FILE__) . "/../../../../export_excel/fiche_vente_poissonnerie/".$nom_file.".xlsx");
		    
		    $this->response([
                'status' => TRUE,
                'response' => $nom_file.".xlsx",
                'resp' => $data,
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