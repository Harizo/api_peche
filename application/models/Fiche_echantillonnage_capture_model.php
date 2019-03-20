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
            'id_region'               =>      $fiche_echantillonnage_capture['id_region'],
            'id_district'             =>      $fiche_echantillonnage_capture['id_district'],
            'id_site_embarquement'    =>      $fiche_echantillonnage_capture['id_site_embarquement'],
            'id_enqueteur'            =>      $fiche_echantillonnage_capture['id_enqueteur'],
            'id_user'                 =>      $fiche_echantillonnage_capture['id_user'],
            'latitude'                =>      $fiche_echantillonnage_capture['latitude'],
            'longitude'               =>      $fiche_echantillonnage_capture['longitude'],
            'altitude'                =>      $fiche_echantillonnage_capture['altitude'],
            'validation'                =>      $fiche_echantillonnage_capture['validation']                      
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

public function findById($id)  {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
           
    }
public function findByDate($date_debut,$date_fin,$validation) {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where('date BETWEEN "'.$date_debut.'" AND "'.$date_fin.'"')
                        ->where('validation',$validation)
                        ->order_by('date', 'asc')
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                 
    }
public function findByValidation($validation) {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where('validation',$validation)
                        ->order_by('date', 'asc')
                        ->get()
                        ->result();
        if($result) {
            return $result;
        }else{
            return null;
        }                 
    }

 public function SauvegarderTout($data) 
{
    $tmp = array();
    $fiche_echantillonnage_capture = array();
    $fiche_echantillonnage_capture = json_decode($data['fiche_echantillonnage_capture']);
    
    $tmp ['code_unique']           = $fiche_echantillonnage_capture->code_unique;                
    $tmp ['date']                  = $fiche_echantillonnage_capture->date;    
    $tmp ['id_region']             = $fiche_echantillonnage_capture->region_id;
    $tmp ['id_district']           = $fiche_echantillonnage_capture->district_id;
    $tmp ['id_site_embarquement']  = $fiche_echantillonnage_capture->site_embarquement_id;
    $tmp ['id_enqueteur']          = $fiche_echantillonnage_capture->enqueteur_id;                
    $tmp ['latitude']              = $fiche_echantillonnage_capture->latitude;    
    $tmp ['longitude']             = $fiche_echantillonnage_capture->longitude;
    $tmp ['altitude']              = $fiche_echantillonnage_capture->altitude;
    $tmp ['id_user']               = $data['user_id'];
    $this->db->set($tmp)
            ->set('date_creation', 'NOW()', false)
            ->set('date_modification', 'NOW()', false)
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


public function numero($date_envoi)
{
   
    $result =  $this->db->select('COUNT(*) as nombre')
                        ->from($this->table)
                        ->where("date_creation", $date_envoi)
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                     
    
}


}
