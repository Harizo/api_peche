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
	public function reporting_peche_thon_etranger_par_espece_par_mois($debut,$fin) {
		 // QTE PAR ESPECE PAR MOIS ET ANNUEL
		 // Reporting ACCESS 13.3
		$query = $this->db->query("CALL sip_proc_thon_etranger_par_espece_par_mois($debut,$fin)");
		return $query->result();		
	}
	public function reporting_peche_thon_etranger_nombre_par_espece_par_mois($debut,$fin) {
		 // NOMBRE PAR ESPECE PAR MOIS ET ANNUEL
		 // Reporting ACCESS 13.4
		$query = $this->db->query("CALL sip_proc_thon_etranger_nombre_par_espece_par_mois($debut,$fin)");
		return $query->result();		
	}
	public function reporting_thoniere_etranger_par_mois_navire_espece($debut,$fin) {
		// PAR MOIS PAR NAVIRE ET PAR ESPECE
		 // Reporting ACCESS 13.5
		$query = $this->db->query("CALL sip_proc_thon_etranger_par_mois_navire_espece($debut,$fin)");
		return $query->result();		
	}
	public function reporting_peche_thon_etranger_par_navire_espece($debut,$fin) {
		// Qté annuel par espèce et par navire
		// Reporting ACCESS 13.6
		$query = $this->db->query("CALL sip_proc_thon_etranger_par_navire_espece($debut,$fin)");
		return $query->result();		
	}	
	public function reporting_peche_thon_etranger_par_societe_mensuel_annuel($debut,$fin) {
		// Qté par societe mensuel annuel
		// Reporting ACCESS 13.7
		$query = $this->db->query("CALL sip_proc_thon_etranger_par_societe_mensuel_annuel($debut,$fin)");
		return $query->result();		
	}		
	public function reporting_peche_thon_etranger_hamecon_mensuel($debut,$fin) {
		// Qté hamecon mensuel et annuel
		// Reporting ACCESS 13.8
		$query = $this->db->query("CALL sip_proc_thon_etranger_hamecon_mensuel($debut,$fin)");
		return $query->result();		
	}	
	public function reporting_peche_thon_etranger_jour_en_mer($debut,$fin) {
		// NOMBRE JOUR EN MER PAR NAVIRE, MENSUEL ET ANNUEL
		// Reporting ACCESS 13.9
		$query = $this->db->query("CALL sip_proc_thon_etranger_jour_en_mer($debut,$fin)");
		return $query->result();		
	}	
	public function reporting_peche_thon_etranger_jour_de_peche($debut,$fin) {
		// NOMBRE JOUR DE PECHE PAR NAVIRE, MENSUEL ET ANNUEL
		// Reporting ACCESS 13.20
		$query = $this->db->query("CALL sip_proc_thon_etranger_jour_de_peche($debut,$fin)");
		return $query->result();		
	}	
	public function reporting_thoniere_etranger_estime_debarque($debut,$fin) {
			// ESTIME / DEBARQUE MENSUEL ET ANNUEL
			// Reporting ACCESS 13.21 et 13.22 REUNI
		$query = $this->db->query("CALL sip_proc_thon_etranger_estime_debarque($debut,$fin)");
		return $query->result();		
	}
	public function reporting_thoniere_etranger_par_position($debut,$fin) {
			// PAR POSITION (lat,long) MENSUEL ET ANNUEL
			// Reporting ACCESS 13.29
		$query = $this->db->query("CALL sip_proc_thon_etranger_par_position($debut,$fin)");
		return $query->result();		
	}
	
}
