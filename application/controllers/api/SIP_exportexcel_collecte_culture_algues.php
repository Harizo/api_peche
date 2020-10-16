<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class SIP_exportexcel_collecte_culture_algues extends REST_Controller {

    public function __construct() {
        parent::__construct();
         $this->load->model('SIP_saisie_collecte_culture_algues_model', 'SIP_saisie_collecte_culture_alguesManager'); 
    }
   
    public function index_get() 
    {
       // set_time_limit(0);
       // ini_set ('memory_limit', '4096M');
        $id_commune   = $this->get('id_commune');
        $id_fokontany = $this->get('id_fokontany');
        $annee        = $this->get('annee');
        $repertoire   = $this->get('repertoire');
        $mois         = $this->get('mois');
        $month         = $this->get('month');
        $region       = $this->get('region');
        $district     = $this->get('district');
        $commune      = $this->get('commune');
        $fokontany    = $this->get('fokontany');
        $data = array();
        $col_cult_alg = array() ;
        $col_cult_alg['region'] = strtoupper($region) ;
        $col_cult_alg['district'] = strtoupper($district) ;
        $col_cult_alg['commune'] = strtoupper($commune) ;
        $col_cult_alg['fokontany'] = $fokontany ;
        $col_cult_alg['annee'] = $annee ;
        $col_cult_alg['month'] = $month ;
        $ans = false ;
        $moi = false ;
        $fkt = false ;
        
        if ($id_commune)
        {   
            $requete= "AND alg.id_commune=".$id_commune."" ;
            if ($id_fokontany!='-' && $id_fokontany!='null' && $id_fokontany!='undefined') {
                
                $requete= $requete." AND alg.id_fokontany=".$id_fokontany."" ;
                $fkt = true ;
            }
            
            if ($mois!='null' && $mois!='-' && $annee!='null' && $annee!='' && $annee!='undefined'){
                
                $requete= $requete." AND alg.annee=".$annee." AND alg.mois=".$mois."" ; 
                $ans = true ;
                $moi = true ;
            } 
            else {
                if ($mois!='null' && $mois!='-'){
                    
                    $requete= $requete." AND alg.mois=".$mois."" ;
                    $moi = true ;
                } 
                if ($annee!='null' && $annee!='undefined' && $annee!='') {
                    $requete= $requete." AND alg.annee=".$annee."" ;
                    $ans = true ; 
                }

            }
            
            $data = $this->SIP_saisie_collecte_culture_alguesManager->findAndGetElements($requete);            
        }

        if (count($data)>0) {
           
            if ($repertoire)
                $this->genererexcel($data, $repertoire, $col_cult_alg, $fkt, $ans, $moi);
            else 
            {
                
                $this->response([
                    'status'        => TRUE,
                    'response'      => $data,
                    'message'       => 'Get data success',
                ], REST_Controller::HTTP_OK);
           
            } 
            
        } 
        else {
            $this->response([
                'status' => FALSE,
                'response' => array(),
                'message' => 'No data were found'
            ], REST_Controller::HTTP_OK);
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

    public function genererexcel($data,$repertoire, $col_cult_alg, $fkt, $ans, $moi)
    {   require_once 'Classes/PHPExcel.php';
        require_once 'Classes/PHPExcel/IOFactory.php';

        $nom_file='fiche_collecte_culture_algues';
        $directoryName = dirname(__FILE__) ."/../../../../export_excel/".$repertoire;
        //Check if the directory already exists.
        if(!is_dir($directoryName))
        {
            mkdir($directoryName, 0777,true);
        }
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Myexcel")
                    ->setLastModifiedBy("Me")
                    ->setTitle("fiche_collecte_culture_algues")
                    ->setSubject("fiche_collecte_culture_algues")
                    ->setDescription("fiche_collecte_culture_algues")
                    ->setKeywords("fiche_collecte_culture_algues")
                    ->setCategory("fiche_collecte_culture_algues");

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

        for ($alphabet=65; $alphabet <70 ; $alphabet++) 
            $objPHPExcel->getActiveSheet()->getColumnDimension(chr($alphabet))->setautoSize(true);
            

        $objPHPExcel->getActiveSheet()->setTitle("fiche_collecte_culture_algues");
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
        $styleMenu = array
        (
            'alignment' => array
            (
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                
            ),
            
            'font' => array
            (
                'name'  => 'Times New Roman',
                'bold'  => false,
                'size'  => 10,
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
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
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
         
        $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(40);
        $objPHPExcel->getActiveSheet()->mergeCells(chr(65).$ligne.":".chr(70).$ligne);
        $objPHPExcel->getActiveSheet()->getStyle(chr(65).$ligne.":".chr(70).$ligne)->applyFromArray($styleTitre);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr(65).$ligne, 'Collecte culture d\'algues');
        
        $ligne++;
            $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":E".$ligne);
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":E".$ligne)->applyFromArray($styleMenu);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "REGION: ".$col_cult_alg['region']);

        $ligne++;
            $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":E".$ligne);
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":E".$ligne)->applyFromArray($styleMenu);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "DISTRICT: ".$col_cult_alg['district']);
        
        $ligne++;
            $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":E".$ligne);
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":E".$ligne)->applyFromArray($styleMenu);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "COMMUNE: ".$col_cult_alg['commune']);
        
        if ($fkt) {
            $ligne++;
            $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":E".$ligne);
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":E".$ligne)->applyFromArray($styleMenu);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "FOKONTANY: ".$col_cult_alg['fokontany']);
        }
        if ($ans) {
            $ligne++;
            $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":E".$ligne);
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":E".$ligne)->applyFromArray($styleMenu);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "ANNEE: ".$col_cult_alg['annee']);
        }
        if ($moi) {
            $ligne++;
            $objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":E".$ligne);
            $objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":E".$ligne)->applyFromArray($styleMenu);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "MOIS: ".$col_cult_alg['month']);
        }

        $ligne++;
        $alphabet = 64 ;

        /* ENTETE */    
        if (!$fkt) {
            $alphabet++ ;
            $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet). $ligne)->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet). $ligne)->applyFromArray($styleSousTitre);
            $objPHPExcel->getActiveSheet()->mergeCells(chr($alphabet).$ligne.":".chr($alphabet).$ligne);
            $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet).$ligne)->applyFromArray($styleSousTitre);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, 'Fokontany');
        }
        if (!$ans) {
            $alphabet++ ;
            $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet). $ligne)->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet). $ligne)->applyFromArray($styleSousTitre);
            $objPHPExcel->getActiveSheet()->mergeCells(chr($alphabet).$ligne.":".chr($alphabet).$ligne);
            $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet).$ligne)->applyFromArray($styleSousTitre);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, 'Année');
            }
        if (!$moi) {
            $alphabet++ ;
            $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet). $ligne)->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet). $ligne)->applyFromArray($styleSousTitre);
            $objPHPExcel->getActiveSheet()->mergeCells(chr($alphabet).$ligne.":".chr($alphabet).$ligne);
            $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet).$ligne)->applyFromArray($styleSousTitre);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne,'Mois');
        }

        $alphabet++ ;
        $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet). $ligne)->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet). $ligne)->applyFromArray($styleSousTitre);
        $objPHPExcel->getActiveSheet()->mergeCells(chr($alphabet).$ligne.":".chr($alphabet).$ligne);
        $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet).$ligne)->applyFromArray($styleSousTitre);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, 'Village');

        $alphabet++ ;
        $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet). $ligne)->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet). $ligne)->applyFromArray($styleSousTitre);
        $objPHPExcel->getActiveSheet()->mergeCells(chr($alphabet).$ligne.":".chr($alphabet).$ligne);
        $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet).$ligne)->applyFromArray($styleSousTitre);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, 'Quantité');

        $alphabet++ ;
        $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet). $ligne)->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet). $ligne)->applyFromArray($styleSousTitre);
        $objPHPExcel->getActiveSheet()->mergeCells(chr($alphabet).$ligne.":".chr($alphabet).$ligne);
        $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet).$ligne)->applyFromArray($styleSousTitre);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, ' Montant ');
        
        $fin_col = $alphabet ;

        /* CORPS */
        
        foreach ($data as $key => $value) {
            $ligne ++;
            $alphabet = 65 ;
            $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($fin_col). $ligne)->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($fin_col). $ligne)->applyFromArray($stylecontenu);
            if (!$fkt){

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, $value->fokontany );
                $alphabet ++ ;
            } 
            if (!$ans) {

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, $this-> nombreFormat($value->annee) );
                $alphabet ++ ;
            }
            if (!$moi) {

                 $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, $value->mois );
                $alphabet ++ ;
            }

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, $value->village );
            $alphabet ++ ;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne,  $this-> nombreFormat($value->quantite));
            $alphabet ++ ;
            $objPHPExcel->getActiveSheet()->getColumnDimension(chr($alphabet))->setWidth(30);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne,  $this-> nombreFormat($value->montant));
        }
        // AJOUTER LE NOM DU FICHIER EN nom_file + entete par exemple
        
        try
        {
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save(dirname(__FILE__) . "/../../../../export_excel/fiche_collecte_culture_algues/".$nom_file.".xlsx");
            
            $this->response([
                'status' => TRUE,
                'nom_file' => $nom_file.".xlsx",
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
/* End of file controllername.php */
/* Location: ./application/controllers/controllername.php */
?>      