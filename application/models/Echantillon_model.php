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
    public function findAllByFiche_echantillonnnage_capture($fiche_echantillonnnage_capture_id) {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('unique_code')
                        ->where("id_fiche_echantillonnage_capture", $fiche_echantillonnnage_capture_id)
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
    }
 public function SauvegarderTout($data) {
        $this->db->trans_begin();

        $echantillon=array();
        $fiche_echantillonnage_capture=array();
        
        $fiche_echantillonnage_capture = json_decode($data['fiche_echantillonnage_capture ']);
        $echantillon=$fiche_echantillonnage_capture->echantillon;
        $valret=array();
        try{    

            for ($i = 0; $i < count($echantillon); $i++) {
                
                $id_type_canoe=null;
                if($echantillon[$i]->id_type_canoe >'') {
                    $id_type_canoe=$echantillon[$i]->id_type_canoe;  
                }
                 $id_type_engin=null;
                if($echantillon[$i]->id_type_engin >'') {
                    $id_type_engin=$echantillon[$i]->id_type_engin;  
                }
                $peche_hier=null;
                if($echantillon[$i]->peche_hier >'') {
                    $peche_hier=$echantillon[$i]->peche_hier;  
                }
                $peche_avant_hier=null;
                if($echantillon[$i]->peche_avant_hier >'') {
                    $peche_avant_hier=$echantillon[$i]->peche_avant_hier;  
                }
                 $nbr_jrs_peche_dernier_sem=null;
                if($echantillon[$i]->nbr_jrs_peche_dernier_sem >'') {
                    $nbr_jrs_peche_dernier_sem=$echantillon[$i]->nbr_jrs_peche_dernier_sem;  
                }
                $total_capture=null;
                if($echantillon[$i]->total_capture >'') {
                    $total_capture=$echantillon[$i]->total_capture;  
                }
                $unique_code=null;
                if($echantillon[$i]->unique_code >'') {
                    $unique_code=$echantillon[$i]->unique_code;  
                }
                $id_data_collect=null;
                if($echantillon[$i]->id_data_collect >'') {
                    $id_data_collect=$echantillon[$i]->id_data_collect;  
                }
                 $nbr_bateau_actif=null;
                if($echantillon[$i]->nbr_bateau_actif >'') {
                    $nbr_bateau_actif=$echantillon[$i]->nbr_bateau_actif;  
                }
                $total_bateau_ecn=null;
                if($echantillon[$i]->total_bateau_ecn >'') {
                    $total_bateau_ecn=$echantillon[$i]->total_bateau_ecn;  
                }

                $tmp = array();
                
                 $tmp ['id_user'] =$data['id_user'];                
                $tmp ['id_fiche_echantillonnage_capture'] = $fiche_echantillonnage_capture->id_fiche_echantillonnage_capture;              
                $tmp ['id_type_canoe'] = $id_type_canoe;    
                $tmp ['id_type_engin'] = $id_type_engin;
                $tmp ['peche_hier'] = $peche_hier;
                $tmp ['peche_avant_hier'] = $peche_avant_hier;
                $tmp ['nbr_jrs_peche_dernier_sem'] = $nbr_jrs_peche_dernier_sem;    
                $tmp ['total_capture'] = $total_capture;
                $tmp ['unique_code'] = $unique_code;
                $tmp ['id_data_collect'] = $id_data_collect;
                $tmp ['nbr_bateau_actif'] = $nbr_bateau_actif;    
                $tmp ['total_bateau_ecn'] = $total_bateau_ecn;
                
                
                $this->db->set($tmp)
                        ->set('date_creation', 'NOW()', false)
                        ->set('date_modification', 'NOW()', false)
                        ->insert($this->table);
                            $NewId=$this->db->insert_id();
                          
                        $tmp['id'] = $NewId;
                   
                   
                $valret[]=$tmp;
            }
            if ($this->db->trans_status() === FALSE) {
                $date=new datetime();
                $date_anio=$date->format('Y-m-d HH:mm:ss');                     
                error_log("Erreur dans Espece_capture_model - Function SauvegarderTout :" . $date_anio.' (Rollback)');
                $this->db->trans_rollback();
                return "ECHEC";
            } else {
                $this->db->trans_commit();          
                return $valret;
            }                   
        } catch(Exception $e){
            $date=new datetime();
            $date_anio=$date->format('Y-m-d HH:mm:ss');                     
            error_log("Erreur dans Espece_capture_model - Function SauvegarderTout :" . $date_anio.' (Except)');
            $this->db->trans_rollback();
            return "ECHEC";
        }           
    }


    
}
