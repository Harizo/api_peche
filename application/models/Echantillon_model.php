<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Echantillon_model extends CI_Model {
    protected $table = 'echantillon';

    public function add($echantillon) {
        $this->db->set($this->_set($echantillon))
                            ->set('date_creation', 'NOW()', false)
                            ->set('date_modification', 'NOW()', false)
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $echantillon) {
        $this->db->set($this->_set($echantillon))
                            ->set('date_modification', 'NOW()', false)
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($echantillon) {
        return array(
            'id_fiche_echantillonnage_capture' => $echantillon['fiche_echantillonnage_capture_id'],
            'id_type_canoe'           =>      $echantillon['type_canoe_id'],
            'id_type_engin'     =>      $echantillon['type_engin_id'],
            'peche_hier'          =>      $echantillon['peche_hier'],
            'peche_avant_hier'           =>      $echantillon['peche_avant_hier'],
            'nbr_jrs_peche_dernier_sem'     =>      $echantillon['nbr_jrs_peche_dernier_sem'] ,
            'total_capture'          =>      $echantillon['total_capture'],
            'unique_code'           =>      $echantillon['unique_code'],
            'id_data_collect'     =>      $echantillon['data_collect_id'], 
            'nbr_bateau_actif'          =>      $echantillon['nbr_bateau_actif'],
            'total_bateau_ecn'           =>      $echantillon['total_bateau_ecn'],
            'id_user'     =>      $echantillon['user_id']                        
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
/*$requete='select d.id,d.nom,d.code,d.region_id,r.nom as region,r.site_id,s.nom as nom_site
		from echantillon as d
		left outer join region as r on r.id=d.region_id
		left outer join site as s on s.id=r.site_id
		order by r.site_id,d.nom,r.nom	';				
		$query= $this->db->query($requete);
        if($query->result()) {
			return $query->result();
            // return $result;
        }else{
            return null;
        }  */               
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('id')
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }
    public function findAllByRegion($region_id) {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('nom')
                        ->where("region_id", $region_id)
                        ->get()
                        ->result();
        if($result) {
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
        // return null;
	/*	$requete='select d.id,d.nom,d.code,d.region_id,r.nom as region,r.site_id,s.nom as nom_site
		from echantillon as d
		left outer join region as r on r.id=d.region_id
		left outer join site as s on s.id=r.site_id where d.id='.$id
		.' order by r.site_id,d.nom,r.nom ';				
			$query= $this->db->query($requete);*/
			// if($query->result())
			// {
				// return $query->row();
				// return $result;
			// }else{
				// return null;
			// }   
    }
}
