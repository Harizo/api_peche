<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_fiche_enquete_marche_model extends CI_Model {
    protected $table = 'sip_fiche_enquete_marche';

    public function add($SIP_fiche_enquete_marche) {
        $this->db->set($this->_set($SIP_fiche_enquete_marche))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $SIP_fiche_enquete_marche) {
        $this->db->set($this->_set($SIP_fiche_enquete_marche))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($SIP_fiche_enquete_marche) {
        return array(
            'id_district'                   =>      $SIP_fiche_enquete_marche['id_district'],
            'nom_marche'                    =>      $SIP_fiche_enquete_marche['nom_marche'],      
            'nom_ville'                     =>      $SIP_fiche_enquete_marche['nom_ville'],     
            'date_releve'                   =>      $SIP_fiche_enquete_marche['date_releve'],     
            'nbr_jour_ouvrable_mois'        =>      $SIP_fiche_enquete_marche['nbr_jour_ouvrable_mois'] ,
            'nbr_tot_etal'                  =>      $SIP_fiche_enquete_marche['nbr_tot_etal'] ,
            'nbr_etal_pdt_frais'            =>      $SIP_fiche_enquete_marche['nbr_etal_pdt_frais'] ,
            'nbr_etal_pdt_transforme'       =>      $SIP_fiche_enquete_marche['nbr_etal_pdt_transforme'], 
            'annee'                         =>      $SIP_fiche_enquete_marche['annee'],     
            'mois'                          =>      $SIP_fiche_enquete_marche['mois'],     
            'domaines'                      =>      $SIP_fiche_enquete_marche['domaines'],     
            'id_espece'                     =>      $SIP_fiche_enquete_marche['id_espece'] ,
            'id_presentation'               =>      $SIP_fiche_enquete_marche['id_presentation'] ,
            'id_conservation'               =>      $SIP_fiche_enquete_marche['id_conservation'] ,
            'detaillant'                    =>      $SIP_fiche_enquete_marche['detaillant'] ,
            'offre_kg'                      =>      $SIP_fiche_enquete_marche['offre_kg'] ,
            'prix_kg'                       =>      $SIP_fiche_enquete_marche['prix_kg'] 

        );
    }
    public function delete($id) {
        $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }  
    }
    public function findAll() {
               
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('date_releve','desc')
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }

    public function findById($id)  {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }  
    }

    public function find_all_by_district($id_district)
    {

        $sql = 
        "
            select 
                scp.id as id,
                scp.nom_marche as nom_marche,
                scp.nom_ville as nom_ville,


                dis.id as id_district,
                dis.nom as nom_district,

                scp.date_releve as date_releve,
                scp.nbr_jour_ouvrable_mois as nbr_jour_ouvrable_mois,

                scp.nbr_tot_etal as nbr_tot_etal,
                scp.nbr_etal_pdt_frais as nbr_etal_pdt_frais,
                scp.nbr_etal_pdt_transforme as nbr_etal_pdt_transforme,

                scp.annee as annee,
                scp.mois as mois,
                scp.domaines as domaines,

                
                esp.id as id_espece,
                esp.code as code,
                esp.nom as nom,
                esp.nom_local as nom_local,
                esp.nom_francaise as nom_francaise,
                esp.nom_scientifique as nom_scientifique,

                ste.id as id_type_espece,
                ste.libelle as libelle_type_espece,

                cons.id as id_conservation,
                cons.libelle as libelle_conservation,

                pres.id as id_presentation,
                pres.libelle as libelle_presentation,

                scp.detaillant as detaillant,
                scp.offre_kg as offre_kg,
                scp.prix_kg as prix_kg





           
            from
                sip_fiche_enquete_marche as scp,
                district as dis,
                region as reg,
                sip_espece as esp,
                sip_type_espece as ste,
                sip_presentation as pres,
                sip_conservation as cons
                
            where
                scp.id_district = dis.id
                and scp.id_conservation = cons.id
                and scp.id_presentation = pres.id
                and dis.id_region = reg.id
                and dis.id = ".$id_district." 
                and scp.id_espece = esp.id
                and esp.type_espece = ste.id
            order by scp.id desc

        " ;
        return $this->db->query($sql)->result();
    }


    

   
}
