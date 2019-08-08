<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fiche_echantillonnage_capture_model extends CI_Model 
{
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

    public function get_nbr_echantillon($condition)
    {
        

            $result = $this->db->select('COUNT(*) as nombre')
                ->from('echantillon')
                
                ->join('fiche_echantillonnage_capture', 'fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture')
                ->where($condition)
                ->where("fiche_echantillonnage_capture.validation = 1")
            
                ->get()
                ->result();
            if($result)
            {
                return $result;
            }else{
                return null;
            }  
    }


    public function get_nbr_max_echantillon($date_echantillon, $id_enqueteur, $id_unite_peche)
    {
        $tab_date = explode('-', $date_echantillon) ;
            $debut = $tab_date[0]."/".$tab_date[1]."/01" ;
            $fin = $tab_date[0]."/".$tab_date[1]."/31"  ;
            
            $array = array('fiche_echantillonnage_capture.date >=' => $debut, 'fiche_echantillonnage_capture.date <=' => $fin);

            $result = $this->db->select('COUNT(*) as nombre')
                ->from('echantillon')
                
                ->join('fiche_echantillonnage_capture', 'fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture')
                ->where($array)
                ->where("fiche_echantillonnage_capture.id_enqueteur" , $id_enqueteur)
                ->where("echantillon.id_unite_peche" , $id_unite_peche)
                ->get()
                ->result();
            if($result)
            {
                return $result;
            }else{
                return null;
            }  
    }

    public function som_capture_totales($requete)
    {
        $result = $this->db->select_sum('capture')
                            ->select_sum('prix')
                            ->from('fiche_echantillonnage_capture')
                            ->join('echantillon', 'fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture')  
                            ->join('espece_capture', 'espece_capture.id_echantillon = echantillon.id')                                      
                            ->where($requete)                          
                            ->where('fiche_echantillonnage_capture.validation',1)                         
                            ->get()
                            ->result();

        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }

    }
     //Debut pivot=='*' analyse_parametrable et requete6.2
   
    public function essai($requete,$reqsansSiteEspece,$reqcadre,$annee)
    {
       $this->db->select("DATE_FORMAT(fiche_echantillonnage_capture.date,'%Y') as anee,DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') as mois,enquete_cadre.id_region as id_reg,region.nom as nom_region,enquete_cadre.id_site_embarquement as id_site,enquete_cadre.id_unite_peche as id_unite,unite_peche.libelle as libelle,date");
        
        $this->db ->select("(select site_embarquement.libelle from site_embarquement where site_embarquement.id= id_site) as site_embarquement",FALSE);
        
        $this->db ->select("(select sum(nbr_unite_peche) from enquete_cadre
                                where  enquete_cadre.id_unite_peche = id_unite and enquete_cadre.id_region = id_reg and enquete_cadre.id_site_embarquement = id_site and ".$reqcadre." and enquete_cadre.annee = anee) as nbr_unit_peche
                               ",FALSE);

         $this->db ->select("(select (SUM(((1+echantillon.peche_hier+echantillon.peche_avant_hier+echantillon.nbr_jrs_peche_dernier_sem)/10)))/(COUNT(echantillon.id)) from fiche_echantillonnage_capture, echantillon, espece_capture where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                                and echantillon.id = espece_capture.id_echantillon
                                and fiche_echantillonnage_capture.id_region = id_reg
                                
                                and echantillon.id_unite_peche = id_unite and ".$reqsansSiteEspece." 
                                and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                                ) as pab_moy",FALSE);
         $this->db ->select("(select (SUM(espece_capture.capture)/1)/(COUNT(DISTINCT(echantillon.id))) from fiche_echantillonnage_capture, echantillon, espece_capture where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture
                                and echantillon.id = espece_capture.id_echantillon
                                and fiche_echantillonnage_capture.id_region = id_reg
                               
                                and echantillon.id_unite_peche = id_unite and ".$reqsansSiteEspece." 
                                and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                                ) as cpue_moyenne",FALSE);
        
        $this->db->select("sqrt((select COUNT(DISTINCT(echantillon.id)) from fiche_echantillonnage_capture, echantillon, espece_capture where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                                and echantillon.id = espece_capture.id_echantillon
                                and fiche_echantillonnage_capture.id_region = id_reg
                                and echantillon.id_unite_peche = id_unite and ".$reqsansSiteEspece." 
                                and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois
                               )) as sqrt",FALSE);

        $this->db->select("(select COUNT(DISTINCT(echantillon.unique_code)) from fiche_echantillonnage_capture, echantillon, espece_capture where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                                and echantillon.id = espece_capture.id_echantillon
                                and fiche_echantillonnage_capture.id_region = id_reg
                                and echantillon.id_unite_peche = id_unite 
                                and ".$reqsansSiteEspece." 
                                and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois
                               ) as nombre_echantillon",FALSE);

        $this->db->select("sqrt((select COUNT(echantillon.id) from fiche_echantillonnage_capture, echantillon where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture
                                and fiche_echantillonnage_capture.id_region = id_reg
                                
                                and echantillon.id_unite_peche = id_unite and ".$reqsansSiteEspece." 
                                and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                               )) as sqrtpab",FALSE);

        $this->db->select("(select ((COUNT(echantillon.id))-1) from fiche_echantillonnage_capture, echantillon where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture
                                and fiche_echantillonnage_capture.id_region = id_reg
                                
                                and echantillon.id_unite_peche = id_unite and ".$reqsansSiteEspece." 
                                and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                               ) as degreepab",FALSE);
       
        $this->db->select("(select STDDEV(((1+echantillon.peche_hier+echantillon.peche_avant_hier+echantillon.nbr_jrs_peche_dernier_sem)/10)) from fiche_echantillonnage_capture, echantillon where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture
                                and fiche_echantillonnage_capture.id_region = id_reg
                                
                                and echantillon.id_unite_peche = id_unite and ".$reqsansSiteEspece." 
                                and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                               ) as ecart_typepab",FALSE);

        $this->db->select("((select COUNT(DISTINCT(echantillon.id)) from fiche_echantillonnage_capture, echantillon, espece_capture where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                                and echantillon.id = espece_capture.id_echantillon
                                and fiche_echantillonnage_capture.id_region = id_reg
                                and echantillon.id_unite_peche = id_unite and ".$reqsansSiteEspece." 
                                and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois
                                ) - 1) as degree",FALSE);

        $this->db->select("(select SUM(espece_capture.capture) from fiche_echantillonnage_capture, echantillon,espece_capture where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture
                                and echantillon.id = espece_capture.id_echantillon
                                and fiche_echantillonnage_capture.id_region = id_reg
                                
                                and echantillon.id_unite_peche = id_unite and ".$reqsansSiteEspece." 
                                and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                               ) as capture_total_par_unite",FALSE);

        $this->db->select("(select AVG(espece_capture.prix) from fiche_echantillonnage_capture, echantillon,espece_capture where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture
                                and echantillon.id = espece_capture.id_echantillon and ".$requete." 
                               ) as prix_moyenne",FALSE);

        $result =  $this->db->from('enquete_cadre,echantillon,fiche_echantillonnage_capture ,espece_capture,unite_peche,region')
                    
                    ->where('enquete_cadre.id_unite_peche = echantillon.id_unite_peche')
                    ->where('enquete_cadre.annee',$annee)
                    ->where('enquete_cadre.id_region = region.id')
                    ->where('enquete_cadre.id_region = fiche_echantillonnage_capture.id_region')
                    ->where('fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture')
                     
                    ->where('echantillon.id = espece_capture.id_echantillon')
                    ->where('echantillon.id_unite_peche = unite_peche.id')
                    ->where($reqcadre)
                    //->order_by('DATE_FORMAT(fiche_echantillonnage_capture.date,"%c") = mois')
                    ->group_by('anee,mois,id_reg,id_site,id_unite')
                    //->group_by('anee')
                                       
                    ->get()
                    ->result();


        if($result)
        {   
            return $result;
        }
        else
        {
            return null;
        }               
    
    }
    // req_L1.1   
    public function capture_total_par_espece($requete)
    {
        $result = $this->db->select('SUM(espece_capture.capture) as capture_total_par_espece, espece.code as coda,espece.nom_local as nom_local, espece.nom_scientifique as nom_scientifique,AVG(espece_capture.prix) as prix')
                            ->from('espece_capture')
                            ->join('espece', 'espece_capture.id_espece = espece.id')
                            ->join('echantillon', 'echantillon.id = espece_capture.id_echantillon')  
                            ->join('fiche_echantillonnage_capture', 'espece_capture.id_fiche_echantillonnage_capture = fiche_echantillonnage_capture.id')
                            ->where($requete)
                            ->group_by('espece.code')                          
                                                    
                                                     
                            ->get()
                            ->result();

        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }

    }
    //calcul ecart_type analyse_parametrable

