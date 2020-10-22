<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_reporting_peche_thon_malagasy_model extends CI_Model {
	public function reporting_peche_thon_malagasy_qte_espece_annuel($debut,$fin) {
		// Qté par espèce annuel
		// Reporting ACCESS 12.1
		$query = $this->db->query("CALL sip_proc_thon_malagasy_qte_espece_annuel($debut,$fin)");
		return $query->result();		
	}	
	public function reporting_thoniere_malagasy_etranger_par_espece_par_mois($debut,$fin,$table_en_tete,$table_sequence,$cle_etranger_sequence,$table_sequence_capture,$cle_etranger_sequence_capture) {
			 // PAR ESPECE PAR MOIS ET ANNUEL
			 // Reporting ACCESS 12.2
		$requete="select fin.annee,fin.code_espece,fin.nom_espece,"
				." (fin.janvier + fin.fevrier + fin.mars + fin.avril + fin.mai + fin.juin + fin.juillet + fin.aout + fin.septembre + fin.octobre + fin.novembre + fin.decembre) as Total,"
			." fin.janvier,fin.fevrier,fin.mars,fin.avril,fin.mai,fin.juin,fin.juillet,fin.aout,fin.septembre,fin.octobre,fin.novembre,fin.decembre"	
			." FROM(" 
			."  select trois.annee,trois.code_espece,trois.nom_espece,"
			."  sum(trois.janvier) as janvier,sum(trois.fevrier) as fevrier,sum(trois.mars) as mars,sum(trois.avril) as avril,"
			."  sum(trois.mai) as mai,sum(trois.juin) as juin,sum(trois.juillet) as juillet,sum(trois.aout) as aout,"
			."  sum(trois.septembre) as septembre,sum(trois.octobre) as octobre,sum(trois.novembre) as novembre,sum(trois.decembre) as decembre"
			."  FROM "
			."  ("
			."  select deux.annee,deux.code_espece,deux.nom_espece,"
			."  ifnull(deux.janvier,0) as janvier,ifnull(deux.fevrier,0) as fevrier,ifnull(deux.mars,0) as mars,"
			."  ifnull(deux.avril,0) as avril,ifnull(deux.mai,0) as mai,ifnull(deux.juin,0) as juin,"
			."  ifnull(deux.juillet,0) as juillet,ifnull(deux.aout,0) as aout,ifnull(deux.septembre,0) as septembre,"
			."  ifnull(deux.octobre,0) as octobre,ifnull(deux.novembre,0) as novembre,ifnull(deux.decembre,0) as decembre"
			."  FROM "
			."  ("
			." select un.annee,un.code_espece,un.nom_espece,"
			." (select ifnull(un.quantite,0) where un.mois=1) as janvier,"
			." (select ifnull(un.quantite,0) where un.mois=2) as fevrier,"
			." (select ifnull(un.quantite,0) where un.mois=3) as mars,"
			." (select ifnull(un.quantite,0) where un.mois=4) as avril,"
			." (select ifnull(un.quantite,0) where un.mois=5) as mai,"
			." (select ifnull(un.quantite,0) where un.mois=6) as juin,"
			." (select ifnull(un.quantite,0) where un.mois=7) as juillet,"
			." (select ifnull(un.quantite,0) where un.mois=8) as aout,"
			." (select ifnull(un.quantite,0) where un.mois=9) as septembre,"
			." (select ifnull(un.quantite,0) where un.mois=10) as octobre,"
			." (select ifnull(un.quantite,0) where un.mois=11) as novembre,"
			." (select ifnull(un.quantite,0) where un.mois=12) as decembre"
			." FROM "
			." ("
			." 	select year(sipth.date_arrive) as annee,month(sipth.date_arrive) as mois,sipe.code as code_espece,sipe.nom as nom_espece,"
			." 	sum(seqcap.qte) as quantite"
			." 	from ".$table_en_tete." as sipth"
			." 	left outer join ".$table_sequence." as seq on seq.".$cle_etranger_sequence."=sipth.id"
			." 	left outer join ".$table_sequence_capture." as seqcap on seqcap.".$cle_etranger_sequence_capture."=seq.id	"
			." 	left outer join sip_espece as sipe on sipe.id=seqcap.id_espece"
			." 	where year(sipth.date_arrive) >=".$debut." and year(sipth.date_arrive) <=".$fin
			." 	group by year(sipth.date_arrive),month(sipth.date_arrive),code_espece,nom_espece"
			." 	order by annee,mois,code_espece"
			." ) as un"	
			." ) as deux"
			." ) as trois "
			." group by trois.annee,trois.code_espece,trois.nom_espece"
			." ) as fin";
		return $this->db->query($requete)->result();			  
	}
	public function reporting_thoniere_malagasy_etranger_par_mois_navire_espece($debut,$fin,$table_en_tete,$table_sequence,$cle_etranger_sequence,$table_sequence_capture,$cle_etranger_sequence_capture) {
		// PAR MOIS PAR NAVIRE ET PAR ESPECE
		// Reporting ACCESS 12.3
		$requete="select final.annee,final.immatricule,final.nom_navire,final.armateur,final.code_espece,final.nom_espece,"
			." sum(final.janvier+final.fevrier+final.mars+final.avril+final.mai+final.juin + "
			." final.juillet+final.aout+final.septembre+final.octobre+final.novembre+final.decembre) as total,"
			." sum(final.janvier) as janvier,sum(final.fevrier) as fevrier,sum(final.mars) as mars,"
			." sum(final.avril) as avril,sum(final.mai) as mai,sum(final.juin) as juin,"
			." sum(final.juillet) as juillet,sum(final.aout) as aout,sum(final.septembre) as septembre,"
			." sum(final.octobre) as octobre,sum(final.novembre) as novembre,sum(final.decembre) as decembre"
			." from "
			." ("
			." select trois.annee,trois.immatricule,trois.nom_navire,trois.armateur,trois.code_espece,trois.nom_espece,"
			." ifnull(trois.janvier,0) as janvier,ifnull(trois.fevrier,0) as fevrier,"
			." ifnull(trois.mars,0) as mars,ifnull(trois.avril,0) as avril,"
			." ifnull(trois.mai,0) as mai,ifnull(trois.juin,0) as juin,"
			." ifnull(trois.juillet,0) as juillet,ifnull(trois.aout,0) as aout,"
			." ifnull(trois.septembre,0) as septembre,ifnull(trois.octobre,0) as octobre,"
			." ifnull(trois.novembre,0) as novembre,ifnull(trois.decembre,0) as decembre"
			." from "
			." ("
			." select deux.annee,deux.immatricule,deux.nom_navire,deux.armateur,deux.code_espece,deux.nom_espece,"
			." (select ifnull(deux.quantite,0) where deux.mois=1) as janvier,"
			." (select ifnull(deux.quantite,0) where deux.mois=2) as fevrier,"
			." (select ifnull(deux.quantite,0) where deux.mois=3) as mars,"
			." (select ifnull(deux.quantite,0) where deux.mois=4) as avril,"
			." (select ifnull(deux.quantite,0) where deux.mois=5) as mai,"
			." (select ifnull(deux.quantite,0) where deux.mois=6) as juin,"
			." (select ifnull(deux.quantite,0) where deux.mois=7) as juillet,"
			." (select ifnull(deux.quantite,0) where deux.mois=8) as aout,"
			." (select ifnull(deux.quantite,0) where deux.mois=9) as septembre,"
			." (select ifnull(deux.quantite,0) where deux.mois=10) as octobre,"
			." (select ifnull(deux.quantite,0) where deux.mois=11) as novembre,"
			." (select ifnull(deux.quantite,0) where deux.mois=12) as decembre"
			." from "
			." ("
			." select un.annee,un.mois,un.immatricule,un.nom_navire,un.armateur,un.code_espece,un.nom_espece,sum(un.quantite) as quantite"
			." from ("
			." 	select year(sipth.date_arrive) as annee,month(sipth.date_arrive) as mois,sipnav.immatricule,sipnav.nom as nom_navire,sipnav.armateur,"
			." 	sipe.code as code_espece,sipe.nom as nom_espece,seqcap.qte as quantite"
			." 	from ".$table_en_tete." as sipth"
			." 	left outer join ".$table_sequence." as seq on seq.".$cle_etranger_sequence."=sipth.id"
			." 	left outer join ".$table_sequence_capture." as seqcap on seqcap.".$cle_etranger_sequence_capture."=seq.id"	
			." 	left outer join sip_navire as sipnav on sipnav.id=sipth.id_navire"
			." 	left outer join sip_espece as sipe on sipe.id=seqcap.id_espece"
			." 	where year(sipth.date_arrive) >=".$debut." and year(sipth.date_arrive) <=".$fin
			." 	order by annee,mois,nom_navire,code_espece"
			."  ) as un"	
			."  group by un.annee,un.mois,un.immatricule,un.nom_navire,un.armateur,un.code_espece,un.nom_espece"
			."  ) as deux"
			."  ) as trois"
			."  order by trois.annee,trois.nom_navire,trois.nom_espece"
			."  ) as final"
			."  group by final.annee,final.immatricule,final.nom_navire,final.armateur,final.code_espece,final.nom_espece"
			."  order by final.annee,final.nom_navire,final.nom_espece";
		
		return $this->db->query($requete)->result();			  
	}
	public function reporting_peche_thon_malagasy_par_navire_espece($debut,$fin) {
		// Qté annuel par espèce et par navire
		// Reporting ACCESS 12.4
		$query = $this->db->query("CALL sip_proc_thon_malagasy_par_navire_espece($debut,$fin)");
		return $query->result();		
	}	
	public function reporting_peche_thon_malagasy_hamecon_mensuel($debut,$fin) {
		// Qté hamecon mensuel et annuel
		// Reporting ACCESS 12.5
		$query = $this->db->query("CALL sip_proc_thon_malagasy_par_navire_espece($debut,$fin)");
		return $query->result();		
	}	

	public function reporting_thoniere_malagasy_etranger_par_societe_mensuel_annuel($debut,$fin,$table_en_tete,$table_sequence,$cle_etranger_sequence,$table_sequence_capture,$cle_etranger_sequence_capture) {
			// PAR SOCIETE MENSUEL ET ANNUEL
			// Reporting ACCESS 12.6
		$requete="select fin.annee,fin.armateur,"
				." (fin.janvier + fin.fevrier + fin.mars + fin.avril + fin.mai + fin.juin + fin.juillet + fin.aout + fin.septembre + fin.octobre + fin.novembre + fin.decembre) as Total,"
			." fin.janvier,fin.fevrier,fin.mars,fin.avril,fin.mai,fin.juin,fin.juillet,fin.aout,fin.septembre,fin.octobre,fin.novembre,fin.decembre"	
			." FROM( "
			."  select trois.annee,trois.armateur,"
			."  sum(trois.janvier) as janvier,sum(trois.fevrier) as fevrier,sum(trois.mars) as mars,sum(trois.avril) as avril,"
			."  sum(trois.mai) as mai,sum(trois.juin) as juin,sum(trois.juillet) as juillet,sum(trois.aout) as aout,"
			."  sum(trois.septembre) as septembre,sum(trois.octobre) as octobre,sum(trois.novembre) as novembre,sum(trois.decembre) as decembre"
			."  FROM "
			."  ("
			."  select deux.annee,deux.armateur,"
			."  ifnull(deux.janvier,0) as janvier,ifnull(deux.fevrier,0) as fevrier,ifnull(deux.mars,0) as mars,"
			."  ifnull(deux.avril,0) as avril,ifnull(deux.mai,0) as mai,ifnull(deux.juin,0) as juin,"
			."  ifnull(deux.juillet,0) as juillet,ifnull(deux.aout,0) as aout,ifnull(deux.septembre,0) as septembre,"
			."  ifnull(deux.octobre,0) as octobre,ifnull(deux.novembre,0) as novembre,ifnull(deux.decembre,0) as decembre"
			."  FROM "
			."  ("
			." select un.annee,un.armateur,"
			." (select ifnull(un.quantite,0) where un.mois=1) as janvier,"
			." (select ifnull(un.quantite,0) where un.mois=2) as fevrier,"
			." (select ifnull(un.quantite,0) where un.mois=3) as mars,"
			." (select ifnull(un.quantite,0) where un.mois=4) as avril,"
			." (select ifnull(un.quantite,0) where un.mois=5) as mai,"
			." (select ifnull(un.quantite,0) where un.mois=6) as juin,"
			." (select ifnull(un.quantite,0) where un.mois=7) as juillet,"
			." (select ifnull(un.quantite,0) where un.mois=8) as aout,"
			." (select ifnull(un.quantite,0) where un.mois=9) as septembre,"
			." (select ifnull(un.quantite,0) where un.mois=10) as octobre,"
			." (select ifnull(un.quantite,0) where un.mois=11) as novembre,"
			." (select ifnull(un.quantite,0) where un.mois=12) as decembre"
			." FROM" 
			." ("
			." 	select year(sipth.date_arrive) as annee,month(sipth.date_arrive) as mois,sipnav.armateur,"
			." 	sum(seqcap.qte) as quantite"
			." 	from ".$table_en_tete." as sipth"
			." 	left outer join ".$table_sequence." as seq on seq.".$cle_etranger_sequence."=sipth.id"
			." 	left outer join ".$table_sequence_capture." as seqcap on seqcap.".$cle_etranger_sequence_capture."=seq.id	"
			." 	left outer join sip_navire as sipnav on sipnav.id=sipth.id_navire"
			." 	where year(sipth.date_arrive) >=".$debut." and year(sipth.date_arrive) <=".$fin
			." 	group by year(sipth.date_arrive),month(sipth.date_arrive),armateur"
			." 	order by annee,mois,armateur"
			." ) as un	"
			." ) as deux"
			." ) as trois "
			." group by trois.annee,trois.armateur"
			." ) as fin"
			." 	order by annee,armateur";
		return $this->db->query($requete)->result();			  
    }
	public function reporting_thoniere_malagasy_etranger_jour_en_mer_navire_mensuel_annuel($debut,$fin,$table_en_tete) {	
			// NOMBRE JOUR EN MER PAR NAVIRE, MENSUEL ET ANNUEL
			// Reporting ACCESS 12.8					
		$requete="select fin.annee,fin.immatricule,fin.armateur,"
				." (fin.janvier + fin.fevrier + fin.mars + fin.avril + fin.mai + fin.juin + fin.juillet + fin.aout + fin.septembre + fin.octobre + fin.novembre + fin.decembre) as Total,"
			." fin.janvier,fin.fevrier,fin.mars,fin.avril,fin.mai,fin.juin,fin.juillet,fin.aout,fin.septembre,fin.octobre,fin.novembre,fin.decembre"	
			." FROM( "
			."  select trois.annee,trois.immatricule,trois.armateur,"
			."  sum(trois.janvier) as janvier,sum(trois.fevrier) as fevrier,sum(trois.mars) as mars,sum(trois.avril) as avril,"
			."  sum(trois.mai) as mai,sum(trois.juin) as juin,sum(trois.juillet) as juillet,sum(trois.aout) as aout,"
			."  sum(trois.septembre) as septembre,sum(trois.octobre) as octobre,sum(trois.novembre) as novembre,sum(trois.decembre) as decembre"
			."  FROM "
			."  ("
			."  select deux.annee,deux.immatricule,deux.armateur,"
			."  ifnull(deux.janvier,0) as janvier,ifnull(deux.fevrier,0) as fevrier,ifnull(deux.mars,0) as mars,"
			."  ifnull(deux.avril,0) as avril,ifnull(deux.mai,0) as mai,ifnull(deux.juin,0) as juin,"
			."  ifnull(deux.juillet,0) as juillet,ifnull(deux.aout,0) as aout,ifnull(deux.septembre,0) as septembre,"
			."  ifnull(deux.octobre,0) as octobre,ifnull(deux.novembre,0) as novembre,ifnull(deux.decembre,0) as decembre"
			."  FROM "
			."  ("
			." select un.annee,un.immatricule,un.armateur,"
			." (select ifnull(un.nombre_jour_en_mer,0) where un.mois=1) as janvier,"
			." (select ifnull(un.nombre_jour_en_mer,0) where un.mois=2) as fevrier,"
			." (select ifnull(un.nombre_jour_en_mer,0) where un.mois=3) as mars,"
			." (select ifnull(un.nombre_jour_en_mer,0) where un.mois=4) as avril,"
			." (select ifnull(un.nombre_jour_en_mer,0) where un.mois=5) as mai,"
			." (select ifnull(un.nombre_jour_en_mer,0) where un.mois=6) as juin,"
			." (select ifnull(un.nombre_jour_en_mer,0) where un.mois=7) as juillet,"
			." (select ifnull(un.nombre_jour_en_mer,0) where un.mois=8) as aout,"
			." (select ifnull(un.nombre_jour_en_mer,0) where un.mois=9) as septembre,"
			." (select ifnull(un.nombre_jour_en_mer,0) where un.mois=10) as octobre,"
			." (select ifnull(un.nombre_jour_en_mer,0) where un.mois=11) as novembre,"
			." (select ifnull(un.nombre_jour_en_mer,0) where un.mois=12) as decembre"
			." FROM "
			." ("
			." 	select year(sipth.date_arrive) as annee,month(sipth.date_arrive) as mois,sipnav.immatricule,sipnav.armateur,"
			." 	sum(sipth.nbr_jour_en_mer) as nombre_jour_en_mer"
			." 	from ".$table_en_tete." as sipth"
			." 	left outer join sip_navire as sipnav on sipnav.id=sipth.id_navire"
			." 	where year(sipth.date_arrive) >=".$debut." and year(sipth.date_arrive) <=".$fin
			." 	group by year(sipth.date_arrive),month(sipth.date_arrive),armateur"
			." 	order by annee,mois,armateur"
			." ) as un	"
			." ) as deux"
			." ) as trois "
			." group by trois.annee,trois.armateur"
			." ) as fin"
			." 	order by annee,armateur";
		return $this->db->query($requete)->result();			  
	}
	public function reporting_thoniere_malagasy_etranger_jour_peche_navire_mensuel_annuel($debut,$fin,$table_en_tete) {
			// NOMBRE JOUR DE PECHE PAR NAVIRE, MENSUEL ET ANNUEL
			// Reporting ACCESS 12.9				
		$requete="select fin.annee,fin.immatricule,fin.armateur,"
				." (fin.janvier + fin.fevrier + fin.mars + fin.avril + fin.mai + fin.juin + fin.juillet + fin.aout + fin.septembre + fin.octobre + fin.novembre + fin.decembre) as Total,"
			." fin.janvier,fin.fevrier,fin.mars,fin.avril,fin.mai,fin.juin,fin.juillet,fin.aout,fin.septembre,fin.octobre,fin.novembre,fin.decembre"	
			." FROM( "
			."  select trois.annee,trois.immatricule,trois.armateur,"
			."  sum(trois.janvier) as janvier,sum(trois.fevrier) as fevrier,sum(trois.mars) as mars,sum(trois.avril) as avril,"
			."  sum(trois.mai) as mai,sum(trois.juin) as juin,sum(trois.juillet) as juillet,sum(trois.aout) as aout,"
			."  sum(trois.septembre) as septembre,sum(trois.octobre) as octobre,sum(trois.novembre) as novembre,sum(trois.decembre) as decembre"
			."  FROM "
			."  ("
			."  select deux.annee,deux.immatricule,deux.armateur,"
			."  ifnull(deux.janvier,0) as janvier,ifnull(deux.fevrier,0) as fevrier,ifnull(deux.mars,0) as mars,"
			."  ifnull(deux.avril,0) as avril,ifnull(deux.mai,0) as mai,ifnull(deux.juin,0) as juin,"
			."  ifnull(deux.juillet,0) as juillet,ifnull(deux.aout,0) as aout,ifnull(deux.septembre,0) as septembre,"
			."  ifnull(deux.octobre,0) as octobre,ifnull(deux.novembre,0) as novembre,ifnull(deux.decembre,0) as decembre"
			."  FROM "
			."  ("
			." select un.annee,un.immatricule,un.armateur,"
			." (select ifnull(un.nombre_jour_de_peche,0) where un.mois=1) as janvier,"
			." (select ifnull(un.nombre_jour_de_peche,0) where un.mois=2) as fevrier,"
			." (select ifnull(un.nombre_jour_de_peche,0) where un.mois=3) as mars,"
			." (select ifnull(un.nombre_jour_de_peche,0) where un.mois=4) as avril,"
			." (select ifnull(un.nombre_jour_de_peche,0) where un.mois=5) as mai,"
			." (select ifnull(un.nombre_jour_de_peche,0) where un.mois=6) as juin,"
			." (select ifnull(un.nombre_jour_de_peche,0) where un.mois=7) as juillet,"
			." (select ifnull(un.nombre_jour_de_peche,0) where un.mois=8) as aout,"
			." (select ifnull(un.nombre_jour_de_peche,0) where un.mois=9) as septembre,"
			." (select ifnull(un.nombre_jour_de_peche,0) where un.mois=10) as octobre,"
			." (select ifnull(un.nombre_jour_de_peche,0) where un.mois=11) as novembre,"
			." (select ifnull(un.nombre_jour_de_peche,0) where un.mois=12) as decembre"
			." FROM "
			." ("
			." 	select year(sipth.date_arrive) as annee,month(sipth.date_arrive) as mois,sipnav.immatricule,sipnav.armateur,"
			." 	sum(sipth.nbr_peche) as nombre_jour_de_peche"
			." 	from ".$table_en_tete." as sipth"
			." 	left outer join sip_navire as sipnav on sipnav.id=sipth.id_navire"
			." 	where year(sipth.date_arrive) >=".$debut." and year(sipth.date_arrive) <=".$fin
			." 	group by year(sipth.date_arrive),month(sipth.date_arrive),armateur"
			." 	order by annee,mois,armateur"
			." ) as un	"
			." ) as deux"
			." ) as trois "
			." group by trois.annee,trois.armateur"
			." ) as fin"
			." order by annee,armateur";
		return $this->db->query($requete)->result();			
	}
	
}
