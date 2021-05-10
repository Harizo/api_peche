<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//harizo
// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Requetes extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('site_embarquement_model', 'Site_embarquementManager');
        $this->load->model('region_model', 'RegionManager');
        $this->load->model('fiche_echantillonnage_capture_model', 'Fiche_echantillonnage_captureManager');
        $this->load->model('unite_peche_model', 'Unite_pecheManager');
        $this->load->model('distribution_fractile_model', 'Distribution_fractileManager');  
        $this->load->model('unite_peche_site_model', 'Unite_peche_site_Manager');
        $this->load->model('enquete_cadre_model', 'Enquete_cadreManager');
        $this->load->model('Espece_model', 'EspeceManager'); 
        
    }
   
    public function index_get() 
    {
        set_time_limit(0);
        ini_set ('memory_limit', '10000000000M');
        $pivot = $this->get('pivot');
        $annee = $this->get('annee');
        $mois = $this->get('mois');
        $date = $this->get('date');
        $date_fin = $this->get('date_fin');
        $id_espece = $this->get('id_espece');
        $id_region = $this->get('id_region');
        $id_district = $this->get('id_district');
        $id_site_embarquement = $this->get('id_site_embarquement');
        $id_unite_peche = $this->get('id_unite_peche');
        $menu = $this->get('menu');
        $repertoire = $this->get('repertoire');
$data=array();
        

        //******************************** Debut ***************************

                  if ($pivot == 'req_1') 
                  {
                    //$data = $this->requete_1($id_region,$annee) ;
                    $data = $this->requete_1($id_region, $annee,$id_district, $id_site_embarquement) ;
                  }                  

                  if ($pivot == 'req_2') 
                  {
                    //$data = $this->requete_2($id_region,$annee) ;                    
                    $data = $this->requete_2( $id_region, $annee,$id_district, $id_site_embarquement) ;
                  }            
            
                  if ($pivot == 'req_3') 
                  {
                    $data = $this->requete_3($id_region,$annee, $id_district, $id_site_embarquement) ;
                  }            
            
                  if ($pivot == 'req_4_1') 
                  {
                    $donnees=$this->Fiche_echantillonnage_captureManager->req_4($id_region,$annee, $id_district, $id_site_embarquement);
      
                    if ($donnees) 
                    {               
                      $data = $donnees ;
                    } 
                   else {
                    $data=array();
                   } 
                  }

                  if ($pivot == 'req_4_2') 
                  {
                    $donnees=$this->Fiche_echantillonnage_captureManager->req_4_2($id_region,$annee, $id_district, $id_site_embarquement);
      
                    if ($donnees) 
                    {               
                      $data = $donnees ;
                    } 
                   else {
                    $data=array();
                   } 
                  }
                  if ($pivot == 'req_5_1') 
                  {
                    $donnees=$this->Fiche_echantillonnage_captureManager->req_5_1($id_region,$annee, $id_district, $id_site_embarquement);
      
                    if ($donnees) 
                    {               
                      $data = $donnees ;
                    } 
                   else {
                    $data=array();
                   } 
                  }
                  if ($pivot == 'req_5_2') 
                  {
                    $donnees=$this->Fiche_echantillonnage_captureManager->req_5_2($id_region,$annee, $id_district, $id_site_embarquement);
      
                    if ($donnees) 
                    {               
                      $data = $donnees ;
                    } 
                   else {
                    $data=array();
                   } 
                  }
                  if ($pivot == 'req_5_6') 
                  {
                    $donnees=$this->Fiche_echantillonnage_captureManager->req_5_6($id_region,$annee, $id_district, $id_site_embarquement);
      
                    if ($donnees) 
                    {               
                      $data = $donnees ;
                    } 
                   else {
                    $data=array();
                   } 
                  }
                  if ($pivot == 'req_5_7') 
                  {
                    $donnees=$this->Fiche_echantillonnage_captureManager->req_5_7($id_region,$annee, $id_district, $id_site_embarquement);
      
                    if ($donnees) 
                    {               
                      $data = $donnees ;
                    } 
                   else {
                    $data=array();
                   } 
                  }
                  if ($pivot == 'req_6_2') 
                  {
                    $donnees=$this->Fiche_echantillonnage_captureManager->req_6_2($id_region,$annee, $id_district, $id_site_embarquement);
      
                    if ($donnees) 
                    {               
                      $data = $donnees ;
                    } 
                   else {
                    $data=array();
                   } 
                  }
                  
                  if ($pivot == 'req_6_2_a') 
                  {
                    $donnees=$this->Fiche_echantillonnage_captureManager->req_6_2_a($id_region,$annee, $id_district, $id_site_embarquement);
      
                    if ($donnees) 
                    {               
                      $data = $donnees ;
                    } 
                   else {
                    $data=array();
                   } 
                  }                  
                  
                  if ($pivot == 'req_7_1') 
                  {
                    $donnees=$this->Fiche_echantillonnage_captureManager->req_7_1($id_region,$annee, $id_district, $id_site_embarquement);
      
                    if ($donnees) 
                    {               
                      $data = $donnees ;
                    } 
                   else {
                    $data=array();
                   } 
                  }                 
                  
                  if ($pivot == 'req_7_2') 
                  {
                    $donnees=$this->Fiche_echantillonnage_captureManager->req_7_2($id_region,$annee, $id_district, $id_site_embarquement);
      
                    if ($donnees) 
                    {               
                      $data = $donnees ;
                    } 
                   else {
                    $data=array();
                   } 
                  }                
                  
                  if ($pivot == 'req_7_3') 
                  {
                    $donnees=$this->Fiche_echantillonnage_captureManager->req_7_3($id_region,$annee, $id_district, $id_site_embarquement);
      
                    if ($donnees) 
                    {               
                      $data = $donnees ;
                    } 
                   else {
                    $data=array();
                   } 
                  }               
                  
                  if ($pivot == 'req_8') 
                  {
                    $donnees=$this->Fiche_echantillonnage_captureManager->req_8_2($id_region,$annee, $id_district, $id_site_embarquement);
      
                    if ($donnees) 
                    {               
                      $data = $donnees ;
                    } 
                   else {
                    $data=array();
                   } 
                  }              
                  
                  if ($pivot == 'req_9') 
                 {
                   $donnees=$this->Fiche_echantillonnage_captureManager->req_9_2($id_region,$annee, $id_district, $id_site_embarquement);
     
                   if ($donnees) 
                   {               
                     $data = $donnees ;
                   } 
                  else {
                   $data=array();
                  }  
                 }             
                  
                   if ($pivot == 'req_9_3') 
                  {
                    $donnees=$this->Fiche_echantillonnage_captureManager->req_9_3($id_region,$annee, $id_district, $id_site_embarquement);
      
                    if ($donnees) 
                    {               
                      $data = $donnees ;
                    } 
                   else {
                    $data=array();
                   }  
                  }
 
        //*********************************  Fin *****************************
        if (count($data)>0)
        {
          if ($menu=="export_excel")
          {
            $export=$this->export_excel($repertoire,$data,$pivot);
          }
          else 
          {
              $this->response([
                'status' => TRUE,
                'response' => $data,
                'message' => 'Get data success',
              ], REST_Controller::HTTP_OK);
          }
                 
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


    public function requete_1($id_region, $annee, $id_district, $id_site_embarquement)
    {
       $data = array();
            $donnees=$this->Fiche_echantillonnage_captureManager->req_1($id_region,$annee, $id_district, $id_site_embarquement);
      
            if ($donnees) 
            {               
              $data = $donnees ;
            }
       return $data ;
    }
    
    public function requete_2($id_region, $annee, $id_district, $id_site_embarquement)
    {
      $data = array();
            $donnees=$this->Fiche_echantillonnage_captureManager->req_2( $id_region, $annee,$id_district, $id_site_embarquement);
      
            if ($donnees) 
            {               
              $data = $donnees ;
            }
       return $data ;
    }
    
    public function requete_3($id_region,$annee, $id_district, $id_site_embarquement)
    {
      $data = array();
            $donnees=$this->Fiche_echantillonnage_captureManager->req_3($id_region,$annee, $id_district, $id_site_embarquement);
      
            if ($donnees) 
            {               
              $data = $donnees ;
            }
       return $data ;
    }
    
    public function requete_4()
    {
      $data = array();
            $donnees=$this->Fiche_echantillonnage_captureManager->req_4();
      
            if ($donnees) 
            {               
              $data = $donnees ;
            }
       return $data ;
    }

    public function export_excel($repertoire,$data,$pivot) 
    {

        require_once 'Classes/PHPExcel.php';
        require_once 'Classes/PHPExcel/IOFactory.php';

        $nom_file='requetes';
        $directoryName = dirname(__FILE__) ."/../../../../../../assets/excel/".$repertoire;
        //Check if the directory already exists.
        if(!is_dir($directoryName))
        {
            mkdir($directoryName, 0777,true);
        }
        
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Myexcel")
                    ->setLastModifiedBy("requetes")
                    ->setTitle("requetes")
                    ->setSubject("requetes")
                    ->setDescription("requetes")
                    ->setKeywords("requetes")
                    ->setCategory("requetes");

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

        $objPHPExcel->getActiveSheet()->setTitle("Requetes");
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
        
        $styleEntete = array
        (
            'alignment' => array
            (
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    
            ),
                
            'font' => array
            (
                'name'  => 'Calibri',
                'bold'  => true,
                'size'  => 11
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
        $titre = array(
          "req_1"=>"Req 1 : CPUE journalière / Unité de pêche",
          "req_2"=>"Req 2 : CPUEmoy par strate mineure/mois/année",
          "req_3"=>"Req 3 : Erreur relative CPUEmoy par strate mineure/mois/année",
          "req_4_1"=>"Req 4.1 : Nombre unité de pêche par strate majeure/strate mineure /site",
          "req_4_2"=>"Req 4.2 : Nombre unité de pêche par strate majeure/strate mineure /site",
          "req_5_1"=>"Req 5.1 : PAB ou Probabilité d’Activité de Bateau (Echantillonnage horizontal)",
          "req_5_2"=>"Req 5.2 : PABmoy par l’unité de pêche /strate majeure/strate mineure/Mois/Année",
          "req_5_6"=>"Req 5.6 : PABRelErrormoy par l’unité de pêche /strate majeure/strate mineure/Mois/Année",
          "req_5_7"=>"Req 5.7 : Nombre unité de pêche/trate majeure/strate mineure/Mois/Année",
          "req_6_2_a"=>"Req 6.2.A : Total jour de pêche  annuelle par l’unité de pêche avec PAB",
          "req_6_2"=>"Req 6.2 : Prix PAB par espèces par l’unité de pêche /Strate majeure/Strate mineure/Année/Mois",
          "req_7_1"=>"Req 7.1 : Capture par espèces par l’unité de pêche par strate majeure/strate mineure/Année / Mois",
          "req_7_2"=>"Req 7.2 : Total capture par espèces par l’unité de pêche /strate majeure/strate mineure/Année/Mois",
          "req_7_3"=>"Req 7.3 : Composition d’espèce par l’unité de pêche",
          "req_8"=>"Req 8 : Prix PAB par espèces par l’unité de pêche/Strate majeure/Strate mineure/Année/Mois",
          "req_9"=>"Req 9.2 : Unité de pêche par site",
          "req_9_3"=>"Req 9.3 : Targeted Unité de pêche par strate mineure par Année / Mois"
          
        );

    //TITRE
        $objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(40);
        $objPHPExcel->getActiveSheet()->mergeCells(chr(65).$ligne.":".chr((65+$nbr_cell)).$ligne);
        $objPHPExcel->getActiveSheet()->getStyle(chr(65).$ligne.":".chr((65+$nbr_cell)).$ligne)->applyFromArray($styleTitre);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr(65).$ligne, $titre[$pivot]);
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

        try
        {
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save(dirname(__FILE__) . "/../../../../../../assets/excel/requetes/".$nom_file.".xlsx");
            
            $this->response([
                'status' => TRUE,
                'nom_file' =>$nom_file.".xlsx",
                'message' => 'Get file success',
            ], REST_Controller::HTTP_OK);
          
        } 
        catch (PHPExcel_Writer_Exception $e)
        {
            $this->response([
                  'status' => FALSE,
                   'nom_file' => array(),
                   'message' => "Something went wrong: ". $e->getMessage(),
                ], REST_Controller::HTTP_OK);
        }
    }

    public function generer_requete($annee, $mois, $id_region, $id_district, $id_site_embarquement, $id_unite_peche, $id_espece)
    {
        $requete = "annee='".$annee."'" ;  
            
            if (($mois!='*')&&($mois!='undefined')) 
            {
                $requete = $requete." AND mois='".$mois."'" ;
            }

            if (($id_region!='*')&&($id_region!='undefined')) 
            {
                $requete = $requete." AND id_region='".$id_region."'" ;
            }

            if (($id_district!='*')&&($id_district!='undefined')) 
            {
                $requete = $requete." AND id_district='".$id_district."'" ;
            }

            if (($id_site_embarquement!='*')&&($id_site_embarquement!='undefined')) 
            {
                $requete = $requete." AND id_site_embarquement='".$id_site_embarquement."'" ;
            }

            if (($id_unite_peche!='*')&&($id_unite_peche!='undefined')) 
            {
                $requete = $requete." AND id_unite_peche='".$id_unite_peche."'" ;
            }

            if (($id_espece!='*')&&($id_espece!='undefined')) 
            {
                $requete = $requete." AND id_espece='".$id_espece."'" ;
            }

        return $requete ;
    }

}
/* End of file controllername.php */
/* Location: ./application/controllers/controllername.php */
?>