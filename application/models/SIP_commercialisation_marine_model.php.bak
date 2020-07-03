<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_commercialisation_marine_model extends CI_Model {
    protected $table = 'sip_commercialisation_marine';

    public function add($SIP_commercialisation_marine) {
        $this->db->set($this->_set($SIP_commercialisation_marine))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $SIP_commercialisation_marine) {
        $this->db->set($this->_set($SIP_commercialisation_marine))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($SIP_commercialisation_marine) {
        return array(
            'id_permis'                  	    =>      $SIP_commercialisation_marine['id_permis'],              
            'numero_visa'                       =>      $SIP_commercialisation_marine['numero_visa'],              
            'numero_cos'                        =>      $SIP_commercialisation_marine['numero_cos'],              
            'annee'               				=>      $SIP_commercialisation_marine['annee'],                 
            'mois'      						=>      $SIP_commercialisation_marine['mois'],                 
            'id_conservation'     				=>      $SIP_commercialisation_marine['id_conservation'],                
            'id_presentation'        			=>      $SIP_commercialisation_marine['id_presentation'],                 
            'coefficiant_conservation'        	=>      $SIP_commercialisation_marine['coefficiant_conservation'],

            'vl_qte'                            =>      $SIP_commercialisation_marine['vl_qte'],
            'vl_prix_par_kg'                    =>      $SIP_commercialisation_marine['vl_prix_par_kg'],
            'vl_poids_vif'                      =>      $SIP_commercialisation_marine['vl_poids_vif'],

            'exp_qte'                           =>      $SIP_commercialisation_marine['exp_qte'],
            'exp_prix_par_kg'                   =>      $SIP_commercialisation_marine['exp_prix_par_kg'],
            'exp_poids_vif'                     =>      $SIP_commercialisation_marine['exp_poids_vif'],
            'exp_destination'                   =>      $SIP_commercialisation_marine['exp_destination'],
            'date_expedition'                   =>      $SIP_commercialisation_marine['date_expedition'],

            'nbr_colis'                         =>      $SIP_commercialisation_marine['nbr_colis'],
            'nom_dest'                          =>      $SIP_commercialisation_marine['nom_dest'],
            'adresse_dest'                      =>      $SIP_commercialisation_marine['adresse_dest'],
            'lieu_exped'                        =>      $SIP_commercialisation_marine['lieu_exped'],
            'moyen_transport'                   =>      $SIP_commercialisation_marine['moyen_transport']
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
                        ->order_by('annee')
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }


    public function find_all_join($id_permis)
    {

        $sql = 
        "
            select 

                sch.id as id,

                cons.id as id_conservation,
                cons.libelle as libelle_conservation,

                pres.id as id_presentation,
                pres.libelle as libelle_presentation,
                sch.coefficiant_conservation,

                sch.numero_visa,
                sch.numero_cos,
                sch.annee,
                sch.mois,

                sch.vl_qte,
                sch.vl_prix_par_kg,
                sch.vl_poids_vif,

                sch.exp_qte,
                sch.exp_prix_par_kg,
                sch.exp_poids_vif,
                sch.exp_destination,
                
                sch.date_expedition,
                sch.nbr_colis,
                sch.nom_dest,
                sch.adresse_dest,
                sch.lieu_exped,
                sch.moyen_transport
               

            from
                sip_commercialisation_marine as sch,
                sip_permis as cm,
                sip_presentation as pres,
                sip_conservation as cons
            where
                sch.id_permis = cm.id
                
                and sch.id_presentation = pres.id
                and sch.id_conservation = cons.id
                and cm.id = ".$id_permis." 
            order by sch.annee desc

        " ;
        return $this->db->query($sql)->result();
    }
    public function findById($id)  {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }  
    }
}
