<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_sequence_peche_thon_etranger_pi_model extends CI_Model {
    protected $table = 'sip_sequence_peche_thon_etranger_pi';
    public function add($sip_sequence_peche_thon_etranger)
    {   $this->db->set($this->_set($sip_sequence_peche_thon_etranger))
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
    public function update($id, $sip_sequence_peche_thon_etranger)
    {   $this->db->set($this->_set($sip_sequence_peche_thon_etranger))
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
    public function _set($sip_sequence_peche_thon_etranger)
    {   return array(

            'id_sequence_peche_thon_etranger' => $sip_sequence_peche_thon_etranger['id_sequence_peche_thon_etranger'],
            'date_pi'                         => $sip_sequence_peche_thon_etranger['date_pi'],
            // 'annee'                           => $sip_sequence_peche_thon_etranger['annee'],
            // 'jour'                            => $sip_sequence_peche_thon_etranger['jour'],
            // 'mois'                            => $sip_sequence_peche_thon_etranger['mois'],
            'postlatitude'                    => $sip_sequence_peche_thon_etranger['postlatitude'],
            'postlongitude'                   => $sip_sequence_peche_thon_etranger['postlongitude'],
            'temperature'                     => $sip_sequence_peche_thon_etranger['temperature'],
            'nb_ham_util'                     => $sip_sequence_peche_thon_etranger['nb_ham_util'],
            'total_estime'                    => $sip_sequence_peche_thon_etranger['total_estime'],
            'total_debarque'                  => $sip_sequence_peche_thon_etranger['total_debarque'],
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
                        ->order_by('annee')
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

    public function findAll_by_sequence_peche_thon_etranger_pi($id_sequence_peche_thon_etranger)
    {   $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("id_sequence_peche_thon_etranger", $id_sequence_peche_thon_etranger)
                        ->order_by('annee')
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
                sip_sequence_peche_thon_etranger as sfp,
                sip_sequence_peche_thon_etranger_pi as ssp
            where
                ssp.id_sequence_peche_thon_etranger = sfp.id
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