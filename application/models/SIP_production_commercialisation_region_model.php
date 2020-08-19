<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_production_commercialisation_region_model extends CI_Model {
    protected $table = 'sip_production_commercialisation_region';
    public function add($sip_production_commercialisation_region)
    {   
        $this->db->set($this->_set($sip_production_commercialisation_region))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }
        else
        {
            return null;
        }                     
    }
    public function update($id, $sip_production_commercialisation_region)
    {   $this->db->set($this->_set($sip_production_commercialisation_region))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }
        else
        {
            return null;
        }                      
    }
    public function _set($sip_production_commercialisation_region)
    {   
        return array(

            'code_activ'                         => $sip_production_commercialisation_region['code_activ'],
            'code_dom'                           => $sip_production_commercialisation_region['code_dom'],
            'code_act_dom'                       => $sip_production_commercialisation_region['code_act_dom'],
            'annee'                              => $sip_production_commercialisation_region['annee'],
            'mois'                               => $sip_production_commercialisation_region['mois'],
            'id_espece'                          => $sip_production_commercialisation_region['id_espece'],
            'quantite'                           => $sip_production_commercialisation_region['quantite'],
            'quantite_en_nbre'                   => $sip_production_commercialisation_region['quantite_en_nbre'],
            'code_comm'                          => $sip_production_commercialisation_region['code_comm'],
            'quantite_comm'                      => $sip_production_commercialisation_region['quantite_comm'],
            'id_region'                          => $sip_production_commercialisation_region['id_region']
        );
    }
    public function delete($id)
    {   $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }
        else
        {
            return null;
        }  
    }
    public function findAll_active_record()
    {   $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('id')
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }                 
    }


    public function findAll()
    {

        $sql = 
        "
            select 

                spcr.id as id,
                spcr.code_activ as code_activ,
                spcr.code_dom as code_dom,
                spcr.code_act_dom as code_act_dom,
                spcr.annee as annee,
                spcr.mois as mois,
                spcr.quantite as quantite,
                spcr.quantite_en_nbre as quantite_en_nbre,
                spcr.code_activ as code_activ,
                spcr.code_comm as code_comm,
                spcr.quantite_comm as quantite_comm,

                esp.id as id_espece,
                esp.nom as nom,
                esp.nom_local as nom_local,
                esp.nom_francaise as nom_francaise,
                esp.nom_scientifique as nom_scientifique,

                reg.id as id_region,
                reg.nom as nom_region

            from
                sip_production_commercialisation_region as spcr,
                sip_espece as esp,
                region as reg
                
            where
                spcr.id_espece = esp.id
                and spcr.id_region = reg.id 
            order by spcr.annee desc

        " ;
        return $this->db->query($sql)->result();
    }


    
    public function findById($id)  
    {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
           
    }
 
  
         
}