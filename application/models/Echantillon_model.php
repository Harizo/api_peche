<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Echantillon_model extends CI_Model {

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('Espece_capture_model', 'Espece_captureManager');
    }
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
            'id_fiche_echantillonnage_capture' => $echantillon['id_fiche_echantillonnage_capture'],
            'peche_hier'          =>      $echantillon['peche_hier'],
            'peche_avant_hier'           =>      $echantillon['peche_avant_hier'],
            'nbr_jrs_peche_dernier_sem'     =>      $echantillon['nbr_jrs_peche_dernier_sem'] ,
            'total_capture'          =>      $echantillon['total_capture'],
            'unique_code'           =>      $echantillon['unique_code'],
            'id_data_collect'     =>      $echantillon['id_data_collect'], 
            'nbr_bateau_actif'          =>      $echantillon['nbr_bateau_actif'],
            'total_bateau_ecn'           =>      $echantillon['total_bateau_ecn'],
            'id_unite_peche'     =>      $echantillon['id_unite_peche'],
            'id_user'     =>      $echantillon['id_user']                        
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
                        ->order_by('id')
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


    public function _set_all($id_fiche,$echantillon) {
        return array(
            'id_fiche_echantillonnage_capture' => $id_fiche,
            'peche_hier'          =>      $echantillon->peche_hier,
            'peche_avant_hier'           =>      $echantillon->peche_avant_hier,
            'nbr_jrs_peche_dernier_sem'     =>      $echantillon->nbr_jrs_peche_dernier_sem ,
            'total_capture'          =>      $echantillon->total_capture,
            'unique_code'           =>      $echantillon->unique_code,
            'id_data_collect'     =>      $echantillon->id_data_collect, 
            'nbr_bateau_actif'          =>      $echantillon->nbr_bateau_actif,
            'total_bateau_ecn'           =>      $echantillon->total_bateau_ecn,
            'id_unite_peche'     =>      $echantillon->id_unite_peche,
            'id_user'     =>      $echantillon->id_user                 
        );
    }

    public function add_all($id_fiche, $echantillon) {
        $this->db->set($this->_set_all($id_fiche,$echantillon))
                            ->set('date_creation', 'NOW()', false)
                            ->set('date_modification', 'NOW()', false)
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }

    public function save_all($id_fiche, $data)
    {
        $this->db->trans_begin ();

        $tab_retour = array();

        

        foreach ($data as $key => $value) 
        {
            
            $echantillon_insert_id = $this->add_all($id_fiche,$value);

            //insert capture
            foreach ($value->especes_captures as $key_capture => $value_capture) 
            {
                 $dataId = $this->Espece_captureManager->add_all($id_fiche, $echantillon_insert_id, $value_capture);
                 if (!$dataId) {
                    
                 $tab_retour[$key_capture] = $value->id_unite_peche ;
                 }
            }
            //insert capture fin
        }
 

        if  ( $this->db->trans_status ()  ===  FALSE ) 
        { 
            $date=new datetime();
            $date_anio=$date->format('Y-m-d HH:mm:ss');                     
            error_log("Erreur dans Echantillon_model - Function save_all :" . $date_anio.' (Rollback)');

            $this->db->trans_rollback (); return "ECHEC" ;
        } 
        else 
        { 
            $date=new datetime();
            $date_anio=$date->format('Y-m-d HH:mm:ss');    
            error_log("Erreur dans Echantillon_model - Function save_all :" . $date_anio.' (test_commit)');
            $this->db->trans_commit ();   return $tab_retour ;
        }
    }
    public function uniquecode($id_fiche)
    {       
        $id=$this->max_id($id_fiche);
        if($id)
        {
            $result =  $this->db->select('unique_code')
                                ->from($this->table)
                                ->where('id_fiche_echantillonnage_capture',$id_fiche)
                                ->where('id',$id[0]->id)
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
    public function max_id($id_fiche)
    {       
        $result =  $this->db->select_max('id')
                            ->from($this->table)
                            ->where('id_fiche_echantillonnage_capture',$id_fiche)
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
