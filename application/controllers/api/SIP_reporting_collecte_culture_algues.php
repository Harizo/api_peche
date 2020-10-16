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
       // set_time_limit(0);
       // ini_set ('memory_limit', '4096M');
        $pivot = $this->get('menu');
        $menu_excel = $this->get('menu_excel');
        $repertoire = $this->get('repertoire');
        $data = array();
        
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
           
            if ($menu_excel=="excel_requetes")
                $this->genererexcel($pivot, $menu_excel, $data, $repertoire);
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

    public function genererexcel($pivot, $menu_excel,$data,$repertoire)
    {   require_once 'Classes/PHPExcel.php';
        require_once 'Classes/PHPExcel/IOFactory.php';

        $nom_file='fiche_requête_vente_poissonnerie_reporting';
        $directoryName = dirname(__FILE__) ."/../../../../export_excel/".$repertoire;
        //Check if the directory already exists.
        if(!is_dir($directoryName))
        {
            mkdir($directoryName, 0777,true);
        }
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Myexcel")
                    ->setLastModifiedBy("Me")
                    ->setTitle("reporting_collecte_culture_dalgues")
                    ->setSubject("reporting_collecte_culture_dalgues")
                    ->setDescription("reporting_collecte_culture_dalgues")
                    ->setKeywords("reporting_collecte_culture_dalgues")
                    ->setCategory("reporting_collecte_culture_dalgues");

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

        $objPHPExcel->getActiveSheet()->setTitle("Requête_poissonnerie_reporting");
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
         
            if ($pivot=='req8_1_quantite_par_mois_culture_dalgues') {

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(40);
                $objPHPExcel->getActiveSheet()->mergeCells(chr(66).$ligne.":".chr(75).$ligne);
                $objPHPExcel->getActiveSheet()->getStyle(chr(66).$ligne.":".chr(75).$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr(66).$ligne, 'Quantité par mois culture d\'algues');
            }
               
            if ($pivot=='req8_2_quantite_par_villagee_culture_dalgues') {

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(40);
                $objPHPExcel->getActiveSheet()->mergeCells(chr(66).$ligne.":".chr(75).$ligne);
                $objPHPExcel->getActiveSheet()->getStyle(chr(66).$ligne.":".chr(75).$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr(66).$ligne, 'Quantité par village culture d\'algues');
            }

            if ($pivot=='req8_3_quantite_par_commune_culture_dalgues') {

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(40);
                $objPHPExcel->getActiveSheet()->mergeCells(chr(66).$ligne.":".chr(75).$ligne);
                $objPHPExcel->getActiveSheet()->getStyle(chr(66).$ligne.":".chr(75).$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr(66).$ligne, 'Quantité par commune culture d\'algues');
             }

            if ($pivot=='req8_4_montant_par_mois_culture_dalgues') {

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(40);
                $objPHPExcel->getActiveSheet()->mergeCells(chr(66).$ligne.":".chr(75).$ligne);
                $objPHPExcel->getActiveSheet()->getStyle(chr(66).$ligne.":".chr(75).$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr(66).$ligne, 'Montant par mois culture d\'algues');
             }

            if ($pivot=='req8_5_montant_par_village_culture_dalgues') {

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(40);
                $objPHPExcel->getActiveSheet()->mergeCells(chr(66).$ligne.":".chr(75).$ligne);
                $objPHPExcel->getActiveSheet()->getStyle(chr(66).$ligne.":".chr(75).$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr(66).$ligne, 'Montant par village culture d\'algues');
            }

            $ligne++;
            $alphabet = 65 ;

            /* ENTETE */ 
            foreach ($data[0] as $key => $value) {
                if ($key=='Total_de_somme_de_montant'||$key=='Total_de_somme_de_quantite') 
                    $objPHPExcel->getActiveSheet()->getColumnDimension(chr($alphabet))->setWidth(30);
                else
                    $objPHPExcel->getActiveSheet()->getColumnDimension(chr($alphabet))->setautoSize(true);
                $key = str_replace('_', ' ', $key) ;
                $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet). $ligne)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet). $ligne)->applyFromArray($styleSousTitre);
                $objPHPExcel->getActiveSheet()->mergeCells(chr($alphabet).$ligne.":".chr($alphabet).$ligne);
                $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet).$ligne)->applyFromArray($styleSousTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, $key);
                $alphabet ++ ;
            }

            /* CORPS */
            for ($i=0; $i <count($data) ; $i++){
                $ligne ++;
                $alphabet = 65 ;
                foreach ($data[$i] as $key => $value) {
                    if ($value>0)
                        $value = $this-> nombreFormat($value);

                    $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet). $ligne)->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet). $ligne)->applyFromArray($stylecontenu);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, $value );
                    $alphabet ++ ;
                }
            }

        // AJOUTER LE NOM DU FICHIER EN nom_file + entete par exemple
        
        try
        {
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save(dirname(__FILE__) . "/../../../../export_excel/reporting_collecte_culture_dalgues/".$nom_file.".xlsx");
            
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