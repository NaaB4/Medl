<?php
class EDC_HELPER extends EDCH{
	static function proceedType(&$type,$name=false){
		if($name){
			if(!is_numeric($type)){
				if(!isset(self::$types[$type])) return false;
				return $type;
			}
			if(!in_array($type,self::$types)) return false;
			foreach(self::$types as $k=>$v){
				if($v==$type){ $type=$k; break; }
			}
		}else{
			if(is_numeric($type)){
				if(!in_array($type,self::$types)) return false;
				return $type;
			}
			if(!isset(self::$types[$type])) return false;
			$type=self::$types[$type];
		}
		return $type;
	}
	static function trimArray(&$arr){
		if(!is_array($arr)) return;
		foreach($arr as $k=>$v){
			if(!is_array($v)) $arr[$k]=is_bool($v) ? $v : trim($v);
			else self::trimArray($arr[$k]);
		}
	}
	static function strictArray(&$arr,$rec=true){
		if(is_array($arr)){
			foreach($arr as $k=>$a){
				if(is_array($a) && $rec) self::strictArray($arr[$k]);
				else{
					self::strict($arr[$k]);
				}
			}
		}
	}
	static function strict(&$val=''){
		if(is_numeric($val)) $val=strpos($val,'.')!==false ? floatval($val) : intval($val);
		elseif(is_bool($val)) $val=(bool)$val;
	}
	static function redirect($location=''){
		if($location=='') $location=get_site_url();
		if(!headers_sent()){
			wp_redirect($location);
		}else{
			echo '<script type="text/javascript">window.location=\''.$location.'\';</script>';
		}
		die();
	}
	static function refresh(){	
		if(isset($_SERVER['REQUEST_URI'])) $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		else{
			$url='http://'.$_SERVER['HTTP_HOST'].(substr($_SERVER['PHP_SELF'],0,1)=="/" ? "" : "/").$_SERVER['PHP_SELF'].($_SERVER['QUERY_STRING']=="" ? "" : "?".$_SERVER['QUERY_STRING']);
		}
		self::redirect($url);
	}
	static function getThemePath($child=true){
		if(function_exists('get_stylesheet_directory') && $child) return get_stylesheet_directory();
		if(function_exists('get_template_directory')) return get_template_directory();
		return '';
	}
	static function getThemeUrl(){
		if(function_exists('get_stylesheet_directory_uri')) return get_stylesheet_directory_uri();
		if(function_exists('get_template_directory_uri')) return get_template_directory_uri();
		return '';
	}
	static function templateFile($tpl){
		$path='';
		$theme_path=self::getThemePath();
		if($theme_path){
			$path=$theme_path.'/edc/templates/'.$tpl.'.php';
			if(!file_exists($path)) $path='';
			$theme_path=self::getThemePath(false);
			if($theme_path && $path==''){
				$path=$theme_path.'/edc/templates/'.$tpl.'.php';
				if(!file_exists($path)) $path='';				
			}
		}
		if($path=='') $path=EDC_PLUGIN_PATH.'/front/templates/'.$tpl.'.php';
		if(!file_exists($path)) return false;
		return $path;
	}
	static function loadTemplate($tpl,$data=array()){
		if(!($path=self::templateFile($tpl))) return false;
		if(!file_exists($path)) return false;
		ob_start();
		include $path;
		return ob_get_clean();
	}
	static function adminTemplateFile($tpl){		
		$path='';
		$theme_path=self::getThemePath();
		if($theme_path){
			$path=$theme_path.'/edc/templates/admin/'.$tpl.'.php';
			if(!file_exists($path)) $path='';
			$theme_path=self::getThemePath(false);
			if($theme_path && $path==''){
				$path=$theme_path.'/edc/templates/admin/'.$tpl.'.php';
				if(!file_exists($path)) $path='';				
			}
		}
		if($path=='') $path=EDC_PLUGIN_PATH.'/admin/templates/'.$tpl.'.php';
		if(!file_exists($path)) return false;
		return $path;
	}
	static function adminTemplate($tpl,$data=array()){
		if(!($path=self::adminTemplateFile($tpl))) return false;
		if(!isset($data['hidden_fields'])) $data['hidden_fields']='';
		$hiddens=apply_filters('edc_hiddens_get',array('page','paged'));
		foreach($_GET as $k=>$v){
			if(in_array($k,$hiddens)) $data['hidden_fields'].='<input type="hidden" name="'.$k.'" value="'.$v.'">';
			if(!isset($data['get_'.$k])) $data['get_'.$k]=$v;
		}
		ob_start();
		include $path;
		return ob_get_clean();
	}
	static function simpleOptions($options,$selected=array()){
		if(!is_array($options) || sizeOf($options)==0) return '';
		if(!is_array($selected)) $selected=trim($selected)=='' ? array() : array($selected);
		self::strictArray($selected);
		foreach($options as $opt){
			self::strict($opt['value']);
			if($opt['optgroup']===true){
				if($optgroup==true) $output.='</optgroup>';
				$output.='<optgroup label="'.$opt['name'].'">';
				$optgroup=true;
			}else{
				$output.='<option value="'.$opt['value'].'" '.(in_array($opt['value'],$selected,true) ? 'selected' : '').'>'.$opt['name'].'</option>';
			}
		}
		if($optgroup==true) $output.='</optgroup>';
		return $output;
	}
	static function getGasPageURL(){
		$pid=self::opts('get','edc_gas_page','settings');
		if(!is_numeric($pid)) return '';
		return get_permalink($pid);
	}
	static function getElectricityPageURL(){
		$pid=self::opts('get','edc_electricity_page','settings');
		if(!is_numeric($pid)) return '';
		return get_permalink($pid);
	}
	static function getTariffsPageURL(){
		$pid=self::opts('get','edc_tariffs_page','settings');
		if(!is_numeric($pid)) return '';
		return get_permalink($pid);
	}
	static function getResultPageURL($tid=''){
		$url='';
		if(is_numeric($tid)){
			$options=EDC_TARIFFS::getTariffOptions($tid);
			if(is_numeric($options['thankyou_page'])) $url=get_permalink($options['thankyou_page']);
		}
		if($url==''){
			$pid=self::opts('get','edc_results_page','settings');
			if(!is_numeric($pid)) return '';
			$url=get_permalink($pid);
		}
		return $url;
	}
	static function getOrderFormURL(){
		$pid=self::opts('get','edc_order_page','settings');
		if(!is_numeric($pid)) return '';
		return get_permalink($pid);
	}
	static function getNoBookLink($tid=''){
		$link=self::getRequestFormURL();
		if($tariff=self::trfs('get',$tid)){
			$opt=self::trfs('tariff_options',$tid);
			if($opt['edc_no_book_link']) $link=$opt['edc_no_book_link'];
		}
		return $link;
	}
	static function getRequestFormURL(){
		$pid=self::opts('get','edc_request_page','settings');
		if(!is_numeric($pid)) return '';
		return get_permalink($pid);
	}
	static function getTabsType(){		
		$type=self::opts('get','edc_tabs_type','settings');
		if($type=='tabs') return $type;
		$el=self::getElectricityPageURL();
		$gl=self::getGasPageURL();
		return $gl && $el ? $type : 'tabs';
	}
	static function dateToHum($date=''){
		if($date=='') return '';
		list($date,$time)=explode(' ',$date);
		$parts=explode('.',$date);
		if(sizeOf($parts)!=3) return '';
		if(strlen($parts[0])==2 && strlen($parts[1])==2 && strlen($parts[2])==4) return $date;
		return implode('.',array_reverse($parts)).($time=='' ? '' : ' '.$time);
	}
	static function dateToDatabase($date=''){
		if($date=='') return '';
		list($date,$time)=explode(' ',$date);
		$parts=explode('.',$date);
		if(sizeOf($parts)!=3) return '';
		if(strlen($parts[0])==4 && strlen($parts[1])==2 && strlen($parts[2])==2) return $date;
		return implode('.',array_reverse($parts)).($time=='' ? '' : ' '.$time);
	}
	static function getPagesList($args=array()){
		$args=wp_parse_args(array(
			'post_type'=>'page',
			'orderby'=>'post_title',
			'order'=>'ASC',
			'posts_per_page'=>-1,
		),$args);
		$query=new WP_Query($args);
		return $query->posts;
	}
	static function getPagesOptions($sel,$args=array()){
		$pages=self::getPagesList($args);
		$options=array();
		foreach($pages as $p) $options[]=array('name'=>$p->post_title,'value'=>$p->ID);
		return self::simpleOptions($options,$sel);
	}
	static function fieldValid($val='',$type='simple',$strict=true){
		$val=trim($val);
		if(!$strict && $val=='') return true;
		//var_dump($type);
		if($type=='numeric') return is_numeric($val);
		elseif($type=='email') return is_email($val);
		else return $val!='';
		
		return true;
	}
	static function drawRecaptcha(){
		$rc=self::opts('get','edc_use_recaptcha','settings');
		if($rc=='') return '';
		$result='';
		$data=array();
		$data['uniq']=++self::$_recaptchas;
		if($rc=='v2'){
			$data['classes']=['v2'];
			$data['key']=self::opts('get','edc_grecaptcha_v2_public','settings');
			$result=self::loadTemplate('recaptcha_v2',$data);
		}elseif($rc=='v3'){
			$data['classes']=['v3'];
			$data['key']=self::opts('get','edc_grecaptcha_v3_public','settings');
			$result=self::loadTemplate('recaptcha_v3',$data);			
		}
		return $result;
	}
	static function validateRecaptcha($resp=''){
		$rc=self::opts('get','edc_use_recaptcha','settings');
		if($rc=='') return true;
		if($rc=='v2'){
			return self::validateRecaptchaV2($resp);
		}elseif($rc=='v3'){
			return self::validateRecaptchaV3($resp);
		}
			
		return true;
	}
	static function validateRecaptchaV2($resp=''){
		if($resp=='') return false;
        // Set the POST data
		$postdata=http_build_query(array('secret'=>self::opts('get','edc_grecaptcha_v2_private','settings'),'response' => $resp,));
		// Set the POST options
		$opts=array('http'=>
			array(
				'method' => 'POST',
				'header' => 'Content-type: application/x-www-form-urlencoded',
				'content' => $postdata
			),
		);

		$context=stream_context_create($opts);
		$url='https://www.google.com/recaptcha/api/siteverify';
		$result=json_decode(file_get_contents($url, false, $context),true);
		return $result['success'];
	}
	static function validateRecaptchaV3($resp=''){
		if(!$resp) return false;
		$url='https://www.google.com/recaptcha/api/siteverify';
		$data=array('secret'=>self::opts('get','edc_grecaptcha_v3_private','settings'), 'response'=>$resp);
		$options=array('http'=>
			array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($data)
			)
		);
		$context=stream_context_create($options);
		$result=json_decode(file_get_contents($url, false, $context),true);
		return $result['success'];
	}
	static function setReplaces(&$text='',$oid){
		if($text=='') return '';
		$order=EDC_ORDERS::exists($oid);
		$mapping=[
			'current_date'=>['{Current date}','{date}'],
			'gender'=>['{Gender}'],
			'name'=>['{Name}','{name}','{first name}','{firstname}','{First name}'],
			'last_name'=>['{Last name}','{Lastname}','{last name}','{lastname}'],
			'order_id'=>['{Order ID}','{number}'],
			'tariff_name'=>['{Tariff name}','{productname}'],
		];
		$tariff=EDCH::trfs('get',$order->id_tariff);
		$name=$tariff && isset($tariff->title) ? $tariff->title : '';
		$gender=$order->options['edc_anrede']=='m' ? __('Man','edc') : __('Woman','edc');
		$replaces=['current_date'=>date('d.m.Y'),'gender'=>$gender,'name'=>$order->options['edc_first_name'],'last_name'=>$order->options['edc_name'],'order_id'=>'#'.$oid,'tariff_name'=>$name];
		foreach($mapping as $k=>$v) if(isset($replaces[$k])){
			foreach($v as $s){
				$text=str_replace($s,$replaces[$k],$text);
			}
		}
	}
}
?>