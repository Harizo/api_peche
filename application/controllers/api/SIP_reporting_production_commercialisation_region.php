<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class SIP_reporting_production_commercialisation_region extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_reporting_production_commercialisation_region_model', 'SIP_reporting_production_commercialisation_regionManager');  
    }
   
    public function index_get() 
    {
        //set_time_limit(0);
        //ini_set ('memory_limit', '4096M');
        $pivot = $this->get('menu');
        $module = $this->get('module');
        $etat_exportExcel = $this->get('etat_exportExcel');
        $repertoire = $this->get('repertoire');
        $titre_etat = $this->get('titre_etat');
        $titre_module = $this->get('titre_module');
        $data = array();
        $total = [] ;

        $report_prod_commecer_region = [];
        $report_prod_commecer_region['titre'] = '' ;
        $entete=array();

        switch ($module) {

            case 'production':
                switch ($pivot) {
                    case 'qte_production_par_region':
                        $data = $this->SIP_reporting_production_commercialisation_regionManager->prod_commerc_region_product_par_region();
                        break;
                    
                    case 'qte_production_par_region_mois':
                        $data = $this->SIP_reporting_production_commercialisation_regionManager->prod_commerc_region_product_par_region_mois();
                        break;
                    
                    case 'production_region_nombre':
                        $data = $this->SIP_reporting_production_commercialisation_regionManager->prod_commerc_region_product_par_region_nbr();
                        break;
                    
                    case 'production_par_region_mois_nombre':
                        $data = $this->SIP_reporting_production_commercialisation_regionManager->prod_commerc_region_product_par_region_mois_nbr(); 
                        break;
                    
                    default:
                        
                        break;
                }
                break;

            case 'commercialisation':
                switch ($pivot) {
                    case 'quantite_commercialise':
                        $data = $this->SIP_reporting_production_commercialisation_regionManager->prod_commerc_region_commercialisation(); 
                        break;
                    
                    case 'quantite_commercialise_par_mois':
                        $data = $this->SIP_reporting_production_commercialisation_regionManager->prod_commerc_region_commercialisation_mois(); 
                        break;
                    
                    case 'quantite_commercialise_region_mois':
                        $data = $this->SIP_reporting_production_commercialisation_regionManager->prod_commerc_region_commercialisation_par_region(); 
                        break;
                    
                    default:
                        
                        break;
                }
                break;
            case 'product_national':
                $data = $this->SIP_reporting_production_commercialisation_regionManager->prod_commerc_region_quantite_production_nationale();

                
                break;
            
            default:
                
                break;
        }


        //********************************* FIN ****************************************
            

        if (count($data)>0) {

            $entete= array_keys((array)$data[0]); 

            if ($pivot='quantite_production_nationale') {
                foreach ($entete as $key => $value){
                    if ($value=='Activité/ Domaine/ Espèces')
                        $total[$value] = 'Production Total' ;    
                    else 
                        $total[$value] = array_sum(array_column($data, $value))." Kg" ;   
                }
            }
           
            if ($etat_exportExcel==1)
                $this->genererexcel($pivot, $etat_exportExcel, $data, $repertoire,$module, $report_prod_commecer_region, $entete, $total, $titre_etat, $titre_module);
            else 
            {
                
                $this->response([
                    'status'        => TRUE,
                    'response'      => $data,
                    'total'         => $total,
                    'message'       => 'Get data success',
                ], REST_Controller::HTTP_OK);
           
            } 
            
        } 
        else {
            $this->response([
                'status'    => FALSE,
                'response'  => array(),
                'message'   => 'No data were found'
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

    public function genererexcel($pivot, $etat_exportExcel,$data,$repertoire,$module,$report_prod_commecer_region, $entete, $total, $titre_etat, $titre_module)
    {   require_once 'Classes/PHPExcel.php';
        require_once 'Classes/PHPExcel/IOFactory.php';

        $nom_file="Fiche ".$titre_etat;
        $sous_repertoire = $titre_module ;
        $directoryName = dirname(__FILE__) ."/../../../../export_excel/".$repertoire.$sous_repertoire.'/';

        //Check if the directory already exists.
        if(!is_dir($directoryName))
            mkdir($directoryName, 0777,true);

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("S.I.P Madagascar")
                    ->setLastModifiedBy("S.I.P Madagascar")
                    ->setTitle("report product commerce région")
                    ->setSubject("report product commerce région")
                    ->setDescription("report product commerce région")
                    ->setKeywords("report product commerce région")
                    ->setCategory("report product commerce région");

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

        $objPHPExcel->getActiveSheet()->setTitle("report product commerce région");
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
                'size'  => 11,
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
         
         // DEBUT TITRE
            $ligne=1;
            $alphabet = 65 ;

                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(40);
                $objPHPExcel->getActiveSheet()->mergeCells(chr($alphabet).$ligne.":".chr($alphabet+count($entete)-1).$ligne);
                $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet+count($entete)-1).$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet+count($entete)-1). $ligne)->getAlignment()->setWrapText(true);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, "Reporting ".$titre_module.": ".$titre_etat);
             
        // FIN TITRE

        $ligne += 2;
            

            /* ENTETE */ 

            foreach ($entete as $key => $value) {
                if ($value=="Total de somme de quantité" ||$value=="Total de somme de quantité commercialisée"||$value=="Total de somme de quantité en nombre"||$value=="Activité/ Domaine/ Espèces") 
                    $objPHPExcel->getActiveSheet()->getColumnDimension(chr($alphabet))->setWidth(35);
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
                    foreach ($entete as $key2 => $value) {
                        $contenu = $data[$i]->$value ;
                        if ($contenu>0)
                            $contenu = $this-> nombreFormat($contenu);

                        $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet). $ligne)->getAlignment()->setWrapText(true);
                        $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet). $ligne)->applyFromArray($stylecontenu);

                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, $contenu );
                        $alphabet ++ ;
                    }
                } 

            /* AJOUT UNE LIGNE DE TOTAL ENPIED */
            if ($module=='product_national') {
                $alphabet = 65 ;
                $ligne++ ;

                foreach ($entete as $key => $value) {
                    if ($total[$value]>0) 
                        $total[$value] = $this-> nombreFormat($total[$value]) ;
                    
                    $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet). $ligne)->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->getStyle(chr($alphabet).$ligne.":".chr($alphabet). $ligne)->applyFromArray($stylecontenu);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, $total[$value]);
                    $alphabet ++ ;
                }
            }

       

        // AJOUTER LE NOM DU FICHIER EN nom_file + entete par exemple
        
        try
        {
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save(dirname(__FILE__) . "/../../../../export_excel/".$repertoire.$sous_repertoire."/".$nom_file.".xlsx");
            
            $this->response([
                'status'    => TRUE,
                'nom_file'  => $nom_file.".xlsx",
                'resp'      => $data,
                'sous_repertoire'      => $sous_repertoire."/",
                'message'   => 'Get file success',
            ], REST_Controller::HTTP_OK);
          
        } 
        catch (PHPExcel_Writer_Exception $e)
        {
            $this->response([
                  'status'      => FALSE,
                   'response'   => array(),
                   'message'    => "Something went wrong: ". $e->getMessage(),
                ], REST_Controller::HTTP_OK);
        }

    }

 }
/* End of file controllername.php */
/* Location: ./application/controllers/controllername.php */
?>      