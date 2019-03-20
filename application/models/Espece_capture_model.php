<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Espece_capture_model extends CI_Model
{
    protected $table = 'espece_capture';


    public function add($espece_capture)
    {
        $this->db->set($this->_set($espece_capture))
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

    public function add_all($id_fiche, $id_echantillon, $espece_capture)
    {
        $this->db->set($this->_set_all($id_fiche, $id_echantillon, $espece_capture))
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

    public function _set_all($id_fiche, $id_echantillon,$espece_capture)
    {
        return array(
            
            'id_fiche_echantillonnage_capture' => $id_fiche,
            'id_echantillon' => $id_echantillon,
            'id_espece' => $espece_capture->id_espece,
            'capture' => $espece_capture->capture,
            'prix' => $espece_capture->prix                  
        );
    }


    public function update($id, $espece_capture)
    {
        $this->db->set($this->_set($espece_capture))
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

    public function _set($espece_capture)
    {
        return array(
            
            'id_fiche_echantillonnage_capture' => $espece_capture['id_fiche_echantillonnage_capture'],
            'id_echantillon' => $espece_capture['id_echantillon'],
            'id_espece' => $espece_capture['id_espece'],
            'capture' => $espece_capture['capture'],
            'prix' => $espece_capture['prix'],
            'id_user' => $espece_capture['id_user']                     
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

    public function findAllByEspece($id_espece)
    {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('capture')
                        ->where("id_espece", $id_espece)
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }
    public function findAllByEchantillon($id_echantillon)
    {
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->order_by('capture')
                        ->where("id_echantillon", $id_echantillon)
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


    public function SauvegarderTout($data) {

        $this->db->trans_begin();
        $espace_capture=array();
        $fiche_echantillonnage_capture=array();
        $fiche_echantillonnage_capture = json_decode($data['fiche_echantillonnage_capture ']);
        $espace_capture=$fiche_echantillonnage_capture->espace_capture;
        $valret=array();
        try{
            
            for ($i = 0; $i < count($espace_capture); $i++) {               
                
                $id_echantillon=null;
                if($espace_capture[$i]->id_echantillon >'') {
                    $id_echantillon=$espace_capture[$i]->id_echantillon;  
                }
                $id_espece=null;
                if($espace_capture[$i]->id_espece >'') {
                    $id_espece=$espace_capture[$i]->id_espece;  
                }
                 $capture=null;
                if($espace_capture[$i]->capture >'') {
                    $capture=$espace_capture[$i]->capture;  
                }
                $prix=null;
                if($espace_capture[$i]->prix >'') {
                    $prix=$espace_capture[$i]->prix;  
                }

                $tmp = array();               
                
                $tmp ['id_fiche_echantillonnage_capture'] = $fiche_echantillonnage_capture->id_fiche_echantillonnage_capture;                
                $tmp ['id_echantillon'] = $id_echantillon;    
                $tmp ['id_espece'] = $id_espece;
                $tmp ['capture'] = $capture;
                $tmp ['prix'] = $prix;
                $tmp ['id_user'] =$data['id_user'];
                
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
