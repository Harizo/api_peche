<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_sequence_peche_thon_malagasy_pi_model extends CI_Model {
    protected $table = 'sip_sequence_peche_thon_malagasy_pi';
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

            'id_sequence_peche_thon_malagasy' => $sip_sequence_peche_thon_malagasy['id_sequence_peche_thon_malagasy'],
            'date_pi'                         => $sip_sequence_peche_thon_malagasy['date_pi'],
            // 'annee'                           => $sip_sequence_peche_thon_malagasy['annee'],
            // 'jour'                            => $sip_sequence_peche_thon_malagasy['jour'],
            // 'mois'                            => $sip_sequence_peche_thon_malagasy['mois'],
            'heuret'                          => $sip_sequence_peche_thon_malagasy['heuret'],
            'minutet'                         => $sip_sequence_peche_thon_malagasy['minutet'],
            'postlatitude'                    => $sip_sequence_peche_thon_malagasy['postlatitude'],
            'postlongitude'                   => $sip_sequence_peche_thon_malagasy['postlongitude'],
            'temperature'                     => $sip_sequence_peche_thon_malagasy['temperature'],
            'nb_ham_entrflot'                 => $sip_sequence_peche_thon_malagasy['nb_ham_entrflot'],
            'nb_ham_util'                     => $sip_sequence_peche_thon_malagasy['nb_ham_util'],
            'appats_util'                     => $sip_sequence_peche_thon_malagasy['appats_util'],
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

    public function findAll_by_sequence_peche_thon_malagasy_pi($id_sequence_peche_thon_malagasy)
    {   $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("id_sequence_peche_thon_malagasy", $id_sequence_peche_thon_malagasy)
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
                sip_sequence_peche_thon_malagasy as sfp,
                sip_sequence_peche_thon_malagasy_pi as ssp
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