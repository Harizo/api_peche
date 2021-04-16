<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SIP_poissonnerie_model extends CI_Model {
    protected $table = 'sip_poissonnerie';

    public function add($SIP_poissonnerie) {
        $this->db->set($this->_set($SIP_poissonnerie))
                            ->insert($this->table);
        if($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }else{
            return null;
        }                    
    }
    public function update($id, $SIP_poissonnerie) {
        $this->db->set($this->_set($SIP_poissonnerie))
                            ->where('id', (int) $id)
                            ->update($this->table);
        if($this->db->affected_rows() === 1)
        {
            return true;
        }else{
            return null;
        }                      
    }
    public function _set($SIP_poissonnerie) {
        return array(
            'id_region'     =>      $SIP_poissonnerie['id_region'],
            'id_district'   =>      $SIP_poissonnerie['id_district'],              
            'id_commune'    =>      $SIP_poissonnerie['id_commune'],
            'nom'           =>      $SIP_poissonnerie['nom'],
           'localisation'   =>      $SIP_poissonnerie['localisation'],                 
            'adresse'      	=>      $SIP_poissonnerie['adresse'],                 
            'rcs'     	    =>      $SIP_poissonnerie['rcs'],                 
            'stat'          =>      $SIP_poissonnerie['stat'],                 
            'nif'           =>      $SIP_poissonnerie['nif'],                 
            'tel'           =>      $SIP_poissonnerie['tel']          

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
               
        $result =  $this->db->select('ps.id as id, ps.nom, ps.localisation, ps.adresse, ps.rcs, ps.stat,
                                    ps.nif,ps.tel , re.nom as nom_region, re.id as id_region')
                        ->from('sip_poissonnerie as ps, region as re')
                        ->where('ps.id_region=re.id')
                        ->order_by('ps.nom')
                        ->get()
                        ->result() ;  
      
        if($result)
        {
            return $result;
        }else{
            return null;
        }                 
    }


    public function find_all_join($id_poissonnerie)
    {

        $sql = 
        "
            select 

                sch.id as id,

                cons.id as id_conservation,
                cons.libelle as libelle_conservation,

                pres.id as id_presentation,
                pres.libelle as libelle_presentation,

                sch.annee,
                sch.mois,
                sch.quantite,
                sch.prix,
                sch.coefficiant_conservation,
                sch.valeur

            from
                SIP_poissonnerie as sch,
                sip_permis as cm,
                sip_presentation as pres,
                sip_conservation as cons
            where
                sch.id_poissonnerie = cm.id
                
                and sch.id_presentation = pres.id
                and sch.id_conservation = cons.id
                and cm.id = ".$id_poissonnerie." 
            order by sch.annee desc

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

    public function findCleRegion($id_region)
    {
      
      $sql = " select ps.id as id,
                    dist.nom as districts,
                    comm.nom as communes, 
                    ps.nom, 
                    ps.localisation, 
                    ps.adresse, 
                    ps.rcs, 
                    ps.stat,
                    ps.nif,
                    ps.tel , 
                    
                    re.id as id_region,
                    re.nom as nom_region,
                    
                    dist.id as id_district,
                    
                    comm.id as id_commune
                               
            FROM sip_poissonnerie as ps, 
                    region as re, 
                    commune as comm, 
                    district as dist
            
            WHERE ps.id_region= re.id AND 
                    ps.id_region = ".$id_region." AND
                    ps.id_district = dist.id AND
                    ps.id_commune = comm.id
            
            ORDER BY re.nom
        ";

        return $this->db->query($sql)->result();
             
                        
        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }

    }
    
    public function findByRegionDistrictCommune($id_region,$id_district,$id_commune)
    {
      
      $sql = " select ps.id as id,
                   dist.nom as districts,
                    comm.nom as communes, 
                    ps.nom, 
                    ps.localisation, 
                    ps.adresse, 
                    ps.rcs, 
                    ps.stat,
                    ps.nif,
                    ps.tel , 
                    
                    re.id as id_region,
                    re.nom as nom_region,
                    
                    dist.id as id_district,
                    
                    comm.id as id_commune

            FROM sip_poissonnerie as ps, 
                    region as re, 
                    district as dist, 
                    commune as comm
            
            WHERE ps.id_region= re.id AND 
                    ps.id_region = ".$id_region." AND
                    ps.id_commune = ".$id_commune." AND
                    comm.id=ps.id_commune AND
                    ps.id_district = ".$id_district." AND
                    dist.id=ps.id_district
            
            ORDER BY re.nom
        ";

        return $this->db->query($sql)->result();
             
                        
        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }

    }
   
     public function findByRegionDistrict($id_region,$id_district)
    {
      
      $sql = " select ps.id as id,
                    dist.nom as districts,
                    comm.nom as communes, 
                    ps.nom, 
                    ps.localisation, 
                    ps.adresse, 
                    ps.rcs, 
                    ps.stat,
                    ps.nif,
                    ps.tel , 
                    
                    re.id as id_region,
                    re.nom as nom_region,
                    
                    dist.id as id_district,
                    
                    comm.id as id_commune

            FROM sip_poissonnerie as ps, 
                    region as re, 
                    district as dist,
                    commune as comm 
            
            WHERE ps.id_region= re.id AND 
                    ps.id_region = ".$id_region." AND
                    ps.id_district = ".$id_district." AND
                    dist.id = ps.id_district AND
                    ps.id_commune = comm.id
            
            ORDER BY re.nom
        ";

        return $this->db->query($sql)->result();
             
                        
        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }

    }
}
