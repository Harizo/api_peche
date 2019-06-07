/*//PRIX UNITAIRE MOYENNE PAR ESPECE
            $this->db->select("(sum((prix))
                 / 
                 (select COUNT(DISTINCT(espece_capture.id_espece)) from fiche_echantillonnage_capture,echantillon,espece_capture
                where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                and espece_capture.id_echantillon = echantillon.id
                and espece_capture.id_espece = id_espece_aff and ".$condition." 
                and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                and fiche_echantillonnage_capture.id_site_embarquement = id_site )) as prix_unitaire_moyenne",FALSE) ;
        //FIN PRIX UNITAIRE MOYENNE PAR ESPECE


        //PRIX UNITAIRE MOYENNE PAR UNITE DE PECHE
            $this->db->select("((select sum(prix) from fiche_echantillonnage_capture,echantillon,espece_capture where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture  
                and espece_capture.id_echantillon = echantillon.id  
                and espece_capture.id_espece = id_espece_aff
                and ".$condition." )
                 / 
                 (select COUNT(DISTINCT(echantillon.id)) from fiche_echantillonnage_capture,echantillon
                    where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                    and echantillon.id_unite_peche = id_unite_peche_aff and ".$condition." 
                    and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                    and fiche_echantillonnage_capture.id_site_embarquement = id_site )) as prix_unitaire_moyenne_unite_peche",FALSE) ;
        //FIN PRIX UNITAIRE MOYENNE PAR UNITE DE PECHE

        //somme PAB par unite peche
            
            $this->db->select("(select sum((1+echantillon.peche_hier+echantillon.peche_avant_hier+echantillon.nbr_jrs_peche_dernier_sem)/10) from fiche_echantillonnage_capture,echantillon 
                            where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
            
                            and echantillon.id_unite_peche = id_unite_peche_aff and ".$condition." 
                            and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                            and fiche_echantillonnage_capture.id_site_embarquement = id_site) as pab",FALSE);
        //somme PAB par unite peche

        //PAB MOYENNE PAR UNITE PECHE
            
            $this->db->select("((select sum((1+echantillon.peche_hier+echantillon.peche_avant_hier+echantillon.nbr_jrs_peche_dernier_sem)/10) from fiche_echantillonnage_capture,echantillon 
                            where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
            
                            and echantillon.id_unite_peche = id_unite_peche_aff and ".$condition." 
                            and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                            and fiche_echantillonnage_capture.id_site_embarquement = id_site) / (select COUNT(DISTINCT(echantillon.id)) from fiche_echantillonnage_capture,echantillon
                                where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                                and echantillon.id_unite_peche = id_unite_peche_aff and ".$condition." 
                                and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                                and fiche_echantillonnage_capture.id_site_embarquement = id_site )) as pab_moyenne",FALSE);
        //PAB MOYENNE PAR UNITE PECHE

        //CAPTURE PAR ESPECE
            $this->db->select("(select sum(capture) from fiche_echantillonnage_capture, echantillon, espece_capture
                                where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                                and echantillon.id_unite_peche = id_unite_peche_aff 
                                and espece_capture.id_echantillon = echantillon.id
                                and echantillon.id_unite_peche = id_unite_peche_aff
                                and ".$condition.") as total_capture_par_unite_peche",FALSE);
        //FIN CAPTURE PAR ESPECE*/