<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class vente_poissonnerie_reporting extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_poissonnerie_model', 'SIP_poissonnerieManager');
        $this->load->model('SIP_saisie_vente_poissonnerie_model', 'SIP_saisie_vente_poissonnerieManager');  
    }
   
    public function index_get() 
    {
       // set_time_limit(0);
       // ini_set ('memory_limit', '4096M');
        $pivot = $this->get('menu');
        $menu_excel = $this->get('menu_excel');
        $repertoire = $this->get('repertoire');
        $data = array();
        $eff_annee = 0 ;
        $eff_mois = 0 ;
        $eff_poiss = 0 ;
        $poiss_val = array() ;
        $mois_val = array() ;
        $annee_val = array() ;
        $donnee = array() ;
        
        //  requête : REQ_1
            if ($pivot=="req_1_vente_poissonneries") 
                $data = $this->SIP_saisie_vente_poissonnerieManager->qte_vendues_par_poissonneries(); 
        // fin requête : REQ_1

        //  requête : REQ_2
            if ($pivot=='req_2_vente_poissonneries') 
                $data = $this->SIP_saisie_vente_poissonnerieManager->qte_vendues_par_poissonneries_mois();
        // fin requête : REQ_2
    

        //  requête : REQ_3
            if ($pivot=='req_3_vente_poissonneries') 
                $data = $this->SIP_saisie_vente_poissonnerieManager->prix_moyen_prod_par_poissonnerie();
        // fin requête : REQ_3

        //  requête : REQ_4
            // misy erreur  ato
            if ($pivot=='req_4_vente_poissonneries') 
                $data = $this->SIP_saisie_vente_poissonnerieManager->qte_vendues_par_famille();
        // fin requête : REQ_4

        //  requête : REQ_5
            if ($pivot=='req_5_vente_poissonneries') 
                $data = $this->SIP_saisie_vente_poissonnerieManager->prix_moyenne_par_famille();
        // fin requête : REQ_5

        //  requête : REQ_6
            if ($pivot=='req_6_vente_poissonneries') 
                 $data = $this->SIP_saisie_vente_poissonnerieManager->chif_aff_par_produit_poissonneries();
        // fin requête : REQ_6

    
        //  requête : REQ_7
            if ($pivot=='req_7_vente_poissonneries') 
                $data = $this->SIP_saisie_vente_poissonnerieManager->qte_vendues_produit_par_poissonneries();
        // fin requête : REQ_7

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
                    ->setTitle("reporting_vente_poissonnerie")
                    ->setSubject("reporting_vente_poissonnerie")
                    ->setDescription("reporting_vente_poissonnerie")
                    ->setKeywords("reporting_vente_poissonnerie")
                    ->setCategory("reporting_vente_poissonnerie");

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

        for ($alphabet=65; $alphabet <87 ; $alphabet++) 
            $objPHPExcel->getActiveSheet()->getColumnDimension(chr($alphabet))->setautoSize(true);
            

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
         
            if ($pivot=='req_1_vente_poissonneries') {

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(40);
                $objPHPExcel->getActiveSheet()->mergeCells(chr(66).$ligne.":".chr(75).$ligne);
                $objPHPExcel->getActiveSheet()->getStyle(chr(66).$ligne.":".chr(75).$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr(66).$ligne, 'Quantité vendues par poissonneries');
            }
               
            if ($pivot=='req_2_vente_poissonneries') {

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(40);
                $objPHPExcel->getActiveSheet()->mergeCells(chr(66).$ligne.":".chr(75).$ligne);
                $objPHPExcel->getActiveSheet()->getStyle(chr(66).$ligne.":".chr(75).$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr(66).$ligne, 'Quantité vendues par poissonneries mois');
            }

            if ($pivot=='req_3_vente_poissonneries') {

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(40);
                $objPHPExcel->getActiveSheet()->mergeCells(chr(66).$ligne.":".chr(75).$ligne);
                $objPHPExcel->getActiveSheet()->getStyle(chr(66).$ligne.":".chr(75).$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr(66).$ligne, 'Prix moyenne produits par poissonneries');
             }

            if ($pivot=='req_4_vente_poissonneries') {

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(40);
                $objPHPExcel->getActiveSheet()->mergeCells(chr(66).$ligne.":".chr(75).$ligne);
                $objPHPExcel->getActiveSheet()->getStyle(chr(66).$ligne.":".chr(75).$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr(66).$ligne, 'Quantité vendues par familles');
             }

            if ($pivot=='req_5_vente_poissonneries') {

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(40);
                $objPHPExcel->getActiveSheet()->mergeCells(chr(66).$ligne.":".chr(75).$ligne);
                $objPHPExcel->getActiveSheet()->getStyle(chr(66).$ligne.":".chr(75).$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr(66).$ligne, 'Prix moyenne par famille');
            }

            if ($pivot=='req_6_vente_poissonneries') {

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(40);
                $objPHPExcel->getActiveSheet()->mergeCells(chr(66).$ligne.":".chr(75).$ligne);
                $objPHPExcel->getActiveSheet()->getStyle(chr(66).$ligne.":".chr(75).$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr(66).$ligne, 'Chiffre d\'affaire par produits poissonneries');
             }

             if ($pivot=='req_7_vente_poissonneries') {

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(40);
                $objPHPExcel->getActiveSheet()->mergeCells(chr(66).$ligne.":".chr(75).$ligne);
                $objPHPExcel->getActiveSheet()->getStyle(chr(66).$ligne.":".chr(75).$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr(66).$ligne, 'Quantité vendues par produit poissonneries');
            }

            $ligne++;
            $alphabet = 65 ;

            /* ENTETE */
            foreach ($data[0] as $key => $value) {
                $key = str_replace('_', ' ', $key) ;
                if ($key>0 && $key<=12)
                    $key= $this->affichage_mois($key) ;
               
               // $objPHPExcel->getActiveSheet()->getColumnDimension(chr($alphabet))->setautoWidth(true);
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
            $objWriter->save(dirname(__FILE__) . "/../../../../export_excel/reporting_vente_poissonnerie/".$nom_file.".xlsx");
            
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