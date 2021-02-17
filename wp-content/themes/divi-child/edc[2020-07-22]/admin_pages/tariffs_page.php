<?php
	class EDC_PAGE_TARIFFS_Extension{
		private $data=array();
		private $db;
		var $pagecount=10;
		public function requestProcessing(){			
			if(isset($_POST['get_tariff_options'])) die($this->getTariffOptionsHTML($_POST['get_tariff_options']));
			if(isset($_POST['edc_tariff'])) die($this->edcProcessTariff($_POST));
			if(isset($_POST['edc_remove_tariff'])) die($this->edcRemoveTariff($_POST));
			if(isset($_POST['edc_remove_tariffs'])) die($this->edcRemoveTariffs($_POST));
			if(isset($_POST['tariffs_import'])) die($this->importTariffs());
		}
		public function getTariffOptionsHTML($type='',$data=array()){
			$data['options']=EDCH::trfs('get_options',$type);
			return EDCH::adminTemplate('custom_options',$data);
		}
		protected function edcProcessTariff($data=array()){
			do_action('edc_before_tariff_process',$data,(!is_numeric($data['edc_tariff']) ? 'new' : 'update'));
			$options=array();
			foreach($data as $k=>$v){
				if(substr($k,0,7)=='option_'){
					$options[substr($k,7)]=is_array($v) ? json_encode($v) : $v;
					unset($data[$k]);
				}
			}
			if(!is_numeric($data['edc_tariff'])) $res=EDCH::trfs('add',$data,$options);
			else $res=EDCH::trfs('update',$data['edc_tariff'],$data,$options);
			if($res===false) return EDCAdmin::inst()->ajaxResult('error',__('An error occured, please try again later','edc'));
			EDCAdmin::inst()->cookieResult('success',__('Tariff has been succesfully processed','edc'));
			$result=EDCAdmin::inst()->ajaxResult('success',__('Tariff has been succesfully processed','edc'),false);
			$result['redirect']=site_url().'/wp-admin/admin.php?page=edc_tariffs&tariff='.$res;
			do_action('edc_after_tariff_process',$res,$data,(!is_numeric($data['edc_tariff']) ? 'new' : 'update'));
			$result=apply_filters('edc_tariffs_page_result',$result);
			return json_encode($result);
		}
		protected function edcRemoveTariff($data){
			do_action('edc_before_tariff_remove',$data['edc_remove_tariff']);
			$res=EDCH::trfs('remove',$data['edc_remove_tariff']);
			if($res===false) return EDCAdmin::inst()->ajaxResult('error',__('An error occured, please try again later','edc'));
			EDCAdmin::inst()->cookieResult('success',__('Tariff was succesfully removed','edc'));
			do_action('edc_after_tariff_remove',$data['edc_remove_tariff']);
			return EDCAdmin::inst()->ajaxResult('success',__('Tariff was succesfully removed.','edc'));	
			die();
		}
		protected function edcRemoveTariffs($data){
			$ids=explode(',',$data['edc_remove_tariffs']);
			do_action('edc_before_tariffs_remove',$ids);
			$i=0;
			foreach($ids as $id) if(is_numeric($id)){
				$res=EDCH::trfs('remove',$id);
				if($res!==false) ++$i;
			}
			EDCAdmin::inst()->cookieResult('success',__('Removed tariffs: '.$i,'edc'));
			do_action('edc_after_tariffs_remove',$ids);
			return EDCAdmin::inst()->ajaxResult('success',__('Removed tariffs: '.$i,'edc'));	
			die();
		}
		protected function importTariffs(){
			if(!is_uploaded_file($_FILES['tariffs']['tmp_name'])) return EDCAdmin::inst()->ajaxResult('error',__('Failed to upload file. Please try again later.','edc'));
			if(!file_exists(get_stylesheet_directory().'/edc/tariffs_importer.class.php')) return EDCAdmin::inst()->ajaxResult('error',__('Plugin files are corrupt. You can not use importer.','edc'));
			require_once get_stylesheet_directory().'/edc/tariffs_importer.class.php';
			$importer=new TariffsImporter($_FILES['tariffs']['tmp_name'],$_FILES['postcodes']['name']);
			if($importer->error!=''){
				if($importer->error==''){
					
				}else{
					return EDCAdmin::inst()->ajaxResult('error',__('An error occured, please try again later','edc'));
				}
			}
			$importer->import();
			//var_dump($importer->error);
			if($importer->error!=''){
				if($importer->error==''){
					
				}else{
					return EDCAdmin::inst()->ajaxResult('error',__('An error occured, please try again later','edc'));
				}					
			}
			EDCAdmin::inst()->cookieResult('success',__('Tariffs has been succesfully imported.','edc'));	
			return EDCAdmin::inst()->ajaxResult('success',__('Tariffs has been succesfully imported.','edc'));	
			die();
		}
		public function __construct(){
			$this->db=EDCH::DB();
		}
		private function getTariffFormTpl($tid=''){
			$data['page_title']=__('Add new','edc');
			$fields=['title','type','code','price_per_period','price_per_kwh','valid_from','valid_to','tariff_image','legal_terms','terms_and_conditions','active','postcodes','excluded_postcodes'];
			$options=EDCH::trfs('get_options');
			$data['clients_type_option']='';
			foreach($options as $opt) if($opt['key']=='tariff_clients_type'){ $data['clients_type_option']=$opt; break; }
			//var_dump($data['clients_type_option']);
			foreach($fields as $opt) $data[$opt]='';
			if(is_numeric($tid)){
				$data['page_title']=__('Tarif bearbeiten - #'.$tid,'medl');
				$tariff=EDCH::trfs('get',$tid);
				if(!$tariff){ EDCH::redirect(site_url().'/wp-admin/admin.php?page=edc_settings'); die(); }
				foreach($fields as $opt) $data[$opt]=isset($tariff->{$opt}) ? $tariff->{$opt} : '';
				$data['custom_options']=$this->getTariffOptionsHTML($tariff->type,$tariff->options);
				$data['tariff_image_url']=is_numeric($data['tariff_image']) ? wp_get_attachment_image_url($data['tariff_image']) : '';
				$tids=explode(',',$tariff->terms_and_conditions);
				$terms=array();
				foreach($tids as $tid){
					$term=is_numeric($tid) ? wp_get_attachment_url($tid) : '';
					if($term && !is_wp_error($term)) $terms[]=$term;
				}
				$data['terms_and_conditions_file']=implode('<br>',$terms);
				$data['tariff_options']=$tariff->options;
				$data['tariff_goodies']=json_decode($data['tariff_options']['tariff_goodies']);
			}
			$pages=EDCAdmin::inst()->getPagesList();
			$data['pages_options']=array();
			foreach($pages as $p) $data['pages_options'][]=array('name'=>$p->post_title,'value'=>$p->ID);
			$data['tariff_types']=array(
				array('value'=>1,'name'=>__('Gas','edc')),
				array('value'=>2,'name'=>__('Electricity','edc')),
				array('value'=>3,'name'=>__('Combi','medl')),
			);
			$data['postcodes_options']=$this->getPostcodesOptions($data['type']);
			$data['tariffs_options']=$this->getTariffsOptions($data['type']);
			if(!$data['tariff_image_url'] || is_wp_error($data['tariff_image_url'])) $data['tariff_image_url']=EDC_PLUGIN_URL . '/admin/images/no-image.jpg';
			
			list($goodies,$total)=EDC_GOODIES::items();
			$data['goodies_options']=[];
			foreach($goodies as $goodie) $data['goodies_options'][]=['name'=>$goodie->name,'value'=>$goodie->id];
			return EDCH::adminTemplate('tariffs_form',$data);
		}
		private function getTariffsListTpl($params=[]){
			$args=array();
			$args['page']=$params['paged'];
			if(!is_numeric($args['page'])) $args['page']=1;
			if(isset($params['keyword'])){
				$args['search']=$params['keyword'];
				if($params['tariff_type']>0) $args['type']=$params['tariff_type'];
			}
			$args['per_page']=$this->pagecount;
			list($data['tariffs'],$total)=EDCH::trfs('get_list',$args);
			$data['tariff_types']=EDCH::simpleOptions(array(
				array('value'=>'','name'=>__('Tariff type','edc')),
				array('name'=>__('Gas','edc'),'value'=>1),
				array('name'=>__('Electricity','edc'),'value'=>2),
				array('name'=>__('Combi','medl'),'value'=>3),
			),$params['tariff_type']);
			$data['pagination']=EDCAdmin::inst()->pagenavigation($total,$this->pagecount);
			return EDCH::adminTemplate('tariffs',$data);
		}
		public function showContent($echo=true){
			$data=array();
			if(isset($_GET['tariff'])){
				$tpl=$this->getTariffFormTpl($_GET['tariff']);
			}else{
				$tpl=$this->getTariffsListTpl($_GET);
			}			
			if($echo) echo $tpl;
			else return $tpl;
		}
		public function getPostcodesOptions($type=''){
			$args=array();
			if($type!='') $args['type']=$type;
			list($postcodes,$total)=EDCH::codes('get',$args);
			$options=array();
			foreach($postcodes as $p) $options[]=array("name"=>$p->name .' ('.$p->code.')',"value"=>$p->id);			
			return $options;
		}
		public function getTariffsOptions($type=''){
			$args=array();
			if($type!='') $args['type']=$type;
			list($tariffs,$total)=EDCH::trfs('get_list',$args);
			$options=array();
			foreach($tariffs as $t) $options[]=array("name"=>$t->title,"value"=>$t->id);			
			return $options;
		}
	}	
?>