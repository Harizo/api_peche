<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Unite_peche_model extends CI_Model {
    protected $table = 'unite_peche';

    public function add($unite_peche)
    {   $this->db->set($this->_set($unite_peche))
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
    public function update($id, $unite_peche)
    {   $this->db->set($this->_set($unite_peche))
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
    public function _set($unite_peche)
    {   return array(
            'id_type_canoe'           =>      $unite_peche['id_type_canoe'],
            'id_type_engin'           =>      $unite_peche['id_type_engin'],
          //  'id_site_embarquement'    =>      $unite_peche['site_embarquement_id'],
            'libelle'                 =>      $unite_peche['libelle']                       
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
                        ->order_by('libelle')
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

    public function findByIdtab($id)
    {   $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("id", $id)
                        ->order_by('libelle')
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
    public function findById($id)  {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
           
    }   

    public function findAllBySite_embarquement($cle_etranger)
    {
        $result =  $this->db->select('*')
                                ->from($this->table)
                                ->join('unite_peche_site', 'unite_peche_site.id_unite_peche = unite_peche.id', 'inner')
                                ->where("id_site_embarquement", $cle_etranger )
                                ->order_by('id_site_embarquement')
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

    public function findAllInTable($annee)
    {   
        $requete = "date BETWEEN '".$annee."-01-01' AND '".$annee."-12-31' " ;

        $result = $this->db->select('echantillon.id_unite_peche as id, unite_peche.libelle as libelle')
                            ->from('echantillon')
                            ->join('unite_peche', 'unite_peche.id = echantillon.id_unite_peche')
                            ->join('fiche_echantillonnage_capture', 'fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture')
                            ->where($requete) 
                            ->group_by('unite_peche.id')                        
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
    
}
