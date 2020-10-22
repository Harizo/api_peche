<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class SIP_reporting_halieutique extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SIP_reporting_halieutique_model', 'halManager');
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
            //COLLECTE
                case 'sip_get_somme_capture_all_espece_by_dist':
                {

                    $data = $this->halManager->get_somme_capture_all_espece_by_dist();
                    break;
                }

                case 'sip_quantite_collecte_region':
                {

                    $data = $this->halManager->sip_quantite_collecte_region();
                    break;
                }

                case 'sip_quantite_collecte_mois':
                {

                    $data = $this->halManager->sip_quantite_collecte_mois();
                    break;
                }

                case 'sip_quantite_collecte_operateur':
                {

                    $data = $this->halManager->sip_quantite_collecte_operateur();
                    break;
                }
                case 'sip_quantite_collecte_espece':
                {

                    $data = $this->halManager->sip_quantite_collecte_espece();
                    break;
                }

                case 'sip_prix_moyenne_mois':
                {

                    $data = $this->halManager->sip_prix_moyenne_mois();
                    break;
                }

                case 'sip_prix_moyenne_district':
                {

                    $data = $this->halManager->sip_prix_moyenne_district();
                    break;
                }

                case 'sip_prix_moyenne_region':
                {

                    $data = $this->halManager->sip_prix_moyenne_region();
                    break;
                }
            //FIN COLLECTE

            //COMMERCE MARINE
                //VENTE LOCALE
                    case 'sip_qte_par_espece_vente_local':
                    {

                        $data = $this->halManager->sip_qte_par_espece_vente_local();
                        break;
                    }

                    case 'sip_qte_par_district_espece_vente_local':
                    {

                        $data = $this->halManager->sip_qte_par_district_espece_vente_local();
                        break;
                    }

                    case 'sip_qte_par_region_espece_vente_local':
                    {

                        $data = $this->halManager->sip_qte_par_region_espece_vente_local();
                        break;
                    }

                    case 'sip_prix_moyenne_par_espece_vente_local':
                    {

                        $data = $this->halManager->sip_prix_moyenne_par_espece_vente_local();
                        break;
                    }

                    case 'sip_prix_moyenne_par_district_vente_local':
                    {

                        $data = $this->halManager->sip_prix_moyenne_par_district_vente_local();
                        break;
                    }

                    case 'sip_prix_moyenne_par_region_vente_local':
                    {

                        $data = $this->halManager->sip_prix_moyenne_par_region_vente_local();
                        break;
                    }

                    case 'sip_qte_par_operateur_vente_local':
                    {

                        $data = $this->halManager->sip_qte_par_operateur_vente_local();
                        break;
                    }

                //FIN VENTE LOCALE 
                //EXPEDITION INERNE

                    case 'sip_qte_par_espece_expedition_interne':
                    {

                        $data = $this->halManager->sip_qte_par_espece_expedition_interne();
                        break;
                    }

                    case 'sip_qte_par_district_espece_expedition_interne':
                    {

                        $data = $this->halManager->sip_qte_par_district_espece_expedition_interne();
                        break;
                    }

                    case 'sip_qte_par_region_espece_expedition_interne':
                    {

                        $data = $this->halManager->sip_qte_par_region_espece_expedition_interne();
                        break;
                    }

                    case 'sip_prix_moyenne_par_espece_expedition_interne':
                    {

                        $data = $this->halManager->sip_prix_moyenne_par_espece_expedition_interne();
                        break;
                    }

                    case 'sip_prix_moyenne_par_district_expedition_interne':
                    {

                        $data = $this->halManager->sip_prix_moyenne_par_district_expedition_interne();
                        break;
                    }

                    case 'sip_prix_moyenne_par_region_expedition_interne':
                    {

                        $data = $this->halManager->sip_prix_moyenne_par_region_expedition_interne();
                        break;
                    }

                    case 'sip_qte_par_operateur_expedition_interne':
                    {

                        $data = $this->halManager->sip_qte_par_operateur_expedition_interne();
                        break;
                    }


                //FIN EXPEDITION INTERNE
                //EXPORTATION
                    case 'sip_qte_par_espece_exportation':
                    {

                        $data = $this->halManager->sip_qte_par_espece_exportation();
                        break;
                    }

                    case 'sip_qte_par_district_espece_exportation':
                    {

                        $data = $this->halManager->sip_qte_par_district_espece_exportation();
                        break;
                    }

                    case 'sip_qte_par_region_espece_exportation':
                    {

                        $data = $this->halManager->sip_qte_par_region_espece_exportation();
                        break;
                    }

                    case 'sip_prix_moyenne_par_espece_exportation':
                    {

                        $data = $this->halManager->sip_prix_moyenne_par_espece_exportation();
                        break;
                    }

                    case 'sip_prix_moyenne_par_district_exportation':
                    {

                        $data = $this->halManager->sip_prix_moyenne_par_district_exportation();
                        break;
                    }

                    case 'sip_prix_moyenne_par_region_exportation':
                    {

                        $data = $this->halManager->sip_prix_moyenne_par_region_exportation();
                        break;
                    }
                //FIN EXPORTATION


                //QTE POIDS VIF
                    case 'sip_qte_vente_locale_qte_poids_vif':
                    {

                        $data = $this->halManager->sip_qte_vente_locale_qte_poids_vif();
                        break;
                    }
                    case 'sip_qte_expedition_interne_qte_poids_vif':
                    {

                        $data = $this->halManager->sip_qte_expedition_interne_qte_poids_vif();
                        break;
                    }
                    case 'sip_qte_exportation_qte_poids_vif':
                    {

                        $data = $this->halManager->sip_qte_exportation_qte_poids_vif();
                        break;
                    }

                    case 'sip_qte_expedie_par_desination':
                    {

                        $data = $this->halManager->sip_qte_expedie_par_desination();
                        break;
                    }
                //FIN QTE POIDS VIF
            //FIN COMMERCE MARINE

            //COMMERCE EAU DOUCE
                //VENTE LOCALE
                    case 'sip_qte_par_espece_vente_local_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_par_espece_comm_eau_douce('vl_qte');
                        break;
                    }

                    case 'sip_qte_par_district_espece_vente_local_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_par_district_comm_eau_douce('vl_qte');
                        break;
                    }

                    case 'sip_qte_par_region_espece_vente_local_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_par_region_comm_eau_douce('vl_qte');
                        break;
                    }

                    case 'sip_prix_moyenne_par_espece_vente_local_eau_douce':
                    {

                        $data = $this->halManager->sip_prix_moy_espece_comm_eau_douce('vl_prix_par_kg');
                        break;
                    }

                    case 'sip_prix_moyenne_par_district_vente_local_eau_douce':
                    {

                        $data = $this->halManager->sip_prix_moy_district_comm_eau_douce('vl_prix_par_kg');
                        break;
                    }

                    case 'sip_prix_moyenne_par_region_vente_local_eau_douce':
                    {

                        $data = $this->halManager->sip_prix_moy_region_comm_eau_douce('vl_prix_par_kg');
                        break;
                    }

                    case 'sip_qte_par_operateur_vente_local_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_par_operateur_comm_eau_douce('vl_qte');
                        break;
                    }

                //FIN VENTE LOCALE 
                //EXPEDITION INERNE

                    case 'sip_qte_par_espece_expedition_interne_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_par_espece_comm_eau_douce('exp_qte');
                        break;
                    }

                    case 'sip_qte_par_district_espece_expedition_interne_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_par_district_comm_eau_douce('exp_qte');
                        break;
                    }

                    case 'sip_qte_par_region_espece_expedition_interne_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_par_region_comm_eau_douce('exp_qte');
                        break;
                    }

                    case 'sip_prix_moyenne_par_espece_expedition_interne_eau_douce':
                    {

                        $data = $this->halManager->sip_prix_moy_espece_comm_eau_douce('exp_prix_par_kg');
                        break;
                    }

                    case 'sip_prix_moyenne_par_district_expedition_interne_eau_douce':
                    {

                        $data = $this->halManager->sip_prix_moy_district_comm_eau_douce('exp_prix_par_kg');
                        break;
                    }

                    case 'sip_prix_moyenne_par_region_expedition_interne_eau_douce':
                    {

                        $data = $this->halManager->sip_prix_moy_region_comm_eau_douce('exp_prix_par_kg');
                        break;
                    }

                    case 'sip_qte_par_operateur_expedition_interne_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_par_operateur_comm_eau_douce('exp_qte');
                        break;
                    }


                //FIN EXPEDITION INTERNE
                //EXPORTATION
                    case 'sip_qte_par_espece_exportation_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_par_espece_comm_eau_douce('export_qte');
                        break;
                    }

                    case 'sip_qte_par_district_espece_exportation_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_par_district_comm_eau_douce('export_qte');
                        break;
                    }

                    case 'sip_qte_par_region_espece_exportation_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_par_region_comm_eau_douce('export_qte');
                        break;
                    }

                    case 'sip_prix_moyenne_par_espece_exportation_eau_douce':
                    {

                        $data = $this->halManager->sip_prix_moy_espece_comm_eau_douce('export_prix_par_kg');
                        break;
                    }

                    case 'sip_prix_moyenne_par_district_exportation_eau_douce':
                    {

                        $data = $this->halManager->sip_prix_moy_district_comm_eau_douce('export_prix_par_kg');
                        break;
                    }

                    case 'sip_prix_moyenne_par_region_exportation_eau_douce':
                    {

                        $data = $this->halManager->sip_prix_moy_region_comm_eau_douce('export_prix_par_kg');
                        break;
                    }

                    case 'sip_qte_par_operateur_exportation_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_par_operateur_comm_eau_douce('export_qte');
                        break;
                    }
                //FIN EXPORTATION


                //QTE POIDS VIF
                    case 'sip_qte_vente_locale_qte_poids_vif_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_par_espece_comm_eau_douce('vl_poids_vif');
                        break;
                    }
                    case 'sip_qte_expedition_interne_qte_poids_vif_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_par_espece_comm_eau_douce('exp_poids_vif');
                        break;
                    }
                    case 'sip_qte_exportation_qte_poids_vif_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_par_espece_comm_eau_douce('export_poids_vif');
                        break;
                    }

                    case 'sip_qte_expedie_par_desination_eau_douce':
                    {

                        $data = $this->halManager->sip_qte_expedie_par_desination_eau_douce();//mbolamila amboarina
                        break;
                    }
                //FIN QTE POIDS VIF
            //FIN COMMERCE EAU DOUCE


            
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
                $objPHPExcel->getProperties()->setCreator("S.I.P Madagascar")
                            ->setLastModifiedBy("S.I.P Madagascar")
                            ->setTitle("reporting production halieutique")
                            ->setSubject("reporting production halieutique")
                            ->setDescription("reporting production halieutique")
                            ->setKeywords("reporting production halieutique")
                            ->setCategory("reporting production halieutique");

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
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($alphabet).$ligne, $data[$i]->$value);
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
                    'message' => 'Get data success',
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
