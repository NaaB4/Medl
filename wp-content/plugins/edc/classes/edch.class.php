<?php
class EDCH{
	protected static $_prefix='edc_';
	protected static $_recaptchas=0;
	public static $locale='edc';
	static $types=array('any'=>0,'gas'=>1,'electricity'=>2);
	static $clients_types=array('private'=>1,'business'=>2);
	/* HELPER FUNCTIONS */
	static function proceedType(&$type,$name=false){ return EDC_HELPER::proceedType($type,$name); }
	static function trimArray(&$arr){ return EDC_HELPER::trimArray($arr); }
	static function strictArray(&$arr){ return EDC_HELPER::strictArray($arr); }
	static function redirect($location){ return EDC_HELPER::redirect($location); }
	static function refresh(){ return EDC_HELPER::refresh(); }
	static function getThemePath(){ return EDC_HELPER::getThemePath(); }
	static function getThemeUrl(){ return EDC_HELPER::getThemeUrl(); }
	static function loadTemplate($tpl,$data=array()){ return EDC_HELPER::loadTemplate($tpl,$data); }
	static function adminTemplateFile($tpl){ return EDC_HELPER::adminTemplateFile($tpl); }
	static function adminTemplate($tpl,$data=array()){ return EDC_HELPER::adminTemplate($tpl,$data); }
	static function simpleOptions($options,$selected=array()){ return EDC_HELPER::simpleOptions($options,$selected); }
	static function getGasPageURL(){ return EDC_HELPER::getGasPageURL(); }
	static function getElectricityPageURL(){ return EDC_HELPER::getElectricityPageURL(); }
	static function getTariffsPageURL(){ return EDC_HELPER::getTariffsPageURL(); }
	static function getOrderFormURL(){ return EDC_HELPER::getOrderFormURL(); }
	static function getResultPageURL(){ return EDC_HELPER::getResultPageURL(); }
	static function getNoBookLink($tid=''){ return EDC_HELPER::getNoBookLink($tid); }
	static function getRequestFormURL(){ return EDC_HELPER::getRequestFormURL(); }
	static function getTabsType(){ return EDC_HELPER::getTabsType(); }
	static function dateToHum($date=''){ return EDC_HELPER::dateToHum($date); }
	static function getPagesList($args=array()){ return EDC_HELPER::getPagesList($args); }
	static function getPagesOptions($sel,$args=array()){ return EDC_HELPER::getPagesOptions($sel,$args); }
	static function fieldValid($val='',$type='simple',$strict=true){ return EDC_HELPER::fieldValid($val,$type,$strict); }
	static function drawRecaptcha(){ return EDC_HELPER::drawRecaptcha(); }
	static function validateRecaptcha($resp=''){ return EDC_HELPER::validateRecaptcha($resp); }
	/*! HELPER FUNCTIONS !*/
	
	static function DB(){
		global $wpdb;
		return $wpdb;
	}
	static function activateEDCPlugin(){
		do_action('edc_before_activation');
		include_once EDC_PLUGIN_PATH . '/inc/activate.class.php';
		$activate=new EDCActivate();
		do_action('edc_after_activation');
	}
	static function deactivateEDCPlugin(){
		// For future
	}
	static function uninstallEDCPlugin(){
		// For future
	}
	static function is($val=''){
		if(is_bool($val)) $val=strtolower($val);
		return $val==1 || $val===true || $val=='yes' || $val=='y' || $val=='on' || $val=='true' || $val=='ya';
	}
	static function isNot($val=''){
		if(is_bool($val)) $val=strtolower($val);
		return $val==0 || $val===false || $val=='no' || $val=='n' || $val=='off' || $val=='false' || $val=='not' || $val=='';
	}
	static function table($name=''){
		if($name=='') return false;
		$tables=array('options','tariffs','tariff_postcodes','tariff_options','orders','order_options','tariffs_options','postcodes','tariff_postcodes_exclude');
		if(!in_array($name,$tables)) return false;
		$prefix='';
		if(defined('EDC_USE_WP_PREFIX') && EDC_USE_WP_PREFIX){
			$prefix.=isset(EDCH::DB()->prefix) ? EDCH::DB()->prefix : '';
		}
		if(defined('EDC_USE_SELF_PREFIX') && EDC_USE_SELF_PREFIX){
			$prefix.=self::$_prefix;
		}
		return $prefix.$name;
	}
	
