<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_commercialisation_crevette_model extends CI_Model {
    protected $table = 'sip_commercialisation_crevette';

    public function add($SIP_commercialisation_crevette) {
        $this->db->set($this->_set($SIP_commercialisation_crevette))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $SIP_commercialisation_crevette) {
        $this->db->set($this->_set($SIP_commercialisation_crevette))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($SIP_commercialisation_crevette) {
        return array(
            'id_societe_crevette'      =>      $SIP_commercialisation_crevette['id_societe_crevette'],
            'annee'                    =>      $SIP_commercialisation_crevette['annee'],      
            'mois'                     =>      $SIP_commercialisation_crevette['mois'],      
            'produit'                  =>      $SIP_commercialisation_crevette['produit'],      
            'id_presentation'          =>      $SIP_commercialisation_crevette['id_presentation'],      
            'id_conservation'          =>      $SIP_commercialisation_crevette['id_conservation'],      
            'qte_vl'                   =>      $SIP_commercialisation_crevette['qte_vl'],      
            'pum_vl'                   =>      $SIP_commercialisation_crevette['pum_vl'],      
            'val_vl'                   =>      $SIP_commercialisation_crevette['val_vl'],      
            'qte_exp'                  =>      $SIP_commercialisation_crevette['qte_exp'],      
            'pum_exp'                  =>      $SIP_commercialisation_crevette['pum_exp'],      
            'val_exp'                  =>      $SIP_commercialisation_crevette['val_exp'],      
            'dest_exp'                 =>      $SIP_commercialisation_crevette['dest_exp'],

            'qte_export'               =>      $SIP_commercialisation_crevette['qte_export'],      
            'pum_export'               =>      $SIP_commercialisation_crevette['pum_export'],      
            'val_export'               =>      $SIP_commercialisation_crevette['val_export'],      
            'dest_export'              =>      $SIP_commercialisation_crevette['dest_export']   

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

                esp.id as id_produit,
                esp.nom as nom_produit,
                

                
                sch.id as id,
                sch.annee,
                sch.mois,
                sch.qte_vl,
                sch.pum_vl,
                sch.val_vl,
                sch.qte_exp,
                sch.pum_exp,
                sch.val_exp,
                sch.dest_exp,

                sch.qte_export,
                sch.pum_export,
                sch.val_export,
                sch.dest_export

           
            from
                sip_commercialisation_crevette as sch,
                sip_societe_crevette as scrv,
                sip_espece as esp,
                sip_presentation as pres,
                sip_conservation as cons
            where
                sch.produit = esp.id
                and sch.id_societe_crevette = scrv.id
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

     public function findClePresentation($id_presentation)
    {
        $sql = " select *
            FROM sip_commercialisation_crevette
            WHERE sip_commercialisation_crevette.id_presentation = ".$id_presentation."
        ";

        return $this->db->query($sql)->result();
                                
        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }

    }
    public function findCleConservation($id_conservation)
    {
        $sql = " select *
            FROM sip_commercialisation_crevette
            WHERE sip_commercialisation_crevette.id_conservation = ".$id_conservation."
        ";

        return $this->db->query($sql)->result();
                                
        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }

    }
 
    public function findEspece($id_espece)
    {
       $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where('produit='.$id_espece)
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        } 
    }
}
