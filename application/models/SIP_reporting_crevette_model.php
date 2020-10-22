<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_reporting_crevette_model extends CI_Model {
   
   
    
    //RELEVE CAPTURE
        public function tableau_de_bord_maree()
        {

            $sql = 
            "
                select 
                    spc.annee AS Année,
                    ssc.nom AS ".'"Société de pêche"'.",
                    sbc.immatriculation AS Immatriculation,
                    sbc.nom AS Bâteau,
                    spc.num_maree AS ".'"Num marée"'.",
                    spc.maree AS Marée
                FROM
                    sip_production_crevette AS spc,
                    sip_bateau_crevette AS sbc,
                    sip_societe_crevette AS ssc
                WHERE
                    spc.id_bateau_crevette = sbc.id
                    AND sbc.id_societe_crevette = ssc.id
                    
                ORDER BY ssc.id,spc.annee

            " ;
            return $this->db->query($sql)->result();
        }

        public function nombre_fiche_par_maree()
        {

            $sql = 
            "
                CALL sip_nombre_fiche_par_maree_peche_crevette 

            " ;
            return $this->db->query($sql)->result();
        }

        public function qte_par_maree($params)
        {

            $sql = 
            "
                CALL sip_qte_par_maree_peche_crevette('".$params."')

            " ;
            return $this->db->query($sql)->result();
        }

        public function sip_qte_par_bateau_peche_crevette($params)
        {

            $sql = 
            "
                CALL sip_qte_par_bateau_peche_crevette('".$params."')

            " ;
            return $this->db->query($sql)->result();
        }

        public function sip_qte_par_societe_peche_crevette($params)
        {

            $sql = 
            "
                CALL sip_qte_par_societe_peche_crevette('".$params."')

            " ;
            return $this->db->query($sql)->result();
        }

        //COMMERCE
        public function sip_qte_commercialise_crevette($params)
        {

            $sql = 
            "
                CALL sip_qte_commercialise_crevette('".$params."')

            " ;
            return $this->db->query($sql)->result();
        }

        public function sip_qte_par_mois_commercialise_crevette($params)
        {

            $sql = 
            "
                CALL sip_qte_par_mois_commercialise_crevette('".$params."')

            " ;
            return $this->db->query($sql)->result();
        }

        public function sip_qte_par_societe_commercialise_crevette($params)
        {

            $sql = 
            "
                CALL sip_qte_par_societe_commercialise_crevette('".$params."')

            " ;
            return $this->db->query($sql)->result();
        }
        public function sip_prix_moy_par_mois_commercialise_crevette($params)
        {

            $sql = 
            "
                CALL sip_prix_moy_par_mois_commercialise_crevette('".$params."')

            " ;
            return $this->db->query($sql)->result();
        }

        public function sip_qte_exporte_par_dest_commerce_crevette()
        {

            $sql = 
            "
                CALL sip_qte_exporte_par_dest_commerce_crevette 

            " ;
            return $this->db->query($sql)->result();
        }
        //FIN COMMERCE


        //EXPORTATION
        public function sip_qte_par_espece_exportation_crevette()
        {

            $sql = 
            "
                CALL sip_qte_par_espece_exportation_crevette 

            " ;
            return $this->db->query($sql)->result();
        }

        public function sip_qte_par_mois_exportation_crevette()
        {

            $sql = 
            "
                CALL sip_qte_par_mois_exportation_crevette 

            " ;
            return $this->db->query($sql)->result();
        }

        public function sip_qte_par_destination_exportation_crevette()
        {

            $sql = 
            "
                CALL sip_qte_par_destination_exportation_crevette 

            " ;
            return $this->db->query($sql)->result();
        }

        public function sip_qte_par_societe_exportation_crevette()
        {

            $sql = 
            "
                CALL sip_qte_par_societe_exportation_crevette 

            " ;
            return $this->db->query($sql)->result();
        }

        public function sip_valeurs_par_espece_exportation_crevette()
        {

            $sql = 
            "
                CALL sip_valeurs_par_espece_exportation_crevette 

            " ;
            return $this->db->query($sql)->result();
        }

        public function sip_valeurs_en_devise_par_mois_exportation_crevette()
        {

            $sql = 
            "
                CALL sip_valeurs_en_devise_par_mois_exportation_crevette 

            " ;
            return $this->db->query($sql)->result();
        }

        //FIN EXPORTATION


    //FIN RELEVE CAPTURE

    

   
}
