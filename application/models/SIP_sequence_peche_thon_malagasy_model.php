<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_sequence_peche_thon_malagasy_model extends CI_Model {
    protected $table = 'sip_sequence_peche_thon_malagasy';
    public function add($sip_sequence_peche_thon_malagasy)
    {   $this->db->set($this->_set($sip_sequence_peche_thon_malagasy))
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
    public function update($id, $sip_sequence_peche_thon_malagasy)
    {   $this->db->set($this->_set($sip_sequence_peche_thon_malagasy))
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
    public function _set($sip_sequence_peche_thon_malagasy)
    {   return array(

            'id_peche_thoniere_malagasy' => $sip_sequence_peche_thon_malagasy['id_peche_thoniere_malagasy'],
            'numseqpeche'                => $sip_sequence_peche_thon_malagasy['numseqpeche'],
            'numfp'                      => $sip_sequence_peche_thon_malagasy['numfp'],
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

    public function findAll_by_fiche_peche_thon_malagasy($id_peche_thoniere_malagasy)
    {  

        $requete="select seqpeche.id,seqpeche.id_peche_thoniere_malagasy,seqpeche.numseqpeche,seqpeche.numfp,
		 0 as detail_sequence_capture_charge,0 as detail_sequence_pi_charge  
			 from sip_sequence_peche_thon_malagasy as seqpeche 
			   where seqpeche.id_peche_thoniere_malagasy=".$id_peche_thoniere_malagasy;
		return $this->db->query($requete)->result();			  
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
                sip_fiche_peche_thon_malagasy as sfp,
                sip_sequence_peche_thon_malagasy as ssp
            where
                ssp.id_peche_thoniere_malagasy = sfp.id
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