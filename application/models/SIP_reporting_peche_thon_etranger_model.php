<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_reporting_peche_thon_etranger_model extends CI_Model {
	public function reporting_peche_thon_etranger_qte_espece_annuel($debut,$fin) {
		// Qté par espèce annuel
		// Reporting ACCESS 13.1
		$query = $this->db->query("CALL sip_proc_thon_etranger_qte_espece_annuel($debut,$fin)");
		return $query->result();		
	}	
	public function reporting_peche_thon_etranger_nombre_espece_annuel($debut,$fin) {
		// Nombre par espèce annuel
		// Reporting ACCESS 13.2
		$query = $this->db->query("CALL sip_proc_thon_etranger_nombre_espece_annuel($debut,$fin)");
		return $query->result();		
	}	
	public function reporting_peche_thon_etranger_par_navire_espece($debut,$fin) {
		// Qté annuel par espèce et par navire
		// Reporting ACCESS 13.6
		$query = $this->db->query("CALL sip_proc_thon_etranger_par_navire_espece($debut,$fin)");
		return $query->result();		
	}	
	public function reporting_peche_thon_etranger_hamecon_mensuel($debut,$fin) {
		// Qté hamecon mensuel et annuel
		// Reporting ACCESS 13.8
		$query = $this->db->query("CALL sip_proc_thon_malagasy_hamecon_mensuel($debut,$fin)");
		return $query->result();		
	}	
	public function reporting_thoniere_etranger_estime_debarque($debut,$fin) {
			// ESTIME / DEBARQUE MENSUEL ET ANNUEL
			// Reporting ACCESS 13.21 et 13.22 REUNI
			$requete="select fin.annee,"
				." (fin.estime_janvier + fin.estime_fevrier + fin.estime_mars + fin.estime_avril + fin.estime_mai + fin.estime_juin + fin.estime_juillet + fin.estime_aout + fin.estime_septembre + fin.estime_octobre + fin.estime_novembre + fin.estime_decembre) as Total_estime,"
				." (fin.debarque_janvier + fin.debarque_fevrier + fin.debarque_mars + fin.debarque_avril + fin.debarque_mai + fin.debarque_juin + fin.debarque_juillet + fin.debarque_aout + fin.debarque_septembre + fin.debarque_octobre + fin.debarque_novembre + fin.debarque_decembre) as Total_debarque,"
				." ((fin.debarque_janvier + fin.debarque_fevrier + fin.debarque_mars + fin.debarque_avril + fin.debarque_mai + fin.debarque_juin + fin.debarque_juillet + fin.debarque_aout + fin.debarque_septembre + fin.debarque_octobre + fin.debarque_novembre + fin.debarque_decembre) - (fin.estime_janvier + fin.estime_fevrier + fin.estime_mars + fin.estime_avril + fin.estime_mai + fin.estime_juin + fin.estime_juillet + fin.estime_aout + fin.estime_septembre + fin.estime_octobre + fin.estime_novembre + fin.estime_decembre)) as difference,"
			." fin.estime_janvier,fin.debarque_janvier,fin.estime_fevrier,fin.debarque_fevrier,fin.estime_mars,fin.debarque_mars,"
			." fin.estime_avril,fin.debarque_avril,fin.estime_mai,fin.debarque_mai,fin.estime_juin,fin.debarque_juin,"
			." fin.estime_juillet,fin.debarque_juillet,fin.estime_aout,fin.debarque_aout,fin.estime_septembre,fin.debarque_septembre,"
			." fin.estime_octobre,fin.debarque_octobre,fin.estime_novembre,fin.debarque_novembre,fin.estime_decembre,fin.debarque_decembre	"
			." FROM( "
			."  select trois.annee,"
			."  sum(trois.estime_janvier) as estime_janvier,sum(trois.debarque_janvier) as debarque_janvier,"
			."  sum(trois.estime_fevrier) as estime_fevrier,sum(trois.debarque_fevrier) as debarque_fevrier,"
			."  sum(trois.estime_mars) as estime_mars,sum(trois.debarque_mars) as debarque_mars,"
			."  sum(trois.estime_avril) as estime_avril,sum(trois.debarque_avril) as debarque_avril,"
			."  sum(trois.estime_mai) as estime_mai,sum(trois.debarque_mai) as debarque_mai,"
			."  sum(trois.estime_juin) as estime_juin,sum(trois.debarque_juin) as debarque_juin,"
			."  sum(trois.estime_juillet) as estime_juillet,sum(trois.debarque_juillet) as debarque_juillet,"
			."  sum(trois.estime_aout) as estime_aout,sum(trois.debarque_aout) as debarque_aout,"
			."  sum(trois.estime_septembre) as estime_septembre,sum(trois.debarque_septembre) as debarque_septembre,"
			."  sum(trois.estime_octobre) as estime_octobre,sum(trois.debarque_octobre) as debarque_octobre,"
			."  sum(trois.estime_novembre) as estime_novembre,sum(trois.debarque_novembre) as debarque_novembre,"
			."  sum(trois.estime_decembre) as estime_decembre,sum(trois.debarque_decembre) as debarque_decembre"
			."  FROM "
			."  ("
			."  select deux.annee,"
			."  ifnull(deux.estime_janvier,0) as estime_janvier,ifnull(deux.debarque_janvier,0) as debarque_janvier,"
			."  ifnull(deux.estime_fevrier,0) as estime_fevrier,ifnull(deux.debarque_fevrier,0) as debarque_fevrier,"
			."  ifnull(deux.estime_mars,0) as estime_mars,ifnull(deux.debarque_mars,0) as debarque_mars,"
			."  ifnull(deux.estime_avril,0) as estime_avril,ifnull(deux.debarque_avril,0) as debarque_avril,"
			."  ifnull(deux.estime_mai,0) as estime_mai,ifnull(deux.debarque_mai,0) as debarque_mai,"
			."  ifnull(deux.estime_juin,0) as estime_juin,ifnull(deux.debarque_juin,0) as debarque_juin,"
			."  ifnull(deux.estime_juillet,0) as estime_juillet,ifnull(deux.debarque_juillet,0) as debarque_juillet,"
			."  ifnull(deux.estime_aout,0) as estime_aout,ifnull(deux.debarque_aout,0) as debarque_aout,"
			."  ifnull(deux.estime_septembre,0) as estime_septembre,ifnull(deux.debarque_septembre,0) as debarque_septembre,"
			."  ifnull(deux.estime_octobre,0) as estime_octobre,ifnull(deux.debarque_octobre,0) as debarque_octobre,"
			."  ifnull(deux.estime_novembre,0) as estime_novembre,ifnull(deux.debarque_novembre,0) as debarque_novembre,"
			."  ifnull(deux.estime_decembre,0) as estime_decembre,ifnull(deux.debarque_decembre,0) as debarque_decembre"
			."  FROM "
			."  ("
			." select un.annee,"
			." (select ifnull(un.estime,0) where un.mois=1) as estime_janvier,"
			." (select ifnull(un.debarque,0) where un.mois=1) as debarque_janvier,"
			." (select ifnull(un.estime,0) where un.mois=2) as estime_fevrier,"
			." (select ifnull(un.debarque,0) where un.mois=2) as debarque_fevrier,"
			." (select ifnull(un.estime,0) where un.mois=3) as estime_mars,"
			." (select ifnull(un.debarque,0) where un.mois=3) as debarque_mars,"
			." (select ifnull(un.estime,0) where un.mois=4) as estime_avril,"
			." (select ifnull(un.debarque,0) where un.mois=4) as debarque_avril,"
			." (select ifnull(un.estime,0) where un.mois=5) as estime_mai,"
			." (select ifnull(un.debarque,0) where un.mois=5) as debarque_mai,"
			." (select ifnull(un.estime,0) where un.mois=6) as estime_juin,"
			." (select ifnull(un.debarque,0) where un.mois=6) as debarque_juin,"
			." (select ifnull(un.estime,0) where un.mois=7) as estime_juillet,"
			." (select ifnull(un.debarque,0) where un.mois=7) as debarque_juillet,"
			." (select ifnull(un.estime,0) where un.mois=8) as estime_aout,"
			." (select ifnull(un.debarque,0) where un.mois=8) as debarque_aout,"
			." (select ifnull(un.estime,0) where un.mois=9) as estime_septembre,"
			." (select ifnull(un.debarque,0) where un.mois=9) as debarque_septembre,"
			." (select ifnull(un.estime,0) where un.mois=10) as estime_octobre,"
			." (select ifnull(un.debarque,0) where un.mois=10) as debarque_octobre,"
			." (select ifnull(un.estime,0) where un.mois=11) as estime_novembre,"
			." (select ifnull(un.debarque,0) where un.mois=11) as debarque_novembre,"
			." (select ifnull(un.estime,0) where un.mois=12) as estime_decembre,"
			." (select ifnull(un.debarque,0) where un.mois=12) as debarque_decembre"
			." FROM "
			." ("
			." 	select year(sipth.date_arrive) as annee,month(sipth.date_arrive) as mois,"
			." 	sum(sipseqpi.total_estime) as estime,sum(sipseqpi.total_debarque) as debarque"
			." 	from sip_peche_thoniere_etranger as sipth"
			." 	left outer join sip_sequence_peche_thon_etranger as sipseq on sipseq.id_peche_thoniere_etranger=sipth.id"
			." 	left outer join sip_sequence_peche_thon_etranger_pi as sipseqpi on sipseqpi.id_sequence_peche_thon_etranger=sipseq.id"
			." 	where year(sipth.date_arrive) >=".$debut." and year(sipth.date_arrive) <=".$fin
			." 	group by year(sipth.date_arrive),month(sipth.date_arrive)"
			." 	order by annee,mois"
			." ) as un	"
			." ) as deux"
			." ) as trois "
			." group by trois.annee"
			." ) as fin"
			." 	order by annee";
		return $this->db->query($requete)->result();			  
	}
	public function reporting_thoniere_etranger_par_position($debut,$fin) {
			// PAR POSITION (lat,long) MENSUEL ET ANNUEL
			// Reporting ACCESS 13.29
			$requete="select fin.annee,fin.latitude,fin.longitude,fin.code_espece,fin.nom_espece,"
				." (fin.janvier + fin.fevrier + fin.mars + fin.avril + fin.mai + fin.juin + fin.juillet + fin.aout + fin.septembre + fin.octobre + fin.novembre + fin.decembre) as Total,"
			." fin.janvier,fin.fevrier,fin.mars,fin.avril,fin.mai,fin.juin,"
			." fin.juillet,fin.aout,fin.septembre,fin.octobre,fin.novembre,fin.decembre"
			." FROM( "
			."  select trois.annee,trois.latitude,trois.longitude,trois.code_espece,trois.nom_espece,"
			."  sum(trois.janvier) as janvier,sum(trois.fevrier) as fevrier,sum(trois.mars) as mars,sum(trois.avril) as avril,"
			."  sum(trois.mai) as mai,sum(trois.juin) as juin,sum(trois.juillet) as juillet,sum(trois.aout) as aout,"
			."  sum(trois.septembre) as septembre,sum(trois.octobre) as octobre,sum(trois.novembre) as novembre,sum(trois.decembre) as decembre"
			."  FROM "
			."  ("
			."  select deux.annee,deux.latitude,deux.longitude,deux.code_espece,deux.nom_espece,"
			."  ifnull(deux.janvier,0) as janvier,ifnull(deux.fevrier,0) as fevrier,ifnull(deux.mars,0) as mars,ifnull(deux.avril,0) as avril,"
			."  ifnull(deux.mai,0) as mai,ifnull(deux.juin,0) as juin,ifnull(deux.juillet,0) as juillet,ifnull(deux.aout,0) as aout,"
			."  ifnull(deux.septembre,0) as septembre, ifnull(deux.octobre,0) as octobre,ifnull(deux.novembre,0) as novembre,ifnull(deux.decembre,0) as decembre"
			."  FROM" 
			."  ("
			." select un.annee,un.latitude,un.longitude,un.code_espece,un.nom_espece,"
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
			." 	select year(sipth.date_arrive) as annee,month(sipth.date_arrive) as mois,"
			." 	sipseqpi.postlatitude as latitude,sipseqpi.postlongitude as longitude,sipe.code as code_espece,sipe.nom as nom_espece, "
			." 	sum(sipseqcap.qte) as quantite"
			." 	from sip_peche_thoniere_etranger as sipth"
			." 	left outer join sip_sequence_peche_thon_etranger as sipseq on sipseq.id_peche_thoniere_etranger=sipth.id"
			." 	left outer join sip_sequence_peche_thon_etranger_pi as sipseqpi on sipseqpi.id_sequence_peche_thon_etranger=sipseq.id"
			." 	left outer join sip_sequence_peche_thon_etranger_capture as sipseqcap on sipseqcap.id_sequence_peche_thon_etranger=sipseq.id"
			." 	left outer join sip_espece as sipe on sipe.id=sipseqcap.id_espece"
			." 	where year(sipth.date_arrive) >=".$debut." and year(sipth.date_arrive) <=".$fin
			." 	group by year(sipth.date_arrive),month(sipth.date_arrive),sipseqpi.postlatitude,sipseqpi.postlongitude,sipe.code,sipe.nom"
			." 	order by annee,mois	"
			." ) as un	"
			." ) as deux"
			." ) as trois "
			." group by trois.annee,trois.latitude,trois.longitude,trois.code_espece,trois.nom_espece"
			." ) as fin"
			." 	order by annee,nom_espece,latitude,longitude";

		return $this->db->query($requete)->result();			  
	}
	
}
