<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Espece_model extends CI_Model
{
    protected $table = 'espece';


    public function add($espece)
    {
        $this->db->set($this->_set($espece))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1)
        {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }


    public function update($id, $espece)
    {
        $this->db->set($this->_set($espece))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }

    public function _set($espece)
    {
        return array(
            'code'       =>      $espece['code'],
            'nom_local'    =>      $espece['nom_local'], 
            'nom_scientifique'=>      $espece['nom_scientifique'], 
            'url_image'    =>      $espece['url_image']                       
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

    public function findByIdtab($id)
    {
        $result =  $this->db->select('id as id_espece,nom_scientifique,nom_local,code')
                        ->from($this->table)
                        ->where("id", $id)
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


    public function findAllByFiche($annee) 
    {
        $requete = "date BETWEEN '".$annee."-01-01' AND '".$annee."-12-31' " ;

        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->join('espece_capture', 'espece_capture.id_espece = espece.id')
                        ->join('echantillon', 'espece_capture.id_echantillon = echantillon.id')
                        ->join('fiche_echantillonnage_capture', 'fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture')  
                            
                        ->order_by('code')
                        ->group_by('espece.id')
                        ->where($requete) 
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

}
