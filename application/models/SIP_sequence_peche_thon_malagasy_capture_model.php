<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_sequence_peche_thon_malagasy_capture_model extends CI_Model {
    protected $table = 'sip_sequence_peche_thon_malagasy_capture';
    public function add($sip_sequence_peche_thon_malagasy_capture)
    {   $this->db->set($this->_set($sip_sequence_peche_thon_malagasy_capture))
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
    public function update($id, $sip_sequence_peche_thon_malagasy_capture)
    {   $this->db->set($this->_set($sip_sequence_peche_thon_malagasy_capture))
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
    public function _set($sip_sequence_peche_thon_malagasy_capture)
    {   return array(

            'id_sequence_peche_thon_malagasy' => $sip_sequence_peche_thon_malagasy_capture['id_sequence_peche_thon_malagasy'],
            'id_espece'                       => $sip_sequence_peche_thon_malagasy_capture['id_espece'],
            'qte'                             => $sip_sequence_peche_thon_malagasy_capture['qte'],
            'nbre'                            => $sip_sequence_peche_thon_malagasy_capture['nbre'],
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
                        ->order_by('id_espece')
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

    public function findAll_by_sequnece_capture_peche_thon_malagasy($type_espece,$id_sequence_peche_thon_malagasy)
    {  

        $requete="select seqcap.id,seqcap.id_sequence_peche_thon_malagasy,seqcap.id_espece,seqcap.qte,seqcap.nbre,             
            esp.nom as espece,esp.code,esp.type_espece,esp.nom_scientifique  
			 from sip_sequence_peche_thon_malagasy_capture as seqcap 
			  left join sip_espece as esp on esp.id=seqcap.id_espece 
			   where seqcap.id_sequence_peche_thon_malagasy=".$id_sequence_peche_thon_malagasy
			   ." and esp.type_espece=".$type_espece;
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
                sip_sequence_peche_thon_malagasy_capture as ssp
            where
                ssp.id_sequence_peche_thon_malagasy = sfp.id
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