public function ecartypeAnalyse($requetesanssite)
{
    $result = $this->db->select('sum(espece_capture.capture) as ecart_type')
                    ->from('espece_capture')
                    
                    //->join('unite_peche','unite_peche.id = echantillon.id_unite_peche')
                    ->join('espece', 'espece_capture.id_espece = espece.id')
                    ->join('echantillon','espece_capture.id_echantillon = echantillon.id')
                    ->join('fiche_echantillonnage_capture','echantillon.id_fiche_echantillonnage_capture = fiche_echantillonnage_capture.id')
                    ->where($requetesanssite)
                    ->group_by('echantillon.id')
                    ->get()
                    ->result();
                   
  $data = array();
  $i=0;
        foreach ($result as $key => $value) {
            $data[$i] = $value->ecart_type ;
            $i++;
        }

        if($result)
        {
           
            return $this->stats_deviation($data);
        }
        else
        {
            return null;
        }
}

    public function som_capture_totales_journaliere($requete)
    {
        $result = $this->db->select('unite_peche.libelle as libelle_unite_peche,date,site_embarquement.libelle,SUM(espece_capture.capture ) as capture,SUM(espece_capture.capture/1) as cpue')
                            ->from('fiche_echantillonnage_capture')
                            ->join('echantillon', 'fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture')  
                            ->join('espece_capture', 'espece_capture.id_echantillon = echantillon.id')                  
                            ->join('unite_peche', 'echantillon.id_unite_peche = unite_peche.id')                  
                            ->join('site_embarquement', 'fiche_echantillonnage_capture.id_site_embarquement = site_embarquement.id')                  
                            ->group_by('id_site_embarquement')                          
                            ->group_by('id_unite_peche')                          
                            ->group_by('date')                          
                            ->where($requete)                          
                            //->where($requete)                          
                            ->where('fiche_echantillonnage_capture.validation',1)                         
                            ->get()
                            ->result();

        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }


    }

    public function som_capture_totales_average($requete)
    {
        
        $result = $this->db->select('fiche_echantillonnage_capture.id_region as id_region,unite_peche.libelle as libelle_unite_peche,unite_peche.id as id_unite_peche,espece_capture.capture as xxx,COUNT(DISTINCT(echantillon.unique_code)) as nombre,SUM(espece_capture.capture/1) as somme,((SUM(espece_capture.capture/1))/(COUNT(DISTINCT(echantillon.unique_code)))) as moyenne,AVG(espece_capture.capture/1) as moyenne_par_ecpece, fiche_echantillonnage_capture.id as id_fiche')
                            ->from('fiche_echantillonnage_capture')
                            ->join('echantillon', 'fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture')  
                            ->join('espece_capture', 'espece_capture.id_echantillon = echantillon.id')                  
                            ->join('unite_peche', 'echantillon.id_unite_peche = unite_peche.id')                
                            ->group_by('fiche_echantillonnage_capture.id_region')                          
                            ->group_by('id_unite_peche')                          
                                                
                            ->where($requete)                            
                         //   ->where('fiche_echantillonnage_capture.validation',1)                         
                            ->get()
                            ->result();

        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }


    }

    public function nbr_echantillon_par_unite_peche_fiche($id_fiche,$id_unite_peche)
    {
        
       
        $result = $this->db->select('COUNT(*) as nombre')
                            ->from('echantillon')              
                            ->where('id_fiche_echantillonnage_capture', $id_fiche)                                             
                            ->where('id_unite_peche', $id_unite_peche)                                             
                            ->get()
                            ->result();

        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }


    }

    public function findAll_unite_peche_date($requete,$id_region,$id_unite_peche) {
        $result = $this->db ->select('capture')
                            ->from('fiche_echantillonnage_capture')
                            ->join('echantillon', 'fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture')  
                            ->join('espece_capture', 'espece_capture.id_echantillon = echantillon.id')                  
                            ->join('unite_peche', 'echantillon.id_unite_peche = unite_peche.id')                  
                                           
                            ->where($requete)                          
                                                 
                            ->where('fiche_echantillonnage_capture.id_region',$id_region)                         
                            ->where('echantillon.id_unite_peche',$id_unite_peche)                         
                           // ->where('fiche_echantillonnage_capture.validation',1)                         
                            ->get()
                            ->result();

        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }               
    }

    public function stats_standard_deviation(array $a) {
        $sample = false;
        $n = count($a);
        if ($n === 0) {
            trigger_error("tableau vide", E_USER_WARNING);
            return false;
        }
        if ($sample && $n === 1) {
            trigger_error("1 seul element dans le tableau", E_USER_WARNING);
            return false;
        }
        $mean = array_sum($a) / $n;
        $carry = 0.0;
        foreach ($a as $val) {
            $d = ((double) $val) - $mean;
            $carry += $d * $d;
        };
        if ($sample) {
           --$n;
        }
        return sqrt($carry / $n-1);
    }


    public function ecart_type($requete,$id_fiche,$id_region,$id_unite_peche) {
        $result = $this->db/*->select_sum('capture')*/
                            ->select('SUM(espece_capture.capture) as ecart_type')
                            ->from('fiche_echantillonnage_capture')
                            ->join('echantillon', 'fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture')  
                            ->join('espece_capture', 'espece_capture.id_echantillon = echantillon.id')                 
                            ->join('unite_peche', 'echantillon.id_unite_peche = unite_peche.id')                  
                            ->group_by('echantillon.id')                
                            ->where($requete)                          
                        
                            ->where('fiche_echantillonnage_capture.id_region',$id_region)                         
                            ->where('echantillon.id_unite_peche',$id_unite_peche)                         
                            ->where('fiche_echantillonnage_capture.id',$id_fiche)                         
                            //->where('fiche_echantillonnage_capture.validation',1)                         
                            ->get()
                            ->result();$data = array();$i=0;
        foreach ($result as $key => $value) {
            $data[$i] = $value->ecart_type ;$i++;
        }

        if($result)
        {
           
            return $this->stats_standard_deviation($data);
        }
        else
        {
            return null;
        }               
    }

        public function ecart_type8METY($requete,$id_fiche,$id_region,$id_unite_peche) {
        $result = $this->db->select('((espece_capture.capture)) as ecart_type')
                            ->from('fiche_echantillonnage_capture')
                            ->join('echantillon', 'fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture')  
                            ->join('espece_capture', 'espece_capture.id_echantillon = echantillon.id')                 
                            ->join('unite_peche', 'echantillon.id_unite_peche = unite_peche.id')                  
                            ->group_by('echantillon.id')                
                            ->where($requete)                          
                        
                            ->where('fiche_echantillonnage_capture.id_region',$id_region)                         
                            ->where('echantillon.id_unite_peche',$id_unite_peche)                         
                            ->where('fiche_echantillonnage_capture.id',$id_fiche)                          
                            ->get()
                            ->result();

        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }           
    }


    public function get_pab_moy_ecart_nbr_jrs_par_unite_peche($requete)
    {
         $result = $this->db ->select('COUNT(echantillon.id) as nombre_echantillon,unite_peche.libelle as libelle_unite_peche,
            SUM(((1+echantillon.peche_hier+echantillon.peche_avant_hier+echantillon.nbr_jrs_peche_dernier_sem)/10)) as somme_pab,
            STDDEV(((1+echantillon.peche_hier+echantillon.peche_avant_hier+echantillon.nbr_jrs_peche_dernier_sem)/10)) as ecart_type,

            ((SUM(((1+echantillon.peche_hier+echantillon.peche_avant_hier+echantillon.nbr_jrs_peche_dernier_sem)/10)))/(COUNT(echantillon.id))) as pab_moy,
            ((SUM(((1+echantillon.peche_hier+echantillon.peche_avant_hier+echantillon.nbr_jrs_peche_dernier_sem)/10)))/(COUNT(echantillon.id))*30.5) as nbr_jrs_peche_mois,
            (sqrt(COUNT(echantillon.id))) as sqrt,
            ((COUNT(echantillon.id))-1) as degree

            ')
                            ->from('echantillon')
                            ->join('fiche_echantillonnage_capture', 'fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture')  

                                            
                            ->join('unite_peche', 'echantillon.id_unite_peche = unite_peche.id')                  
                            ->join('site_embarquement', 'fiche_echantillonnage_capture.id_site_embarquement = site_embarquement.id')
                            ->group_by('echantillon.id_unite_peche')                                                           
                            ->where($requete)                                                 
                            ->get()
                            ->result();

        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }               
    
    }
    

    public function erreur_relative_pab_moy_par_unite_peche($requete)
    {
         $result = $this->db ->select('COUNT(echantillon.id) as nombre_echantillon,unite_peche.libelle as libelle_unite_peche,
            SUM(((1+echantillon.peche_hier+echantillon.peche_avant_hier+echantillon.nbr_jrs_peche_dernier_sem)/10)) as somme_pab,
            STDDEV(((1+echantillon.peche_hier+echantillon.peche_avant_hier+echantillon.nbr_jrs_peche_dernier_sem)/10)) as ecart_type,

            ((SUM(((1+echantillon.peche_hier+echantillon.peche_avant_hier+echantillon.nbr_jrs_peche_dernier_sem)/10)))/(COUNT(echantillon.id))) as pab_moy,
            ((SUM(((1+echantillon.peche_hier+echantillon.peche_avant_hier+echantillon.nbr_jrs_peche_dernier_sem)/10)))/(COUNT(echantillon.id))*30.5) as nbr_jrs_peche_mois,
            (sqrt(COUNT(echantillon.id))) as sqrt,
            ((COUNT(echantillon.id))-1) as degree,
            date,
            DATE_FORMAT(fiche_echantillonnage_capture.date,"%c") as mois

            ')
                            ->from('echantillon')
                            ->join('fiche_echantillonnage_capture', 'fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture')  

                                            
                            ->join('unite_peche', 'echantillon.id_unite_peche = unite_peche.id')                  
                            ->join('site_embarquement', 'fiche_echantillonnage_capture.id_site_embarquement = site_embarquement.id')
                            ->group_by('echantillon.id_unite_peche')                                                           
                            ->group_by('mois')                                                           
                            ->where($requete)        
                           // ->where('fiche_echantillonnage_capture.validation',1)                                            
                            ->get()
                            ->result();

        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }               
    
    }

    public function nbr_jrs_peche_mensuel_pab($mois , $annee, $id_unite_peche)//mila  verifiena raha asina ragion ny parametre
    {
        $res = mktime( 0, 0, 0, $mois, 1, $annee ); 
        $nbr_jour = intval(date("t",$res));
     

        $debut = $annee."/".$mois."/01" ;
        $fin = $annee."/".$mois."/".$nbr_jour  ;
        $array = array('fiche_echantillonnage_capture.date >=' => $debut, 'fiche_echantillonnage_capture.date <=' => $fin);

         $result = $this->db ->select('

            ((SUM(((1+echantillon.peche_hier+echantillon.peche_avant_hier+echantillon.nbr_jrs_peche_dernier_sem)/10)))/(COUNT(echantillon.id))) as pab_moy,
            COUNT(echantillon.id) as nbr_echantillon

            ')
                            ->from('echantillon')
                            ->join('fiche_echantillonnage_capture', 'fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture')  

                                            
                            ->join('unite_peche', 'echantillon.id_unite_peche = unite_peche.id')                  
                            ->join('site_embarquement', 'fiche_echantillonnage_capture.id_site_embarquement = site_embarquement.id')
                            ->group_by('echantillon.id_unite_peche')                                                           
                            ->where($array)                                                 
                            ->where('echantillon.id_unite_peche',$id_unite_peche)                                                 
                                                                          
                            ->get()
                            ->result();

        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }               
    
    }

    public function get_pab_region_site_unite_peche($requete)//requete 5.1
    {
         $result = $this->db ->select('fiche_echantillonnage_capture.date as date,fiche_echantillonnage_capture.code_unique as code_unique_fiche,echantillon.unique_code as code_unique_echantillon , echantillon.nbr_jrs_peche_dernier_sem, echantillon.peche_hier, echantillon.peche_avant_hier,unite_peche.libelle as libelle_unite_peche, site_embarquement.libelle as libelle,((1+echantillon.peche_hier+echantillon.peche_avant_hier+echantillon.nbr_jrs_peche_dernier_sem)/10) as pab')
                            ->from('echantillon')
                            ->join('fiche_echantillonnage_capture', 'fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture')  
                                            
                            ->join('unite_peche', 'echantillon.id_unite_peche = unite_peche.id')                  
                            ->join('site_embarquement', 'fiche_echantillonnage_capture.id_site_embarquement = site_embarquement.id')                                                           
                            ->where($requete)                                                 
                            ->get()
                            ->result();

        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }               
    
    }

    public function req_7_1($requete)
    {
        
        $result = $this->db->select('SUM(espece_capture.capture) as capture_par_espece,unite_peche.libelle as libelle_unite_peche,unite_peche.id as id_unite_peche,
                                    espece.code as code,espece.nom_local as nom_local,COUNT(espece_capture.id) as nbr,SUM(espece_capture.prix) as prix_unitaire_total,ROUND(((SUM(espece_capture.prix))/(COUNT(espece_capture.id))),2) as prix_unitaire_moyenne,DATE_FORMAT(fiche_echantillonnage_capture.date,"%c") as mois,fiche_echantillonnage_capture.date,
                                        DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y") as annee,fiche_echantillonnage_capture.id_region as id_region')
                            ->from('fiche_echantillonnage_capture')
                            ->join('echantillon', 'fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture')  
                            ->join('espece_capture', 'espece_capture.id_echantillon = echantillon.id')                  
                            ->join('unite_peche', 'echantillon.id_unite_peche = unite_peche.id')                
                            ->join('espece', 'espece_capture.id_espece = espece.id')                
                            ->group_by('fiche_echantillonnage_capture.id_region')                          
                            ->group_by('mois')    //groupe par mois                      
                            ->group_by('espece.id')                          
                                                
                            ->where($requete)                            
                            ->where('fiche_echantillonnage_capture.validation',1)                         
                            ->get()
                            ->result();

        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }


    }

    public function req_7_2($requete)
    {
        
        $result = $this->db->select('SUM(espece_capture.capture) as capture_par_espece,unite_peche.libelle as libelle_unite_peche,
                                    espece.code as code,espece.nom_local as nom_local,COUNT(espece_capture.id) as nbr,SUM(espece_capture.prix) as prix_unitaire_total,ROUND(((SUM(espece_capture.prix))/(COUNT(espece_capture.id))),2) as prix_unitaire_moyenne,DATE_FORMAT(fiche_echantillonnage_capture.date,"%c") as mois,fiche_echantillonnage_capture.date')
                            ->from('fiche_echantillonnage_capture')
                            ->join('echantillon', 'fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture')  
                            ->join('espece_capture', 'espece_capture.id_echantillon = echantillon.id')                  
                            ->join('unite_peche', 'echantillon.id_unite_peche = unite_peche.id')                
                            ->join('espece', 'espece_capture.id_espece = espece.id')                
                            ->group_by('fiche_echantillonnage_capture.id_region')                          
                            ->group_by('mois')    //groupe par mois                      
                            ->group_by('unite_peche.id')                          
                                                
                            ->where($requete)                            
                          //  ->where('fiche_echantillonnage_capture.validation',1)            //ESORINA IZAY VAO MITOVY @ACCESS,IZA NORAISINA VALIDER SA IZY REHETRA?????             
                            ->get()
                            ->result();

        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }


    }

    public function pour_7_3($mois, $annee, $id_unite_peche, $id_region)
    {
        $res = mktime( 0, 0, 0, $mois, 1, $annee ); 
        $nbr_jour = intval(date("t",$res));
     

        $debut = $annee."/".$mois."/01" ;
        $fin = $annee."/".$mois."/".$nbr_jour  ;
        $array = array('fiche_echantillonnage_capture.date >=' => $debut, 'fiche_echantillonnage_capture.date <=' => $fin);

        $result = $this->db->select('SUM(espece_capture.capture) as capture_total')
                            ->from('fiche_echantillonnage_capture')
                            ->join('echantillon', 'fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture')
                            ->join('espece_capture', 'espece_capture.id_echantillon = echantillon.id')   
                            ->join('unite_peche', 'echantillon.id_unite_peche = unite_peche.id')           
                            ->where($array)                            
                            ->where('echantillon.id_unite_peche',$id_unite_peche)                         
                            ->where('fiche_echantillonnage_capture.id_region',$id_region)                         
                            ->where('fiche_echantillonnage_capture.validation',1)                         
                            ->get()
                            ->result();

        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }


    }


    /*public function req_8($requete)
    {
        
        $result = $this->db->select('SUM(espece_capture.capture) as capture_par_espece,
            unite_peche.libelle as libelle_unite_peche,unite_peche.id as id_unite_peche,
            espece.code as code,espece.nom_local as nom_local,
            SUM(espece_capture.prix) as prix_unitaire_total,
            ROUND(((SUM(espece_capture.prix))/(COUNT(espece_capture.id))),2) as prix_unitaire_moyenne,
            DATE_FORMAT(fiche_echantillonnage_capture.date,"%c") as mois,
            fiche_echantillonnage_capture.date,
            DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y") as annee,
            fiche_echantillonnage_capture.id_region as id_region,
            ((SUM(((1+echantillon.peche_hier+echantillon.peche_avant_hier+echantillon.nbr_jrs_peche_dernier_sem)/10)))/(COUNT(echantillon.id))) as pab_moy,
            COUNT(DISTINCT(echantillon.id)) as nbr_echantillon,
            ((SUM(((1+echantillon.peche_hier+echantillon.peche_avant_hier+echantillon.nbr_jrs_peche_dernier_sem)/10)))/(COUNT(echantillon.id))*30.5) as nbr_jrs_peche_mois,
            (sqrt(COUNT(echantillon.id))) as sqrt,
            ((COUNT(echantillon.id))-1) as degree,
            ((SUM(espece_capture.capture/1))/(COUNT(DISTINCT(echantillon.unique_code)))) as cpue_moyenne,
            site_embarquement.libelle as libelle_site,site_embarquement.id as id_site,')

                            ->from('fiche_echantillonnage_capture')
                            ->join('echantillon', 'fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture')  
                            ->join('espece_capture', 'espece_capture.id_echantillon = echantillon.id')                  
                            ->join('unite_peche', 'echantillon.id_unite_peche = unite_peche.id')                
                            ->join('espece', 'espece_capture.id_espece = espece.id')     
                            ->join('site_embarquement', 'fiche_echantillonnage_capture.id_site_embarquement = site_embarquement.id') 
                            ->group_by('fiche_echantillonnage_capture.id_region')                          
                            ->group_by('mois')    //groupe par mois                      
                            ->group_by('echantillon.id_unite_peche')                          
                            ->group_by('espece.id')                          
                            //->group_by('fiche_echantillonnage_capture.id_site_embarquement')                          
                                                
                            ->where($requete)                            
                            ->where('fiche_echantillonnage_capture.validation',1)                         
                            ->get()
                            ->result();

        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }


    }*/


    public function req_8_query($condition)
    {
        $requete = "select  
                    id_espece as es,
                    espece.code as code_espece,
                    site_embarquement.id as id_site,
                    site_embarquement.libelle as l_site,
                    echantillon.id_unite_peche as i_id_unite , 
                        unite_peche.libelle as l_id_unite , 
                        DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') as mois,
                        DATE_FORMAT(fiche_echantillonnage_capture.date,'%Y') as annee,
                        
                    ( ROUND((( 
                      sum(capture) / 
                      (select sum(capture) from fiche_echantillonnage_capture,espece_capture,echantillon 
                        where espece_capture.id_echantillon = echantillon.id 
                        and echantillon.id_unite_peche = i_id_unite and ".$condition." 
                        and fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                        and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois
                        ) ) * 100),2)) as composition_espece,

                        (select sum((espece_capture.capture)) from fiche_echantillonnage_capture,espece_capture,echantillon
                         where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                        
                        and  echantillon.id = espece_capture.id_echantillon
                        and echantillon.id_unite_peche = i_id_unite and ".$condition." 
                        and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                        and fiche_echantillonnage_capture.id_site_embarquement = id_site 
                        
                        ) as somme_capture_par_unite_peche_site ,

                        (ROUND(((select sum((espece_capture.capture)) from fiche_echantillonnage_capture,espece_capture,echantillon
                            where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 

                            and  echantillon.id = espece_capture.id_echantillon
                            and echantillon.id_unite_peche = i_id_unite and ".$condition." 
                            and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                            and fiche_echantillonnage_capture.id_site_embarquement = id_site 

                            ) * (( ((( 
                            sum(capture) / 
                            (select sum(capture) from fiche_echantillonnage_capture,espece_capture,echantillon 
                            where espece_capture.id_echantillon = echantillon.id 
                            and echantillon.id_unite_peche = i_id_unite and ".$condition." 
                            and fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                            and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois
                            ) ) ))))),2)) 
                        as total_capture_espece,

                            (ROUND(((SUM(((1+echantillon.peche_hier+echantillon.peche_avant_hier+echantillon.nbr_jrs_peche_dernier_sem)/10)))/(COUNT(echantillon.id))),2)) as pab_moy,
                           
                            (select COUNT(DISTINCT(echantillon.id)) from fiche_echantillonnage_capture,echantillon
                                                          where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                                                         
                                                        
                                                         and echantillon.id_unite_peche = i_id_unite and ".$condition." 
                                                         and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                                                         and fiche_echantillonnage_capture.id_site_embarquement = id_site ) 
                            as nbr_echantillon,

                            (select count(echantillon.id) from echantillon,fiche_echantillonnage_capture, espece_capture where  fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture and
                            espece_capture.id_echantillon = echantillon.id 
                                and espece_capture.id_espece = es and ".$condition." 
                                                         and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                                                         and fiche_echantillonnage_capture.id_site_embarquement = id_site) as nbr_echantillon_espece,

                            (select sum(prix) from fiche_echantillonnage_capture,echantillon,espece_capture where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture and 
                                espece_capture.id_espece = es and 
                                espece_capture.id_echantillon = echantillon.id  
                                and ".$condition.") as prix_unitaire_totale_espece,

                            (ROUND((sum((prix)) / count(espece_capture.id_espece)),2)) as prix_moyenne ,


                            (ROUND(((select sum(prix) from fiche_echantillonnage_capture,echantillon,espece_capture where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture and 
                                espece_capture.id_espece = es and 
                                espece_capture.id_echantillon = echantillon.id  
                                and ".$condition.") / (select count(echantillon.id) from echantillon,fiche_echantillonnage_capture where  fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture  
                                    and espece_capture.id_espece = es and ".$condition." and 
                                    espece_capture.id_echantillon = echantillon.id  
                                    and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                                    and fiche_echantillonnage_capture.id_site_embarquement = id_site)),2)) as prix_moyenne_espece ,



                            (ROUND((select sum(prix) from fiche_echantillonnage_capture,echantillon,espece_capture where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture and 
                                espece_capture.id_espece = es and 
                                espece_capture.id_echantillon = echantillon.id  
                                and ".$condition.")
                                / (select count(echantillon.id) from echantillon,fiche_echantillonnage_capture where  fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture  
                                and echantillon.id_unite_peche = i_id_unite and ".$condition." 
                                                         and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                                                         and fiche_echantillonnage_capture.id_site_embarquement = id_site ),2)) as prix_moyenne_unite_peche ,

                            (ROUND((select sum(espece_capture.capture) from fiche_echantillonnage_capture,espece_capture,echantillon
                                    where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                                   
                                   and  echantillon.id = espece_capture.id_echantillon
                                   and echantillon.id_unite_peche = i_id_unite and ".$condition." 
                                   and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                                   and fiche_echantillonnage_capture.id_site_embarquement = id_site 
                                   
                                   ) * (
                                                         sum(capture) / 
                                                         (select sum(capture) from fiche_echantillonnage_capture,espece_capture,echantillon 
                                                           where espece_capture.id_echantillon = echantillon.id 
                                                           and echantillon.id_unite_peche = i_id_unite and ".$condition." 
                                                           and fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                                                           and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois
                                                           )  * (sum(prix) / count(espece_capture.id_espece))),2)
                            ) as prix_espece,

                                    ((select COUNT(DISTINCT(echantillon.id)) from fiche_echantillonnage_capture,echantillon
                                          where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                                         and echantillon.id_unite_peche = i_id_unite and ".$condition." 
                                         and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                                         and fiche_echantillonnage_capture.id_site_embarquement = id_site ) - 1) 
                                    as degree,

                                    (ROUND((select PercentFractile90 from distribution_fractile where DegreesofFreedom = ((select COUNT(DISTINCT(echantillon.id)) from fiche_echantillonnage_capture,echantillon
                                    where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                                   and echantillon.id_unite_peche = i_id_unite and ".$condition." 
                                   and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                                   and fiche_echantillonnage_capture.id_site_embarquement = id_site ) - 1)),2)) as distribution,

                                   ROUND(STDDEV(( 1+echantillon.peche_hier+echantillon.peche_avant_hier+echantillon.nbr_jrs_peche_dernier_sem)/10),2) as ecart_type_pab,


                                    (ROUND(sqrt((select COUNT(DISTINCT(echantillon.id)) from fiche_echantillonnage_capture,echantillon
                                            where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                                           
                                          
                                           and echantillon.id_unite_peche = i_id_unite and ".$condition." 
                                           and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                                           and fiche_echantillonnage_capture.id_site_embarquement = id_site )),2)) as sqrt,

        (ROUND(((STDDEV(( 1+echantillon.peche_hier+echantillon.peche_avant_hier+echantillon.nbr_jrs_peche_dernier_sem)/10)
            *
            (select PercentFractile90 from distribution_fractile where DegreesofFreedom = ((select COUNT(DISTINCT(echantillon.id)) from fiche_echantillonnage_capture,echantillon
        where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
        and echantillon.id_unite_peche = i_id_unite and ".$condition." 
        and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
        and fiche_echantillonnage_capture.id_site_embarquement = id_site ) - 1))*

        sqrt((select COUNT(DISTINCT(echantillon.id)) from fiche_echantillonnage_capture,echantillon
             where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
            
           
            and echantillon.id_unite_peche = i_id_unite and ".$condition." 
            and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
            and fiche_echantillonnage_capture.id_site_embarquement = id_site ))) * 100 ),2)) as erreur_relative_90                                         

                             
                             

                    from espece_capture , echantillon , fiche_echantillonnage_capture , unite_peche , espece ,site_embarquement
                    where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture
                    and espece_capture.id_echantillon = echantillon.id 
                    and unite_peche.id = echantillon.id_unite_peche
                    and espece.id = espece_capture.id_espece
                    and fiche_echantillonnage_capture.id_site_embarquement = site_embarquement.id

                    and ".$condition."
                    group by id_unite_peche,id_espece ,mois,annee,site_embarquement.id order by mois" ;

        
        if ($requete) 
        {
            $query= $this->db->query($requete);
            return $query->result();
        }
        else
            return false;
    }


    public function req_8($condition)
    {
        //RESULTAT GLOBAL
            $this->db->select("espece_capture.id_espece as id_espece_aff,
                                espece.code as code_espece,
                                fiche_echantillonnage_capture.id_site_embarquement as id_site,
                                site_embarquement.libelle as libelle_site,
                                echantillon.id_unite_peche as id_unite_peche_aff,
                                unite_peche.libelle as libelle_unite_peche,
                                DATE_FORMAT(fiche_echantillonnage_capture.date,'%Y') as annee,
                                DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') as mois,
                                sum(capture) as capture_espece"
                            ); 
        //FIN RESULTAT GLOBAL
       

        //NBR ECHANTILLON PAR UNITE DE PECHE par espece
            $this->db->select("(select COUNT(DISTINCT(echantillon.id)) from fiche_echantillonnage_capture, echantillon, espece_capture
                                where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                                and echantillon.id = espece_capture.id_echantillon

                                and echantillon.id_unite_peche = id_unite_peche_aff and ".$condition." 
                                and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                                and fiche_echantillonnage_capture.id_site_embarquement = id_site ) 
                            as nbr_echantillon_par_unite_peche_espece_selected ", FALSE); 
        //FIN NBR ECHANTILLON PAR UNITE DE PECHE par espece

        //DEGREE DE LIBERTE
            $this->db->select("((select COUNT(DISTINCT(echantillon.id)) from fiche_echantillonnage_capture, echantillon, espece_capture
                                            where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                                            and echantillon.id = espece_capture.id_echantillon
            
                                            and echantillon.id_unite_peche = id_unite_peche_aff and ".$condition." 
                                            and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                                            and fiche_echantillonnage_capture.id_site_embarquement = id_site ) - 1) as degree_librtee",FALSE);
        //DEGREE DE LIBERTE

        //SQRT NBR ECHANTILLON
            $this->db->select("sqrt((select COUNT(DISTINCT(echantillon.id)) from fiche_echantillonnage_capture, echantillon, espece_capture
                                            where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                                            and echantillon.id = espece_capture.id_echantillon
            
                                            and echantillon.id_unite_peche = id_unite_peche_aff and ".$condition." 
                                            and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                                            and fiche_echantillonnage_capture.id_site_embarquement = id_site )) as sqrt",FALSE);
        //SQRT NBR ECHANTILLON

        //PAB MOYENNE PAR UNITE PECHE
            
            $this->db->select("((select sum((1+echantillon.peche_hier+echantillon.peche_avant_hier+echantillon.nbr_jrs_peche_dernier_sem)/10) from fiche_echantillonnage_capture,echantillon 
                            where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
            
                            and echantillon.id_unite_peche = id_unite_peche_aff and ".$condition." 
                            and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                            and fiche_echantillonnage_capture.id_site_embarquement = id_site) / (select COUNT(DISTINCT(echantillon.id)) from fiche_echantillonnage_capture,echantillon
                                where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                                and echantillon.id_unite_peche = id_unite_peche_aff and ".$condition." 
                                and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                                and fiche_echantillonnage_capture.id_site_embarquement = id_site )) as pab_moyenne",FALSE);
        //PAB MOYENNE PAR UNITE PECHE

        //STDEV PAB
            
            $this->db->select("(select STDDEV((1+echantillon.peche_hier+echantillon.peche_avant_hier+echantillon.nbr_jrs_peche_dernier_sem)/10) from fiche_echantillonnage_capture,echantillon 
                            where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
            
                            and echantillon.id_unite_peche = id_unite_peche_aff and ".$condition." 
                            and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                            and fiche_echantillonnage_capture.id_site_embarquement = id_site) as stddev_pab",FALSE);
        //STDEV PAB

        //t-distribution 90%
            $this->db->select("(select PercentFractile90 from distribution_fractile where DegreesofFreedom = ((select COUNT(DISTINCT(echantillon.id)) from fiche_echantillonnage_capture, echantillon, espece_capture
                                            where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                                            and echantillon.id = espece_capture.id_echantillon
            
                                            and echantillon.id_unite_peche = id_unite_peche_aff and ".$condition." 
                                            and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                                            and fiche_echantillonnage_capture.id_site_embarquement = id_site ) - 1)) as fraction_t_distribution",FALSE);
        //t-distribution 90%

        //ERROR RELATIVE
            $this->db->select("(select STDDEV((1+echantillon.peche_hier+echantillon.peche_avant_hier+echantillon.nbr_jrs_peche_dernier_sem)/10) from fiche_echantillonnage_capture,echantillon 
                            where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
            
                            and echantillon.id_unite_peche = id_unite_peche_aff and ".$condition." 
                            and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                            and fiche_echantillonnage_capture.id_site_embarquement = id_site) * (select PercentFractile90 from distribution_fractile where DegreesofFreedom = ((select COUNT(DISTINCT(echantillon.id)) from fiche_echantillonnage_capture, echantillon, espece_capture
                                            where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                                            and echantillon.id = espece_capture.id_echantillon
            
                                            and echantillon.id_unite_peche = id_unite_peche_aff and ".$condition." 
                                            and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                                            and fiche_echantillonnage_capture.id_site_embarquement = id_site ) - 1)) * (sqrt((select COUNT(DISTINCT(echantillon.id)) from fiche_echantillonnage_capture, echantillon, espece_capture
                                            where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                                            and echantillon.id = espece_capture.id_echantillon
            
                                            and echantillon.id_unite_peche = id_unite_peche_aff and ".$condition." 
                                            and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                                            and fiche_echantillonnage_capture.id_site_embarquement = id_site ))) as error_relative",FALSE);
        //ERROR RELATIVE
       
        //CAPTURE TOTAL UNITE DE PECHE
            $this->db->select("(select SUM((capture)) from fiche_echantillonnage_capture, echantillon, espece_capture
                                where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                                and echantillon.id = espece_capture.id_echantillon

                                and echantillon.id_unite_peche = id_unite_peche_aff and ".$condition." 
                                and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                                and fiche_echantillonnage_capture.id_site_embarquement = id_site ) 
                            as capture_total_unite_peche ", FALSE); 
        //FIN CAPTURE TOTAL UNITE DE PECHE

        //CAPTURE TOTAL UNITE DE PECHE
           // $this->db->select("", FALSE); 
        //FIN CAPTURE TOTAL UNITE DE PECHE

        //COMPOSITION ESPECE

            $this->db->select("( ROUND((( sum(capture) / 
                      (select sum(capture) from fiche_echantillonnage_capture,espece_capture,echantillon 
                        where espece_capture.id_echantillon = echantillon.id 
                        and echantillon.id_unite_peche = id_unite_peche_aff and ".$condition." 
                        and fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                        and fiche_echantillonnage_capture.id_site_embarquement = id_site
                        and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois
                        ) ) * 100),2)) as composition_espece",FALSE);
        //FIN COMPOSITION ESPECE
        
        //PRIX MOY
            $this->db->select("(ROUND((sum((prix)) / count(espece_capture.id_espece)),2)) as prix_moyenne",FALSE);
        //PRIX MOY


        //TOTAL CAPTURE PAR ESPECE capture total * composition espece
            $this->db->select("(((select SUM((capture)) from fiche_echantillonnage_capture, echantillon, espece_capture
                                                        where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                                                        and echantillon.id = espece_capture.id_echantillon
                        
                                                        and echantillon.id_unite_peche = id_unite_peche_aff and ".$condition." 
                                                        and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                                                        and fiche_echantillonnage_capture.id_site_embarquement = id_site ) * ( ((( sum(capture) / 
                                  (select sum(capture) from fiche_echantillonnage_capture,espece_capture,echantillon 
                                    where espece_capture.id_echantillon = echantillon.id 
                                    and echantillon.id_unite_peche = id_unite_peche_aff and ".$condition." 
                                    and fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                                    and fiche_echantillonnage_capture.id_site_embarquement = id_site
                                    and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois
                                    ) ) ))))/100) as total_capture_espece",FALSE);
        //FIN TOTAL CAPTURE PAR ESPECE

        //Prix par ESPECE
            $this->db->select("((((select SUM((capture)) from fiche_echantillonnage_capture, echantillon, espece_capture
                                where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                                and echantillon.id = espece_capture.id_echantillon

                                and echantillon.id_unite_peche = id_unite_peche_aff and ".$condition." 
                                and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois 
                                and fiche_echantillonnage_capture.id_site_embarquement = id_site ) * ( ((( sum(capture) / 
          (select sum(capture) from fiche_echantillonnage_capture,espece_capture,echantillon 
            where espece_capture.id_echantillon = echantillon.id 
            and echantillon.id_unite_peche = id_unite_peche_aff and ".$condition." 
            and fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
            and fiche_echantillonnage_capture.id_site_embarquement = id_site
            and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois
            ) ) ))))/100) * (ROUND((sum((prix)) / count(espece_capture.id_espece)),2))) as prix_par_espece",FALSE);
        //Prix par ESPECE

     
        

            $result =  $this->db->from('fiche_echantillonnage_capture, echantillon, espece_capture, unite_peche, espece, site_embarquement')
                    ->where($condition)
                    ->where('fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture')
                    ->where('echantillon.id = espece_capture.id_echantillon')
                    ->where('espece_capture.id_espece = espece.id')
                    ->where('echantillon.id_unite_peche = unite_peche.id')
                    ->where('fiche_echantillonnage_capture.id_site_embarquement = site_embarquement.id')
                    ->group_by('fiche_echantillonnage_capture.id_site_embarquement, echantillon.id_unite_peche, espece_capture.id_espece,mois,annee')  
                    ->get()
                    ->result();



            if($result)
            {
                return $result;
            }
            else
            {
                return null;
            }
       
    }


    public function req_7_3_new($condition)//efa OK ny valiny fa mila verifiena ny resaka annee sy ntariny
    {
        
        $requete = "select  
                        echantillon.id_unite_peche as i_id_unite , 
                        unite_peche.libelle as libelle_unite_peche , 
                        id_espece as es,
                        espece.code as code,
                        DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') as mois,
                        DATE_FORMAT(fiche_echantillonnage_capture.date,'%Y') as annee,
                        sum(capture) as somme_capture_par_espece,

                        (select sum(capture) from fiche_echantillonnage_capture,espece_capture,echantillon  where espece_capture.id_echantillon = echantillon.id 
                        and echantillon.id_unite_peche = i_id_unite and ".$condition." 
                        and fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                        and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois
                        ) as somme_capture_par_unite_peche ,

                        (select count(DISTINCT(echantillon.id)) from fiche_echantillonnage_capture,espece_capture,echantillon  where espece_capture.id_echantillon = echantillon.id 
                        and echantillon.id_unite_peche = i_id_unite and ".$condition." 
                        ) as nombre_echantillon_par_unit_peche ,

                        (select count(DISTINCT(echantillon.id)) from fiche_echantillonnage_capture,espece_capture,echantillon  where espece_capture.id_echantillon = echantillon.id 
                        and espece_capture.id_espece = es and ".$condition." 
                        ) as nombre_echantillon_par_espece ,

                        (select sum(prix) from fiche_echantillonnage_capture,espece_capture,echantillon  where espece_capture.id_echantillon = echantillon.id 
                        and espece_capture.id_espece = es and ".$condition." 
                        and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois
                        ) as prix_total_par_espece ,

                        (select sum(prix) from fiche_echantillonnage_capture,espece_capture,echantillon  where espece_capture.id_echantillon = echantillon.id 
                        and espece_capture.id_espece = es and ".$condition." and fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                        and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois
                        ) / ((select count(DISTINCT(echantillon.id)) from fiche_echantillonnage_capture,espece_capture,echantillon  where espece_capture.id_echantillon = echantillon.id 
                        and espece_capture.id_espece = es and ".$condition." 
                        )) as prix_moyenne_1 ,

                        (sum(prix) / count(espece_capture.id_espece)) as prix_moyenne ,

                      ( ROUND((( 
                      sum(capture) / 
                      (select sum(capture) from fiche_echantillonnage_capture,espece_capture,echantillon  where espece_capture.id_echantillon = echantillon.id 
                        and echantillon.id_unite_peche = i_id_unite and ".$condition." 
                        and fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture 
                        and DATE_FORMAT(fiche_echantillonnage_capture.date,'%c') = mois
                        ) ) * 100),2)) as composition_espece



                    from espece_capture , echantillon , fiche_echantillonnage_capture , unite_peche , espece
                    where fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture
                    and espece_capture.id_echantillon = echantillon.id 
                    and unite_peche.id = echantillon.id_unite_peche
                    and espece.id = espece_capture.id_espece

                    and ".$condition."
                    group by id_unite_peche,id_espece ,mois,annee order by mois" ;

        
        if ($requete) 
        {
            $query= $this->db->query($requete);
            return $query->result();
        }
        else
            return false;


    }


    public function req_9($condition)
    {
        $result = $this->db->select('SUM(DISTINCT(enquete_cadre.nbr_unite_peche)) as nbr_unite_peche,
                                    enquete_cadre.annee as annee,
                                    unite_peche.libelle as libelle_unite_peche,
                                    site_embarquement.libelle as libelle')
                            ->from('enquete_cadre')
                            ->join('site_embarquement', 'enquete_cadre.id_site_embarquement = site_embarquement.id')
                            ->join('unite_peche', 'enquete_cadre.id_unite_peche = unite_peche.id')                
                                     
                            ->group_by('site_embarquement.id')                          
                            ->group_by('enquete_cadre.annee')    //groupe par mois                      
                            ->group_by('unite_peche.id')                          
                                                
                            ->where($condition)                            
                            ->order_by('site_embarquement.id')        
                            ->get()
                            ->result();

        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }
    }

    public function req_10($condition)
    {
        $result = $this->db->select('SUM(DISTINCT(enquete_cadre.nbr_unite_peche)) as nbr_unite_peche,
                                    enquete_cadre.annee as annee,
                                    unite_peche.libelle as libelle_unite_peche,
                                    region.nom as libelle_region')
                            ->from('enquete_cadre')
                            ->join('region', 'enquete_cadre.id_region = region.id')
                            ->join('unite_peche', 'enquete_cadre.id_unite_peche = unite_peche.id')                
                                     
                            ->group_by('enquete_cadre.id_region')                          
                            ->group_by('enquete_cadre.annee')    //groupe par mois                      
                            ->group_by('enquete_cadre.id_unite_peche')                          
                                                
                            ->where($condition)                            
                            ->order_by('region.id')        
                            ->get()
                            ->result();

        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }
    }


    public function calcule_pab($mois, $annee, $id_unite_peche, $id_region)
    {
        $res = mktime( 0, 0, 0, $mois, 1, $annee ); 
        $nbr_jour = intval(date("t",$res));
     

        $debut = $annee."/".$mois."/01" ;
        $fin = $annee."/".$mois."/".$nbr_jour  ;
    }
