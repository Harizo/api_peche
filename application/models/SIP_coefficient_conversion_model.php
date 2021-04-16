<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_coefficient_conversion_model extends CI_Model {
    protected $table = 'sip_coefficient_conversion';

    public function add($SIP_coefficient_conversion) {
        $this->db->set($this->_set($SIP_coefficient_conversion))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $SIP_coefficient_conversion) {
        $this->db->set($this->_set($SIP_coefficient_conversion))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($SIP_coefficient_conversion) {
        return array(
            'id_espece'             =>      $SIP_coefficient_conversion['id_espece'],
            'id_presentation'       =>      $SIP_coefficient_conversion['id_presentation'],      
            'id_conservation'       =>      $SIP_coefficient_conversion['id_conservation'],      
            'coefficient'           =>      $SIP_coefficient_conversion['coefficient'] 

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
                        ->order_by('id_espece')
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }

    public function find_all_join()
    {

        $sql = 
        "
            select

                scc.id as id,
                ste.id as id_type_espece,
                ste.libelle as libelle_type_espece,

                se.id as id_espece,
                se.nom as nom_espece,


                cons.id as id_conservation,
                cons.libelle as libelle_conservation,

                pres.id as id_presentation,
                pres.libelle as libelle_presentation,


                scc.coefficient as coefficient

          
                

           
            from
              sip_coefficient_conversion as scc,
              sip_presentation as pres,
              sip_conservation as cons,
              sip_espece as se,
              sip_type_espece as ste
            where
                scc.id_presentation = pres.id
                and scc.id_conservation = cons.id 
                and scc.id_espece = se.id 
                and se.type_espece = ste.id 
            order by se.nom 

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

    public function find_coeff($condition)  
    {
        $this->db->select('coefficient')
                ->where($condition);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }  
    }
}
