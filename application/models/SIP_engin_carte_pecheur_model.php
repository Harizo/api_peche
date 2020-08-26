<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_engin_carte_pecheur_model extends CI_Model {
    protected $table = 'sip_engin_carte_pecheur';

    public function add($SIP_engin_carte_pecheur) {
        $this->db->set($this->_set($SIP_engin_carte_pecheur))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $SIP_engin_carte_pecheur) {
        $this->db->set($this->_set($SIP_engin_carte_pecheur))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($SIP_engin_carte_pecheur) {
        return array(
            'id_carte_pecheur'          =>      $SIP_engin_carte_pecheur['id_carte_pecheur'],
            'id_type_engin'             =>      $SIP_engin_carte_pecheur['id_type_engin'],
            'nbr_engin'                 =>      $SIP_engin_carte_pecheur['nbr_engin'],      
            'utilisation_engin'         =>      $SIP_engin_carte_pecheur['utilisation_engin'],      
            'longueur'                  =>      $SIP_engin_carte_pecheur['longueur'],      
            'largeur'                   =>      $SIP_engin_carte_pecheur['largeur'],
            'hauteur'                   =>      $SIP_engin_carte_pecheur['hauteur'],
            'maille'                    =>      $SIP_engin_carte_pecheur['maille'],
            'hamecon'                   =>      $SIP_engin_carte_pecheur['hamecon']

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



    public function findAllbycartepecheur($id_carte_pecheur) {
               
        $result =  $this->db->select('*')
                        ->from($this->table)
                        ->where("id_carte_pecheur", $id_carte_pecheur)
                        ->order_by('id','desc')
                        ->get()
                        ->result();
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }

    public function find_all_by_carte($id_carte_pecheur)
    {
        $sql = 
        "
            select
                secp.id as id,
                secp.nbr_engin as nbr_engin,
                secp.utilisation_engin as utilisation_engin,
                secp.longueur as longueur,
                secp.largeur as largeur,
                secp.hauteur as hauteur,
                secp.maille as maille,
                secp.hamecon as hamecon,

                scp.id as id_carte_pecheur,
                

                te.id as id_type_engin,
                te.libelle as libelle_type_engin


            from
                sip_engin_carte_pecheur as secp,
                sip_carte_pecheur as scp,
                type_engin as te
            where 
                secp.id_carte_pecheur = scp.id
                and secp.id_type_engin = te.id
                and scp.id = ".$id_carte_pecheur."
        " ;


        return $this->db->query($sql)->result();
    }




    public function findById($id)  {
        $this->db->where("id", $id);
        $q = $this->db->get($this->table);
        if ($q->num_rows() > 0) {
            return $q->row();
        }  
    }
}