//STDDEV(((1+echantillon.peche_hier+echantillon.peche_avant_hier+echantillon.nbr_jrs_peche_dernier_sem)/10)) as ecart_type_pab'
   // ,((SUM(((1+echantillon.peche_hier+echantillon.peche_avant_hier+echantillon.nbr_jrs_peche_dernier_sem)/10)))/(COUNT(echantillon.id))) as pab_moy

public function requetes_6($requete)
{
    $result = $this->db->select('fiche_echantillonnage_capture.id_region as id_region, fiche_echantillonnage_capture.id_site_embarquement as id_site_embarquement, unite_peche.id as id_unite_peche,unite_peche.libelle as unite_peche_libelle,fiche_echantillonnage_capture.date as date,((SUM(espece_capture.capture)/1)/(COUNT(DISTINCT(echantillon.id)))) as cpue_moyenne, STDDEV(espece_capture.capture) as stdevcpue,fiche_echantillonnage_capture.id as id_fiche_echantillon,COUNT(DISTINCT(echantillon.unique_code)) as nombre_echantillon,((COUNT(DISTINCT(echantillon.id)))-1) as degree,(sqrt(COUNT(DISTINCT(echantillon.id)))) as sqrt')
                    ->from('echantillon')
                    ->join('fiche_echantillonnage_capture','echantillon.id_fiche_echantillonnage_capture = fiche_echantillonnage_capture.id')
                    ->join('unite_peche','unite_peche.id = echantillon.id_unite_peche')
                    ->join('espece_capture','espece_capture.id_echantillon = echantillon.id')
                    ->where($requete)
                    //->where('fiche_echantillonnage_capture.validation',1)
                    ->group_by('fiche_echantillonnage_capture.id_region')
                    ->group_by('fiche_echantillonnage_capture.id_site_embarquement')
                    ->group_by('echantillon.id_unite_peche')
                    ->get()
                    ->result();
    if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }
}

