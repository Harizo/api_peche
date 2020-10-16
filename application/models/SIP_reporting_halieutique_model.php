<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_reporting_halieutique_model extends CI_Model {
   
   
    
    //COLLECTE
        public function get_somme_capture_all_espece_by_dist()
        {

            $sql = 
            "
            call sip_get_somme_capture_all_espece_by_dist

            " ;
            return $this->db->query($sql)->result();
        }


        public function sip_quantite_collecte_region()
        {

            $sql = 
            "
            call sip_quantite_collecte_region

            " ;
            return $this->db->query($sql)->result();
        }

        public function sip_quantite_collecte_mois()
        {

            $sql = 
            "
            call sip_quantite_collecte_mois

            " ;
            return $this->db->query($sql)->result();
        }

        public function sip_quantite_collecte_operateur()
        {

            $sql = 
            "
            call sip_quantite_collecte_operateur

            " ;
            return $this->db->query($sql)->result();
        }

        public function sip_quantite_collecte_espece()
        {

            $sql = 
            "
            call sip_quantite_collecte_espece

            " ;
            return $this->db->query($sql)->result();
        }

        public function sip_prix_moyenne_mois()
        {

            $sql = 
            "
            call sip_prix_moyenne_mois

            " ;
            return $this->db->query($sql)->result();
        }

        public function sip_prix_moyenne_district()
        {

            $sql = 
            "
            call sip_prix_moyenne_district

            " ;
            return $this->db->query($sql)->result();
        }

        public function sip_prix_moyenne_region()
        {

            $sql = 
            "
            call sip_prix_moyenne_region

            " ;
            return $this->db->query($sql)->result();
        }
    //FIN COLLECTE

    //COMMERCE MARINE

        //VENTE LOCALE
            public function sip_qte_par_espece_vente_local()
            {

                $sql = 
                "
                call sip_qte_par_espece_vente_local

                " ;
                return $this->db->query($sql)->result();
            }

            public function sip_qte_par_district_espece_vente_local()
            {

                $sql = 
                "
                call sip_qte_par_district_espece_vente_local

                " ;
                return $this->db->query($sql)->result();
            }

            public function sip_qte_par_region_espece_vente_local()
            {

                $sql = 
                "
                call sip_qte_par_region_espece_vente_local

                " ;
                return $this->db->query($sql)->result();
            }

            public function sip_prix_moyenne_par_espece_vente_local()
            {

                $sql = 
                "
                call sip_prix_moyenne_par_espece_vente_local

                " ;
                return $this->db->query($sql)->result();
            }

            public function sip_prix_moyenne_par_district_vente_local()
            {

                $sql = 
                "
                call sip_prix_moyenne_par_district_vente_local

                " ;
                return $this->db->query($sql)->result();
            }

            public function sip_prix_moyenne_par_region_vente_local()
            {

                $sql = 
                "
                call sip_prix_moyenne_par_region_vente_local

                " ;
                return $this->db->query($sql)->result();
            }

            public function sip_qte_par_operateur_vente_local()
            {

                $sql = 
                "
                call sip_qte_par_operateur_vente_local

                " ;
                return $this->db->query($sql)->result();
            }

        //FIN VENTE LOCALE
        //EXPEDITION INTERNE

            public function sip_qte_par_espece_expedition_interne()
            {

                $sql = 
                "
                call sip_qte_par_espece_expedition_interne

                " ;
                return $this->db->query($sql)->result();
            }

            public function sip_qte_par_district_espece_expedition_interne()
            {

                $sql = 
                "
                call sip_qte_par_district_espece_expedition_interne

                " ;
                return $this->db->query($sql)->result();
            }

            public function sip_qte_par_region_espece_expedition_interne()
            {

                $sql = 
                "
                call sip_qte_par_region_espece_expedition_interne

                " ;
                return $this->db->query($sql)->result();
            }

            public function sip_prix_moyenne_par_espece_expedition_interne()
            {

                $sql = 
                "
                call sip_prix_moyenne_par_espece_expedition_interne

                " ;
                return $this->db->query($sql)->result();
            }

            public function sip_prix_moyenne_par_district_expedition_interne()
            {

                $sql = 
                "
                call sip_prix_moyenne_par_district_expedition_interne

                " ;
                return $this->db->query($sql)->result();
            }


            public function sip_prix_moyenne_par_region_expedition_interne()
            {

                $sql = 
                "
                call sip_prix_moyenne_par_region_expedition_interne

                " ;
                return $this->db->query($sql)->result();
            }

            public function sip_qte_par_operateur_expedition_interne()
            {

                $sql = 
                "
                call sip_qte_par_operateur_expedition_interne

                " ;
                return $this->db->query($sql)->result();
            }

        //FIN EXPEDITION INTERNE

        //EXPORTATION
            public function sip_qte_par_espece_exportation()
            {

                $sql = 
                "
                call sip_qte_par_espece_exportation

                " ;
                return $this->db->query($sql)->result();
            }

            public function sip_qte_par_district_espece_exportation()
            {

                $sql = 
                "
                call sip_qte_par_district_espece_exportation

                " ;
                return $this->db->query($sql)->result();
            }

            public function sip_qte_par_region_espece_exportation()
            {

                $sql = 
                "
                call sip_qte_par_region_espece_exportation

                " ;
                return $this->db->query($sql)->result();
            }

            public function sip_prix_moyenne_par_espece_exportation()
            {

                $sql = 
                "
                call sip_prix_moyenne_par_espece_exportation

                " ;
                return $this->db->query($sql)->result();
            }

            public function sip_prix_moyenne_par_district_exportation()
            {

                $sql = 
                "
                call sip_prix_moyenne_par_district_exportation

                " ;
                return $this->db->query($sql)->result();
            }

            public function sip_prix_moyenne_par_region_exportation()
            {

                $sql = 
                "
                call sip_prix_moyenne_par_region_exportation

                " ;
                return $this->db->query($sql)->result();
            }
        //FIN EXPORTATION

        //QTE POIDS VIF
            public function sip_qte_vente_locale_qte_poids_vif()
            {

                $sql = 
                "
                call sip_qte_vente_locale_qte_poids_vif

                " ;
                return $this->db->query($sql)->result();
            }
            public function sip_qte_expedition_interne_qte_poids_vif()
            {

                $sql = 
                "
                call sip_qte_expedition_interne_qte_poids_vif

                " ;
                return $this->db->query($sql)->result();
            }
            public function sip_qte_exportation_qte_poids_vif()
            {

                $sql = 
                "
                call sip_qte_exportation_qte_poids_vif

                " ;
                return $this->db->query($sql)->result();
            }

            public function sip_qte_expedie_par_desination()
            {

                $sql = 
                "
                call sip_qte_expedie_par_desination

                " ;
                return $this->db->query($sql)->result();
            }
        //FIN QTE POIDS VIF

    //FIN COMMERCE MARINE


    //COMMERCE EAU DOUCE

       
            public function sip_qte_par_espece_comm_eau_douce($params)
            {

                $sql = 
                '
                call sip_qte_par_espece_comm_eau_douce("'.$params.'")

                ' ;
                return $this->db->query($sql)->result();
            }

            public function sip_qte_par_district_comm_eau_douce($params)
            {

                $sql = 
                "
                call sip_qte_par_district_comm_eau_douce('".$params."')

                " ;
                return $this->db->query($sql)->result();
            }

            public function sip_qte_par_region_comm_eau_douce($params)
            {

                $sql = 
                "
                call sip_qte_par_region_comm_eau_douce('".$params."')

                " ;
                return $this->db->query($sql)->result();
            }

            public function sip_prix_moy_espece_comm_eau_douce($params)
            {

                $sql = 
                "
                call sip_prix_moy_espece_comm_eau_douce('".$params."')

                " ;
                return $this->db->query($sql)->result();
            }

            public function sip_prix_moy_district_comm_eau_douce($params)
            {

                $sql = 
                "
                call sip_prix_moy_district_comm_eau_douce('".$params."')

                " ;
                return $this->db->query($sql)->result();
            }

            public function sip_prix_moy_region_comm_eau_douce($params)
            {

                $sql = 
                "
                call sip_prix_moy_region_comm_eau_douce('".$params."')

                " ;
                return $this->db->query($sql)->result();
            }

            public function sip_qte_par_operateur_comm_eau_douce($params)
            {

                $sql = 
                "
                call sip_qte_par_operateur_comm_eau_douce('".$params."')

                " ;
                return $this->db->query($sql)->result();
            }

            public function sip_qte_expedie_par_desination_eau_douce()
            {

                $sql = 
                "
                call sip_qte_expedie_par_desination_eau_douce

                " ;
                return $this->db->query($sql)->result();
            }

      

    //FIN COMMERCE EAU DOUCE

   
}
