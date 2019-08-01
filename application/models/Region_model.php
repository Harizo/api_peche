<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Region_model extends CI_Model
{
    protected $table = 'region';


    public function add($region)
    {
        $this->db->set($this->_set($region))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }


    public function update($id, $region)
    {
        $this->db->set($this->_set($region))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }

    public function _set($region)
    {
        return array(
            'code'       =>      $region['code'],
            'nom'        =>      $region['nom'],
            'id_pays'     =>      $region['id_pays']                       
        );
    }


    public function delete($id)
    {
        $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }  
    }

    public function findAll()
    {
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

    public function findById($id)
    {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return null;
    }
    public function findByIdtab($id)
    {   $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("id", $id)
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
    public function findByIdtable($id,$annee)
    {   
        $requete = "date BETWEEN '".$annee."-01-01' AND '".$annee."-12-31' " ;
        
        $result = $this->db->select('fiche_echantillonnage_capture.id_region as id, region.nom as nom')
                            ->from('fiche_echantillonnage_capture')
                            ->join('region', 'region.id = fiche_echantillonnage_capture.id_region')
                            ->where('fiche_echantillonnage_capture.id_region',$id)
                            ->where($requete)
                            ->group_by('region.id')                        
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
        
        $result = $this->db->select('fiche_echantillonnage_capture.id_region as id, region.nom as nom')
                            ->from('fiche_echantillonnage_capture')
                            ->join('region', 'region.id = fiche_echantillonnage_capture.id_region')
                            ->where($requete)
                            ->group_by('region.id')                        
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
