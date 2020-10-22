<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_reporting_poisson_demersaux_model extends CI_Model {
	public function reporting_poisson_demersaux_qte_espece_annuel($debut,$fin) {
		// Qté par espèce
		// Reporting ACCESS 11.1
		$query = $this->db->query("CALL sip_proc_poisson_demersaux_qte_espece_annuel($debut,$fin)");
		return $query->result();		
	}	
	public function reporting_poisson_demersaux_par_navire_par_espece($debut,$fin) {
		// Qté par navire espèce
		// Reporting ACCESS 11.2
		$query = $this->db->query("CALL sip_proc_poisson_demersaux_par_navire_espece($debut,$fin)");
		return $query->result();		
	}	
	public function reporting_poisson_demersaux_par_navire_espece_maree($debut,$fin) {
		// Qté par navire espèce maree
		// Reporting ACCESS 11.4
		$query = $this->db->query("CALL sip_proc_poisson_demersaux_par_navire_espece_maree($debut,$fin)");
		return $query->result();		
	}	
	public function reporting_poisson_demersaux_par_societe($debut,$fin) {
		// Qté par espèce société
		// Reporting ACCESS 11.5
		$query = $this->db->query("CALL sip_proc_poisson_demersaux_par_societe($debut,$fin)");
		return $query->result();		
	}	

    
}
