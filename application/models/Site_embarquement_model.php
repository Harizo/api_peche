<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site_embarquement_model extends CI_Model {
    protected $table = 'site_embarquement';

    public function add($site_embarquement) {
        $this->db->set($this->_set($site_embarquement))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $site_embarquement) {
        $this->db->set($this->_set($site_embarquement))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1) {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($site_embarquement) {
        return array(
            'code'           =>      $site_embarquement['code'],
            'libelle'        =>      $site_embarquement['libelle'],
            'code_unique'    =>      $site_embarquement['code_unique'],
            'id_region'      =>      $site_embarquement['region_id'],
            'id_district'    =>      $site_embarquement['district_id'],
            'latitude'       =>      $site_embarquement['latitude'],
            'longitude'      =>      $site_embarquement['longitude'],
            'altitude'       =>      $site_embarquement['altitude'],                       
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
                        ->order_by('code')
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }
	public function find_site_embarquement_avec_District_et_Region() {
		$requete='select c.id,c.nom,c.code,c.district_id,d.nom as nomdistrict,d.region_id,r.nom as region
				from site_embarquement as c
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
    public function findAllByDistrict($district_id) {
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
    }
    /*public function findById($id) {
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
    public function findById($id)  {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
           
    }
    /*public function findById($id)  {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return null;
		if(isset($id)) {
			$requete='select c.id,c.nom,c.code,c.district_id,d.nom as nomdistrict,r.nom as region,r.site_id,s.nom as nom_site
			from site_embarquement as c
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
