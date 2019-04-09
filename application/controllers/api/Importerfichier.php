<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//harizo
// afaka fafana refa ts ilaina
// require APPPATH . '/libraries/REST_Controller.php';

class Importerfichier extends CI_Controller {
    public function __construct() {
        parent::__construct();
        

    }
	public function save_upload_file() {

		$erreur="aucun";
		$replace=array('e','e','e','a','o','c','_');
		$search= array('é','è','ê','à','ö','ç',' ');
		$repertoire= $_POST['repertoire'];
		$name_image=$_POST['name_image'];

		$repertoire=str_replace($search,$replace,$repertoire);
		$name_image=str_replace($search,$replace,$name_image);
		//The name of the directory that we need to create.
		$directoryName = dirname(__FILE__) ."/../../../../../../assets/ddb/" .$repertoire;
		//Check if the directory already exists.
		if(!is_dir($directoryName)){
			//Directory does not exist, so lets create it.
			mkdir($directoryName, 0777,true);
		}				

		$rapport=array();
		//$rapport['repertoire']=dirname(__FILE__) ."/../../../../../../assets/ddb/" .$repertoire;
		$config['upload_path']          = dirname(__FILE__) ."/../../../../../../assets/ddb/".$repertoire;
		$config['allowed_types'] = 'png';
		$config['max_size'] = 222048;
		$config['overwrite'] = TRUE;
		if (isset($_FILES['file']['tmp_name']))
		{
			$name=$_FILES['file']['name'];
			//$name1=str_replace($search,$replace,$name);
			$file_ext = pathinfo($name,PATHINFO_EXTENSION);
			$rapport['nomImage']=$name_image.'.'.$file_ext;
			$config['file_name']=$name_image;
			//$rapport['repertoire']=$name_image;

			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			//$ff=$this->upload->do_upload('file');
			if(!$this->upload->do_upload('file'))
			{

				$rapport["erreur"]= 'Type d\'image invalide. Veuillez inserer une image.png';
				echo json_encode($rapport);
			}else{
				
				echo json_encode($rapport);
			}
			
		} else {
			$rapport["erreur"]= 'File upload not found' ;
           // echo 'File upload not found';
            echo json_encode($rapport);
		} 
		
	}
	  
	
} ?>	
