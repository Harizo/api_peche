<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_carte_pecheur_model extends CI_Model {
    protected $table = 'sip_carte_pecheur';

    public function add($SIP_carte_pecheur) {
        $this->db->set($this->_set($SIP_carte_pecheur))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $SIP_carte_pecheur) {
        $this->db->set($this->_set($SIP_carte_pecheur))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($SIP_carte_pecheur) {
        return array(
            'numero'                =>      $SIP_carte_pecheur['numero'],
            'date'                  =>      $SIP_carte_pecheur['date'],      
            'id_fokontany'          =>      $SIP_carte_pecheur['id_fokontany'],      
            'village'               =>      $SIP_carte_pecheur['village'] ,
            'association'           =>      $SIP_carte_pecheur['association'] ,
            'nom'                   =>      $SIP_carte_pecheur['nom'] ,
            'prenom'                =>      $SIP_carte_pecheur['prenom'] ,
            'cin'                   =>      $SIP_carte_pecheur['cin'] ,
            'date_cin'              =>      $SIP_carte_pecheur['date_cin'] ,
            'date_naissance'        =>      $SIP_carte_pecheur['date_naissance'] ,
            'lieu_cin'              =>      $SIP_carte_pecheur['lieu_cin'] ,
            'nbr_pirogue'           =>      $SIP_carte_pecheur['nbr_pirogue'] 

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
                        ->order_by('date','desc')
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

    public function find_all_by_district($id_district)
    {

        $sql = 
        "
            select 
                scp.id as id,
                scp.numero as numero,
                scp.date as date,

                scp.id_fokontany as id_fokontany,
                fkt.nom as nom_fokontany,

                com.id as id_commune,
                com.nom as nom_commune,

                scp.village as village,
                scp.association as association,
                scp.nom as nom,
                scp.prenom as prenom,
                scp.cin as cin,
                scp.date_cin as date_cin,
                scp.date_naissance as date_naissance,
                scp.lieu_cin as lieu_cin,
                scp.nbr_pirogue as nbr_pirogue


           
            from
                sip_carte_pecheur as scp,
                fokontany as fkt,
                commune as com,
                district as dis,
                region as reg
                
            where
                scp.id_fokontany = fkt.id
                and fkt.id_commune = com.id
                and com.id_district = dis.id
                and dis.id_region = reg.id
                and dis.id = ".$id_district." 
            order by scp.date desc

        " ;
        return $this->db->query($sql)->result();
    }


    

   
}
