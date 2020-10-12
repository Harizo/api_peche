<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_donnees_cadre_enquete_marche_model extends CI_Model {
    protected $table = 'sip_donnees_cadre_enquete_marche';

    public function add($SIP_donnees_cadre_enquete_marche) {
        $this->db->set($this->_set($SIP_donnees_cadre_enquete_marche))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $SIP_donnees_cadre_enquete_marche) {
        $this->db->set($this->_set($SIP_donnees_cadre_enquete_marche))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($SIP_donnees_cadre_enquete_marche) {
        return array(
            'id_commune'                    =>      $SIP_donnees_cadre_enquete_marche['id_commune'],
            'nom_marche'                    =>      $SIP_donnees_cadre_enquete_marche['nom_marche'],      
            'nom_ville'                     =>      $SIP_donnees_cadre_enquete_marche['nom_ville'],      
            'nbr_jour_ouvrable_mois'        =>      $SIP_donnees_cadre_enquete_marche['nbr_jour_ouvrable_mois'] ,
            'affluence_max'                 =>      $SIP_donnees_cadre_enquete_marche['affluence_max'] ,
            'nom_vendeur_spec_frais'        =>      $SIP_donnees_cadre_enquete_marche['nom_vendeur_spec_frais'] ,
            'nom_vendeur_spec_transforme'   =>      $SIP_donnees_cadre_enquete_marche['nom_vendeur_spec_transforme'] ,
            'id_espece'                     =>      $SIP_donnees_cadre_enquete_marche['id_espece'] ,
            'nbr_tot_etal'                  =>      $SIP_donnees_cadre_enquete_marche['nbr_tot_etal'] ,
            'nbr_etal_pdt_frais'            =>      $SIP_donnees_cadre_enquete_marche['nbr_etal_pdt_frais'] ,
            'nbr_etal_pdt_transforme'       =>      $SIP_donnees_cadre_enquete_marche['nbr_etal_pdt_transforme'] 

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
                        ->order_by('date','desc')
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


                com.id as id_commune,
                com.nom as nom_commune,

                scp.nbr_jour_ouvrable_mois as nbr_jour_ouvrable_mois,
                scp.affluence_max as affluence_max,
                scp.nom_vendeur_spec_frais as nom_vendeur_spec_frais,
                scp.nom_vendeur_spec_transforme as nom_vendeur_spec_transforme,

                
                esp.id as id_espece,
                esp.code as code,
                esp.nom as nom,
                esp.nom_local as nom_local,
                esp.nom_francaise as nom_francaise,
                esp.nom_scientifique as nom_scientifique,

                scp.nbr_tot_etal as nbr_tot_etal,
                scp.nbr_etal_pdt_frais as nbr_etal_pdt_frais,
                scp.nbr_etal_pdt_transforme as nbr_etal_pdt_transforme


           
            from
                sip_donnees_cadre_enquete_marche as scp,
             
                commune as com,
                district as dis,
                region as reg,
                sip_espece as esp
                
            where
                scp.id_commune = com.id
                and com.id_district = dis.id
                and dis.id_region = reg.id
                and dis.id = ".$id_district." 
                and scp.id_espece = esp.id
            order by scp.id desc

        " ;
        return $this->db->query($sql)->result();
    }


    

   
}
