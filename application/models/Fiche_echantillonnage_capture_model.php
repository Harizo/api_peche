<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fiche_echantillonnage_capture_model extends CI_Model {
    protected $table = 'fiche_echantillonnage_capture';

    public function add($fiche_echantillonnage_capture) {
        $this->db->set($this->_set($fiche_echantillonnage_capture))
                            ->set('date_creation', 'NOW()', false)
                            ->set('date_modification', 'NOW()', false)
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $fiche_echantillonnage_capture) {
        $this->db->set($this->_set($fiche_echantillonnage_capture))
                            ->set('date_modification', 'NOW()', false)
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1) {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($fiche_echantillonnage_capture) {
        return array(
            'code_unique'             =>      $fiche_echantillonnage_capture['code_unique'],
            'date'                    =>      $fiche_echantillonnage_capture['date'],
           // 'date_creation'           =>      $fiche_echantillonnage_capture['date_creation'],
            //'date_modification'       =>      $fiche_echantillonnage_capture['date_modification'],
            'id_region'               =>      $fiche_echantillonnage_capture['region_id'],
            'id_district'             =>      $fiche_echantillonnage_capture['district_id'],
            'id_site_embarquement'    =>      $fiche_echantillonnage_capture['site_embarquement_id'],
            'id_enqueteur'            =>      $fiche_echantillonnage_capture['enqueteur_id'],
            'id_user'                 =>      $fiche_echantillonnage_capture['user_id'],
            'latitude'                =>      $fiche_echantillonnage_capture['latitude'],
            'longitude'               =>      $fiche_echantillonnage_capture['longitude'],
            'altitude'                =>      $fiche_echantillonnage_capture['altitude'],                       
        );
    }
    public function delete($id) {
        $this->db->where('id', (int) $id)->delete($this->table);
        if($this->db->affected_rows() === 1) {
            return true;
        }else{
            return null;
        }  
    }
    public function findAll() {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('code_unique')
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }
	public function find_fiche_echantillonnage_capture_avec_District_et_Region() {
		$requete='select c.id,c.nom,c.code,c.district_id,d.nom as nomdistrict,d.region_id,r.nom as region
				from fiche_echantillonnage_capture as c
				left outer join district as d on d.id=c.district_id
				left outer join region as r on r.id=d.region_id
				order by c.nom,d.nom,r.nom	';				
		$query= $this->db->query($requete);		
		if($query->result()) {
			return $query->result();
        }else{
            return null;
        }  
	}
    /*public function findAllByDistrict($district_id) {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('nom')
                        ->where("district_id", $district_id)
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                 
    }*/

public function findById($id)  {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
           
    }



   /* public function findById($id) {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("id", $id)
                        ->order_by('id', 'asc')
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                 
    }*/



    /*public function findById($id)  {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return null;
		if(isset($id)) {
			$requete='select c.id,c.nom,c.code,c.district_id,d.nom as nomdistrict,r.nom as region,r.site_id,s.nom as nom_site
			from fiche_echantillonnage_capture as c
			left outer join district as d on d.id=c.district_id
			left outer join region as r on r.id=d.region_id
			left outer join site as s on s.id=r.site_id where c.id='.$id
			.' order by r.site_id,c.nom,d.nom	';				
			$query= $this->db->query($requete);
			if($query->result())
			{
				return $query->row();
				// return $result;
			}else{
				return null;
			}   
		} else {
			return null;
		}	
    }*/
}
