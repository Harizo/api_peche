<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class SIP_reporting_collecte_culture_algues extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_reporting_collecte_culture_algues_model', 'SIP_reporting_collecte_culture_alguesManager');  
    }
   
    public function index_get() 
    {
        set_time_limit(0);
        ini_set ('memory_limit', '4096M');
        $pivot = $this->get('menu');
        $choix_pivot = $this->get('choix_pivot');
        $menu_excel = $this->get('menu_excel');
        $repertoire = $this->get('repertoire');
        $data = array();
        $entete = array() ;
        
        //  requête : REQ_8_1
            if ($pivot=="req8_1_quantite_par_mois_culture_dalgues") 
                $data = $this->SIP_reporting_collecte_culture_alguesManager->quantite_par_mois_culture_dalgues(); 
        // fin requête : REQ_8_1

        //  requête : REQ_8_2
            if ($pivot=='req8_2_quantite_par_villagee_culture_dalgues') 
                $data = $this->SIP_reporting_collecte_culture_alguesManager->quantite_par_villagee_culture_dalgues();
        // fin requête : REQ_8_2
    

        //  requête : REQ_8_3
            if ($pivot=='req8_3_quantite_par_commune_culture_dalgues') 
                $data = $this->SIP_reporting_collecte_culture_alguesManager->quantite_par_commune_culture_dalgues();
        // fin requête : REQ_8_3

        //  requête : REQ_8_4
            // misy erreur  ato
            if ($pivot=='req8_4_montant_par_mois_culture_dalgues') 
                $data = $this->SIP_reporting_collecte_culture_alguesManager->montant_par_mois_culture_dalgues();
        // fin requête : REQ_8_4

        //  requête : REQ_8_5
            if ($pivot=='req8_5_montant_par_village_culture_dalgues') 
                $data = $this->SIP_reporting_collecte_culture_alguesManager->montant_par_village_culture_dalgues();
        // fin requête : REQ_8_5

        //********************************* FIN ****************************************
            

        if (count($data)>0) {
           
            $entete= array_keys((array)$data[0]) ;

            if ($menu_excel=="excel_requetes")
                $this->genererexcel($menu_excel, $data, $repertoire, $choix_pivot, $entete, $pivot);
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
        }
        if ($part=='') {
            $inverse = strrev($res); // renverse la chaine
            $part = chunk_split($inverse, 3, ' ') ; // ajout un espace à chaque 3 lettrede la chaine
            $part = strrev($part) ; // reenverse la chaine
        }
        return $res = $part.$val ;
    }

    public function genererexcel($menu_excel,$data,$repertoire, $choix_pivot, $entete, $pivot)
    {   
        require_once 'Classes/PHPExcel.php';
        require_once 'Classes/PHPExcel/IOFactory.php';
  
        $nom_file='fiche_reporting_collecte_culture_dalgues_'.$pivot;
        $directoryName = dirname(__FILE__) ."/../../../../export_excel/".$repertoire;

        //Check if the directory already exists.
        if(!is_dir($directoryName))
            mkdir($directoryName, 0777,true);

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("S.I.P Madagascar")
                    ->setLastModifiedBy("S.I.P Madagascar")
                    ->setTitle("reporting collect cultur d'algue")
                    ->setSubject("reporting collect cultur d'algue")
                    ->setDescription("reporting collect cultur d'algue")
                    ->setKeywords("reporting collect cultur d'algue")
                    ->setCategory("reporting collect cultur d'algue");

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

        $objPHPExcel->getActiveSheet()->setTitle("reporting collect cultur algue");
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
           'font' => array
            (
                'bold'  => true,
                'size'  => 11
            ),

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
  
        $alphabet = 65 ;
        $fin_colonne = $alphabet + count($entete)-1 ;
        
        $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(40);
        $objPHPExcel->getActiveSheet()->mergeCells(chr(65).$ligne.":".chr($fin_colonne).$ligne);
        $objPHPExcel->getActiveSheet()->getStyle(chr(65).$ligne.":".chr($fin_colonne).$ligne)->applyFromArray($styleTitre);
        $objPHPExcel->getActiveSheet()->getStyle(chr(65).$ligne.":".chr(65+$fin_colonne). $ligne)->getAlignment()->setWrapText(true);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr(65).$ligne, "Reporting collecte ".$choix_pivot);
     
        $ligne+=2;

        /* ENTETE */ 
        foreach ($entete as $key => $value) {
            if ($value=='Total de somme de montant'||$value=='Total de somme de quantité') 
                $objPHPExcel->getActiveSheet()->getColumnDimension(chr($alphabet))->setWidth(30);
            else
                $objPHPExcel->getActiveSheet()->getColumnDimension(chr($alphabet))->setautoSize(true);
            $value = str_replace('_', ' ', $value) ;
            $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet). $ligne)->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet). $ligne)->applyFromArray($styleSousTitre);
            $objPHPExcel->getActiveSheet()->mergeCells(chr($alphabet).$ligne.":".chr($alphabet).$ligne);
            $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet).$ligne)->applyFromArray($styleSousTitre);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, $value);
            $alphabet ++ ;
        }

        /* CORPS */
        for ($i=0; $i <count($data) ; $i++) {
            $alphabet = 65 ;
            $ligne ++;
            foreach ($entete as $key => $value) {
                $contenu = $data[$i]->$value ;
                if ($contenu>0)
                    $contenu = $this-> nombreFormat($contenu);

                $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet). $ligne)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet). $ligne)->applyFromArray($stylecontenu);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, $contenu);
                $alphabet ++ ;
            }
        } 
        
        try
        {
  
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save(dirname(__FILE__) . "/../../../../export_excel/reporting_collecte_culture_dalgues/".$nom_file.".xlsx");

            $this->response([
                'status' => TRUE,
                'nom_file' => $nom_file.".xlsx",
                'resp' => $data,
                'res' => $choix_pivot,
              //  'isa' => $resultat,
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