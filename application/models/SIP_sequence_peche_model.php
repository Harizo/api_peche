<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_sequence_peche_model extends CI_Model {
    protected $table = 'sip_sequence_peche';
    public function add($sip_sequence_peche)
    {   $this->db->set($this->_set($sip_sequence_peche))
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
    public function update($id, $sip_sequence_peche)
    {   $this->db->set($this->_set($sip_sequence_peche))
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
    public function _set($sip_sequence_peche)
    {   return array(

            'id_fiche_peche_crevette'       => $sip_sequence_peche['id_fiche_peche_crevette'],
            'numseqpeche'                   => $sip_sequence_peche['numseqpeche']
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
    public function findAll()
    {   $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('numseqpeche')
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

    public function findAll_by_fiche_peche_crevette($id_fiche_peche_crevette)
    {   $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("id_fiche_peche_crevette", $id_fiche_peche_crevette)
                        ->order_by('numseqpeche')
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

    public function get_nbr_sequence_peche($annee)
    {
        $debut = $annee."/01/01" ;
        $fin = $annee."/12/31"  ;
        //$array = array('date_depart >=' => $debut, 'date_depart <=' => $fin);

        $sql = 
        "
            select 
                count(ssp.id) as nbr_sequence_peche
                

            from
                sip_fiche_peche_crevette as sfp,
                sip_sequence_peche as ssp
            where
                ssp.id_fiche_peche_crevette = sfp.id
                AND sfp.date_depart BETWEEN '".$debut."' AND '".$fin."'


        " ;
        return $this->db->query($sql)->result();
    }

    
    public function findById($id_type_sequence_peche)  {
        $this->db->where("id", $id_type_sequence_peche);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
           
    }
 
  
         
}