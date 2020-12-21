<?php
//harizo
defined('BASEPATH') OR exit('No direct script access allowed');

// afaka fafana refa ts ilaina
require APPPATH . '/libraries/REST_Controller.php';

class Rapport_enqueteur extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('enqueteur_model', 'EnqueteurManager');
        $this->load->model('echantillon_model', 'EchantillonManager');
        $this->load->model('site_enqueteur_model', 'Site_enqueteurManager');
        $this->load->model('unite_peche_model', 'Unite_pecheManager');
        $this->load->model('nbr_echantillon_enqueteur_model', 'Nbr_echantillon_enqueteurManager');
        $this->load->model('region_model', 'RegionManager');
    }

    public function index_get()
    {	$cle_etrangere = $this->get('cle_etrangere');
    	$menu = $this->get('menu');
    	$annee = $this->get('annee');
    	$mois = $this->get('mois');
    	$id_enqueteur = $this->get('id_enqueteur');
    	$nom_enqueteur = $this->get('nom_enqueteur');
    	$prenom_enqueteur = $this->get('prenom_enqueteur');
    	$id_unite_peche = $this->get('id_unite_peche');
    	$repertoire = $this->get('repertoire');
    	$date_fin = $this->get('date_fin');
    	$date_debut = $this->get('date_debut');
    	$data = array();
    	$max = array();
    	if($menu=='fichesuivienqueteur')
    	{	
    		if($id_enqueteur)
	        {
	            $site_embarquement = $this->EnqueteurManager->findSiteByEnqueteur($id_enqueteur);
	        }
	        if(($id_unite_peche!='*')&&($id_unite_peche!='undefined'))
	        {
	            $all_unite_peche = $this->Unite_pecheManager->findByIdtab($id_unite_peche);
	        }
	        else
	        {
	            $all_unite_peche=$this->EnqueteurManager->findUniteBySite_embarquement($site_embarquement[0]->id_site);
	        }
	        
	        if($site_embarquement)
	        {

	        	$region=strtoupper($site_embarquement[0]->region);
	        	$nomSite=strtoupper($site_embarquement[0]->libelle);
	        }
	        if($nom_enqueteur)
	        {
	        	$nom=strtoupper($nom_enqueteur);
	        	if($prenom_enqueteur)
	        	{
	        		$nom= $nom." ".$prenom_enqueteur;  
	        	}
	        	$nomEnqueteur=$nom;
	        }
	        	        
	        if($mois)
	        {	
	        	if(($mois=='01') ||($mois=='03') || ($mois=='05') || ($mois=='07') || ($mois=='08') || ($mois=='10') || ($mois=='12'))
	        	{
	        		$j=31;
	        	}
	        	elseif ($mois==02)
	        	{
	        	$j = cal_days_in_month(CAL_GREGORIAN, $mois, $annee);	
	        	}
	        	else
	        	{
	        		$j=30;
	        	}
	        	
	        	for ($i=1; $i <=$j ; $i++)
	        	{ 	
		        	$jour=$i;
		        	if($jour<10)
		        	{
		        		$jour= '0'.$jour;
		        	}
		        	$date[$i] = $annee."-".$mois."-".$jour;
	        	}
	        	//setlocale(LC_TIME, "fr_FR");
	        	setlocale(LC_TIME, 'french', 'fr_FR', 'fra');
	        	$months=ucfirst(strftime("%B", strtotime($annee."/".$mois."/01")));       	
	        	$nbrweek = $this->weeks($annee, $mois);
	        	$week[1]="01-".$mois."-".$annee;
	        	
	        	for($k=2; $k<=$nbrweek; $k++)
	        	{
	        		$week[$k] = date( "d-m-Y" ,strtotime('next Monday', strtotime( $week[$k-1] ) ) );
	        	}
	        	$nbrDate= count($date);

	        	$nbrmax=array();
	        	$totalechant=array();
	        	$sum_total=0;
	        	foreach ($date as $key => $value)
	        	{	        		
	        		foreach ($all_unite_peche as $key2 => $value2)
		        	{ 	
		        		if($key==1)
		        		{
		        			$nbrmax = $this->Nbr_echantillon_enqueteurManager->max_echantillon_enqueteur($id_enqueteur,$value2->id,$site_embarquement[0]->id_site);
		        		}
		        		if($key==$nbrDate)
		        		{
		        			$totalechant= $this->EchantillonManager->nbrechantillontotal($this->genererequete($annee,$mois,$id_enqueteur,$value2->id));
		        		}
		    			$nbrechant= $this->EchantillonManager->nbrechantilonpartiel($value,$value,$id_enqueteur,$value2->id);
		    			if($nbrmax)
		    			{
		    				$data[$value][$key2]['max_echan_enque']=$nbrmax[0]->max;	
		    			}
		    			if($totalechant)
		    			{
		    				$data[$value][$key2]['total_echan_mois']=$totalechant[0]->nombre;
		    				$sum_total=$sum_total+$totalechant[0]->nombre;    				
		    			}
		    			$data[$value][$key2]['unite_peche']=$value2->libelle;
		    			
		    			$data[$value][$key2]['nombre']=$nbrechant[0]->nombre;
    				}
	        	}
	        	
	        $menu=array();
	        $menu['titre']='FICHE DE SUIVI NOMBRE D\'ECHANTILLON';	
	        $menu['repertoire']=$repertoire;	
	        $menu['region']=$region;
	        $menu['nomEnqueteur']=$nomEnqueteur;
	        $menu['nomSite']=$nomSite;
	        $menu['months']=$months;	 
	        }

	        foreach ($data[$annee."-".$mois."-01"] as $key5 => $value5)
	        {	        		
	        	$max[$key5]=$value5;	
	        }
    	$this->genererexcel($menu,$week,$nbrweek,$data,$annee,$mois,$date,$sum_total);	
    	}

    	/*if($menu=='filtredate')
    	{	$data=array();
    		if($id_enqueteur)
	        {
	            $site_embarquement = $this->EnqueteurManager->findSiteByEnqueteur($id_enqueteur);
	        }
	        if($site_embarquement)
	        {
	        	$all_unite_peche=$this->EnqueteurManager->findUniteBySite_embarquement($site_embarquement[0]->id_site);
	        	$region=strtoupper($site_embarquement[0]->region);
	        	$nomSite=strtoupper($site_embarquement[0]->libelle);
	        }
	        foreach ($all_unite_peche as $key => $value)
		    { 
    			$nbrechant= $this->EchantillonManager->nbrechantilonpartiel($date_debut,$date_fin,$id_enqueteur,$value->id);
    			$data[$key]['unite_peche']=$value;
    			$data[$key]['nombre']=$nbrechant[0]->nombre;
    		}
    		$site =array();
    		$site['region']=$region;
    		$site['site_embarquement']= $nomSite; 
    	}*/
        
	}
	public function genererequete($annee,$mois,$id_enqueteur,$id_unite_peche)
    	{
    		$requete = "date BETWEEN '".$annee."-".$mois."-01' AND '".$annee."-".$mois."-31' ";
    		if($id_enqueteur)
	        {
	            $requete = $requete." AND id_enqueteur='".$id_enqueteur."'"; 
	        }
	        if($id_unite_peche)
	        {
	            $requete = $requete." AND id_unite_peche='".$id_unite_peche."'"; 
	        }
	     return $requete;   
    	}
    /*public function generequete($dated,$datef,$id_enqueteur,$id_unite_peche)
    	{
    		$requete = "date BETWEEN '".$dated." AND ".$datef."'";
    		if($id_enqueteur)
	        {
	            $requete = $requete." AND id_enqueteur='".$id_enqueteur."'"; 
	        }
	        if($id_unite_peche)
	        {
	            $requete = $requete." AND id_unite_peche='".$id_unite_peche."'"; 
	        }
	     return $requete;   
    	}*/
    
  /* public function weeks($year, $month)
    {
        $num_of_days = date("t", mktime(0,0,0,$month,1,$year)); 
        $lastday = date("t", mktime(0, 0, 0, $month, 1, $year)); 
        $no_of_weeks = 0; 
        $count_weeks = 0; 
        while($no_of_weeks < $lastday)
        { 
            $no_of_weeks += 7; 
            $count_weeks++; 
        } 
		return $count_weeks;
	}*/