//calcul ecart_type requet_6.2
/*public function ecarty($requete,$id_fiche,$id_region,$id_unite_peche)
{
    $result = $this->db->select('sum(espece_capture.capture) as ecart_type')
                    ->from('espece_capture')
                    
                    //->join('unite_peche','unite_peche.id = echantillon.id_unite_peche')
                    ->join('echantillon','espece_capture.id_echantillon = echantillon.id')
                    ->join('fiche_echantillonnage_capture','echantillon.id_fiche_echantillonnage_capture = fiche_echantillonnage_capture.id')
                    ->where($requete)
                    //->where('fiche_echantillonnage_capture.id_region',$id_region)
                    ->where('echantillon.id_unite_peche',$id_unite_peche)      
                    ->where('fiche_echantillonnage_capture.id',$id_fiche)
                    //->where('fiche_echantillonnage_capture.validation',1)
                    //->group_by('fiche_echantillonnage_capture.id_region')
                    //->group_by('fiche_echantillonnage_capture.id_site_embarquement')
                    //->group_by('echantillon.id_unite_peche')
                    ->group_by('echantillon.id')
                    ->get()
                    ->result();
                   
  $data = array();
  $i=0;
        foreach ($result as $key => $value) {
            $data[$i] = $value->ecart_type ;
            $i++;
        }

        if($result)
        {
           
            return $this->stats_deviation($data);
        }
        else
        {
            return null;
        }
}*/
public function stats_deviation(array $a) {
      
        $n = count($a);
        $sample_mean = array_sum($a) / $n;
        $variance = 0.0;

        foreach ($a as $val)
        {
            $d =  ((double) $val) - $sample_mean;
            $variance += $d * $d;
        };

        if($n>1)
        {          
          return sqrt($variance / ($n-1));  
        }
        
    }
