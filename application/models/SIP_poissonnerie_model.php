<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_poissonnerie_model extends CI_Model {
    protected $table = 'SIP_poissonnerie';

    public function add($SIP_poissonnerie) {
        $this->db->set($this->_set($SIP_poissonnerie))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $SIP_poissonnerie) {
        $this->db->set($this->_set($SIP_poissonnerie))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($SIP_poissonnerie) {
        return array(
            'id_region'     =>      $SIP_poissonnerie['id_region'],              
            'nom'           =>      $SIP_poissonnerie['nom'],
           'localisation'   =>      $SIP_poissonnerie['localisation'],                 
            'adresse'      	=>      $SIP_poissonnerie['adresse'],                 
            'rcs'     	    =>      $SIP_poissonnerie['rcs'],                 
            'stat'          =>      $SIP_poissonnerie['stat'],                 
            'nif'           =>      $SIP_poissonnerie['nif'],                 
            'tel'           =>      $SIP_poissonnerie['tel']          

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


    public function find_all_join($id_poissonnerie)
    {

        $sql = 
        "
            select 

                sch.id as id,

                cons.id as id_conservation,
                cons.libelle as libelle_conservation,

                pres.id as id_presentation,
                pres.libelle as libelle_presentation,

                sch.annee,
                sch.mois,
                sch.quantite,
                sch.prix,
                sch.coefficiant_conservation,
                sch.valeur

            from
                SIP_poissonnerie as sch,
                sip_permis as cm,
                sip_presentation as pres,
                sip_conservation as cons
            where
                sch.id_poissonnerie = cm.id
                
                and sch.id_presentation = pres.id
                and sch.id_conservation = cons.id
                and cm.id = ".$id_poissonnerie." 
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

   
    
    public function findCleRegion($id_region)
    {
        $sql = " select *
            FROM SIP_poissonnerie
            WHERE SIP_poissonnerie.id_region = ".$id_region."
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
    
}
