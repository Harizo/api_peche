<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_reporting_peche_thon_malagasy_model extends CI_Model {
	public function reporting_peche_thon_malagasy_qte_espece_annuel($debut,$fin) {
		// Qté par espèce annuel
		// Reporting ACCESS 12.1
		$query = $this->db->query("CALL sip_proc_thon_malagasy_qte_espece_annuel($debut,$fin)");
		return $query->result();		
	}	
	public function reporting_thoniere_malagasy_etranger_par_espece_par_mois($debut,$fin) {
			 // PAR ESPECE PAR MOIS ET ANNUEL
			 // Reporting ACCESS 12.2
		$query = $this->db->query("CALL sip_proc_thon_malagasy_par_espece_par_mois($debut,$fin)");
		return $query->result();		
	}
	public function reporting_thoniere_malagasy_par_mois_navire_espece($debut,$fin) {
		// PAR MOIS PAR NAVIRE ET PAR ESPECE
		// Reporting ACCESS 12.3
		$query = $this->db->query("CALL sip_proc_thon_malagasy_par_mois_navire_espece($debut,$fin)");
		return $query->result();					
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
		$query = $this->db->query("CALL sip_proc_thon_malagasy_hamecon_mensuel($debut,$fin)");
		return $query->result();		
	}	

	public function reporting_peche_thon_malagasy_par_societe_mensuel_annuel($debut,$fin) {
			// PAR SOCIETE MENSUEL ET ANNUEL
			// Reporting ACCESS 12.6
		$query = $this->db->query("CALL sip_proc_thon_malagasy_par_societe_mensuel_annuel($debut,$fin)");
		return $query->result();		
    }
	public function reporting_thoniere_malagasy_etranger_jour_en_mer_navire_mensuel_annuel($debut,$fin) {	
			// NOMBRE JOUR EN MER PAR NAVIRE, MENSUEL ET ANNUEL
			// Reporting ACCESS 12.8					
		$query = $this->db->query("CALL sip_proc_thon_malagasy_jour_en_mer($debut,$fin)");
		return $query->result();		
	}
	public function reporting_thoniere_malagasy_etranger_jour_peche_navire_mensuel_annuel($debut,$fin) {
			// NOMBRE JOUR DE PECHE PAR NAVIRE, MENSUEL ET ANNUEL
			// Reporting ACCESS 12.9				
		$query = $this->db->query("CALL sip_proc_thon_malagasy_jour_de_peche($debut,$fin)");
		return $query->result();		
	}
	
}