// calcul pab requet_6.2
/*public function pab_moy_par_unite_peche($requete,$id_fiche_echantillon)
    {
         $result = $this->db ->select('COUNT(echantillon.id) as nombre_echantillon,unite_peche.libelle as libelle_unite_peche,
            SUM(((1+echantillon.peche_hier+echantillon.peche_avant_hier+echantillon.nbr_jrs_peche_dernier_sem)/10)) as somme_pab,
            STDDEV(((1+echantillon.peche_hier+echantillon.peche_avant_hier+echantillon.nbr_jrs_peche_dernier_sem)/10)) as ecart_type,

            ((SUM(((1+echantillon.peche_hier+echantillon.peche_avant_hier+echantillon.nbr_jrs_peche_dernier_sem)/10)))/(COUNT(echantillon.id))) as pab_moy,
            ((SUM(((1+echantillon.peche_hier+echantillon.peche_avant_hier+echantillon.nbr_jrs_peche_dernier_sem)/10)))/(COUNT(echantillon.id))*30.5) as nbr_jrs_peche_mois,
            (sqrt(COUNT(echantillon.id))) as sqrt,
            ((COUNT(echantillon.id))-1) as degree,
            date

            ')
                            ->from('echantillon')
                            ->join('fiche_echantillonnage_capture', 'fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture')  

                                            
                            ->join('unite_peche', 'echantillon.id_unite_peche = unite_peche.id')                  
                            ->join('site_embarquement', 'fiche_echantillonnage_capture.id_site_embarquement = site_embarquement.id')
                            ->group_by('echantillon.id_unite_peche')                                                           
                            ->where($requete) 
                            ->where('fiche_echantillonnage_capture.id',$id_fiche_echantillon)                                                
                            ->get()
                            ->result();

        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }               
    
    }                
*/




public function max_id()
{   
    $result =  $this->db->select_max('id')
                        ->from($this->table)
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