	/* options pseudonyms */
	static function options(){ return EDC_OPTIONS; }
	static function opts($key=''){
		if($key=='') return false;
		$args=func_get_args();
		switch($key){
			case 'add' :
				return self::options()::add($args[1],$args[2],$args[3]);
			break;
			case 'update' :
				return self::options()::update($args[1],$args[2],$args[3]);
			break;
			case 'get' :
				return self::options()::getValue($args[1],$args[2],$args[3]);
			break;
			case 'id' :
				return self::options()::get($args[1],$args[2]);
			break;
			case 'exists' :
				return self::options()::exists($args[1],$args[2]);
			break;
		}
	}
	/*! options pseudonyms !*/
	/* postcodes pseudonyms */
	static function postcodes(){ return EDC_POSTCODES; }
	static function codes($key=''){
		if($key=='') return false;
		$args=func_get_args();
		switch($key){
			case 'add' :
				return self::postcodes()::add($args[1],$args[2],$args[3]);
			break;
			case 'update' :
				return self::postcodes()::update($args[1],$args[2],$args[3],$args[4]);
			break;
			case 'remove' :
				return self::postcodes()::remove($args[1]);
			break;
			case 'get' :
				return self::postcodes()::get($args[1]);
			break;
			case 'type' :
				return self::postcodes()::getType($args[1]);
			break;
			case 'exists' :
				return self::postcodes()::exists($args[1],$args[2],$args[3],$args[4]);
			break;
		}
	}
	/*! postcodes pseudonyms !*/
	/* tariffs pseudonyms */
	static function tariffs(){ return EDC_TARIFFS; }
	static function trfs($key=''){
		if($key=='') return false;
		$args=func_get_args();
		switch($key){
			case 'add' :
				return self::tariffs()::add($args[1],$args[2]);
			break;
			case 'update' :
				return self::tariffs()::update($args[1],$args[2],$args[3]);
			break;
			case 'remove' :
				return self::tariffs()::remove($args[1]);
			break;
			case 'get_options' :
				return self::tariffs()::getOptions($args[1]);
			break;
			case 'get_list' :
				return self::tariffs()::getList($args[1]);
			break;
			case 'get' :
				return self::tariffs()::get($args[1]);
			break;
			case 'exists' :
				return self::tariffs()::exists($args[1]);
			break;
			case 'remove' :
				return self::tariffs()::remove($args[1]);
			break;
			case 'price' :
				return self::tariffs()::getPrices($args[1],$args[2]);
			break;
			case 'image' :
				return self::tariffs()::getImage($args[1],$args[2]);
			break;
			case 'image_path' :
				return self::tariffs()::getImagePath($args[1],$args[2]);
			break;
			case 'tariff_options' :
				return self::tariffs()::getTariffOptions($args[1]);			
			break;
		}
	}
	/*! tariffs pseudonyms !*/
	/* orders pseudonyms */
	static function orders(){ return EDC_ORDERS; }
	static function order($key=''){
		if($key=='') return false;
		$args=func_get_args();
		switch($key){
			case 'add' :
				return self::orders()::add($args[1],$args[2]);
			break;
			case 'update' :
				return self::orders()::update($args[1],$args[2],$args[3]);
			break;
			case 'remove' :
				return self::orders()::remove($args[1]);
			break;
			case 'get_fields' :
				return self::orders()::getFields();
			break;
			case 'get_list' :
				return self::orders()::getList($args[1]);
			break;
			case 'get' :
				return self::orders()::get($args[1]);
			break;
			case 'exists' :
				return self::orders()::exists($args[1]);
			break;
			case 'remove' :
				return self::orders()::remove($args[1]);
			break;
			case 'create_pdf' :
				return self::orders()::createPDF($args[1]);
			break;
			case 'send' :
				return self::orders()::send($args[1]);
			break;
		}
	}
	/*! orders pseudonyms !*/
	static function pdf($key=''){
		if($key=='') return false;
		$args=func_get_args();
		switch($key){
			case 'create' :
				return EDC_PDF::create($args[1]);
			break;
			case 'download' :
				return EDC_PDF::downloadFromOrder($args[1]);
			break;
		}		
	}
	/*static function addOrder($data=array()){
		return EDC_ORDERS::add($data);
	}
	static function getOrders($data=array()){
		return EDC_ORDERS::getList($data);		
	}
	static function createPDF($pdf=''){
		return EDC_PDF::create($pdf);
	}
	static function downLoadOrdersPDF($oid){
		return EDC_PDF::downloadFromOrder($oid);
	}*/
	/* price functions */
	static function getPrice($ppr,$ppk,$data=[]){ return EDC_PRICE::getPrice($ppr,$ppk,$data); }
	static function addNDS(&$price=0,$type=''){ return EDC_PRICE::addNDS($price,$type);}
	static function formatPrice($price=0){ return EDC_PRICE::formatPrice($price); }
	static function addCurrency($text=''){ return EDC_PRICE::addCurrency($text); }
	static function displayPrice($price='',$class='',$sym='',$above=false){ return EDC_PRICE::displayPrice($price,$class,$sym,$above); }
	/*! price functions !*/
}
?>