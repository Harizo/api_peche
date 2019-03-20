<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Importbddaccess_model extends CI_Model
{
    protected $table = 'region';
	function __construct(){
		parent::__construct();
		// $this->legacy_db = $this->load->database('bddaccess',true);
	}	

	public function importerbasededonnees() {
		// $this->legacy_db->db->query("select * from reftbl 6 NameEnumerator");
		$this->legacy_db->select('*');
		$this->legacy_db->from('reftbl 6 NameEnumerator');
		$query = $this->legacy_db->get();
		$kq=$query->result();
		$this->legacy_db->close();
		return $kq;    		
	}
	public function RAZ_Table_a_importer() {		
		$requete="delete from espece_capture;"
			." delete from echantillon;"
			." delete from fiche_echantillonnage_capture;"
			." delete from fiche_echantillonnage_capture_temporaire;"
			." delete from enquete_cadre;"
			." delete from unite_peche_site;"
			." delete from unite_peche;"
			." delete from espece;"
			." delete from site_enqueteur;"
			." delete from site_embarquement;"
			." delete from enqueteur;"
			." delete from type_engin;"
			." delete from type_canoe;"
			." delete from espece_capture;"
			." alter table espece_capture AUTO_INCREMENT =1;"
			." alter table echantillon AUTO_INCREMENT =1;"
			." alter table fiche_echantillonnage_capture AUTO_INCREMENT =1;"
			." alter table fiche_echantillonnage_capture_temporaire AUTO_INCREMENT =1;"
			." alter table enquete_cadre AUTO_INCREMENT =1;"
			." alter table unite_peche_site AUTO_INCREMENT =1;"
			." alter table unite_peche AUTO_INCREMENT =1;"
			." alter table espece AUTO_INCREMENT =1;"
			." alter table site_enqueteur AUTO_INCREMENT =1;"
			." alter table site_embarquement AUTO_INCREMENT =1;"
			." alter table enqueteur AUTO_INCREMENT =1;"
			." alter table type_engin AUTO_INCREMENT =1;"
			." alter table type_canoe AUTO_INCREMENT =1;"
			." alter table espece_capture AUTO_INCREMENT =1;";		
			$query = $this->db->query($requete);
			return 'OK';
	}
	public function AjouterEnqueteur($nom,$nom_original) {
		$requete="select id,nom from enqueteur where lower(nom) ='".$nom."'";
		$query = $this->db->query($requete);
        $id = $query->result();	
		if(!$id) {
			$requete="insert into enqueteur (nom) values ('".$nom_original."')";
			$query = $this->db->query($requete);
			$id_temp = $this->db->insert_id();
			$requete1="select id,nom from enqueteur where id ='".$id_temp."'";
			$query1 = $this->db->query($requete1);
			$id = $query1->result();		
		}	
		return $id;
	}
	public function AjouterSiteEmbarquement($libelle,$libelle_original,$code,$code_unique,$region) {
		$retour=array();
		$requete='select id,code,libelle,code_unique,id_region,"'.$region.'" as region from site_embarquement where code_unique ="'.$code_unique.'"';
		$query = $this->db->query($requete);
        $id = $query->result();	
		$regions=strtolower($region);
		if(!$id) {			
			$requete1='select id from region where lower(nom) ="'.$regions.'"';
			$query1 = $this->db->query($requete1);
			$retour_region = $query1->result();	
			if(!$retour_region) {
				$requete2="insert into region (nom) values ('".$region."')";
				$query2 = $this->db->query($requete2);
				$id_region = $this->db->insert_id();				
			} else {
				foreach($retour_region as $k=>$v) {
					if($k==0) {
						$id_region=$v->id;
					}
				}
			}
			$requete='insert into site_embarquement (code,libelle,code_unique,id_region) values ("'.$code.'","'.$libelle_original.'","'.$code_unique.'","'.$id_region.'")';
			$query = $this->db->query($requete);
			$id_temp = $this->db->insert_id();
			$requete='select id,code,libelle,code_unique,id_region,"'.$region.'" as region from site_embarquement where id ="'.$id_temp.'"';
			$query = $this->db->query($requete);
			$id = $query->result();		
		}	
		return $id;		
	}
	public function AjouterTypeEngin($libelle,$libelle_original,$code) {
		$codes=strtolower($code);
		$requete='select id,code,libelle from type_engin where lower(code) ="'.$codes.'"';
		$query = $this->db->query($requete);
        $id = $query->result();	
		if(!$id) {
			$requete='insert into type_engin (code,libelle) values ("'.$code.'","'.$libelle_original.'")';
			$query = $this->db->query($requete);
			$id_temp = $this->db->insert_id();
			$requete1="select id,code,libelle from type_engin where id ='".$id_temp."'";
			$query1 = $this->db->query($requete1);
			$id = $query1->result();		
		}	
		return $id;
	}
	public function AjouterTypeCanoe($nom,$nom_original,$code) {
		$codes=strtolower($code);
		$requete='select id,code,nom from type_canoe where lower(code) ="'.$codes.'"';
		$query = $this->db->query($requete);
        $id = $query->result();	
		if(!$id) {
			$requete='insert into type_canoe (code,nom) values ("'.$code.'","'.$nom_original.'")';
			$query = $this->db->query($requete);
			$id_temp = $this->db->insert_id();
			$requete1="select id,code,nom from type_canoe where id ='".$id_temp."'";
			$query1 = $this->db->query($requete1);
			$id = $query1->result();		
		}	
		return $id;
	}
	public function AjouterEspece($nom_local,$nom_local_original,$nom_scientifique,$code) {
		$codes=strtolower($code);
		$requete='select id,code,nom_local,nom_scientifique from espece where lower(code) ="'.$codes.'"';
		$query = $this->db->query($requete);
        $id = $query->result();	
		if(!$id) {
			$requete='insert into espece (code,nom_local,nom_scientifique) values ("'.$code.'","'.$nom_local_original.'","'.$nom_scientifique.'")';
			$query = $this->db->query($requete);
			$id_temp = $this->db->insert_id();
			$requete1="select id,code,nom_local,nom_scientifique from espece where id ='".$id_temp."'";
			$query1 = $this->db->query($requete1);
			$id = $query1->result();		
		} else {
			// Correction nom .... lors ERREUR transfert espece_capture : espece inexistant Hahahahahaha
			$retour_espece=$id;
			$nom_local="";
			foreach($retour_espece as $k=>$v) {
				if($k==0) {
					$id_espece=$v->id;
					$nom_local=$v->nom_local;
				}
			}
			if($nom_local=='XXXXXX') {
				$req1="update espece set nom_local='".$nom_local_original."',nom_scientifique='".$nom_scientifique."' where id=".$id_espece;
				$que1 = $this->db->query($req1);
				$requete1="select id,code,nom_local,nom_scientifique from espece where id ='".$id_espece."'";
				$query1 = $this->db->query($requete1);
				$id = $query1->result();
			}
		}	
		return $id;
	}
	public function AjouterSiteEnqueteur($nom,$region,$site,$site_sansespace) {
		$id_enqueteur=null;
		$id_site=null;
		$id_region=null;
		$requete2='select id from region where lower(nom) ="'.$region.'"';
		$query2 = $this->db->query($requete2);
		$retour_region = $query2->result();	
		foreach($retour_region as $k=>$v) {
			if($k==0) {
				$id_region=$v->id;
			}
		}	
		$requete="select id,nom from enqueteur where lower(nom) ='".$nom."'";
		$query = $this->db->query($requete);
        $retour_enqueteur = $query->result();	
		foreach($retour_enqueteur as $k=>$v) {
			if($k==0) {
				$id_enqueteur=$v->id;
			}
		}
		$requete1='select id,libelle from site_embarquement where (lower(libelle)="'.$site.'" or lower(libelle)="'.$site_sansespace.'") and id_region='.$id_region;
		$query1 = $this->db->query($requete1);
        $retour_site = $query1->result();	
		foreach($retour_site as $k=>$v) {
			if($k==0) {
				$id_site=$v->id;
			}
		}
		if($id_enqueteur && $id_site && $id_region) {
			$req="select count(*) as nombre from site_enqueteur where id_enqueteur=".$id_enqueteur." and id_site=".$id_site;
			$que=$this->db->query($req);
			$retour_testexistence = $que->result();	
			$nombre=0;
			foreach($retour_testexistence as $k=>$v) {
				if($k==0) {
					$nombre=$v->nombre;
				}
			}
			if($nombre==0) {
				$requete="insert into site_enqueteur (id_enqueteur,id_site) values ('".$id_enqueteur."','".$id_site."')";
				$query = $this->db->query($requete);
				$id_temp = $this->db->insert_id();
				$requete1="select id,id_enqueteur,id_site,'ajout' as ajout from site_enqueteur where id ='".$id_temp."'";
				$query1 = $this->db->query($requete1);
				$id = $query1->result();
			} 	else {
				$requete1="select id,id_enqueteur,id_site,'existe deja' as ajout from site_enqueteur where id_enqueteur=".$id_enqueteur." and id_site=".$id_site;
				$query1 = $this->db->query($requete1);
				$id = $query1->result();				
			}
		} else {
			$id=null;
		}	
		return $id;
	}
	public function AjouterUnitePeche($region,$site,$site_sansespace,$engin_canoe) {
		$pos_space = strpos($engin_canoe," ");
		$ajout_canoe="existe deja";
		$ajout_engin="existe deja";
		if($pos_space >0) {
			$type_canoe = substr($engin_canoe,0,($pos_space));
			$type_engin = substr($engin_canoe,($pos_space + 1));
		} else {
			$type_canoe =$engin_canoe;
			$type_engin =$engin_canoe;
		}		
		$requete='select id,code,libelle from type_engin where lower(libelle) ="'.$type_engin.'" or lower(code)="'.$type_engin.'"';
		$query = $this->db->query($requete);
        $retour_engin = $query->result();	
		
		$requete='select id,code,nom from type_canoe where lower(code) ="'.$type_canoe.'" or lower(nom)="'.$type_canoe.'"';
		$query = $this->db->query($requete);
        $retour_canoe = $query->result();	
		if(!$retour_engin) { 
		    $code = substr($type_engin,0,4);
			$requete='insert into type_engin (code,libelle) values ("'.$code.'","'.$type_engin.'")';
			$query = $this->db->query($requete);
			$id_temp = $this->db->insert_id();
			$requete1="select id,code,libelle from type_engin where id ='".$id_temp."'";
			$query1 = $this->db->query($requete1);
			$retour_engin = $query1->result();	
			$ajout_engin="ajout";
		}
		if(!$retour_canoe) {
			$code=substr($type_canoe,0,4);
			$requete='insert into type_canoe (code,nom) values ("'.$code.'","'.$type_canoe.'")';
			$query = $this->db->query($requete);
			$id_temp = $this->db->insert_id();
			$requete1="select id,code,nom from type_canoe where id ='".$id_temp."'";
			$query1 = $this->db->query($requete1);
			$retour_canoe = $query1->result();	
			$ajout_canoe="ajout";	
		}	
		$id_engin=null;
		$id_canoe=null;
		foreach($retour_engin as $k=>$v) {
			if($k==0) {
				$id_engin=$v->id;
			}
		}	
		foreach($retour_canoe as $k=>$v) {
			if($k==0) {
				$id_canoe=$v->id;
			}
		}	
		$id_site=null;
		$id_region=null;
		$requete2='select id from region where lower(nom) ="'.$region.'"';
		$query2 = $this->db->query($requete2);
		$retour_region = $query2->result();	
		foreach($retour_region as $k=>$v) {
			if($k==0) {
				$id_region=$v->id;
			}
		}	
		$requete1='select id,libelle from site_embarquement where (lower(libelle)="'.$site.'" or lower(libelle)="'.$site_sansespace.'") and id_region='.$id_region;
		$query1 = $this->db->query($requete1);
        $retour_site = $query1->result();	
		foreach($retour_site as $k=>$v) {
			if($k==0) {
				$id_site=$v->id;
			}
		}
		if($id_engin && $id_canoe && $id_site) {
			$req="select id,count(*) as nombre from unite_peche where id_type_canoe=".$id_canoe." and id_type_engin=".$id_engin." group by id";
			$que=$this->db->query($req);
			$retour_testexistence = $que->result();	
			$nombre=0;
			$id_unite_peche=null;
			foreach($retour_testexistence as $k=>$v) {
				if($k==0) {
					$nombre=$v->nombre;
					$id_unite_peche=$v->id;
				}
			}
			if($nombre==0) {
				$requete="insert into unite_peche (id_type_canoe,id_type_engin,libelle) values ('".$id_canoe."','".$id_engin."','".$engin_canoe."')";
				$query = $this->db->query($requete);
				$id_temp = $this->db->insert_id();
				$requete1="select up.id,up.id_type_canoe,up.id_type_engin,'".$id_site."' as id_site_embarquement,'ajout' as ajout,"
				."tc.code as code_canoe,tc.nom nom_canoe,te.code as code_engin,te.libelle as libelle_engin,1 as cas,"
				."'".$ajout_engin."' as ajout_engin,"."'".$ajout_canoe."' as ajout_canoe,".$id_region." as id_region"
				." from unite_peche as up"
				." left outer join type_canoe as tc on tc.id=up.id_type_canoe"
				." left outer join type_engin as te on te.id=up.id_type_engin"
				." where up.id ='".$id_temp."'";
				$query1 = $this->db->query($requete1);
				$id = $query1->result();
			} 	else {
				$requete1="select up.id,up.id_type_canoe,up.id_type_engin,'".$id_site."' as id_site_embarquement,'existe deja' as ajout,"
				."tc.code as code_canoe,tc.nom nom_canoe,te.code as code_engin,te.libelle as libelle_engin,2 as cas,"
				."'".$ajout_engin."' as ajout_engin,"."'".$ajout_canoe."' as ajout_canoe,".$id_region." as id_region"
				." from unite_peche as up"
				." left outer join type_canoe as tc on tc.id=up.id_type_canoe"
				." left outer join type_engin as te on te.id=up.id_type_engin"
				." where up.id_type_canoe=".$id_canoe." and up.id_type_engin=".$id_engin;
				$query1 = $this->db->query($requete1);
				$id = $query1->result();		
			}
		} else {
			$id=null;
		}	
		return $id;
	}
	public function AjouterUnitePecheSite($id_unite_peche,$id_site_embarquement) {
			$req="select id,count(*) as nombre from unite_peche_site where id_unite_peche=".$id_unite_peche." and id_site_embarquement=".$id_site_embarquement." group by id";
			$que=$this->db->query($req);
			$retour_testexistence = $que->result();	
			$nombre=0;
			$id=null;
			foreach($retour_testexistence as $k=>$v) {
				if($k==0) {
					$nombre=$v->nombre;
					$id=$v->id;
				}
			}
			if($nombre==0) {
				$requete="insert into unite_peche_site (id_unite_peche,id_site_embarquement) values ('".$id_unite_peche."','".$id_site_embarquement."')";
				$query = $this->db->query($requete);
				$id_temp = $this->db->insert_id();
				$requete1="select ups.id,ups.id_unite_peche,ups.id_site_embarquement,up.libelle,'ajout' as ajout"
						." from unite_peche_site as ups"
						." left outer join unite_peche as up on ups.id_unite_peche=up.id"
						." where ups.id=".$id_temp;
				$query1 = $this->db->query($requete1);
				$id = $query1->result();		
			} else {
				$id=null;
				$requete1="select ups.id,ups.id_unite_peche,ups.id_site_embarquement,up.libelle,'déjà existe' as ajout"
						." from unite_peche_site as ups"
						." left outer join unite_peche as up on ups.id_unite_peche=up.id"
						." where ups.id_unite_peche=".$id_unite_peche;
				$query1 = $this->db->query($requete1);
				$id = $query1->result();		
			}	
		return $id;		
	}
	public function AjouterFiche_Echantillonnage_Capture($id_unite_peche,$unique_code,$date_peche,$id_site_embarquement,$enqueteur,$id_region,$validation,$region,$site_original,$peche_hier,$peche_avant_hier,$nbr_jrs_peche_dernier_sem,$date_code_unique,$site,$site_sansespace) {
		$requete1='select id,libelle from site_embarquement where (lower(libelle)="'.$site.'" or lower(libelle)="'.$site_sansespace.'") and id_region='.$id_region;
		$query1 = $this->db->query($requete1);
        $retour_site = $query1->result();	
		$id_site_embarquement=null;
		foreach($retour_site as $k=>$v) {
			if($k==0) {
				$id_site_embarquement=$v->id;
			}
		}
		$requete="select se.id,e.nom as enqueteur,se.id_site,se.id_enqueteur"
			." from enqueteur as e"
			." left outer join site_enqueteur as se on se.id_enqueteur=e.id"
			." where lower(e.nom)='".$enqueteur."' and se.id_site='".$id_site_embarquement."'";
			$que=$this->db->query($requete);
			$retour_enqueteur = $que->result();	
			$id_enqueteur=null;
			if($retour_enqueteur) {
				foreach($retour_enqueteur as $k=>$v) {
					if($k==0) {
						$id_enqueteur=$v->id_enqueteur;
					}
				}				
			} else {
				// DANGER DE MORT : l'interlocuteur est un vaut rien ou bien l'expert qu'ils ont mis leur confiance est NUL
				// REGLE DE GESTION : Un enqueteur est affecté à UN et UN SEUL site d'embarquement
				// (dernière reunion et avec insistance (selon l'interlocuteur ??? je suis l'homme) car l'enqueteur fait partie des villageois).
				// l'enqueteur CYNTHIA est affecté au site d'Abemokoty ou bien  au site d'Ankazomborona ???? Hahahaha
				// OLONA MANEMANINY
				$requete="select id as id_enqueteur,nom as enqueteur"
				." from enqueteur as e"
				." where lower(nom)='".$enqueteur."'";
				$que=$this->db->query($requete);
				$retour_enqueteur = $que->result();	
				$id_enqueteur=null;
				if($retour_enqueteur) {
					foreach($retour_enqueteur as $k=>$v) {
						if($k==0) {
							$id_enqueteur=$v->id_enqueteur;
						}
					}				
				}				
			}
			$req="select id,count(*) as nombre from fiche_echantillonnage_capture where date='".$date_peche."' and id_site_embarquement=".$id_site_embarquement." and id_region=".$id_region." group by id";
			$que=$this->db->query($req);
			$retour_testexistence = $que->result();	
			$nombre=0;
			$id_fiche=null;
			if($retour_testexistence) {
				foreach($retour_testexistence as $k=>$v) {
					if($k==0) {
						$nombre=$v->nombre;
						$id_fiche=$v->id;
					}
				}
			}	
			$code_unique=$region."-".$site_original."-".$date_code_unique;
			if($nombre==0) {
				$requete="insert into fiche_echantillonnage_capture (code_unique,date,id_site_embarquement,id_enqueteur,id_region,date_creation,validation) values ('".$code_unique."','".$date_peche."','".$id_site_embarquement."','".$id_enqueteur."','".$id_region."','".$date_peche."','".$validation."')";
				$query = $this->db->query($requete);
				$id_temp = $this->db->insert_id();
				$id_fiche=$id_temp;
				$re="insert into fiche_echantillonnage_capture_temporaire (ancien_code_unique,nouveau_code_unique,id_fiche_enchantillonnage_capture,date_peche) values ('".$unique_code."','".$code_unique."','".$id_temp."','".$date_peche."')";
				$qu = $this->db->query($re);
				$id_temp_capture = $this->db->insert_id();	
				$requete1="select fe.id,fe.code_unique,fe.date,fe.id_site_embarquement,fe.id_enqueteur,fe.id_region,fe.date_creation,fe.validation,'ajout' as ajout"
						." from fiche_echantillonnage_capture as fe"
						." where fe.id=".$id_temp;
				$query1 = $this->db->query($requete1);
				$id = $query1->result();		
			} else {
				$re="insert into fiche_echantillonnage_capture_temporaire (ancien_code_unique,nouveau_code_unique,id_fiche_enchantillonnage_capture,date_peche) values ('".$unique_code."','".$code_unique."','".$id_fiche."','".$date_peche."')";
				$qu = $this->db->query($re);				
				$id_temp_capture = $this->db->insert_id();	
				$requete1="select fe.id,fe.code_unique,fe.date,fe.id_site_embarquement,fe.id_enqueteur,fe.id_region,fe.date_creation,fe.validation,'existe deja' as ajout"
						." from fiche_echantillonnage_capture as fe"
						." where fe.date='".$date_peche."' and fe.id_site_embarquement=".$id_site_embarquement." and fe.id_region=".$id_region;
				$query1 = $this->db->query($requete1);
				$id = $query1->result();	
				foreach($id as $k=>$v) {
					if($k==0) {
						$id_temp=$v->id;
					}
				}	
			}
			$req1="select id as id_data_collect from data_collect where code='PAB'";	
			$que1=$this->db->query($req1);
			$retour_pab = $que1->result();	
			$id_data_collect=null;
			if($retour_pab) {
				foreach($retour_pab as $k=>$v) {
					if($k==0) {
						$id_data_collect=$v->id_data_collect;
					}
				}				
			}
			// Insertion table echantillon
			$requeteechantillon="insert into echantillon(id_fiche_echantillonnage_capture,peche_hier,peche_avant_hier,nbr_jrs_peche_dernier_sem,unique_code,id_data_collect,date_creation,id_unite_peche)"
									." values('".$id_fiche."','".$peche_hier."','".$peche_avant_hier."','".$nbr_jrs_peche_dernier_sem."','".$unique_code."','".$id_data_collect."','".$date_peche."','".$id_unite_peche."')";
			$que2=$this->db->query($requeteechantillon);
			$id_echantillon= $this->db->insert_id();
			$req="update fiche_echantillonnage_capture_temporaire set id_echantillon=".$id_echantillon." where id=".$id_temp_capture;
			$que=$this->db->query($req);	
		return $id;		
	}
	public function AjouterFiche_Echantillonnage_CaptureCAB($id_unite_peche,$unique_code,$date_peche,$enqueteur,$id_region,$validation,$region,$site_original,$bateau_actif,$bateau_total,$date_code_unique,$site,$site_sansespace) {
		/*$req="delete from fiche_echantillonnage_capture_temporaire;";
		$quer = $this->db->query($req);
		$req="alter table fiche_echantillonnage_capture_temporaire AUTO_INCREMENT =1;";
		$quer = $this->db->query($req);*/
		$requete1='select id,libelle from site_embarquement where (lower(libelle)="'.$site.'" or lower(libelle)="'.$site_sansespace.'") and id_region='.$id_region;
		$query1 = $this->db->query($requete1);
        $retour_site = $query1->result();	
		$id_site_embarquement=null;
		foreach($retour_site as $k=>$v) {
			if($k==0) {
				$id_site_embarquement=$v->id;
			}
		}
		$requete="select se.id,e.nom as enqueteur,se.id_site,se.id_enqueteur"
			." from enqueteur as e"
			." left outer join site_enqueteur as se on se.id_enqueteur=e.id"
			." where lower(e.nom)='".$enqueteur."' and se.id_site='".$id_site_embarquement."'";
			$que=$this->db->query($requete);
			$retour_enqueteur = $que->result();	
			$id_enqueteur=null;
			if($retour_enqueteur) {
				foreach($retour_enqueteur as $k=>$v) {
					if($k==0) {
						$id_enqueteur=$v->id_enqueteur;
					}
				}				
			} else {
				// DANGER DE MORT : l'interlocuteur est un vaut rien ou bien l'expert qu'ils ont mis leur confiance est NUL
				// REGLE DE GESTION : Un enqueteur est affecté à UN et UN SEUL site d'embarquement
				// (dernière reunion et avec insistance (selon l'interlocuteur ??? je suis l'homme) car l'enqueteur fait partie des villageois).
				// l'enqueteur CYNTHIA est affecté au site d'Abemokoty ou bien  au site d'Ankazomborona ???? Hahahaha
				// OLONA MANEMANINY
				$requete="select id as id_enqueteur,nom as enqueteur"
				." from enqueteur as e"
				." where lower(nom)='".$enqueteur."'";
				$que=$this->db->query($requete);
				$retour_enqueteur = $que->result();	
				$id_enqueteur=null;
				if($retour_enqueteur) {
					foreach($retour_enqueteur as $k=>$v) {
						if($k==0) {
							$id_enqueteur=$v->id_enqueteur;
						}
					}				
				}				
			}
			$req="select id,count(*) as nombre from fiche_echantillonnage_capture where date='".$date_peche."' and id_site_embarquement=".$id_site_embarquement." and id_region=".$id_region." group by id";
			$que=$this->db->query($req);
			$retour_testexistence = $que->result();	
			$nombre=0;
			$id_fiche=null;
			if($retour_testexistence) {
				foreach($retour_testexistence as $k=>$v) {
					if($k==0) {
						$nombre=$v->nombre;
						$id_fiche=$v->id;
					}
				}
			}	

			$code_unique=$region."-".$site_original."-".$date_code_unique;
			if($nombre==0) {
				$requete="insert into fiche_echantillonnage_capture (code_unique,date,id_site_embarquement,id_enqueteur,id_region,date_creation,validation) values ('".$code_unique."','".$date_peche."','".$id_site_embarquement."','".$id_enqueteur."','".$id_region."','".$date_peche."','".$validation."')";
				$query = $this->db->query($requete);
				$id_temp = $this->db->insert_id();
				$id_fiche=$id_temp;
				$re="insert into fiche_echantillonnage_capture_temporaire (ancien_code_unique,nouveau_code_unique,id_fiche_enchantillonnage_capture,date_peche) values ('".$unique_code."','".$code_unique."','".$id_temp."','".$date_peche."')";
				$qu = $this->db->query($re);				
				$id_temp_capture = $this->db->insert_id();	
				$requete1="select fe.id,fe.code_unique,fe.date,fe.id_site_embarquement,fe.id_enqueteur,fe.id_region,fe.date_creation,fe.validation,'ajout' as ajout"
						." from fiche_echantillonnage_capture as fe"
						." where fe.id=".$id_temp;
				$query1 = $this->db->query($requete1);
				$id = $query1->result();		
			} else {
				$re="insert into fiche_echantillonnage_capture_temporaire (ancien_code_unique,nouveau_code_unique,id_fiche_enchantillonnage_capture,date_peche) values ('".$unique_code."','".$code_unique."','".$id_fiche."','".$date_peche."')";
				$qu = $this->db->query($re);				
				$id_temp_capture = $this->db->insert_id();	
				$requete1="select fe.id,fe.code_unique,fe.date,fe.id_site_embarquement,fe.id_enqueteur,fe.id_region,fe.date_creation,fe.validation,'existe deja' as ajout"
						." from fiche_echantillonnage_capture as fe"
						." where fe.date='".$date_peche."' and fe.id_site_embarquement=".$id_site_embarquement." and fe.id_region=".$id_region;
				$query1 = $this->db->query($requete1);
				$id = $query1->result();		
				foreach($id as $k=>$v) {
					if($k==0) {
						$id_temp=$v->id;
					}
				}	
			}
			$req1="select id as id_data_collect from data_collect where code='CAB'";	
			$que1=$this->db->query($req1);
			$retour_pab = $que1->result();	
			$id_data_collect=null;
			if($retour_pab) {
				foreach($retour_pab as $k=>$v) {
					if($k==0) {
						$id_data_collect=$v->id_data_collect;
					}
				}				
			}
			// Insertion table echantillon
			$requeteechantillon="insert into echantillon(id_fiche_echantillonnage_capture,nbr_bateau_actif,total_bateau_ecn,unique_code,id_data_collect,date_creation,id_unite_peche)"
									." values('".$id_fiche."','".$bateau_actif."','".$bateau_total."','".$unique_code."','".$id_data_collect."','".$date_peche."','".$id_unite_peche."')";
			$que2=$this->db->query($requeteechantillon);
			$id_echantillon= $this->db->insert_id();
			$req="update fiche_echantillonnage_capture_temporaire set id_echantillon=".$id_echantillon." where id=".$id_temp_capture;
			$que=$this->db->query($req);	
			
		return $id;		
	}
	public function AjouterEspeceCapture($code_unique,$Code3Alpha,$Catch,$Price,$Total_Value,$validation) {
			$requete="select id as id_espece from espece where code='".$Code3Alpha."'";
			$que=$this->db->query($requete);
			$retour_espece = $que->result();	
			$id_espece=null;
			if($retour_espece) {
				foreach($retour_espece as $k=>$v) {
					if($k==0) {
						$id_espece=$v->id_espece;
					}
				}				
			} else {
				// Données parfaite hono e nefa tsssssssss .....mbola misy espece mitsirapaka tsy hita any @ DDB
				$requete="insert into espece (code,nom_local,nom_scientifique) values ('".$Code3Alpha."','XXXXXX','YYYYYY')";
				$query = $this->db->query($requete);
				$id_espece = $this->db->insert_id();			
			}
			$id_echantillon=null;
			$id_fiche_enchantillonnage_capture=null;
			$date_peche=null;
			$requete="select id as id_echantillon,id_fiche_enchantillonnage_capture,date_peche from fiche_echantillonnage_capture_temporaire where ancien_code_unique='".$code_unique."'";
			$que=$this->db->query($requete);
			$retour_echantillon = $que->result();	
			if($retour_echantillon) {
				foreach($retour_echantillon as $k=>$v) {
					if($k==0) {
						$id_echantillon=$v->id_echantillon;
						$id_fiche_enchantillonnage_capture=$v->id_fiche_enchantillonnage_capture;
						$date_peche=$v->date_peche;
					}
				}								
			}
			$requete="insert into espece_capture (id_fiche_echantillonnage_capture,id_echantillon,id_espece,capture,prix,date_creation,validation) values ('".$id_fiche_enchantillonnage_capture."','".$id_echantillon."','".$id_espece."','".$Catch."','".$Price."','".$date_peche."','".$validation."')";
			$query = $this->db->query($requete);
			$id_temp = $this->db->insert_id();
			$req="select * from espece_capture where id=".$id_temp;
			$que=$this->db->query($req);
			$id = $que->result();	

		return $id;		
	}
}
