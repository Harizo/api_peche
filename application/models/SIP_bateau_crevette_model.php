<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_bateau_crevette_model extends CI_Model {
    protected $table = 'sip_bateau_crevette';

    public function add($SIP_bateau_crevette) {
        $this->db->set($this->_set($SIP_bateau_crevette))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $SIP_bateau_crevette) {
        $this->db->set($this->_set($SIP_bateau_crevette))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($SIP_bateau_crevette) {
        return array(
            'id_societe_crevette'           =>      $SIP_bateau_crevette['id_societe_crevette'],
            'immatriculation'               =>      $SIP_bateau_crevette['immatriculation'],      
            'deb_validite'                  =>      $SIP_bateau_crevette['deb_validite'],      
            'fin_validite'                  =>      $SIP_bateau_crevette['fin_validite'],      
            'nom'                           =>      $SIP_bateau_crevette['nom'],      
            'segment'                       =>      $SIP_bateau_crevette['segment'],      
            'type'                          =>      $SIP_bateau_crevette['type'],      
            'numero_license'                =>      $SIP_bateau_crevette['numero_license'],      
            'license_1'                     =>      $SIP_bateau_crevette['license_1'],      
            'license_2'                     =>      $SIP_bateau_crevette['license_2'],      
            'an_acquis'                     =>      $SIP_bateau_crevette['an_acquis'],      
            'cout'                          =>      $SIP_bateau_crevette['cout']

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

    public function findAllbysociete($id_societe_crevette) {
               
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("id_societe_crevette", $id_societe_crevette)
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
                sch.dest_exp

           
            from
                sip_bateau_crevette as sch,
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
}
