<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//harizo
// afaka fafana refa ts ilaina
// require APPPATH . '/libraries/REST_Controller.php';

class Importbddaccess extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('importbddaccess_model', 'ImportbddaccessManager');
    }
	public function importerbasededonnees() {
		require_once 'Classes/PHPExcel.php';
		require_once 'Classes/PHPExcel/IOFactory.php';
		set_time_limit(0);
		ini_set ('memory_limit', '1024M');
		$repertoire= $_POST['repertoire'];
		$chemin=dirname(__FILE__) . "/../../../../".$repertoire;
		// Attention :: faut mettre en commentaire la fonction RAZ_Table_a_importer si on devrait importer toutes les données
		// ce n'est qu'un exemple et c'est pour cela qu'on reinitialise les tables avant d'importer quoi que ce soit
		//$inona=$this->ImportbddaccessManager->RAZ_Table_a_importer();
		$lien_vers_mon_document_excel = $chemin . "enqueteur.xlsx";
		if(strpos($lien_vers_mon_document_excel,"xlsx") >0) {
			// pour mise à jour après : G4 = id_fiche_paiement <=> déjà importé => à ignorer
			$objet_read_write = PHPExcel_IOFactory::createReader('Excel2007');
			$excel = $objet_read_write->load($lien_vers_mon_document_excel);			 
			$sheet = $excel->getSheet(0);
			// pour lecture début - fin seulement
			$XLSXDocument = new PHPExcel_Reader_Excel2007();
		} else {
			$objet_read_write = PHPExcel_IOFactory::createReader('Excel2007');
			$excel = $objet_read_write->load($lien_vers_mon_document_excel);			 
			$sheet = $excel->getSheet(0);
			$XLSXDocument = new PHPExcel_Reader_Excel5();
		}
		$Excel = $XLSXDocument->load($lien_vers_mon_document_excel);
		// get all the row of my file
		$rowIterator = $Excel->getActiveSheet()->getRowIterator();
		$remplacer=array("'");
		$trouver= array("’");
		$remplacer=array('&eacute;','e','e','a','o','c','_');
		$trouver= array('é','è','ê','à','ö','ç',' ');
		$liste_retour=array();
		$liste_id_enqueteur=array();
		$iter=0;
		foreach($rowIterator as $row) {
			 $ligne = $row->getRowIndex ();
			if($ligne >=2) {
				 $cellIterator = $row->getCellIterator();
				 // Loop all cells, even if it is not set
				 $cellIterator->setIterateOnlyExistingCells(false);
				 $rowIndex = $row->getRowIndex ();
				 $a_inserer =0;
				foreach ($cellIterator as $cell) {
					 if('A' == $cell->getColumn()) {
							$nom =$cell->getValue();
							$nom_original =$cell->getValue();
					 } 
				}
				$nom = strtolower($nom);
				$retour=$this->ImportbddaccessManager->AjouterEnqueteur($nom,$nom_original);
				$id_enqueteur=99999999;
				$nom_original="";
				foreach($retour as $k=>$v) {
					if($k==0) {
						$id_enqueteur=$v->id;
						$nom_original=$v->nom;
					}
				}
				$liste_id_enqueteur[$iter]["id"]=$id_enqueteur;
				$liste_id_enqueteur[$iter]["nom"]=$nom_original;
				$iter=$iter + 1;
			}		
		}	
		// FERMETURE FICHIER : désallocation mémoire
		unset($Excel);
		unset($objet_read_write);		
		$lien_vers_mon_document_excel = $chemin . "site_embarquement.xlsx";
		if(strpos($lien_vers_mon_document_excel,"xlsx") >0) {
			// pour mise à jour après : G4 = id_fiche_paiement <=> déjà importé => à ignorer
			$objet_read_write = PHPExcel_IOFactory::createReader('Excel2007');
			$excel = $objet_read_write->load($lien_vers_mon_document_excel);			 
			$sheet = $excel->getSheet(0);
			// pour lecture début - fin seulement
			$XLSXDocument = new PHPExcel_Reader_Excel2007();
		} else {
			$objet_read_write = PHPExcel_IOFactory::createReader('Excel2007');
			$excel = $objet_read_write->load($lien_vers_mon_document_excel);			 
			$sheet = $excel->getSheet(0);
			$XLSXDocument = new PHPExcel_Reader_Excel5();
		}
		$Excel = $XLSXDocument->load($lien_vers_mon_document_excel);
		// get all the row of my file
		$rowIterator = $Excel->getActiveSheet()->getRowIterator();
		$remplacer=array("'");
		$trouver= array("’");
		$remplacer=array('&eacute;','e','e','a','o','c','_');
		$trouver= array('é','è','ê','à','ö','ç',' ');
		$liste_id_site=array();
		$iter=0;
		foreach($rowIterator as $row) {
			$ligne = $row->getRowIndex ();
			if($ligne >=2) {
				 $cellIterator = $row->getCellIterator();
				 // Loop all cells, even if it is not set
				 $cellIterator->setIterateOnlyExistingCells(false);
				 $rowIndex = $row->getRowIndex ();
				 $a_inserer =0;
				foreach ($cellIterator as $cell) {
					 if('C' == $cell->getColumn()) {
						$libelle =$cell->getValue();
						$libelle_original =$cell->getValue();
					 } else	 if('D' == $cell->getColumn()) {
						$code =$cell->getValue();
					 } else	 if('F' == $cell->getColumn()) {
						$code_unique =$cell->getValue();
					 } else	 if('B' == $cell->getColumn()) {
						$region =$cell->getValue();
					 } 
				}
				$libelle = strtolower($libelle);
				$tadiavo=array("'");
				$soloy=array("\''");
				$code_uniques=str_replace($tadiavo,$soloy,$code_unique);
				$region=trim($region);
				$retour=$this->ImportbddaccessManager->AjouterSiteEmbarquement($libelle,$libelle_original,$code,$code_unique,$region);
				$id_site=99999999;
				$id_region=99999999;
				foreach($retour as $k=>$v) {
					if($k==0) {
						$id_site=$v->id;
						$id_region=$v->id_region;
						$region=$v->region;
					}
				}
				$liste_id_site[$iter]["id"]=$id_site;
				$liste_id_site[$iter]["libelle"]=$libelle_original;
				$liste_id_site[$iter]["code"]=$code;
				$liste_id_site[$iter]["code_unique"]=$code_unique;
				$liste_id_site[$iter]["id_region"]=$id_region;
				$liste_id_site[$iter]["region"]=$region;
				$iter=$iter + 1;
			}		
		}	
		// FERMETURE FICHIER : désallocation mémoire
		unset($Excel);
		unset($objet_read_write);	
		$lien_vers_mon_document_excel = $chemin . "type_engin.xlsx";
		if(strpos($lien_vers_mon_document_excel,"xlsx") >0) {
			// pour mise à jour après : G4 = id_fiche_paiement <=> déjà importé => à ignorer
			$objet_read_write = PHPExcel_IOFactory::createReader('Excel2007');
			$excel = $objet_read_write->load($lien_vers_mon_document_excel);			 
			$sheet = $excel->getSheet(0);
			// pour lecture début - fin seulement
			$XLSXDocument = new PHPExcel_Reader_Excel2007();
		} else {
			$objet_read_write = PHPExcel_IOFactory::createReader('Excel2007');
			$excel = $objet_read_write->load($lien_vers_mon_document_excel);			 
			$sheet = $excel->getSheet(0);
			$XLSXDocument = new PHPExcel_Reader_Excel5();
		}
		$Excel = $XLSXDocument->load($lien_vers_mon_document_excel);
		// get all the row of my file
		$rowIterator = $Excel->getActiveSheet()->getRowIterator();
		$remplacer=array("'");
		$trouver= array("’");
		$remplacer=array('&eacute;','e','e','a','o','c','_');
		$trouver= array('é','è','ê','à','ö','ç',' ');
		$liste_type_engin=array();
		$iter=0;
		foreach($rowIterator as $row) {
			$ligne = $row->getRowIndex ();
			if($ligne >=2) {
				 $cellIterator = $row->getCellIterator();
				 // Loop all cells, even if it is not set
				 $cellIterator->setIterateOnlyExistingCells(false);
				 $rowIndex = $row->getRowIndex ();
				 $a_inserer =0;
				foreach ($cellIterator as $cell) {
					 if('C' == $cell->getColumn()) {
						$libelle =$cell->getValue();
						$libelle_original =$cell->getValue();
					 } else	 if('A' == $cell->getColumn()) {
						$code =$cell->getValue();
					 } 
				}
				$libelle = strtolower($libelle);
				$retour=$this->ImportbddaccessManager->AjouterTypeEngin($libelle,$libelle_original,$code);
				$id_engin=99999999;
				$code="";
				$libelle="";
				foreach($retour as $k=>$v) {
					if($k==0) {
						$id_engin=$v->id;
						$code=$v->code;
						$libelle=$v->libelle;
					}
				}
				$liste_type_engin[$iter]["id"]=$id_engin;
				$liste_type_engin[$iter]["code"]=$code;
				$liste_type_engin[$iter]["libelle"]=$libelle;
				$iter=$iter + 1;
			}		
		}	
		// FERMETURE FICHIER : désallocation mémoire
		unset($Excel);
		unset($objet_read_write);		
		$lien_vers_mon_document_excel = $chemin . "type_canoe.xlsx";
		if(strpos($lien_vers_mon_document_excel,"xlsx") >0) {
			// pour mise à jour après : G4 = id_fiche_paiement <=> déjà importé => à ignorer
			$objet_read_write = PHPExcel_IOFactory::createReader('Excel2007');
			$excel = $objet_read_write->load($lien_vers_mon_document_excel);			 
			$sheet = $excel->getSheet(0);
			// pour lecture début - fin seulement
			$XLSXDocument = new PHPExcel_Reader_Excel2007();
		} else {
			$objet_read_write = PHPExcel_IOFactory::createReader('Excel2007');
			$excel = $objet_read_write->load($lien_vers_mon_document_excel);			 
			$sheet = $excel->getSheet(0);
			$XLSXDocument = new PHPExcel_Reader_Excel5();
		}
		$Excel = $XLSXDocument->load($lien_vers_mon_document_excel);
		// get all the row of my file
		$rowIterator = $Excel->getActiveSheet()->getRowIterator();
		$remplacer=array("'");
		$trouver= array("’");
		$remplacer=array('&eacute;','e','e','a','o','c','_');
		$trouver= array('é','è','ê','à','ö','ç',' ');
		$liste_type_canoe=array();
		$iter=0;
		foreach($rowIterator as $row) {
			$ligne = $row->getRowIndex ();
			if($ligne >=2) {
				 $cellIterator = $row->getCellIterator();
				 // Loop all cells, even if it is not set
				 $cellIterator->setIterateOnlyExistingCells(false);
				 $rowIndex = $row->getRowIndex ();
				 $a_inserer =0;
				foreach ($cellIterator as $cell) {
					 if('C' == $cell->getColumn()) {
						$nom =$cell->getValue();
						$nom_original =$cell->getValue();
					 } else	 if('A' == $cell->getColumn()) {
						$code =$cell->getValue();
					 } 
				}
				$nom = strtolower($nom);
				$retour=$this->ImportbddaccessManager->AjouterTypeCanoe($nom,$nom_original,$code);
				$id_canoe=99999999;
				$code="";
				$nom="";
				foreach($retour as $k=>$v) {
					if($k==0) {
						$id_canoe=$v->id;
						$code=$v->code;
						$nom=$v->nom;
					}
				}
				$liste_type_canoe[$iter]["id"]=$id_canoe;
				$liste_type_canoe[$iter]["code"]=$code;
				$liste_type_canoe[$iter]["nom"]=$nom;
				$iter=$iter + 1;
			}		
		}	
		// FERMETURE FICHIER : désallocation mémoire
		unset($Excel);
		unset($objet_read_write);		
		$lien_vers_mon_document_excel = $chemin . "espece.xlsx";
		if(strpos($lien_vers_mon_document_excel,"xlsx") >0) {
			// pour mise à jour après : G4 = id_fiche_paiement <=> déjà importé => à ignorer
			$objet_read_write = PHPExcel_IOFactory::createReader('Excel2007');
			$excel = $objet_read_write->load($lien_vers_mon_document_excel);			 
			$sheet = $excel->getSheet(0);
			// pour lecture début - fin seulement
			$XLSXDocument = new PHPExcel_Reader_Excel2007();
		} else {
			$objet_read_write = PHPExcel_IOFactory::createReader('Excel2007');
			$excel = $objet_read_write->load($lien_vers_mon_document_excel);			 
			$sheet = $excel->getSheet(0);
			$XLSXDocument = new PHPExcel_Reader_Excel5();
		}
		$Excel = $XLSXDocument->load($lien_vers_mon_document_excel);
		// get all the row of my file
		$rowIterator = $Excel->getActiveSheet()->getRowIterator();
		$remplacer=array("'");
		$trouver= array("’");
		$remplacer=array('&eacute;','e','e','a','o','c','_');
		$trouver= array('é','è','ê','à','ö','ç',' ');
		$liste_espece=array();
		$iter=0;
		foreach($rowIterator as $row) {
			$ligne = $row->getRowIndex ();
			if($ligne >=2) {
				 $cellIterator = $row->getCellIterator();
				 // Loop all cells, even if it is not set
				 $cellIterator->setIterateOnlyExistingCells(false);
				 $rowIndex = $row->getRowIndex ();
				 $a_inserer =0;
				foreach ($cellIterator as $cell) {
					 if('A' == $cell->getColumn()) {
						$nom_local =$cell->getValue();
						$nom_local_original =$cell->getValue();
					 } else	 if('B' == $cell->getColumn()) {
						$nom_scientifique =$cell->getValue();
					 } else	 if('C' == $cell->getColumn()) {
						$code =$cell->getValue();
					 } 
				}
				$nom_local = strtolower($nom_local);
				$retour=$this->ImportbddaccessManager->AjouterEspece($nom_local,$nom_local_original,$nom_scientifique,$code);
				$id_espece=99999999;
				$code="";
				$nom_local="";
				$nom_scientifique="";
				foreach($retour as $k=>$v) {
					if($k==0) {
						$id_espece=$v->id;
						$code=$v->code;
						$nom_local=$v->nom_local;
						$nom_scientifique=$v->nom_scientifique;
					}
				}
				$liste_espece[$iter]["id"]=$id_espece;
				$liste_espece[$iter]["code"]=$code;
				$liste_espece[$iter]["nom_local"]=$nom_local;
				$liste_espece[$iter]["nom_scientifique"]=$nom_scientifique;
				$iter=$iter + 1;
			}		
		}	
		// FERMETURE FICHIER : désallocation mémoire
		unset($Excel);
		unset($objet_read_write);		
		$lien_vers_mon_document_excel = $chemin . "enqueteur.xlsx";
		if(strpos($lien_vers_mon_document_excel,"xlsx") >0) {
			// pour mise à jour après : G4 = id_fiche_paiement <=> déjà importé => à ignorer
			$objet_read_write = PHPExcel_IOFactory::createReader('Excel2007');
			$excel = $objet_read_write->load($lien_vers_mon_document_excel);			 
			$sheet = $excel->getSheet(0);
			// pour lecture début - fin seulement
			$XLSXDocument = new PHPExcel_Reader_Excel2007();
		} else {
			$objet_read_write = PHPExcel_IOFactory::createReader('Excel2007');
			$excel = $objet_read_write->load($lien_vers_mon_document_excel);			 
			$sheet = $excel->getSheet(0);
			$XLSXDocument = new PHPExcel_Reader_Excel5();
		}
		$Excel = $XLSXDocument->load($lien_vers_mon_document_excel);
		// get all the row of my file
		$rowIterator = $Excel->getActiveSheet()->getRowIterator();
		$remplacer=array("'");
		$trouver= array("’");
		$remplacer=array('&eacute;','e','e','a','o','c','_');
		$trouver= array('é','è','ê','à','ö','ç',' ');
		$liste_site_enqueteur=array();
		$iter=0;
		foreach($rowIterator as $row) {
			 $ligne = $row->getRowIndex ();
			if($ligne >=2) {
				 $cellIterator = $row->getCellIterator();
				 // Loop all cells, even if it is not set
				 $cellIterator->setIterateOnlyExistingCells(false);
				 $rowIndex = $row->getRowIndex ();
				 $a_inserer =0;
				foreach ($cellIterator as $cell) {
					 if('A' == $cell->getColumn()) {
							$nom =$cell->getValue();
							$nom_original =$cell->getValue();
					 } else if('D' == $cell->getColumn()) {
							$region =$cell->getValue();
							$region_original =$cell->getValue();
					 } else if('E' == $cell->getColumn()) {
							$site =$cell->getValue();
							$site_original =$cell->getValue();
					 }
				}
				$nom = strtolower($nom);
				$region = strtolower($region);
				$site = strtolower($site);
				$remplacer=array("");
				$trouver= array(" ");
				$site_sansespace=str_replace($trouver,$remplacer,$site);
				$retour=$this->ImportbddaccessManager->AjouterSiteEnqueteur($nom,$region,$site,$site_sansespace);
				$id='néant';
				$id_enqueteur=$nom;
				$id_site=$site;
				$ajout='introuvable';
				if($retour) {
					foreach($retour as $k=>$v) {
						if($k==0) {
							$id=$v->id;
							$id_enqueteur=$v->id_enqueteur;
							$id_site=$v->id_site;
							$ajout=$v->ajout;
						}
					}
				}	
				$liste_site_enqueteur[$iter]["id"]=$id;
				$liste_site_enqueteur[$iter]["id_site"]=$id_site;
				$liste_site_enqueteur[$iter]["id_enqueteur"]=$id_enqueteur;
				$liste_site_enqueteur[$iter]["nom"]=$nom_original;
				$liste_site_enqueteur[$iter]["site"]=$site_original;
				$liste_site_enqueteur[$iter]["region"]=$region_original;
				$liste_site_enqueteur[$iter]["ajout"]=$ajout;
				$iter=$iter + 1;
			}		
		}	
		// FERMETURE FICHIER : désallocation mémoire
		unset($Excel);
		unset($objet_read_write);
		$lien_vers_mon_document_excel = $chemin . "fiche_echantillonnage_capture.xlsx";
		if(strpos($lien_vers_mon_document_excel,"xlsx") >0) {
			// pour mise à jour après : G4 = id_fiche_paiement <=> déjà importé => à ignorer
			$objet_read_write = PHPExcel_IOFactory::createReader('Excel2007');
			$excel = $objet_read_write->load($lien_vers_mon_document_excel);			 
			$sheet = $excel->getSheet(0);
			// pour lecture début - fin seulement
			$XLSXDocument = new PHPExcel_Reader_Excel2007();
		} else {
			$objet_read_write = PHPExcel_IOFactory::createReader('Excel2007');
			$excel = $objet_read_write->load($lien_vers_mon_document_excel);			 
			$sheet = $excel->getSheet(0);
			$XLSXDocument = new PHPExcel_Reader_Excel5();
		}
		$Excel = $XLSXDocument->load($lien_vers_mon_document_excel);
		// get all the row of my file
		$rowIterator = $Excel->getActiveSheet()->getRowIterator();
		$remplacer=array("'");
		$trouver= array("’");
		$remplacer=array('&eacute;','e','e','a','o','c','_');
		$trouver= array('é','è','ê','à','ö','ç',' ');
		$liste_unite_peche=array();
		$liste_unite_peche_site=array();
		$liste_fiche_echantillonnage_capture=array();
		$iter=0;
		$u_p_s=0;
		$f_e_c=0;
		foreach($rowIterator as $row) {
			 $ligne = $row->getRowIndex ();
			if($ligne >=2) {
				 $cellIterator = $row->getCellIterator();
				 // Loop all cells, even if it is not set
				 $cellIterator->setIterateOnlyExistingCells(false);
				 $rowIndex = $row->getRowIndex ();
				 $a_inserer =0;
				foreach ($cellIterator as $cell) {
					 if('G' == $cell->getColumn()) {
						$region =$cell->getValue();
						$region_original =$cell->getValue();
					 } else if('B' == $cell->getColumn()) {
						$unique_code =$cell->getValue();
					 } else if('C' == $cell->getColumn()) {
						$validation =$cell->getValue();
					 } else if('D' == $cell->getColumn()) {
						$date_peche =$cell->getValue();
						if(isset($date_peche) && $date_peche>"") {
							if(PHPExcel_Shared_Date::isDateTime($cell)) {
								 $date_peche = date($format='Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date_peche)); 
							}
						} else {
							$date_peche=null;
						}	
					 } else if('H' == $cell->getColumn()) {
						$site =$cell->getValue();
						$site_original =$cell->getValue();
					 } else if('I' == $cell->getColumn()) {
						$enqueteur =$cell->getValue();
					 } else if('J' == $cell->getColumn()) {
						$engin_canoe =$cell->getValue();
					 } else if('M' == $cell->getColumn()) {
						$peche_hier =$cell->getValue();
					 } else if('N' == $cell->getColumn()) {
						$peche_avant_hier =$cell->getValue();
					 } else if('O' == $cell->getColumn()) {
						$nbr_jrs_peche_dernier_sem =$cell->getValue();
					 }
				}
				$region = strtolower($region);
				$site = strtolower($site);
				$remplacer=array("");
				$trouver= array(" ");
				$site_sansespace=str_replace($trouver,$remplacer,$site);
				$retour=$this->ImportbddaccessManager->AjouterUnitePeche($region,$site,$site_sansespace,$engin_canoe);
				$id='néant';
				$id_site=$site;
				$id_region=null;
				$ajout='introuvable';
				$entre_engin="tsy niditra ENGIN";
				$entre_canoe="tsy niditra CANOE";
				if($retour) {
					foreach($retour as $k=>$v) {
						if($k==0) {
							$id=$v->id;
							$id_type_canoe=$v->id_type_canoe;
							$id_type_engin=$v->id_type_engin;
							$id_region=$v->id_region;
							$id_site_embarquement=$v->id_site_embarquement;
							$code_canoe=$v->code_canoe;
							$nom_canoe=$v->nom_canoe;
							$code_engin=$v->code_engin;
							$libelle_engin=$v->libelle_engin;
							$ajout_engin=$v->ajout_engin;
							$ajout_canoe=$v->ajout_canoe;
							$ajout=$v->ajout;
							if($ajout_canoe=="ajout") {
								$nombre_canoe=count($liste_type_canoe);
								$liste_type_canoe[$nombre_canoe]["id"]=$id_type_canoe;
								$liste_type_canoe[$nombre_canoe]["code"]=$code_canoe;
								$liste_type_canoe[$nombre_canoe]["nom"]=$nom_canoe;
								$entre_canoe="tao ka";
							}
							if($ajout_engin=="ajout") {
								$nombre_canoe=count($liste_type_engin);
								$liste_type_engin[$nombre_canoe]["id"]=$id_type_engin;
								$liste_type_engin[$nombre_canoe]["code"]=$code_engin;
								$liste_type_engin[$nombre_canoe]["libelle"]=$libelle_engin;
								$entre_engin="tao izy ka";
							}
						}
					}
				}	
				$inexistant=true;
				if($liste_unite_peche) {
					foreach($liste_unite_peche as $k=>$v) {
						if($v["id"]==$id) {
							$inexistant=false;
						}
					}	
				}
				if($inexistant==true) {
					$liste_unite_peche[$iter]["id"]=$id;
					$liste_unite_peche[$iter]["id_type_canoe"]=$id_type_canoe;
					$liste_unite_peche[$iter]["id_type_engin"]=$id_type_engin;
					$liste_unite_peche[$iter]["id_site_embarquement"]=$id_site_embarquement;
					$liste_unite_peche[$iter]["site"]=$site_original;
					$liste_unite_peche[$iter]["region"]=$region_original;
					$liste_unite_peche[$iter]["libelle"]=$engin_canoe;
					$liste_unite_peche[$iter]["ajout"]=$ajout;
					$liste_unite_peche[$iter]["ajout_canoe"]=$ajout_canoe;
					$liste_unite_peche[$iter]["code_canoe"]=$code_canoe;
					$liste_unite_peche[$iter]["nom_canoe"]=$nom_canoe;
					$liste_unite_peche[$iter]["ajout_engin"]=$ajout_engin;
					$liste_unite_peche[$iter]["code_engin"]=$code_engin;
					$liste_unite_peche[$iter]["libelle_engin"]=$libelle_engin;
					$liste_unite_peche[$iter]["niditra_engin"]=$entre_engin;
					$liste_unite_peche[$iter]["niditra_canoe"]=$entre_canoe;					
					$iter=$iter + 1;
				}
				// Table unite_peche_site
				$id_unite_peche =$id;
				$retour=$this->ImportbddaccessManager->AjouterUnitePecheSite($id_unite_peche,$id_site_embarquement);
				$id="néant";
				$libelle="";
				$id_unite_peche=null;
				if($retour) {
					foreach($retour as $k=>$v) {
						if($k==0) {
							$id=$v->id;
							$id_site_embarquement=$v->id_site_embarquement;
							$id_unite_peche=$v->id_unite_peche;
							$libelle=$v->libelle;
						}
					}	
				}	
				$inexistant=true;
				if($liste_unite_peche_site) {
					foreach($liste_unite_peche_site as $k=>$v) {
						if($v["id"]==$id) {
							$inexistant=false;
						}
					}	
				}
				if($inexistant==true) {
					$liste_unite_peche_site[$u_p_s]["id"]=$id;
					$liste_unite_peche_site[$u_p_s]["id_unite_peche"]=$id_unite_peche;
					$liste_unite_peche_site[$u_p_s]["id_site_embarquement"]=$id_site_embarquement;
					$liste_unite_peche_site[$u_p_s]["site"]=$site_original;
					$liste_unite_peche_site[$u_p_s]["region"]=$region_original;
					$liste_unite_peche_site[$u_p_s]["libelle"]=$libelle;
					$liste_unite_peche_site[$u_p_s]["ajout"]=$ajout;
					$u_p_s=$u_p_s + 1;
				}
			}		
		}	
		// FERMETURE FICHIER : désallocation mémoire
		unset($Excel);
		unset($objet_read_write);
		$lien_vers_mon_document_excel = $chemin . "fiche_echantillonnage_capture.xlsx";
		if(strpos($lien_vers_mon_document_excel,"xlsx") >0) {
			// pour mise à jour après : G4 = id_fiche_paiement <=> déjà importé => à ignorer
			$objet_read_write = PHPExcel_IOFactory::createReader('Excel2007');
			$excel = $objet_read_write->load($lien_vers_mon_document_excel);			 
			$sheet = $excel->getSheet(0);
			// pour lecture début - fin seulement
			$XLSXDocument = new PHPExcel_Reader_Excel2007();
		} else {
			$objet_read_write = PHPExcel_IOFactory::createReader('Excel2007');
			$excel = $objet_read_write->load($lien_vers_mon_document_excel);			 
			$sheet = $excel->getSheet(0);
			$XLSXDocument = new PHPExcel_Reader_Excel5();
		}
		$Excel = $XLSXDocument->load($lien_vers_mon_document_excel);
		// get all the row of my file
		$rowIterator = $Excel->getActiveSheet()->getRowIterator();
		$remplacer=array("'");
		$trouver= array("’");
		$remplacer=array('&eacute;','e','e','a','o','c','_');
		$trouver= array('é','è','ê','à','ö','ç',' ');
		$liste_unite_peche=array();
		$liste_unite_peche_site=array();
		$liste_fiche_echantillonnage_capture=array();
		$iter=0;
		$u_p_s=0;
		$f_e_c=0;
		foreach($rowIterator as $row) {
			 $ligne = $row->getRowIndex ();
			if($ligne >=2) {
				 $cellIterator = $row->getCellIterator();
				 // Loop all cells, even if it is not set
				 $cellIterator->setIterateOnlyExistingCells(false);
				 $rowIndex = $row->getRowIndex ();
				 $a_inserer =0;
				foreach ($cellIterator as $cell) {
					 if('G' == $cell->getColumn()) {
						$region =$cell->getValue();
						$region_original =$cell->getValue();
					 } else if('B' == $cell->getColumn()) {
						$unique_code =$cell->getValue();
					 } else if('C' == $cell->getColumn()) {
						$validation =$cell->getValue();
					 } else if('D' == $cell->getColumn()) {
						$date_peche =$cell->getValue();
						if(isset($date_peche) && $date_peche>"") {
							if(PHPExcel_Shared_Date::isDateTime($cell)) {
								 $date_peche = date($format='Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date_peche)); 
							}
						} else {
							$date_peche=null;
						}	
					 } else if('H' == $cell->getColumn()) {
						$site =$cell->getValue();
						$site_original =$cell->getValue();
					 } else if('I' == $cell->getColumn()) {
						$enqueteur =$cell->getValue();
					 } else if('J' == $cell->getColumn()) {
						$engin_canoe =$cell->getValue();
					 } else if('M' == $cell->getColumn()) {
						$peche_hier =$cell->getValue();
					 } else if('N' == $cell->getColumn()) {
						$peche_avant_hier =$cell->getValue();
					 } else if('O' == $cell->getColumn()) {
						$nbr_jrs_peche_dernier_sem =$cell->getValue();
					 }
				}
				$site = strtolower($site);
				$remplacer=array("");
				$trouver= array(" ");
				$site_sansespace=str_replace($trouver,$remplacer,$site);
				// Table fiche_echantillonnage_capture
				if($validation=="VRAI") {
					$validation=1;
				} else {
					$validation=0;
				}
				$xx  = date($date_peche);
				$date_code_unique  = date($date_peche);
				//$date_code_unique= $xx->format("d/m/Y");		
				$retour=$this->ImportbddaccessManager->AjouterFiche_Echantillonnage_Capture($id_unite_peche,$unique_code,$date_peche,$id_site_embarquement,$enqueteur,$id_region,$validation,$region,$site_original,$peche_hier,$peche_avant_hier,$nbr_jrs_peche_dernier_sem,$date_code_unique,$site,$site_sansespace);
				foreach($retour as $k=>$v) {
					if($k==0) {
						$id=$v->id;
						$code_unique=$v->code_unique;
						$date=$v->date;
						$id_site_embarquement=$v->id_site_embarquement;
						$id_enqueteur=$v->id_enqueteur;
						$id_region=$v->id_region;
						$date_creation=$v->date_creation;
						$validation=$v->validation;
						$ajout=$v->ajout;
					}
				}
				if($ajout=='ajout') {
					$liste_fiche_echantillonnage_capture[$f_e_c]["id"]=$id;
					$liste_fiche_echantillonnage_capture[$f_e_c]["code_unique"]=$code_unique;
					$liste_fiche_echantillonnage_capture[$f_e_c]["date"]=$date;
					$liste_fiche_echantillonnage_capture[$f_e_c]["site"]=$site_original;
					$liste_fiche_echantillonnage_capture[$f_e_c]["id_site_embarquement"]=$id_site_embarquement;
					$liste_fiche_echantillonnage_capture[$f_e_c]["enqueteur"]=$enqueteur;
					$liste_fiche_echantillonnage_capture[$f_e_c]["id_enqueteur"]=$id_enqueteur;
					$liste_fiche_echantillonnage_capture[$f_e_c]["id_region"]=$id_region;
					$liste_fiche_echantillonnage_capture[$f_e_c]["validation"]=$validation;
					$f_e_c=$f_e_c + 1;
				}				
			}		
		}	
		// FERMETURE FICHIER : désallocation mémoire
		unset($Excel);
		unset($objet_read_write);
		$lien_vers_mon_document_excel = $chemin . "espece_capture.xlsx";
		if(strpos($lien_vers_mon_document_excel,"xlsx") >0) {
			// pour mise à jour après : G4 = id_fiche_paiement <=> déjà importé => à ignorer
			$objet_read_write = PHPExcel_IOFactory::createReader('Excel2007');
			$excel = $objet_read_write->load($lien_vers_mon_document_excel);			 
			$sheet = $excel->getSheet(0);
			// pour lecture début - fin seulement
			$XLSXDocument = new PHPExcel_Reader_Excel2007();
		} else {
			$objet_read_write = PHPExcel_IOFactory::createReader('Excel2007');
			$excel = $objet_read_write->load($lien_vers_mon_document_excel);			 
			$sheet = $excel->getSheet(0);
			$XLSXDocument = new PHPExcel_Reader_Excel5();
		}
		$Excel = $XLSXDocument->load($lien_vers_mon_document_excel);
		// get all the row of my file
		$rowIterator = $Excel->getActiveSheet()->getRowIterator();
		$remplacer=array("'");
		$trouver= array("’");
		$remplacer=array('&eacute;','e','e','a','o','c','_');
		$trouver= array('é','è','ê','à','ö','ç',' ');
		$liste_espece_capture=array();
		$e_c=0;
		foreach($rowIterator as $row) {
			 $ligne = $row->getRowIndex ();
			if($ligne >=2) {
				 $cellIterator = $row->getCellIterator();
				 // Loop all cells, even if it is not set
				 $cellIterator->setIterateOnlyExistingCells(false);
				 $rowIndex = $row->getRowIndex ();
				 $a_inserer =0;
				foreach ($cellIterator as $cell) {
					 if('A' == $cell->getColumn()) {
						$code_unique =$cell->getValue();
					 } else if('C' == $cell->getColumn()) {
						$code_unique_espece =$cell->getValue();
					 } else if('D' == $cell->getColumn()) {
						$Code3Alpha =$cell->getValue();
					 } else if('E' == $cell->getColumn()) {
						$Catch =$cell->getValue();
					 } else if('F' == $cell->getColumn()) {
						$Price =$cell->getValue();
					 } else if('G' == $cell->getColumn()) {
						$Total_Value =$cell->getValue();
					 } else if('H' == $cell->getColumn()) {
						$validation =$cell->getValue();
					 }
				}
				if($validation=="VRAI") {
					$validation=1;
				} else {
					$validation=0;
				}			
				$retour=$this->ImportbddaccessManager->AjouterEspeceCapture($code_unique,$Code3Alpha,$Catch,$Price,$Total_Value,$validation);
				foreach($retour as $k=>$v) {
					if($k==0) {
						$id=$v->id;
						$id_fiche_echantillonnage_capture=$v->id_fiche_echantillonnage_capture;
						$id_echantillon=$v->id_echantillon;
						$id_espece=$v->id_espece;
						$capture=$v->capture;
						$prix=$v->prix;
						$date_creation=$v->date_creation;
						$validation=$v->validation;
					}
				}
					$liste_espece_capture[$e_c]["id"]=$id;
					$liste_espece_capture[$e_c]["id_fiche_echantillonnage_capture"]=$id_fiche_echantillonnage_capture;
					$liste_espece_capture[$e_c]["id_echantillon"]=$id_echantillon;
					$liste_espece_capture[$e_c]["id_espece"]=$id_espece;
					$liste_espece_capture[$e_c]["capture"]=$capture;
					$liste_espece_capture[$e_c]["prix"]=$prix;
					$liste_espece_capture[$e_c]["date_creation"]=$date_creation;
					$liste_espece_capture[$e_c]["validation"]=$validation;
					$e_c=$e_c + 1;
			}		
		} 
		$lien_vers_mon_document_excel = $chemin . "fiche_echantillonnage_capturecab.xlsx";
		if(strpos($lien_vers_mon_document_excel,"xlsx") >0) {
			// pour mise à jour après : G4 = id_fiche_paiement <=> déjà importé => à ignorer
			$objet_read_write = PHPExcel_IOFactory::createReader('Excel2007');
			$excel = $objet_read_write->load($lien_vers_mon_document_excel);			 
			$sheet = $excel->getSheet(0);
			// pour lecture début - fin seulement
			$XLSXDocument = new PHPExcel_Reader_Excel2007();
		} else {
			$objet_read_write = PHPExcel_IOFactory::createReader('Excel2007');
			$excel = $objet_read_write->load($lien_vers_mon_document_excel);			 
			$sheet = $excel->getSheet(0);
			$XLSXDocument = new PHPExcel_Reader_Excel5();
		}
		$Excel = $XLSXDocument->load($lien_vers_mon_document_excel);
		// get all the row of my file
		$rowIterator = $Excel->getActiveSheet()->getRowIterator();
		$remplacer=array("'");
		$trouver= array("’");
		$remplacer=array('&eacute;','e','e','a','o','c','_');
		$trouver= array('é','è','ê','à','ö','ç',' ');
		$liste_fiche_echantillonnage_capturecab=array();
		$iter=0;
		$u_p_s=0;
		$f_e_c=0;
		foreach($rowIterator as $row) {
			$ligne = $row->getRowIndex ();
			if($ligne >=2) {
				 $cellIterator = $row->getCellIterator();
				 // Loop all cells, even if it is not set
				 $cellIterator->setIterateOnlyExistingCells(false);
				 $rowIndex = $row->getRowIndex ();
				 $a_inserer =0;
				foreach ($cellIterator as $cell) {
					 if ('B' == $cell->getColumn()) {
						$unique_code =$cell->getValue();
					 } else if('C' == $cell->getColumn()) {
						$date_peche =$cell->getValue();
						if(isset($date_peche) && $date_peche>"") {
							if(PHPExcel_Shared_Date::isDateTime($cell)) {
								 $date_peche = date($format='Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date_peche)); 
							}
						} else {
							$date_peche=null;
						}	
					 } else if('F' == $cell->getColumn()) {
						$region =$cell->getValue();
						$region_original =$cell->getValue();
					 } else if('G' == $cell->getColumn()) {
						$enqueteur =$cell->getValue();
					 } else if('H' == $cell->getColumn()) {
						$site =$cell->getValue();
						$site_original =$cell->getValue();
					 } else if('I' == $cell->getColumn()) {
						$engin_canoe =$cell->getValue();
					 } else if('J' == $cell->getColumn()) {
						$bateau_actif =$cell->getValue();
					 } else if('K' == $cell->getColumn()) {
						$bateau_total =$cell->getValue();
					 } else if('M' == $cell->getColumn()) {
						$validation =$cell->getValue();
					 } 
				}
				$site = strtolower($site);
				$remplacer=array("");
				$trouver= array(" ");
				$site_sansespace=str_replace($trouver,$remplacer,$site);
				// Table fiche_echantillonnage_capture
				if($validation=="VRAI") {
					$validation=1;
				} else {
					$validation=0;
				}
				$xx  = date($date_peche);
				$date_code_unique  = date($date_peche);
				//$date_code_unique= $xx->format("d/m/Y");		
				$retour=$this->ImportbddaccessManager->AjouterFiche_Echantillonnage_CaptureCAB($id_unite_peche,$unique_code,$date_peche,$enqueteur,$id_region,$validation,$region,$site_original,$bateau_actif,$bateau_total,$date_code_unique,$site,$site_sansespace);
				foreach($retour as $k=>$v) {
					if($k==0) {
						$id=$v->id;
						$code_unique=$v->code_unique;
						$date=$v->date;
						$id_site_embarquement=$v->id_site_embarquement;
						$id_enqueteur=$v->id_enqueteur;
						$id_region=$v->id_region;
						$date_creation=$v->date_creation;
						$validation=$v->validation;
						$ajout=$v->ajout;
					}
				}
				if($ajout=='ajout') {
					$liste_fiche_echantillonnage_capturecab[$f_e_c]["id"]=$id;
					$liste_fiche_echantillonnage_capturecab[$f_e_c]["code_unique"]=$code_unique;
					$liste_fiche_echantillonnage_capturecab[$f_e_c]["date"]=$date;
					$liste_fiche_echantillonnage_capturecab[$f_e_c]["site"]=$site_original;
					$liste_fiche_echantillonnage_capturecab[$f_e_c]["id_site_embarquement"]=$id_site_embarquement;
					$liste_fiche_echantillonnage_capturecab[$f_e_c]["enqueteur"]=$enqueteur;
					$liste_fiche_echantillonnage_capturecab[$f_e_c]["id_enqueteur"]=$id_enqueteur;
					$liste_fiche_echantillonnage_capturecab[$f_e_c]["id_region"]=$id_region;
					$liste_fiche_echantillonnage_capturecab[$f_e_c]["validation"]=$validation;
					$f_e_c=$f_e_c + 1;
				}				
			}		
		}	
		
		$liste_retour[0]["enqueteur"] = $liste_id_enqueteur;
		$liste_retour[0]["site_embarquement"] = $liste_id_site;
		$liste_retour[0]["site_enqueteur"] = $liste_site_enqueteur;
		$liste_retour[0]["type_engin"] = $liste_type_engin;
		$liste_retour[0]["type_canoe"] = $liste_type_canoe;
		$liste_retour[0]["espece"] = $liste_espece;
		$liste_retour[0]["unite_peche"] = $liste_unite_peche;
		$liste_retour[0]["unite_peche_site"] = $liste_unite_peche_site;
		$liste_retour[0]["fiche_echantillonnage_capture"] = $liste_fiche_echantillonnage_capture;
		$liste_retour[0]["fiche_echantillonnage_capturecab"] = $liste_fiche_echantillonnage_capturecab;
		// $liste_retour[0]["espece_capture"] = $liste_espece_capture;
		// FERMETURE FICHIER : désallocation mémoire
		unset($Excel);
		unset($objet_read_write);
		echo json_encode($liste_retour);
	}
	public function importerbasededonneesespececapture() {
		require_once 'Classes/PHPExcel.php';
		require_once 'Classes/PHPExcel/IOFactory.php';
		set_time_limit(0);
		$repertoire= $_POST['repertoire'];
		$chemin=dirname(__FILE__) . "/../../../../".$repertoire;
		$lien_vers_mon_document_excel = $chemin . "espece_capture.xlsx";
		if(strpos($lien_vers_mon_document_excel,"xlsx") >0) {
			// pour mise à jour après : G4 = id_fiche_paiement <=> déjà importé => à ignorer
			$objet_read_write = PHPExcel_IOFactory::createReader('Excel2007');
			$excel = $objet_read_write->load($lien_vers_mon_document_excel);			 
			$sheet = $excel->getSheet(0);
			// pour lecture début - fin seulement
			$XLSXDocument = new PHPExcel_Reader_Excel2007();
		} else {
			$objet_read_write = PHPExcel_IOFactory::createReader('Excel2007');
			$excel = $objet_read_write->load($lien_vers_mon_document_excel);			 
			$sheet = $excel->getSheet(0);
			$XLSXDocument = new PHPExcel_Reader_Excel5();
		}
		$Excel = $XLSXDocument->load($lien_vers_mon_document_excel);
		// get all the row of my file
		$rowIterator = $Excel->getActiveSheet()->getRowIterator();
		$remplacer=array("'");
		$trouver= array("’");
		$remplacer=array('&eacute;','e','e','a','o','c','_');
		$trouver= array('é','è','ê','à','ö','ç',' ');
		$liste_espece_capture=array();
		$e_c=0;
		foreach($rowIterator as $row) {
			 $ligne = $row->getRowIndex ();
			if($ligne >=2) {
				 $cellIterator = $row->getCellIterator();
				 // Loop all cells, even if it is not set
				 $cellIterator->setIterateOnlyExistingCells(false);
				 $rowIndex = $row->getRowIndex ();
				 $a_inserer =0;
				foreach ($cellIterator as $cell) {
					 if('A' == $cell->getColumn()) {
						$code_unique =$cell->getValue();
					 } else if('C' == $cell->getColumn()) {
						$code_unique_espece =$cell->getValue();
					 } else if('D' == $cell->getColumn()) {
						$Code3Alpha =$cell->getValue();
					 } else if('E' == $cell->getColumn()) {
						$Catch =$cell->getValue();
					 } else if('F' == $cell->getColumn()) {
						$Price =$cell->getValue();
					 } else if('G' == $cell->getColumn()) {
						$Total_Value =$cell->getValue();
					 } else if('H' == $cell->getColumn()) {
						$validation =$cell->getValue();
					 }
				}
				if($validation=="VRAI") {
					$validation=1;
				} else {
					$validation=0;
				}			
				$retour=$this->ImportbddaccessManager->AjouterEspeceCapture($code_unique,$Code3Alpha,$Catch,$Price,$Total_Value,$validation);
				foreach($retour as $k=>$v) {
					if($k==0) {
						$id=$v->id;
						$id_fiche_echantillonnage_capture=$v->id_fiche_echantillonnage_capture;
						$id_echantillon=$v->id_echantillon;
						$id_espece=$v->id_espece;
						$capture=$v->capture;
						$prix=$v->prix;
						$date_creation=$v->date_creation;
						$validation=$v->validation;
					}
				}
					$liste_espece_capture[$e_c]["id"]=$id;
					$liste_espece_capture[$e_c]["id_fiche_echantillonnage_capture"]=$id_fiche_echantillonnage_capture;
					$liste_espece_capture[$e_c]["id_echantillon"]=$id_echantillon;
					$liste_espece_capture[$e_c]["id_espece"]=$id_espece;
					$liste_espece_capture[$e_c]["capture"]=$capture;
					$liste_espece_capture[$e_c]["prix"]=$prix;
					$liste_espece_capture[$e_c]["date_creation"]=$date_creation;
					$liste_espece_capture[$e_c]["validation"]=$validation;
					$e_c=$e_c + 1;
			}		
		}	
		$liste_retour[0]["espece_capture"] = $liste_espece_capture;
		// FERMETURE FICHIER : désallocation mémoire
		unset($Excel);
		unset($objet_read_write);		
	}
} ?>	
