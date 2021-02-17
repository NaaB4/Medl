<?php
	class EDC_GOODIES_PAGE{
		private $data=array();
		private $db;
		var $pagecount=10;
		public function requestProcessing(){
			/*if(isset($_POST['get_tariff_options'])) die($this->getTariffOptionsHTML($_POST['get_tariff_options']));
			if(isset($_POST['edc_tariff'])) die($this->edcProcessTariff($_POST));
			if(isset($_POST['edc_remove_tariff'])) die($this->edcRemoveTariff($_POST));
			if(isset($_POST['edc_remove_tariffs'])) die($this->edcRemoveTariffs($_POST));
			if(isset($_POST['tariffs_import'])) die($this->importTariffs());*/
			if($_POST['edc_goodie']=='new') die($this->edcAddGoodie($_POST));
			if(is_numeric($_POST['edc_goodie'])) die($this->edcEditGoodie($_POST));
			if(is_numeric($_POST['edc_remove_goodie'])) die($this->edcDeleteGoodie($_POST['edc_remove_goodie']));
			if(isset($_POST['edc_remove_goodies'])) die($this->edcRemoveGoodies($_POST));
		}
		protected function edcAddGoodie($data=[]){
			$res=EDC_GOODIES::add($data);
			if($res===false) EDCAdmin::inst()->cookieResult('error',__('An error occured, please try again later','edc'));
			else EDCAdmin::inst()->cookieResult('success',__('Goodie was added','medl'));
			EDCH::refresh();
			die();
		}
		protected function edcEditGoodie($data=[]){
			$res=EDC_GOODIES::update($data['edc_goodie'],$data);
			if($res===false) EDCAdmin::inst()->cookieResult('error',__('An error occured, please try again later','edc'));
			else EDCAdmin::inst()->cookieResult('success',__('Goodie was updated','medl'));
			EDCH::refresh();
			die();
		}
		protected function edcDeleteGoodie($id){
			$res=EDC_GOODIES::remove($id);
			if($res===false) return EDCAdmin::inst()->ajaxResult('error',__('An error occured, please try again later','edc'));
			return EDCAdmin::inst()->ajaxResult('success',__('Goodie was removed.','edc'));
			die();
		}
		protected function edcRemoveGoodies($data){
			$ids=explode(',',$data['edc_remove_goodies']);
			$i=0;
			foreach($ids as $id) if(is_numeric($id)){
				$res=EDC_GOODIES::remove($id);
				if($res!==false) ++$i;
			}
			EDCAdmin::inst()->cookieResult('success',__('Removed goodies: '.$i,'edc'));
			return EDCAdmin::inst()->ajaxResult('success',__('Removed goodies: '.$i,'edc'));	
			die();
		}
		/*protected function edcRemoveTariffs($data){
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
		}*/
		public function __construct(){
			$this->db=EDCH::DB();
		}
		private function getGoodiesList($params=[]){
			$args=array();
			$args['page']=$params['paged'];
			$args['per_page']=$this->pagecount;
			if(!is_numeric($args['page'])) $args['page']=1;
			
			list($data['goodies'],$total)=EDC_GOODIES::items($args);
			
			$data['pagination']=EDCAdmin::inst()->pagenavigation($total,$this->pagecount);
			return EDCH::adminTemplate('goodies',$data);
		}
		public function showContent($echo=true){
			$data=array();
			$tpl=$this->getGoodiesList($_GET);
			if($echo) echo $tpl;
			else return $tpl;
		}
	}	
?>