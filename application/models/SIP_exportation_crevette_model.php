<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_exportation_crevette_model extends CI_Model {
    protected $table = 'sip_exportation_crevette';

    public function add($SIP_exportation_crevette) {
        $this->db->set($this->_set($SIP_exportation_crevette))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $SIP_exportation_crevette) {
        $this->db->set($this->_set($SIP_exportation_crevette))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($SIP_exportation_crevette) {
        return array(
            'id_societe_crevette'      =>      $SIP_exportation_crevette['id_societe_crevette'],
            'annee'                    =>      $SIP_exportation_crevette['annee'],      
            'mois'                     =>      $SIP_exportation_crevette['mois'],      
            'date_visa'                =>      $SIP_exportation_crevette['date_visa'],      
            'numero_visa'              =>      $SIP_exportation_crevette['numero_visa'],      
            'date_cos'                 =>      $SIP_exportation_crevette['date_cos'],      
            'numero_cos'               =>      $SIP_exportation_crevette['numero_cos'],      
            'date_edrd'                =>      $SIP_exportation_crevette['date_edrd'],      
            'id_presentation'          =>      $SIP_exportation_crevette['id_presentation'],      
            'id_conservation'          =>      $SIP_exportation_crevette['id_conservation'],      
            'quantite'                 =>      $SIP_exportation_crevette['quantite'],      
            'valeur_ar'                =>      $SIP_exportation_crevette['valeur_ar'],      
            'valeur_euro'              =>      $SIP_exportation_crevette['valeur_euro'],  
            'valeur_usd'               =>      $SIP_exportation_crevette['valeur_usd'],  
            'destination'              =>      $SIP_exportation_crevette['destination']  

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
                        ->order_by('nom')
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }

    public function find_all_join($id_societe_crevette)
    {

        $sql = 
        "
            select 


                cons.id as id_conservation,
                cons.libelle as libelle_conservation,

                pres.id as id_presentation,
                pres.libelle as libelle_presentation,

          
                
                sch.id as id,
                sch.annee,
                sch.mois,
                sch.date_visa,
                sch.numero_visa,
                sch.date_cos,
                sch.numero_cos,
                sch.date_edrd,
                sch.quantite,
                sch.valeur_ar,
                sch.valeur_euro,
                sch.valeur_usd,
                sch.destination

           
            from
                sip_exportation_crevette as sch,
                sip_societe_crevette as scrv,
              
                sip_presentation as pres,
                sip_conservation as cons
            where
                sch.id_societe_crevette = scrv.id
                and sch.id_presentation = pres.id
                and sch.id_conservation = cons.id
                and scrv.id = ".$id_societe_crevette." 
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