public function weeks($year,$month) {
	// Start of mois
	$start = mktime(0, 0, 0, $month, 1, $year);
	// End of mois
	$end = mktime(0, 0, 0, $month, date('t', $start), $year);
	// Start week
	$start_week = date('W', $start);
	// End week
	$end_week = date('W', $end);
	
	if ($end_week < $start_week) { 
			   //year has 52 weeks
			   $weeksInYear = 52;
			   //but if leap year, it has 53 weeks
			   if($year % 4 == 0) {
				   $weeksInYear = 53;
			   }
			   return (($weeksInYear + $end_week) - $start_week) + 1;
		   }
	
	return ($end_week - $start_week) + 1;
   }
    public function genererexcel($menu,$week,$nbrweek,$data,$annee, $mois,$date,$sum_total)
    {	require_once 'Classes/PHPExcel.php';
		require_once 'Classes/PHPExcel/IOFactory.php';

		$nom_file='fiche_suivi';
    	$directoryName = dirname(__FILE__) ."/../../../../../../assets/excel/".$menu['repertoire'];
		
		//Check if the directory already exists.
		if(!is_dir($directoryName))
		{
			mkdir($directoryName, 0777,true);
		}
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("Myexcel")
					->setLastModifiedBy("Me")
					->setTitle("FICHE SUIVI")
					->setSubject("FICHE SUIVI")
					->setDescription("FICHE SUIVI")
					->setKeywords("FICHE SUIVI")
					->setCategory("FICHE SUIVI");

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

		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AJ')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AL')->setWidth(5);
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('AM')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AN')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AO')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AP')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AQ')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AU')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AR')->setWidth(5);


		$objPHPExcel->getActiveSheet()->setTitle("Fiche_suivi");
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
		$styleSousTitre = array
		(
		'alignment' => array
			(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
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
		//$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":AE".$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":AE".$ligne)->getAlignment()->setWrapText(true);                        
		$objPHPExcel->getActiveSheet()->getRowDimension($ligne)->setRowHeight(30);
		$objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":AL".$ligne);
		$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":AL".$ligne)->applyFromArray($styleTitre);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $menu['titre']);
		
		$ligne = 2;
		$objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":AL".$ligne);
		$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":AL".$ligne)->applyFromArray($styleSousTitre);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Région: ".$menu['region']);

		$ligne=3;
		$objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":AL".$ligne);
		$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":AL".$ligne)->applyFromArray($styleSousTitre);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Nom enqueteur: ".$menu['nomEnqueteur']);
		
		$ligne=4;
		$objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":AL".$ligne);
		$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":AL".$ligne)->applyFromArray($styleSousTitre);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "Site: ".$menu['nomSite']);

		$ligne=5;
		$objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":B".$ligne);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "MOIS: ".$menu['months']);
		if($nbrweek==5)
		{
			$objPHPExcel->getActiveSheet()->mergeCells("C".$ligne.":I".$ligne);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, "Semaine du: ".$week[1]);

			$objPHPExcel->getActiveSheet()->mergeCells("J".$ligne.":P".$ligne);
			$objPHPExcel->getActiveSheet()->getStyle("J".$ligne.":P".$ligne)->applyFromArray($styleSousTitre);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ligne, "Semaine du: ".$week[2]);

			$objPHPExcel->getActiveSheet()->mergeCells("Q".$ligne.":W".$ligne);
			$objPHPExcel->getActiveSheet()->getStyle("Q".$ligne.":W".$ligne)->applyFromArray($styleSousTitre);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$ligne, "Semaine du: ".$week[3]);

			$objPHPExcel->getActiveSheet()->mergeCells("X".$ligne.":AD".$ligne);
			$objPHPExcel->getActiveSheet()->getStyle("X".$ligne.":AD".$ligne)->applyFromArray($styleSousTitre);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('X'.$ligne, "Semaine du: ".$week[4]);

			$objPHPExcel->getActiveSheet()->mergeCells("AE".$ligne.":AK".$ligne);
			$objPHPExcel->getActiveSheet()->getStyle("AE".$ligne.":AK".$ligne)->applyFromArray($styleSousTitre);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AE'.$ligne, "Semaine du: ".$week[5]);

			$objPHPExcel->getActiveSheet()->mergeCells("AL".$ligne.":AR".$ligne);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AL'.$ligne, "Semaine du: 0000-00-00");
		}elseif($nbrweek==6)
		{
			$objPHPExcel->getActiveSheet()->mergeCells("C".$ligne.":I".$ligne);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, "Semaine du: ".$week[1]);

			$objPHPExcel->getActiveSheet()->mergeCells("J".$ligne.":P".$ligne);
			$objPHPExcel->getActiveSheet()->getStyle("J".$ligne.":P".$ligne)->applyFromArray($styleSousTitre);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ligne, "Semaine du: ".$week[2]);

			$objPHPExcel->getActiveSheet()->mergeCells("Q".$ligne.":W".$ligne);
			$objPHPExcel->getActiveSheet()->getStyle("Q".$ligne.":W".$ligne)->applyFromArray($styleSousTitre);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$ligne, "Semaine du: ".$week[3]);

			$objPHPExcel->getActiveSheet()->mergeCells("X".$ligne.":AD".$ligne);
			$objPHPExcel->getActiveSheet()->getStyle("X".$ligne.":AD".$ligne)->applyFromArray($styleSousTitre);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('X'.$ligne, "Semaine du: ".$week[4]);

			$objPHPExcel->getActiveSheet()->mergeCells("AE".$ligne.":AK".$ligne);
			$objPHPExcel->getActiveSheet()->getStyle("AE".$ligne.":AK".$ligne)->applyFromArray($styleSousTitre);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AE'.$ligne, "Semaine du: ".$week[5]);

			$objPHPExcel->getActiveSheet()->mergeCells("AL".$ligne.":AR".$ligne);
			$objPHPExcel->getActiveSheet()->getStyle("AL".$ligne.":AR".$ligne)->applyFromArray($styleSousTitre);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AL'.$ligne, "Semaine du: ".$week[6]);

		}
		else
		{
			$objPHPExcel->getActiveSheet()->mergeCells("C".$ligne.":I".$ligne);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, "Semaine du: ".$week[1]);

			$objPHPExcel->getActiveSheet()->mergeCells("J".$ligne.":P".$ligne);
			$objPHPExcel->getActiveSheet()->getStyle("J".$ligne.":P".$ligne)->applyFromArray($styleSousTitre);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ligne, "Semaine du: ".$week[2]);

			$objPHPExcel->getActiveSheet()->mergeCells("Q".$ligne.":W".$ligne);
			$objPHPExcel->getActiveSheet()->getStyle("Q".$ligne.":W".$ligne)->applyFromArray($styleSousTitre);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$ligne, "Semaine du: ".$week[3]);

			$objPHPExcel->getActiveSheet()->mergeCells("X".$ligne.":AD".$ligne);
			$objPHPExcel->getActiveSheet()->getStyle("X".$ligne.":AD".$ligne)->applyFromArray($styleSousTitre);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('X'.$ligne, "Semaine du: ".$week[4]);

			$objPHPExcel->getActiveSheet()->mergeCells("AE".$ligne.":AK".$ligne);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AE'.$ligne, "Semaine du: 0000-00-00");

			$objPHPExcel->getActiveSheet()->mergeCells("AL".$ligne.":AR".$ligne);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AL'.$ligne, "Semaine du: 0000-00-00");
		}

		$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":AS".$ligne)->applyFromArray($stylecontenu);
		$ligne=6;
		$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":AS".$ligne)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":AS".$ligne)->applyFromArray($stylecontenu);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, "NON ENGIN ");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ligne, "Nombre d'echantillon réquis ");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ligne, "Lun ");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ligne, "Mar ");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ligne, "Mer ");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ligne, "Jeu ");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ligne, "Ven");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ligne, "Sam");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ligne, "Dim");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ligne, "Lun ");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$ligne, "Mar");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$ligne, "Mer ");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$ligne, "Jeu");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$ligne, "Ven");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$ligne, "Sam");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$ligne, "Dim ");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$ligne, "Lun");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$ligne, "Mar");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$ligne, "Mer");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$ligne, "Jeu");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$ligne, "Ven");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$ligne, "Sam ");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('W'.$ligne, "Dim");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('X'.$ligne, "Lun");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Y'.$ligne, "Mar");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z'.$ligne, "Mer");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA'.$ligne, "Jeu");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AB'.$ligne, "Ven");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AC'.$ligne, "Sam ");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AD'.$ligne, "Dim");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AE'.$ligne, "Lun");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AF'.$ligne, "Mar");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AG'.$ligne, "Mer");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AH'.$ligne, "Jeu");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AI'.$ligne, "Ven");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AJ'.$ligne, "Sam ");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AK'.$ligne, "Dim");	
		
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AL'.$ligne, "Lun");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AM'.$ligne, "Mar");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AN'.$ligne, "Mer");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AO'.$ligne, "Jeu");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AP'.$ligne, "Ven");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AQ'.$ligne, "Sam ");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AR'.$ligne, "Dim");
		$objPHPExcel->getActiveSheet()->mergeCells("AS5:AS".$ligne);		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AS5', "Total");
		
		$ligne++;
		$colonne=array();
		$colonne[1]='C';
		$colonne[2]='D';
		$colonne[3]='E';
		$colonne[4]='F';
		$colonne[5]='G';
		$colonne[6]='H';
		$colonne[7]='I';
		$colonne[8]='J';
		$colonne[9]='K';
		$colonne[10]='L';
		$colonne[11]='M';
		$colonne[12]='N';
		$colonne[13]='O';
		$colonne[14]='P';
		$colonne[15]='Q';
		$colonne[16]='R';
		$colonne[17]='S'; 
		$colonne[18]='T';
		$colonne[19]='U';
		$colonne[20]='V';
		$colonne[21]='W';
		$colonne[22]='X';
		$colonne[23]='Y';
		$colonne[24]='Z';
		$colonne[25]='AA';
		$colonne[26]='AB';
		$colonne[27]='AC';
		$colonne[28]='AD';
		$colonne[29]='AE';
		$colonne[30]='AF';
		$colonne[31]='AG';
		$colonne[32]='AH';
		$colonne[33]='AI';
		$colonne[34]='AJ';
		$colonne[35]='AK';
		$colonne[36]='AL';
		$colonne[37]='AM';
		$colonne[38]='AN';
		$c=1;
		$lignecontenu=$ligne;
		$nbrDate= count($date);
		$lignefin=0;$cool = array();
		foreach ($date as $dat => $valuedate) {			
			foreach ($data[$valuedate] as $key => $valu) {
				if($valuedate==$annee."-".$mois."-01")
				{	$max_echantillon=' ';
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$lignecontenu, $valu['unite_peche']);

					if(isset($valu['max_echan_enque']))
					{	
						$max_echantillon=$valu['max_echan_enque'];						
					}

					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$lignecontenu, $max_echantillon);
					setlocale(LC_TIME, "fr_FR");
				    $d= strftime("%A", strtotime($annee."-".$mois."-01"));
				    $d = strtolower($d);
				    if ($valu['nombre']==0) {
				    	$valu['nombre']='';
				    }
					/*if($valu['nombre']>0)
					{*/
						switch ($d)
						{
							case 'lundi':
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colonne[$c].$lignecontenu, $valu['nombre']);
								break;
							case 'mardi':
								$c=2;
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colonne[$c].$lignecontenu, $valu['nombre']);
								break;
							case 'Mercredi':
								$c=3;
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colonne[$c].$lignecontenu, $valu['nombre']);
								break;
							case 'jeudi':
								$c=4;
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colonne[$c].$lignecontenu, $valu['nombre']);
								break;
							case 'vendredi':
								$c=5;
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colonne[$c].$lignecontenu, $valu['nombre']);
								break;
							case 'samedi':
								$c=6;
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colonne[$c].$lignecontenu, $valu['nombre']);
								break;
							case 'dimanche':
								$c=7;
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colonne[$c].$lignecontenu, $valu['nombre']);
								break;
							case 'monday':
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colonne[$c].$lignecontenu, $valu['nombre']);
								break;
							case 'tuesday':
								$c=2;
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colonne[$c].$lignecontenu, $valu['nombre']);
								break;
							case 'wednesday':
								$c=3;
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colonne[$c].$lignecontenu, $valu['nombre']);
								break;
							case 'thursday':
								$c=4;
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colonne[$c].$lignecontenu, $valu['nombre']);
								break;
							case 'friday':
								$c=5;
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colonne[$c].$lignecontenu, $valu['nombre']);
								break;
							case 'saturday':
								$c=6;
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colonne[$c].$lignecontenu, $valu['nombre']);
								break;
							case 'sunday':
								$c=7;
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colonne[$c].$lignecontenu, $valu['nombre']);
								break;
						}

						
					//}	
				
				}
				if($valuedate!=$annee."-".$mois."-01")
				{
					if($valu['nombre']>0)
					{
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colonne[$c].$lignecontenu, $valu['nombre']);				
					}
				}
				if($dat==$nbrDate)
				{
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AS'.$lignecontenu, $valu['total_echan_mois']);
				}
				$objPHPExcel->getActiveSheet()->getStyle("A".$lignecontenu.":AS".$lignecontenu)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle("A".$lignecontenu.":AS".$lignecontenu)->applyFromArray($stylecontenu);
				$lignecontenu++; $cool[$dat][$key]	=$c	;		
			}
			$lignefin=$lignecontenu;
			$lignecontenu=$ligne;
			$c++;
		}
		$ligne = intval($lignefin)+1;
		$objPHPExcel->getActiveSheet()->mergeCells("A".$ligne.":AR".$ligne);		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ligne, 'TOTAL');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AS'.$ligne, $sum_total);
		$objPHPExcel->getActiveSheet()->getStyle("A".$ligne.":AS".$ligne)->applyFromArray($stylecontenu);

		try
		{
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		    $objWriter->save(dirname(__FILE__) . "/../../../../../../assets/excel/fiche_suivi/".$nom_file.".xlsx");
		    
		    $this->response([
                'status' => TRUE,
                'response' => $nom_file.".xlsx",
                'resp' => $data,
                //'res' => $week,
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