<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_reporting_crevette extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_reporting_crevette_model', 'crevManager');
    }

    public function index_get() 
    {
        $etat = $this->get('etat');
        $etat_export_excel = $this->get('etat_export_excel');
        $repertoire = $this->get('repertoire');
        $titre_module = $this->get('titre_module');
        $titre_etat = $this->get('titre_etat');

        switch ($etat) 
        {
            //RELEVE CAPTURE
                    case 'tableau_de_bord_maree':
                    {

                        $data = $this->crevManager->tableau_de_bord_maree();
                        break;
                    }
                    case 'nombre_fiche_par_maree':
                    {

                        $data = $this->crevManager->nombre_fiche_par_maree();
                        break;
                    }
                    case 'qte_crevette_par_maree':
                    {

                        $data = $this->crevManager->qte_par_maree('qte_crevette');
                        break;
                    }

                    case 'qte_poisson_par_maree':
                    {

                        $data = $this->crevManager->qte_par_maree('qte_poisson');
                        break;
                    }

                    case 'qte_crevette_par_bateau':
                    {

                        $data = $this->crevManager->sip_qte_par_bateau_peche_crevette('qte_crevette');
                        break;
                    }

                    case 'qte_poisson_par_bateau':
                    {

                        $data = $this->crevManager->sip_qte_par_bateau_peche_crevette('qte_poisson');
                        break;
                    }

                    case 'qte_crevette_par_societe':
                    {

                        $data = $this->crevManager->sip_qte_par_societe_peche_crevette('qte_crevette');
                        break;
                    }

                    case 'qte_poisson_par_societe':
                    {

                        $data = $this->crevManager->sip_qte_par_societe_peche_crevette('qte_poisson');
                        break;
                    }

                //COMMERCE

                    case 'qte_vente_locale_commerce':
                    {

                        $data = $this->crevManager->sip_qte_commercialise_crevette('qte_vl');
                        break;
                    }

                    case 'qte_exportation_commerce':
                    {

                        $data = $this->crevManager->sip_qte_commercialise_crevette('qte_export');
                        break;
                    }

                    case 'qte_vente_locale_par_mois_commerce':
                    {

                        $data = $this->crevManager->sip_qte_par_mois_commercialise_crevette('qte_vl');
                        break;
                    }

                    case 'qte_exportation_par_mois_commerce':
                    {

                        $data = $this->crevManager->sip_qte_par_mois_commercialise_crevette('qte_export');
                        break;
                    }


                    case 'qte_vente_locale_par_societe_commerce':
                    {

                        $data = $this->crevManager->sip_qte_par_societe_commercialise_crevette('qte_vl');
                        break;
                    }

                    case 'qte_exportation_par_societe_commerce':
                    {

                        $data = $this->crevManager->sip_qte_par_societe_commercialise_crevette('qte_export');
                        break;
                    }



                    case 'prix_moy_vente_locale_mois_commerce':
                    {

                        $data = $this->crevManager->sip_prix_moy_par_mois_commercialise_crevette('pum_vl');
                        break;
                    }

                    case 'prix_moy_exportation_mois_commerce':
                    {

                        $data = $this->crevManager->sip_prix_moy_par_mois_commercialise_crevette('pum_export');
                        break;
                    }

                    case 'sip_qte_exporte_par_dest_commerce_crevette':
                    {

                        $data = $this->crevManager->sip_qte_exporte_par_dest_commerce_crevette();
                        break;
                    }

                //FIN COMMERCE

                //EXPORTATION
                    case 'sip_qte_par_espece_exportation_crevette':
                    {

                        $data = $this->crevManager->sip_qte_par_espece_exportation_crevette();
                        break;
                    }

                    case 'sip_qte_par_mois_exportation_crevette':
                    {

                        $data = $this->crevManager->sip_qte_par_mois_exportation_crevette();
                        break;
                    }

                    case 'sip_qte_par_destination_exportation_crevette':
                    {

                        $data = $this->crevManager->sip_qte_par_destination_exportation_crevette();
                        break;
                    }

                    case 'sip_qte_par_societe_exportation_crevette':
                    {

                        $data = $this->crevManager->sip_qte_par_societe_exportation_crevette();
                        break;
                    }

                    case 'sip_valeurs_par_espece_exportation_crevette':
                    {

                        $data = $this->crevManager->sip_valeurs_par_espece_exportation_crevette();
                        break;
                    }

                    case 'sip_valeurs_en_devise_par_mois_exportation_crevette':
                    {

                        $data = $this->crevManager->sip_valeurs_en_devise_par_mois_exportation_crevette();
                        break;
                    }

                //FIN EXPORTATION

            //FIN RELEVE CAPTURE

          
            
            default:
               
                break;
        }

        
        //EXPORT EXCEL
            if ($etat_export_excel == 1) 
            {

                require_once 'Classes/PHPExcel.php';
                require_once 'Classes/PHPExcel/IOFactory.php';

                $nom_file='fiche_requête_vente_poissonnerie_reporting';
                $directoryName = dirname(__FILE__) ."/../../../../export_excel/".$repertoire;
                //Check if the directory already exists.
                if(!is_dir($directoryName))
                {
                    mkdir($directoryName, 0777,true);
                }
                
                $objPHPExcel = new PHPExcel();
                $objPHPExcel->getProperties()->setCreator("S.I.P")
                            ->setLastModifiedBy("S.I.P")
                            ->setTitle("reporting peche crevettiere")
                            ->setSubject("reporting peche crevettiere")
                            ->setDescription("reporting peche crevettiere")
                            ->setKeywords("reporting peche crevettiere")
                            ->setCategory("reporting peche crevettiere");

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

                foreach ($entete as $key => $value) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension(chr($alphabet))->setautoSize(true) ;
                    $alphabet++ ;
                }

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
                $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(40);
                $objPHPExcel->getActiveSheet()->mergeCells(chr(65).$ligne.":".chr((65+$nbr_cell)).$ligne);
                $objPHPExcel->getActiveSheet()->getStyle(chr(65).$ligne.":".chr((65+$nbr_cell)).$ligne)->applyFromArray($styleTitre);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr(65).$ligne, "Etat ".$titre_module.": ".$titre_etat);
            //FIN TITRE
                $ligne = $ligne + 2 ;

            //ENTETE

                $objPHPExcel->getActiveSheet()->getStyle(chr(65).$ligne.":".chr((65+$nbr_cell)).$ligne)->applyFromArray($stylesousTitre);
                $objPHPExcel->getActiveSheet()->getStyle(chr(65).$ligne.":".chr((65+$nbr_cell)).$ligne)->getAlignment()->setWrapText(true);
                /*$objPHPExcel -> getActiveSheet()->getStyle("A".$ligne.":N".$ligne)->getNumberFormat()->applyFromArray(
		            array(
		                'code' => PHPExcel_Style_NumberFormat::FORMAT_GENERAL
		            )
		        );*/
                $alphabet = 65 ;
                foreach ($entete as $key => $value) 
                {
                	$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, (string)$value);
                	$alphabet++;
                }

                $ligne++;
            //FIN ENTETE

            //CONTENU
                
                for ($i=0; $i < count($data); $i++) 
                {
                	$objPHPExcel->getActiveSheet()->getStyle(chr(65).$ligne.":".chr((65+$nbr_cell)).$ligne)->applyFromArray($stylecontenu);
                	$objPHPExcel->getActiveSheet()->getStyle(chr(65).$ligne.":".chr((65+$nbr_cell)).$ligne)->getAlignment()->setWrapText(true);

                	$alphabet = 65 ;
	                foreach ($entete as $key => $value) 
	                {
	                	$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, (string)($data[$i]->$value));
	                	$alphabet++;
	                }

	                $ligne++;
                	
                }
            //FIN CONTENU



                
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save(dirname(__FILE__) . "/../../../../export_excel/".$repertoire."/".$titre_etat.".xlsx");
            }
        //FIN EXPORT EXCEL

         
        if ($etat_export_excel == 1) 
        {

            if ($data) 
            {
                $this->response([
                    'status' => TRUE,
                    'nom_file' => $titre_etat.".xlsx",
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
            
        }
        else
        {
            if ($data) 
            {
                $this->response([
                    'status' => TRUE,
                    'response' => $data,
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
        }
         
        
    }
   
}
/* End of file controllername.php */
/* Location: ./application/controllers/controllername.php */
