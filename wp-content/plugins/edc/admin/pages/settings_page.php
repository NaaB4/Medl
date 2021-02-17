<?php
	class EDC_PAGE_SETTINGS{
		private $data=array();
		private $db;
		public function __construct(){
			$this->db=EDCH::DB();
		}
		public function requestProcessing(){
			if(isset($_POST['settings_submitted'])) die($this->saveSettings($_POST));		
		}
		public function saveSettings($data=array()){
			do_action('edc_before_save_settings',$data);
			$settings=$this->getSettings();
			$keys=array_keys($settings);
			$key=false;
			foreach($keys as $k){
				if(isset($data['edc_'.$k.'_settings'])){
					$key=$k;
					break;
				}
			}
			if($key===false) return EDCAdmin::inst()->ajaxResult('error',__('An error occured, please try again later','edc'));
			EDCH::trimArray($data);
			$values=array();
			$errors=array();
			
			foreach($settings[$key]['items'] as $i=>$items) foreach($items as $opt=>$field){
				if(is_array($field)){
					$req=isset($field['required']) ? $field['required'] : false;
					if($req){
						if(!EDCH::fieldValid($data[$opt],$field['validate'])) $errors[]=$field['name'];
					}elseif(isset($field['validate'])){
						if(!EDCH::fieldValid($data[$opt],$field['validate'],false))  $errors[]=$field['name'];				
					}
					$values[$opt]=stripslashes($data[$opt]);
				}
			}
			
			if(sizeOf($errors)>0){
				$str='';
				foreach($errors as $err) $str.=', '.$err;
				return EDCAdmin::inst()->ajaxResult('error',sprintf(__('The next fields are not valid: %s','edc'),substr($str,2)));
			}
			
			if($settings[$key]['function'] && method_exists($this,$settings[$key]['function'])) return call_user_func(array($this,$settings[$key]['function']),$values,$data);
			$res=true;
			foreach($values as $k=>$v){
				$res=EDCH::opts('update',$k,'settings',$v);
				if($res===false) break;
			}
			//var_dump($values);
			//die();
			
			$res=apply_filters('edc_settings_result',$res);
			if($res===false) return  EDCAdmin::inst()->ajaxResult('error',__('While options updating An error occured, please try again later','edc'));
			do_action('edc_after_save_settings',$data);
			return is_array($res) ? $res :  EDCAdmin::inst()->ajaxResult('success',__('EDC settings has been updated succesfully.','edc'));
		}
		public function resetPlugin($values,$data){
			if(!class_exists('EDCReset')) include_once EDC_PLUGIN_PATH . "/inc/reset.class.php";
			$reset=new EDCReset();
			$result=true;
			if($values['edc_reset_tariffs']==1){
				$result=$result && $reset->resetTariffsData();
			}
			if($values['edc_reset_postcodes']==1){
				$result=$result && $reset->resetPostcodes();
			}
			if($values['edc_reset_orders']==1){
				$result=$result && $reset->resetOrders();				
			}
			if($values['edc_reset_settings']==1){
				$result=$result && $reset->setDefaults();
			}
			if($result===false) EDCAdmin::inst()->ajaxResult('success',__('An error occured, please try again later','edc'));
			return EDCAdmin::inst()->ajaxResult('success',__('Data has been successfully reset','edc'));
		}
		public function showContent($echo=true){
			$data=array();
			$data['settings']=$this->getSettings();
			foreach($data['settings'] as $settings) foreach($settings['items'] as $fields) foreach($fields as $k=>$field){
				$data['values'][$k]=EDCH::opts('get',$k,'settings');
			}
			$tpl=EDCH::adminTemplate('settings',$data);			
			if($echo) echo $tpl;
			else return $tpl;
		}
		public function getPositionOption(){
			return array(
				array('value'=>'','name'=>__('Before page content','edc')),
				array('value'=>'after','name'=>__('After page content','edc')),
				array('value'=>'custom','name'=>__('Custom position (via shortcode)','edc')),
			);
		}
		public function getSettings(){
			$settings=array();
			$settings['tariff']=$this->getTariffsSettings();
			$settings['email']=$this->getMailSettings();
			$settings['main']=$this->getMainSettings();
			$settings['order']=$this->getOrderSettings();
			$settings['recaptcha']=$this->getGRecaptchaSettings();
			$settings['reset']=$this->getResetSettings();
			uasort($settings,array($this,'sortSettings'));
			return apply_filters('edc_settings',$settings);
		}
		public function getTariffsSettings(){
			$settings=array(
				'title'=>__('Tariffs settings','edc'),
				'order'=>2,
				'items'=>array(
					array(
						'title'=>__('Eletricity','edc'),
						'edc_max_kwh_electricity'=>array(
							'name'=>__('Upper limit (kWh)','edc'),
							'placeholder'=>'10000',
							'type'=>'text',
							'validate'=>'numeric',
						),
						'edc_per_year_1_electricity'=>array(
							'name'=>__('Energy per 1 person','edc'),
							'placeholder'=>'1800',
							'type'=>'text',
							'validate'=>'numeric',
						),
						'edc_per_year_2_electricity'=>array(
							'name'=>__('Energy per 2 person','edc'),
							'placeholder'=>'2500',
							'type'=>'text',
							'validate'=>'numeric',
						),
						'edc_per_year_3_electricity'=>array(
							'name'=>__('Energy per 3 person','edc'),
							'placeholder'=>'3600',
							'type'=>'text',
							'validate'=>'numeric',
						),
						'edc_per_year_4_electricity'=>array(
							'name'=>__('Energy per 4 person','edc'),
							'placeholder'=>'4400',
							'type'=>'text',
							'validate'=>'numeric',
						),
					),
					array(
						'title'=>__('Gas','edc'),
						'edc_max_kwh_gas'=>array(
							'name'=>__('Upper limit (kWh)','edc'),
							'placeholder'=>'10000',
							'type'=>'text',
							'validate'=>'numeric',
						),
						'edc_per_year_1_gas'=>array(
							'name'=>__('Energy per 1 person','edc'),
							'placeholder'=>'1800',
							'type'=>'text',
							'validate'=>'numeric',
						),
						'edc_per_year_2_gas'=>array(
							'name'=>__('Energy per 2 person','edc'),
							'placeholder'=>'2500',
							'type'=>'text',
							'validate'=>'numeric',
						),
						'edc_per_year_3_gas'=>array(
							'name'=>__('Energy per 3 person','edc'),
							'placeholder'=>'3600',
							'type'=>'text',
							'validate'=>'numeric',
						),
						'edc_per_year_4_gas'=>array(
							'name'=>__('Energy per 4 person','edc'),
							'placeholder'=>'4400',
							'type'=>'text',
							'validate'=>'numeric',
						),
					),
				),
			);
			return apply_filters('edc_settings_tariff',$settings);
		}
		public function getMailSettings(){
			$settings=array(
				'title'=>__('Mail settings','edc'),
				'order'=>3,
				'items'=>array(
					array(
						'edc_mail_from'=>array(
							'name'=>__('Mail from','edc'),
							'placeholder'=>'info@info.com',
							'type'=>'text',
						),
						'edc_mail_to'=>array(
							'name'=>__('Mail to','edc'),
							'placeholder'=>'info@info.com',
							'type'=>'text',
							'validate'=>'email',
						),
						'edc_mail_banner'=>array(
							'name'=>__('Mail banner','edc'),
							'type'=>'image',
						),
						'edc_mail_subject'=>array(
							'name'=>__('Subject of the customer notification e-mail','edc'),
							'type'=>'text',
						),
						'edc_mail_text'=>array(
							'name'=>__('Text of the customer notification e-mail','edc'),
							'type'=>'rich',
						),
						'edc_self_mail_subject'=>array(
							'name'=>__('Subject of the admin notification e-mail','edc'),
							'type'=>'text',
						),
						'edc_self_mail_text'=>array(
							'name'=>__('Text of the admin notification e-mail','edc'),
							'type'=>'rich',
						),
					),
				),
			);
			return apply_filters('edc_settings_mail',$settings);
		}
		public function getMainSettings(){
			$settings=array(
				'title'=>__('Main settings','edc'),
				'order'=>1,
				'grouped'=>true,
				'items'=>array(
					array(
						'title'=>__('Price settings','edc'),
						'edc_price_format'=>array(
							'name'=>__('Price format','edc'),
							'type'=>'dropdown',
							'items'=>array(
								array('name'=>number_format(1000000.50,2,',',' ').' (1000000.50)','value'=>'2_comma_space'),
								array('name'=>number_format(1000000.50,0,',',' ').' (1000000.50)','value'=>'0_comma_space'),
								array('name'=>number_format(1000000.50,2,'.',' ').' (1000000.50)','value'=>'2_dot_space'),
								array('name'=>number_format(1000000.50,0,'.',' ').' (1000000.50)','value'=>'0_dot_space'),
								array('name'=>number_format(1000000.50,2,',','.').' (1000000.50)','value'=>'2_comma_dot'),
								array('name'=>number_format(1000000.50,0,',','.').' (1000000.50)','value'=>'0_comma_dot'),
							),
							'description'=>__('Format for price on the site (in brackets are shown price numeric value, before brackets - format how price will be displayed on the site)','edc'),
						),
						'edc_use_nds'=>array(
							'name'=>__('Use NDS on the site','edc'),
							'type'=>'dropdown',
							'items'=>array(							
								array('name'=>__('None','edc'),'value'=>''),
								array('name'=>__('For private tariffs','edc'),'value'=>'private'),
								array('name'=>__('For business tariffs','edc'),'value'=>'business'),
								array('name'=>__('For all tariffs','edc'),'value'=>'both'),
							),
							'description'=>__('Here you can set for which tariffs NDS should be added. If you will choose something instead of "None" you have to enter NDS value in "NDS value" option.','edc'),
						),
						'edc_nds_value'=>array(
							'name'=>__('NDS value','edc'),
							'type'=>'text',
							'description'=>__('This must be a number more than zero. Calculated price will be multiplied with value which entered in this option (based on option "Use NDS on the site).','edc'),
							'validate'=>'numeric',
						),
					),
					array(
						'title'=>__('Tariff main form settings','edc'),
						'edc_use_type_select'=>array(
							'label'=>__('User can choose tariff type','edc'),
							'type'=>'checkbox',
							'default'=>true,
						),
						'edc_outside_area_text'=>array(
							'name'=>__('Text when the chosen postal code is outside from coverage area','edc'),
							'type'=>'rich',
							'default'=>__('Chosen postal code is outside a coverage area','edc'),
						),
						'edc_gas_page'=>array(
							'name'=>__('Page for gas calculator','edc'),
							'type'=>'pages_dropdown',
							'description'=>__('Gas tab will be linked to this page (if chosen redirect to page variant in "Calculator tabs action" option)','edc'),
						),
						'edc_electricity_page'=>array(
							'name'=>__('Page for electricity calculator','edc'),
							'type'=>'pages_dropdown',
							'description'=>__('Electricity tab will be linked to this page (if chosen redirect to page variant in "Calculator tabs action" option)','edc'),
						),
						'edc_tabs_type'=>array(
							'name'=>__('Calculator tabs action','edc'),
							'type'=>'dropdown',
							'items'=>array(							
								array('name'=>__('Switch tabs','edc'),'value'=>'tabs'),
								array('name'=>__('Redirect to page','edc'),'value'=>'links'),
							),
						),
					),
					array(
						'title'=>__('Tariff second step settings','edc'),
						'edc_tariffs_page'=>array(
							'name'=>__('Page with tariffs','edc'),
							'type'=>'pages_dropdown',
							'description'=>__('Here you can choose a page where the user will be redirected after first step submission (by default this will be the same page where the calculator displayed)','edc'),
						),
						'edc_tariffs_pos'=>array(
							'name'=>__('Tariffs position','edc'),
							'type'=>'dropdown',
							'items'=>$this->getPositionOption(),
							'description'=>__('Here you can choose this step data position on the page. *Note that if you have calculator shortcode on the page (calculator visual redactor element) the step data always will be displayed on its place.','edc'),
						),
					),
					array(
						'title'=>__('Tariff order step settings','edc'),
						'edc_order_page'=>array(
							'name'=>__('Page with order form','edc'),
							'type'=>'pages_dropdown',
							'description'=>__('Here you can choose a page where the user will be redirected after second step submission (by default this will be the same page where the second step data displayed)','edc'),
						),
						'edc_order_pos'=>array(
							'name'=>__('Order form position','edc'),
							'type'=>'dropdown',
							'items'=>$this->getPositionOption(),
							'description'=>__('Here you can choose this step data position on the page. *Note that if you have calculator shortcode on the page (calculator visual redactor element) the step data always will be displayed on its place.','edc'),
						),
					),
					array(
						'title'=>__('Tariff result page settings','edc'),
						'edc_results_page'=>array(
							'name'=>__('Page with results','edc'),
							'type'=>'pages_dropdown',
							'description'=>__('Here you can choose a page where the user will be redirected after third step submission (by default this will be the same page where the third step data displayed)','edc'),
						),
						'edc_results_pos'=>array(
							'name'=>__('Results position','edc'),
							'type'=>'dropdown',
							'items'=>$this->getPositionOption(),
							'description'=>__('Here you can choose this step data position on the page. *Note that if you have calculator shortcode on the page (calculator visual redactor element) the step data always will be displayed on its place.','edc'),
						),
					),
					array(
						'title'=>__('Request form settings','edc'),
						'edc_request_page'=>array(
							'name'=>__('Page with request form','edc'),
							'type'=>'pages_dropdown',
							'description'=>__('Tariffs whether unavailable to book will be linked to this page','edc'),
							'description'=>__('Here you can choose a page where the user will be redirected if the tariff is unavailable for online booking','edc'),
						),
					),
				),
			);
			return apply_filters('edc_settings_main',$settings);			
		}
		public function getOrderSettings(){
			$settings=array(
				'title'=>__('Order settings','edc'),
				'order'=>4,
				'items'=>array(
					array(
						'edc_use_confirmation'=>array(
							'label'=>__('Use e-mail confirmation before order sending','edc'),
							'type'=>'checkbox',
						),
						'edc_first_legal_text'=>array(
							'name'=>__('First legal checkbox text','edc'),
							'type'=>'rich',
						),
						'edc_second_legal_text'=>array(
							'name'=>__('Second legal checkbox text','edc'),
							'type'=>'rich',
						),
						'edc_third_legal_text'=>array(
							'name'=>__('Third legal checkbox text','edc'),
							'type'=>'rich',
						),						
						'edc_pdf_logo'=>array(
							'name'=>__('Logo in the PDF file','edc'),
							'type'=>'image',
							'description'=>__('This image will appear on the top of the PDF file','edc'),
						),
						'edc_pdf_text'=>array(
							'name'=>__('Additional text for PDF','edc'),
							'type'=>'rich',
							'description'=>__('Here you can add any custom text (e.g. contacts information) which will appear in the PDF order','edc'),
						),
					),
				)
			);
			return apply_filters('edc_settings_order',$settings);
		}
		public function getGRecaptchaSettings(){
			$settings=array(
				'title'=>__('Google recaptcha settings','edc'),
				'order'=>5,
				'items'=>array(
					array(					
						'edc_use_recaptcha'=>array(
							'name'=>__('Use re-captcha in order form','edc'),
							'type'=>'dropdown',
							'items'=>array(
								array('name'=>__('Do not use google re-captcha','edc'),'value'=>''),
								array('name'=>__('Use re-captcha V2','edc'),'value'=>'v2'),
								array('name'=>__('Use re-captcha V3','edc'),'value'=>'v3'),
							),
						),
						'edc_grecaptcha_v2_public'=>array(
							'name'=>__('Re-captcha v2 public key','edc'),
							'type'=>'text',
						),
						'edc_grecaptcha_v2_private'=>array(
							'name'=>__('Re-captcha v2 private key','edc'),
							'type'=>'password',
						),
						'edc_grecaptcha_v3_public'=>array(
							'name'=>__('Re-captcha v3 public key','edc'),
							'type'=>'text',
						),
						'edc_grecaptcha_v3_private'=>array(
							'name'=>__('Re-captcha v3 private key','edc'),
							'type'=>'password',
						),
						'edc_recaptcha_v3_hide_badge'=>array(
							'label'=>__('Hide re-captcha badge','edc'),
							'type'=>'checkbox',
						),
					),
				)
			);
			return apply_filters('edc_settings_recaptcha',$settings);
		}
		function getResetSettings(){	
			$settings=array(
				'title'=>__('Reset plugin','edc'),
				'order'=>6,
				'items'=>array(
					array(
						'edc_reset_tariffs'=>array(
							'label'=>__('Remove all tariffs data','edc'),
							'type'=>'checkbox',
						),
						'edc_reset_postcodes'=>array(
							'label'=>__('Remove all postal codes','edc'),
							'type'=>'checkbox',
						),
						'edc_reset_orders'=>array(
							'label'=>__('Reset all orders','edc'),
							'type'=>'checkbox',
						),
						'edc_reset_settings'=>array(
							'label'=>__('Reset settings to defaults','edc'),
							'type'=>'checkbox',
						),
					),
				),
				'function'=>'resetPlugin',
			);
			return apply_filters('edc_settings_reset',$settings);
		}
		public function sortSettings($a,$b){
			if(!isset($a['order'])) return -1;
			if(!isset($b['order'])) return 1;
			if($a['order']==$b['order']) return 1;
			return $a['order']>$b ? 1 : -1;
		}
	}
?>