<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_reporting_peche_artisanale_model extends CI_Model {

	public function reporting_peche_artisanale_qte_espece_annuel($debut,$fin) {
		// Qté par espèce
		// Reporting ACCESS 11.1
		$query = $this->db->query("CALL sip_proc_peche_artisanale_qte_espece_annuel($debut,$fin)");
		return $query->result();		
	}	
	public function reporting_artisanale_par_navire_par_espece($debut,$fin) {
		// Qté par navire espèce
		// Reporting ACCESS 11.2
		$query = $this->db->query("CALL sip_proc_peche_artisanale_par_navire_espece($debut,$fin)");
		return $query->result();		
	}	
	public function reporting_artisanale_demersaux_par_espece_par_mois($debut,$fin,$table_en_tete,$table_detail,$cle_etrangere_detail) {
		 // PAR ESPECE PAR MOIS
		 // Reporting ACCESS 11.3
		$requete="select fin.annee as Année,fin.nom_espece as Espèce,"
				." (fin.janvier + fin.fevrier + fin.mars + fin.avril + fin.mai + fin.juin + fin.juillet + fin.aout + fin.septembre + fin.octobre + fin.novembre + fin.decembre) as Total,"
			." fin.janvier as Janv,fin.fevrier as Fév,fin.mars as Mars,fin.avril as Avr,fin.mai as Mai,fin.juin as Juin,fin.juillet as Juil,fin.aout as Aout,fin.septembre as Sept,fin.octobre as Oct,fin.novembre as Nov,fin.decembre as Déc"	
			." FROM( "
			."  select trois.annee,trois.code_espece,trois.nom_espece,"
			."  sum(trois.janvier) as janvier,sum(trois.fevrier) as fevrier,sum(trois.mars) as mars,sum(trois.avril) as avril,"
			."  sum(trois.mai) as mai,sum(trois.juin) as juin,sum(trois.juillet) as juillet,sum(trois.aout) as aout,"
			."  sum(trois.septembre) as septembre,sum(trois.octobre) as octobre,sum(trois.novembre) as novembre,sum(trois.decembre) as decembre"
			."  FROM" 
			."  ("
			."  select deux.annee,deux.code_espece,deux.nom_espece,"
			."  ifnull(deux.janvier,0) as janvier,ifnull(deux.fevrier,0) as fevrier,ifnull(deux.mars,0) as mars,"
			."  ifnull(deux.avril,0) as avril,ifnull(deux.mai,0) as mai,ifnull(deux.juin,0) as juin,"
			."  ifnull(deux.juillet,0) as juillet,ifnull(deux.aout,0) as aout,ifnull(deux.septembre,0) as septembre,"
			."  ifnull(deux.octobre,0) as octobre,ifnull(deux.novembre,0) as novembre,ifnull(deux.decembre,0) as decembre"
			."  FROM "
			."  ("
			." select un.annee,un.code_espece,un.nom_espece,"
			." case when un.mois= 1 then round(ifnull(un.quantite,0),2) end as janvier,"
			." case when un.mois= 2 then round(ifnull(un.quantite,0),2) end as fevrier,"
			." case when un.mois= 3 then round(ifnull(un.quantite,0),2) end as mars,"
			." case when un.mois= 4 then round(ifnull(un.quantite,0),2) end as avril,"
			." case when un.mois= 5 then round(ifnull(un.quantite,0),2) end as mai,"
			." case when un.mois= 6 then round(ifnull(un.quantite,0),2) end as juin,"
			." case when un.mois= 7 then round(ifnull(un.quantite,0),2) end as juillet,"
			."case when un.mois= 8 then round(ifnull(un.quantite,0),2) end as aout,"
			." case when un.mois= 9 then round(ifnull(un.quantite,0),2) end as septembre,"
			." case when un.mois= 10 then round(ifnull(un.quantite,0),2) end as octobre,"
			." case when un.mois= 11 then round(ifnull(un.quantite,0),2) end as novembre,"
			." case when un.mois= 12 then round(ifnull(un.quantite,0),2) end as decembre "
			." FROM "
			." ("
			." 	select year(sipd.date_arrive) as annee,month(sipd.date_arrive) as mois,sipe.code as code_espece,sipe.nom as nom_espece,"
			." 	sum(sipdet.quantite) as quantite"
			." 	from  ".$table_en_tete." as sipd"
			." 	left outer join  ".$table_detail." as sipdet on sipdet.".$cle_etrangere_detail."=sipd.id"
			." 	left outer join sip_espece as sipe on sipe.id=sipdet.id_espece"
			." 	where year(sipd.date_arrive) >=".$debut." and year(sipd.date_arrive) <=".$fin
			." 	group by year(sipd.date_arrive),month(sipd.date_arrive),code_espece,nom_espece"
			." 	order by annee,mois,code_espece"
			." ) as un"	
			." ) as deux"
			." ) as trois" 
			." group by trois.annee,trois.code_espece,trois.nom_espece"
			." ) as fin";
		return $this->db->query($requete)->result();			  
	}
	public function reporting_peche_artisanale_par_navire_espece_maree($debut,$fin) {
		// Qté par navire espèce maree
		// Reporting ACCESS 11.4
		$query = $this->db->query("CALL sip_proc_peche_artisanale_par_navire_espece_maree($debut,$fin)");
		return $query->result();		
	}	
	public function reporting_peche_artisanale_par_societe($debut,$fin) {
		// Qté par espèce société
		// Reporting ACCESS 11.5
		$query = $this->db->query("CALL sip_proc_peche_artisanale_par_societe($debut,$fin)");
		return $query->result();		
	}	
	public function reporting_artisanale_demersaux_par_mois_navire_espece($debut,$fin,$table_en_tete,$table_detail,$cle_etrangere_detail) {
		// PAR MOIS PAR NAVIRE ET PAR ESPECE
		// Reporting ACCESS 11.6
		$requete="select final.annee as Année,final.nom_navire as Navire,final.armateur as Armateur,final.nom_espece as Espèce,"
			." sum(final.janvier+final.fevrier+final.mars+final.avril+final.mai+final.juin + "
			." final.juillet+final.aout+final.septembre+final.octobre+final.novembre+final.decembre) as Total,"
			." sum(final.janvier) as Janv,sum(final.fevrier) as Fév,sum(final.mars) as Mars,sum(final.avril) as Avr,"
			." sum(final.mai) as Mai,sum(final.juin) as Juin,sum(final.juillet) as Juil,sum(final.aout) as Aout,"
			." sum(final.septembre) as Sept,sum(final.octobre) as Oct,sum(final.novembre) as Nov,sum(final.decembre) as Déc"
			." from "
			." ("
			." select trois.annee,trois.nom_navire,trois.armateur,trois.code_espece,trois.nom_espece,"
			." ifnull(trois.janvier,0) as janvier,ifnull(trois.fevrier,0) as fevrier,"
			." ifnull(trois.mars,0) as mars,ifnull(trois.avril,0) as avril,"
			." ifnull(trois.mai,0) as mai,ifnull(trois.juin,0) as juin,"
			." ifnull(trois.juillet,0) as juillet,ifnull(trois.aout,0) as aout,"
			." ifnull(trois.septembre,0) as septembre,ifnull(trois.octobre,0) as octobre,"
			." ifnull(trois.novembre,0) as novembre,ifnull(trois.decembre,0) as decembre"
			." from "
			." ("
			." select deux.annee,deux.nom_navire,deux.armateur,deux.code_espece,deux.nom_espece,"
			." case when deux.mois= 1 then round(ifnull(deux.quantite,0),2) end as janvier,"
			." case when deux.mois= 2 then round(ifnull(deux.quantite,0),2) end as fevrier,"
			." case when deux.mois= 3 then round(ifnull(deux.quantite,0),2) end as mars,"
			." case when deux.mois= 4 then round(ifnull(deux.quantite,0),2) end as avril,"
			." case when deux.mois= 5 then round(ifnull(deux.quantite,0),2) end as mai,"
			." case when deux.mois= 6 then round(ifnull(deux.quantite,0),2) end as juin,"
			." case when deux.mois= 7 then round(ifnull(deux.quantite,0),2) end as juillet,"
			."case when deux.mois= 8 then round(ifnull(deux.quantite,0),2) end as aout,"
			." case when deux.mois= 9 then round(ifnull(deux.quantite,0),2) end as septembre,"
			." case when deux.mois= 10 then round(ifnull(deux.quantite,0),2) end as octobre,"
			." case when deux.mois= 11 then round(ifnull(deux.quantite,0),2) end as novembre,"
			." case when deux.mois= 12 then round(ifnull(deux.quantite,0),2) end as decembre "
			." from "
			." ("
			." select un.annee,un.mois,un.nom_navire,un.armateur,un.code_espece,un.nom_espece,sum(un.quantite) as quantite"
			." from ("
			." 	select year(sipd.date_arrive) as annee,month(sipd.date_arrive) as mois,sipnav.nom as nom_navire,sipnav.armateur,"
			." 	sipe.code as code_espece,sipe.nom as nom_espece,sipdet.quantite"
			." 	from ".$table_en_tete." as sipd"
			." 	left outer join sip_navire as sipnav on sipnav.id=sipd.id_navire"
			." 	left outer join ".$table_detail." as sipdet on sipdet.".$cle_etrangere_detail."=sipd.id"
			." 	left outer join sip_espece as sipe on sipe.id=sipdet.id_espece" 
			." 	where year(sipd.date_arrive) >=".$debut." and year(sipd.date_arrive) <=".$fin
			." 	order by annee,mois,nom_navire,code_espece"
			."  ) as un"
			."  group by un.annee,un.mois,un.nom_navire,un.armateur,un.code_espece,un.nom_espece"
			."  ) as deux"
			."  ) as trois"
			."  order by trois.annee,trois.nom_navire,trois.nom_espece"
			."  ) as final"
			."  group by final.annee,final.nom_navire,final.armateur,final.code_espece,final.nom_espece"
			."  order by final.annee,final.nom_navire,final.nom_espece";
		return $this->db->query($requete)->result();			  
	}
    
}
