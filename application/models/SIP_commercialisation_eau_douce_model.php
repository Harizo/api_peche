<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_commercialisation_eau_douce_model extends CI_Model {
    protected $table = 'sip_commercialisation_eau_douce';

    public function add($SIP_commercialisation_eau_douce) {
        $this->db->set($this->_set($SIP_commercialisation_eau_douce))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $SIP_commercialisation_eau_douce) {
        $this->db->set($this->_set($SIP_commercialisation_eau_douce))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($SIP_commercialisation_eau_douce) {
        return array(
            'id_permis'                  	    =>      $SIP_commercialisation_eau_douce['id_permis'],              
            'numero_visa'                       =>      $SIP_commercialisation_eau_douce['numero_visa'],              
            'numero_cos'                        =>      $SIP_commercialisation_eau_douce['numero_cos'],              
            'annee'               				=>      $SIP_commercialisation_eau_douce['annee'],                 
            'mois'      						=>      $SIP_commercialisation_eau_douce['mois'],                 
            'id_conservation'     				=>      $SIP_commercialisation_eau_douce['id_conservation'],                
            'id_presentation'        			=>      $SIP_commercialisation_eau_douce['id_presentation'],                 
            'coefficiant_conservation'        	=>      $SIP_commercialisation_eau_douce['coefficiant_conservation'],

            'vl_qte'                            =>      $SIP_commercialisation_eau_douce['vl_qte'],
            'vl_prix_par_kg'                    =>      $SIP_commercialisation_eau_douce['vl_prix_par_kg'],
            'vl_poids_vif'                      =>      $SIP_commercialisation_eau_douce['vl_poids_vif']


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
                sch.vl_poids_vif
               

            from
                sip_commercialisation_eau_douce as sch,
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
