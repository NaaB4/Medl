<?php
class EDC{
	protected static $_instance;
	var $statuses="";
	var $page=null;
	var $result='';
	var $is_processed=false;
	var $cookie_name='edc';
	protected $_REQUEST=array();
	private $_ie_compatibility=true;
	protected $_output=null;
	public function getInstance(){
		if(!self::$_instance){
			do_action('edc_before_instance_initialized');
			self::$_instance=new self();
			self::$_instance->requestProcessing();
			self::$_instance->output();
			do_action('edc_after_instance_initialized');
		}
		return self::$_instance;
	}
	public function inst(){
		return self::getInstance();
	}
	public function __construct(){}
	private function __clone(){}
	private function __sleep(){}
	private function __wakeup(){}
	final public function __destruct(){
	    self::$_instance = null;
	}
	
	protected function output(){
		include_once EDC_PLUGIN_PATH . '/front/output.class.php';
		$this->_output=new EDCOutput();
	}
	/* FORM PORCESSING */
	protected function getRequestData(){
		$data=array();
		$from=EDCH::opts('get','edc_request_from','settings');
		/*! FOR FUTURE, now only $_POST in use !*/
		if($from=='session'){
			$data=$_SESSION;
		}elseif($from=='cookie'){
			$data=$_COOKIE[$this->cookie_name];
			$data=stripslashes($data);
			$data=json_decode($data);
		}elseif($from=='get'){
			$data=$_GET;
		}else{
			$data=$_POST;
		}
		if(isset($data['steps_data'])){
			$steps=json_decode(stripslashes($data['steps_data']),true);
			if(is_array($steps) && sizeOf($steps)>0){
				foreach($steps as $g=>$items) foreach($items as $k=>$v){
					if(!isset($data[$k])) $data[$k]=$v;
				}
			}
		}
		return apply_filters('edc_request_data',$data);
	}
	public function getData($key=''){
		return isset($this->_REQUEST[$key]) ? $this->_REQUEST[$key] : false;
	}
	protected function requestProcessing($process=true){
		if(isset($_POST['edc_get_postcodes_options'])) die($this->getPostcodesOptionsHTML($_POST));
		if(isset($_POST['send_confirmation_code'])) die($this->sendConfirmationCode($_POST));
		if(isset($_POST['confirmation_code'])) die($this->confirmCode($_POST));
		$this->_REQUEST=$this->getRequestData();
		if(isset($this->_REQUEST['edc_processing'])) $this->is_processed=true;
		if($process){
			if(isset($this->_REQUEST['step_1']) && isset($this->_REQUEST['validate'])) die($this->validateFirstStep($this->_REQUEST));
			if(isset($this->_REQUEST['step_1'])) $this->processFirstStep($this->_REQUEST);
			if(isset($this->_REQUEST['step_2']) && isset($this->_REQUEST['validate'])) die($this->validateSecondStep($this->_REQUEST));
			if(isset($this->_REQUEST['step_2'])) $this->processSecondStep($this->_REQUEST);
			if(isset($this->_REQUEST['step_3']) && isset($this->_REQUEST['validate'])) die($this->validateThirdStep($this->_REQUEST));
			if(isset($this->_REQUEST['step_3'])) $this->processThirdStep($this->_REQUEST);
		}
	}
	protected function getPostcodesOptionsHTML($data=array()){
		$result=array('type'=>'error','text'=>__('An error occured, please try again later','edc'));
		if(!$data['type'] || !is_numeric($data['edc_get_postcodes_options'])) return json_encode($result);
		list($codes,$total)=EDCH::codes('get',array('code'=>$data['edc_get_postcodes_options'],'type'=>$data['type']));
		if($total>0){
			$options=array();
			$options[]=array('value'=>'','name'=>__('Choose location','edc'));
			foreach($codes as $c) $options[]=array('value'=>$c->id,'name'=>$c->name);
			$options=apply_filters('edc_get_ajax_options',$options);
			$result=array('type'=>'success','text'=>EDCH::simpleOptions($options));
		}else{
			$text=EDCH::opts('get','edc_outside_area_text','settings')=='' ?  __('Chosen postal code is outside a coverage area','edc') : EDCH::opts('get','edc_outside_area_text','settings');
			$result=array('type'=>'special','text'=>do_shortcode($text));
		}
		return json_encode($result);
	}
	protected function getTariffsByStepsData($data=[]){
		$args=array('type'=>$data['type'],'postcodes'=>$data['districts']);
		$args['options']=array();
		$args['valid']=true;
		if($data['type']=='electricity'){
			$args['options'][]=array('name'=>'min_electricity_delivery_per_year','value'=>$data['annual_consumption'],'compare'=>'<=','compare_type'=>'numeric','strict'=>false);
			$args['options'][]=array('name'=>'max_electricity_delivery_per_year','value'=>$data['annual_consumption'],'compare'=>'>=','compare_type'=>'numeric','strict'=>false);
		}elseif($data['type']=='gas'){
			$args['options'][]=array('name'=>'min_gas_delivery_per_year','value'=>$data['annual_consumption'],'compare'=>'<=','compare_type'=>'numeric','strict'=>false);
			$args['options'][]=array('name'=>'max_gas_delivery_per_year','value'=>$data['annual_consumption'],'compare'=>'>=','compare_type'=>'numeric','strict'=>false);
		}
		if(EDCH::is(EDCH::opts('get','edc_use_type_select','settings')) && $data['edc_tariff_type']){
			$types=EDCH::$clients_types;
			if(isset($types[$data['edc_tariff_type']])){
				$args['options'][]=array('name'=>'tariff_clients_type','value'=>$types[$data['edc_tariff_type']],'compare'=>'=','compare_type'=>'numeric');
			}
		}
		return EDCH::trfs('get_list',$args);
	}
	protected function validateFirstStep($data=array()){
		EDCH::trimArray($data);
		do_action('edc_before_first_step_validation',$data);
		if(!isset(EDCH::$types[$data['type']])) return $this->ajaxResult('error',__('An error occured, please try again later','edc'));
		if(!is_numeric($data['postcode']) || !is_numeric($data['districts']) || !is_numeric($data['annual_consumption'])) return $this->ajaxResult('error',__('Please complete all required fields.','edc'));
		if(!($postcode=EDC_POSTCODES::exists($data['districts']))) return $this->ajaxResult('error',__("We have not this postal code in our database.",'edc'));
		list($tariffs,$total)=$this->getTariffsByStepsData($data);
		if($total==0 || sizeOf($tariffs)==0) return $this->ajaxResult('error',__("Sorry but we have not any tariffs for your parameters",'edc'));
		
		do_action('edc_after_first_step_validation',$data);
		return apply_filters('edc_first_step_result',$this->ajaxResult('success',''));
	}
	protected function processFirstStep($data=array()){
		if(!isset(EDCH::$types[$data['type']]) || !is_numeric($data['postcode']) || !is_numeric($data['districts']) || !is_numeric($data['annual_consumption'])) return false;
		do_action('edc_before_first_step_process',$data);
		$this->is_tariffs_step=true;
		list($tariffs,$total)=$this->getTariffsByStepsData($data);
		$this->tariffs=$tariffs;
		list($code)=EDCH::codes('get',array('id'=>$data['districts']));
		$this->steps_data=array(
			'first'=>array(
				'type'=>$data['type'],
				'postcode'=>$data['postcode'],
				'postcode_data'=>$code[0],
				'districts'=>$data['districts'],
				'annual_consumption'=>$data['annual_consumption'],
			),
		);
		if($data['square']) $this->first_step_data['square']=$data['square'];
		if($data['people']) $this->first_step_data['people']=$data['people'];
		do_action('edc_after_first_step_validation',$data);
	}
	protected function validateSecondStep($data=array()){
		EDCH::trimArray($data);
		do_action('edc_before_second_step_validation',$data);
		if(!is_array($this->steps_data)){
			$data['steps_data']=stripslashes($data['steps_data']);
			$this->steps_data=json_decode($data['steps_data'],true);
		}
		$valid=json_decode($this->validateFirstStep($this->steps_data['first']),true);
		if($valid['type']!='success') return json_encode($valid);		
		if(!EDCH::trfs('exists',$data['id_tariff']) || !is_array($this->steps_data) || sizeOf($this->steps_data)==0) return $this->ajaxResult('error',__('An error occured, please try again later','edc'));
		do_action('edc_after_second_step_validation',$data);
		return apply_filters('edc_second_step_result',$this->ajaxResult('success',''));
	}
	protected function processSecondStep($data=array()){
		do_action('edc_before_second_step_process',$data);	
		$this->is_order_step=true;
		$data['steps_data']=stripslashes($data['steps_data']);
		$this->steps_data=json_decode($data['steps_data'],true);
		$this->tariff=EDCH::trfs('get',$data['id_tariff']);
		$this->steps_data['second']=array(
			'id_tariff'=>$data['id_tariff'],
		);
		do_action('edc_after_second_step_process',$data);
	}
	protected function validateThirdStep($data=array()){
		EDCH::trimArray($data);
		do_action('edc_before_third_step_validation',$data);
		$data['steps_data']=stripslashes($data['steps_data']);
		$this->steps_data=json_decode($data['steps_data'],true);
		$valid=json_decode($this->validateFirstStep($this->steps_data['first']),true);
		if($valid['type']!='success') return json_encode($valid);
		$valid=json_decode($this->validateSecondStep($this->steps_data['second']),true);
		if($valid['type']!='success') return json_encode($valid);
		if(!EDCH::validateRecaptcha($data['recaptcha_response'])) return $this->ajaxResult('error',__('You must to prove that you are not a robot','edc'));
		if(!EDCH::trfs('exists',$data['id_tariff'])) return $this->ajaxResult('error',__('An error occured, please try again later','edc'));		
		$order_options=EDCH::order('get_fields');
		$errors=array();
		foreach($order_options as $group=>$opts) foreach($opts['items'] as $key=>$val){
			if(is_array($val)){
				$req=isset($val['required']) ? $val['required'] : false;
				if($req){
					$text=isset($val['error_text']) ? sprintf($val['error_text'],$val['name']) : sprintf(__('The next field has incorrect value: %s'),$val['name']);
					if(!EDCH::fieldValid($data[$key],$val['validate'])){
						$errors[]=array('text'=>$text,'key'=>$key);
					}
				}elseif(isset($val['validate'])){
					if(!EDCH::fieldValid($data[$key],$val['validate'],false)){						
						$errors[]=array('text'=>$text,'key'=>$key);
					}
				}
			}
		}
		if(EDCH::is(EDCH::opts('get','edc_use_confirmation','settings'))){
			session_id() || session_start();
			if(!$_SESSION['edc_confirmation_code'] || $data['confirmation']!=$_SESSION['edc_confirmation_code']) return $this->ajaxResult('error',array(array('key'=>'confirmation','text'=>__('The confirmation code is not correct','edc'),'type'=>'alert')));
		}
		$errors=apply_filters('edc_third_step_errors_array',$errors,$data);
		//echo 11111;
		//var_dump($errors);
		if(sizeOf($errors)>0){ return $this->ajaxResult('error',$errors); }
		$_SESSION['edc_confirmation_code']='';
		do_action('edc_after_third_step_validation',$data);
		return apply_filters('edc_third_step_result',$this->ajaxResult('success',''));
	}
	protected function processThirdStep($data=array()){
		//echo 1111;
		EDCH::trimArray($data);
		$this->is_result_step=true;
		do_action('edc_before_third_step_process',$data);
		$tariff=EDCH::trfs('get',$data['id_tariff']);
		$this->tariff=$tariff;
		$data['steps_data']=stripslashes($data['steps_data']);
		$this->steps_data=json_decode($data['steps_data'],true);			
		$order_options=EDCH::order('get_fields');
		$options=array();
		$params=array();
		$errors=array();
		foreach($order_options as $group=>$opts) foreach($opts['items'] as $key=>$val){
			if(is_array($val)){
				if($val['field']){
					if($val['field']=='option') $options[$key]=$data[$key];
					else $params[$key]=$data[$key];
				}
			}
		}
		$params['id_tariff']=$this->tariff->id;
		$pdf=[];
		$pdf['form_data']=$options;
		$pdf['tariff']=$this->tariff;
		$params['pdf_path']=EDCH::order('create_pdf',$pdf);
		$oid=EDCH::order('add',$params,$options);
		EDCH::order('send',$oid);
		do_action('edc_after_third_step_process',$data);
	}
	protected function sendConfirmationCode($data=array()){
		EDCH::trimArray($data);
		do_action('edc_before_confirmation_code_sent',$data);
		session_id() || session_start();
		$code=$_SESSION['edc_confirmation_code'] ? $_SESSION['edc_confirmation_code'] : wp_generate_password(32);
		$code=apply_filters('edc_confirmation_code',$code);
		$message=__('To successfully authenticate, please enter the code below in the required field and click on "Confirm". With best regards.','edc');
		$message.="\r\n";
		$message.=sprintf(__('Your confirmation code: %s','edc'),$code);
		$from=EDCH::opts('get','edc_mail_from','settings');
		$headers=[];
		if(is_email($from)) $headers[]='From: '.$from.'<'.$from.'>';
		elseif($from!='') $headers[]='From: '.$from;
		/* IF PGP PLUGIN IN USE should fix email issue */
		
		// TODO discuss need encrypt confirmation mails or not
		
		/*if((class_exists('WP_PGP_Encrypted_Emails') || EDCH::is(EDCH::opts('get','PGP_FIX_EMAILS','settings'))) && !email_exists($data['email'])){
            $uid=wp_create_user($data['email'], wp_generate_password(32),$data['email']);
			if($uid && !is_wp_error($uid)){
				$public_key=get_option("pgp_keypair");
				if($public_key) $is_key_added=add_user_meta($uid, "pgp_public_key", $public_key["publickey"]);
			}
        }*/
		/*! !*/
		$result=wp_mail($data['email'],sprintf(__('Confirmation code from site %s','edc'),get_bloginfo('description')),$message,$headers);
		if(!$result) return $this->ajaxResult('error',__('An error occured, please try again later','edc'));
		$_SESSION['edc_confirmation_code']=$code;
		do_action('edc_after_confirmation_code_sent',$data);
		return $this->ajaxResult('success','');
	}
	protected function confirmCode($data=array()){
		EDCH::trimArray($data);
		if(!$data['confirmation_code']) return $this->ajaxResult('error',array(array('key'=>'confirmation_code','text'=>__('The code is not correct','edc'))));
		session_id() || session_start();
		$code_invalid=!$_SESSION['edc_confirmation_code'] || $data['confirmation_code']!=$_SESSION['edc_confirmation_code'];
		$code_invalid=apply_filters('edc_code_invalid',$code_invalid);
		if($code_check) return $this->ajaxResult('error',array(array('key'=>'confirmation_code','text'=>__('The confirmation code is not correct','edc'))));
		return $this->ajaxResult('success','');
	}
	public function ajaxResult($type,$text,$json=true){
		$res=array('type'=>$type);
		if($type=='success') $res['success_text']=$text;
		elseif($type=='error') $res['error_text']=$text;
		else $res['text']=$text;
		return $json===true ? json_encode($res) : $res;
	}
	public function cookieResult($type,$text){
		setcookie('edc_result',$this->ajaxResult($type,$text),null,'/');
	}
	
	/*! FORM PORCESSING !*/
	public function isIE(){
		if(!$this->_ie_compatibility) return false;
		$ua = htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8');
		return preg_match('~MSIE|Internet Explorer~i', $ua) || (strpos($ua, 'Trident/7.0') !== false && strpos($ua, 'rv:11.0') !== false);
	}
}

function createEDCInstance(){
	EDC::getInstance();
}
add_action('init','createEDCInstance',30);
?>