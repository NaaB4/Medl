<?php
	class EDC_PAGE_POSTCODES{
		private $data=array();
		private $db;
		var $pagecount=30;
		public function requestProcessing(){
			if(isset($_POST['postcodes_import'])) die($this->importPostCodes());
			if(isset($_POST['edc_postcode'])) die($this->edcProcessPostcode($_POST));
			if(isset($_POST['edc_remove_postcode'])) die($this->edcRemovePostcode($_POST));
			if(isset($_POST['edc_remove_postcodes'])) die($this->edcRemovePostcodes($_POST));
		}
		protected function importPostCodes(){
		    if(!is_uploaded_file($_FILES['postcodes']['tmp_name'])) return EDCAdmin::inst()->ajaxResult('error',__('Failed to upload file. Please try again later.','edc'));
			if(!file_exists(EDC_PLUGIN_PATH.'/inc/postcodes_importer.class.php')) return EDCAdmin::inst()->ajaxResult('error',__('Plugin files are corrupt. You can not use importer.','edc'));
			require_once EDC_PLUGIN_PATH.'/inc/postcodes_importer.class.php';
			$importer=new PostcodesImporter($_FILES['postcodes']['tmp_name'],$_FILES['postcodes']['name']);
			if($importer->error!=''){
				if($importer->error==''){
					
				}else{
					return EDCAdmin::inst()->ajaxResult('error',__('An error occured, please try again later','edc'));
				}
			}
			$importer->import();
			if($importer->error!=''){						
				if($importer->error==''){
					
				}else{
					return EDCAdmin::inst()->ajaxResult('error',__('An error occured, please try again later','edc'));
				}					
			}
			EDCAdmin::inst()->cookieResult('success',__('Postcodes has been succesfully imported.','edc'));	
			return EDCAdmin::inst()->ajaxResult('success',__('Postcodes has been succesfully imported.','edc'));	
			die();
		}
		protected function edcProcessPostcode($data){
			do_action('edc_before_postcode_process',$data,(!is_numeric($data['edc_postcode']) ? 'new' : 'update'));
			$res=false;
			$street_list = json_encode($data["street_list"], JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT);
			$res=EDCH::codes('update',$data['edc_postcode'],$data['name'],$data['code'],$data['type'],$street_list);
			if($res===false) EDCAdmin::inst()->cookieResult('error',__('An error occured, please try again later','edc'));
			do_action('edc_after_postcode_process',$res,$data,(!is_numeric($data['edc_postcode']) ? 'new' : 'update'));
			if($data['edc_postcode']=='new') EDCAdmin::inst()->cookieResult('success',__('Postcode was succesfully added','edc'));
			else EDCAdmin::inst()->cookieResult('success',__('Postcode was succesfully updated','edc'));
			EDCH::refresh();
			die();
		}
		protected function edcRemovePostcode($data){
			do_action('edc_before_postcode_remove',$data['edc_remove_postcode']);
			$res=EDCH::codes('remove',$data['edc_remove_postcode']);
			if($res===false) return EDCAdmin::inst()->ajaxResult('error',__('An error occured, please try again later','edc'));
			do_action('edc_after_postcode_remove',$data['edc_remove_postcode']);
			EDCAdmin::inst()->cookieResult('success',__('Postcode was succesfully removed','edc'));
			return EDCAdmin::inst()->ajaxResult('success',__('Postcode was succesfully removed.','edc'));	
			die();
		}
		protected function edcRemovePostcodes($data){
			$ids=explode(',',$data['edc_remove_postcodes']);
			do_action('edc_before_postcodes_remove',$ids);
			$i=0;
			foreach($ids as $id) if(is_numeric($id)){
				$res=EDCH::codes('remove',$id);
				if($res!==false) ++$i;
			}
			do_action('edc_after_postcodes_remove',$ids);
			EDCAdmin::inst()->cookieResult('success',__('Removed postcodes: '.$i,'edc'));
			return EDCAdmin::inst()->ajaxResult('success',__('Removed postcodes: '.$i,'edc'));	
			die();
		}
		public function __construct(){
			$this->db=EDCH::DB();
		}
		public function showContent($echo=true){
			$data=array();
			$args=array();
			if(isset($_GET['code'])) $args['code']=$_GET['code'];
			if(isset($_GET['name'])) $args['name']=$_GET['name'];
			$args['page']=$_GET['paged'];
			if(!is_numeric($args['page'])) $args['page']=1;
			$args['per_page']=$this->pagecount;
			list($data['postcodes'],$total)=EDCH::codes('get',$args);
			$data['pagination']=EDCAdmin::inst()->pagenavigation($total,$this->pagecount);
			$tpl=EDCH::adminTemplate('postcodes',$data);
			
			if($echo) echo $tpl;
			else return $tpl;
		}
	}	
?>