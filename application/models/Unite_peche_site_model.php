<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Unite_peche_site_model extends CI_Model
{
    protected $table = 'unite_peche_site';


    public function add($unite_peche_site)
    {
        $this->db->set($this->_set($unite_peche_site))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }


    public function update($id, $unite_peche_site)
    {
        $this->db->set($this->_set($unite_peche_site))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }

    public function _set($unite_peche_site)
    {
        return array(
            'id_site_embarquement'     =>      $unite_peche_site['id_site_embarquement'],
            'id_unite_peche'     =>      $unite_peche_site['id_unite_peche'] ,
            'nbr_echantillon'     =>      $unite_peche_site['nbr_echantillon']                       
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
                        ->order_by('id_site_embarquement')
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }

    public function findAllByRequetes()
    {
        $result =  $this->db->select('region.nom as nom_region, unite_peche.libelle as libelle_unite_peche, site_embarquement.libelle as libelle_site, COUNT(*) as nombre')
                        ->from($this->table)
                        ->join('site_embarquement', 'site_embarquement.id = unite_peche_site.id_site_embarquement')
                        ->join('unite_peche', 'unite_peche.id = unite_peche_site.id_unite_peche')
                        ->join('region', 'region.id = site_embarquement.id_region')

                        ->group_by('id_site_embarquement')                          
                        ->group_by('id_unite_peche')  
                        ->group_by('id_region')  
                        
                        //->order_by('id_site_embarquement')
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

    public function findAllBySite_embarquement($cle_etranger)
    {
        $result =  $this->db->select('*')
                                ->from($this->table)
                                ->join('unite_peche', 'unite_peche.id = unite_peche_site.id_unite_peche')
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

    public function findAllBySite_embarquementCanoeEngin($cle_site)
    {
        $result =  $this->db->select('unite_peche.id as id_unite_peche, unite_peche_site.id as id,unite_peche_site.nbr_echantillon as nbr_echantillon, unite_peche.id_type_canoe as id_type_canoe, unite_peche.id_type_engin as id_type_engin')
                                ->from($this->table)
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

  public function findnbrechantillonBypecheandsite($id_unite_peche,$id_site_embarquement)
  {
    $result =  $this->db->select('nbr_echantillon')
                                ->from($this->table)
                                ->join('unite_peche', 'unite_peche.id = unite_peche_site.id_unite_peche')
                                ->where("id_site_embarquement", $id_site_embarquement )
                                ->where("id_unite_peche", $id_unite_peche )
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
