<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Enqueteur_model extends CI_Model {
    protected $table = 'enqueteur';

    public function add($enqueteur) {
        $this->db->set($this->_set($enqueteur))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $enqueteur) {
        $this->db->set($this->_set($enqueteur))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($enqueteur) {
        return array(
            'nom'          =>      $enqueteur['nom'],
            'prenom'           =>      $enqueteur['prenom'],
            'telephone'     =>      $enqueteur['telephone']                      
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
    public function findById($id)  {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        } 
    }

    public function rapportenqueteur($date,$id_enqueteur)
    {               
        $result =  $this->db->count_all('*')
                        ->from('echantillon')
                        ->join('fiche_echantillonnnage_capture','fiche_echantillonnnage_capture.id = echantillon.id_fiche_echantillonnnage_capture')
                        ->where('date',$date)
                        ->where('id_enqueteur',$id_enqueteur)
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }
    public function findSiteByEnqueteur($cle_etranger)
    {
        $result =  $this->db->select('site_embarquement.id as id_site, site_embarquement.libelle as libelle, region.nom as region')
                                ->from('site_enqueteur')
                                ->join('site_embarquement', 'site_enqueteur.id_site = site_embarquement.id', 'inner')
                                ->join('region', 'region.id= site_embarquement.id_region', 'inner')
                                ->where("id_enqueteur", $cle_etranger )
                                ->order_by('id_enqueteur')
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
    public function findUniteBySite_embarquement($cle_site)
    {
        $result =  $this->db->select('unite_peche.id as id,unite_peche.libelle as libelle')
                                ->from('unite_peche_site')
                                ->join('unite_peche', 'unite_peche_site.id_unite_peche = unite_peche.id', 'inner')
                                ->where("id_site_embarquement", $cle_site )
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

}
