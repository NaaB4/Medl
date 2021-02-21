<?php
class EDC_Extension extends EDC{
	public function getInstance(){
		if(!self::$_instance){
			self::$_instance=new self();
			self::$_instance->output();
			self::$_instance->requestProcessing();
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
	
	protected function output(){
		include_once get_stylesheet_directory() . '/edc/output.class.php';
		$this->_output=new EDCOutput();
	}
	protected function requestProcessing($process=true){
	    if(isset($_POST['edc_get_postcodes_options'])) die($this->getPostcodesOptionsHTML($_POST));
        if(isset($_POST['edc_get_street_list'])) die($this->getStreetListHTML($_POST));
		if(isset($_POST['send_confirmation_code'])) die($this->sendConfirmationCode($_POST));
		if(isset($_POST['confirmation_code'])) die($this->confirmCode($_POST));
		$this->_REQUEST=$this->getRequestData();
		if(isset($this->_REQUEST['edc_processing'])) $this->is_processed=true;
		if(isset($_POST['edc_get_heimkehrer_price'])) die($this->getHeimkehrerPrice($_POST));
		if($process){
			if(isset($this->_REQUEST['edc_popup_form'])) die($this->sendPopupForm($this->_REQUEST));
			if(isset($this->_REQUEST['step_1']) && isset($this->_REQUEST['validate'])) die($this->validateFirstStep($this->_REQUEST));
			if(isset($this->_REQUEST['step_1'])) $this->processFirstStep($this->_REQUEST);
			if(isset($this->_REQUEST['step_2']) && isset($this->_REQUEST['validate'])) die($this->validateSecondStep($this->_REQUEST));
			if(isset($this->_REQUEST['step_2'])) $this->processSecondStep($this->_REQUEST);
			if(isset($this->_REQUEST['step_3']) && isset($this->_REQUEST['validate'])){
				$this->_REQUEST['edc_phone']=$this->_REQUEST['edc_phone1'].' - '.$this->_REQUEST['edc_phone2'];
				die($this->validateThirdStep($this->_REQUEST));
			}
			if(isset($this->_REQUEST['step_3'])){
				$this->_REQUEST['edc_phone']=$this->_REQUEST['edc_phone1'].' - '.$this->_REQUEST['edc_phone2'];
				$this->processThirdStep($this->_REQUEST);
			}
		}
	}
	protected function getPostcodesOptionsHTML($data=array()){
	    $result=array('type'=>'error','text'=>__('An error occured, please try again later','edc'));
		if(!$data['type'] || !is_numeric($data['edc_get_postcodes_options'])) return json_encode($result);
		list($codes,$total)=EDCH::codes('get',array('code'=>$data['edc_get_postcodes_options'],'type'=>$data['type']));
		if($total>0){
			$options=array();
			$options[]=array('value'=>'','name'=>__('Stadt auswählen','medl'));
			foreach($codes as $c) $options[]=array('value'=>$c->id,'name'=>$c->name);
			$options=apply_filters('edc_get_ajax_options',$options);
			$result=array('type'=>'success','text'=>EDCH::simpleOptions($options));
		}else{
			$text=EDCH::opts('get','edc_outside_area_text','settings')=='' ?  __('Chosen postal code is outside a coverage area','edc') : EDCH::opts('get','edc_outside_area_text','settings');
			$result=array('type'=>'special','text'=>do_shortcode($text));
		}
		return json_encode($result);
	}
    protected function getStreetListHTML($data=array()){
	    $result=array('type'=>'error','text'=>__('An error occured, please try again later','edc'));
	    if(!is_numeric($data['edc_get_street_list'])) return json_encode($result);
        list($code,$total)=EDCH::codes('get',array('ids'=>[$data['edc_get_street_list']],'type'=>$data['type']));
        if($total>0){
            $street_list = json_decode($code[0]->street_list);
            if($street_list) {
                $options=array();
                $options[]=array('value'=>'','name'=>__('Street','medl'));
                foreach ($street_list as $key => $street) $options[] = array('value' => $key, 'name' => $street);
                $options = apply_filters('edc_get_ajax_options', $options);
                $result=array('type'=>'success','text'=>EDCH::simpleOptions($options));
            } else {
                $result=array('type'=>'success', 'isNull' => true);
            }
        }else{
            $text=EDCH::opts('get','edc_outside_area_text','settings')=='' ?  __('Chosen postal code is outside a coverage area','edc') : EDCH::opts('get','edc_outside_area_text','settings');
            $result=array('type'=>'special','text'=>do_shortcode($text));
        }
        return json_encode($result);
    }
	protected function getHeimkehrerPrice($data){
		if(!is_numeric($data['edc_get_heimkehrer_price'])) $this->ajaxResult('error','');
		$args['options'][]=array('name'=>'landing_page_uniq','value'=>'heimkehrer','compare'=>'=');
		list($tariff)=EDCH::trfs('get_list',$args);
		$data['steps_data']=stripslashes($data['steps_data']);
		$this->steps_data=json_decode($data['steps_data'],true);
		$tariff=$tariff[0];
		list($goodie)=EDC_GOODIES::items(['ids'=>[$data['edc_get_heimkehrer_price']]]);
		if($goodie[0]->name=='100 € Heimkehrer-Bonus + 20 € Online-Bonus (Reduzierung des Abschlages)'){
			$tariff->prices['total_per_year']-=120;
			$tariff->prices['total_per_month']-=10;
		}
		$ppy=EDCH::displayPrice(ceil($tariff->prices['total_per_year']),'ppm','&euro;').' im Jahr';
		$ppm=EDCH::displayPrice(ceil($tariff->prices['total_per_month']),'ppm','&euro;').' im Monat';
		return $this->ajaxResult('success',[$ppy,$ppm]);
	}
	protected function sendPopupForm($data=[]){
		EDCH::trimArray($data);
		$fields=['name','email','phone','birthdate','street_and_house','plz_and_ort','type', 'type_of_change'];
		$res=true;
		foreach($fields as $f) if(!isset($data[$f]) || $data[$f]==''){ $res=false; break; }
		
		if($data['type']=='gas'){
		}else if($data['type']=='strom'){
			if($data['consumption']==''){ $res=false; }		
		}else{
			if($data['consumption']==''){ $res=false; }
		}
		if(!date["type_of_change"]) $res=false;
		
		if(!$res) return $this->ajaxResult('error',__('Please complete all required fields.','edc'));
		
		$from=EDCH::opts('get','edc_mail_from','settings');
		$to=EDCH::opts('get','edc_mail_to','settings');		
		if(is_email($from)) $headers[]='From: '.$from.'<'.$from.'>';
		elseif($from!='') $headers[]='From: '.$from;
		$message=[];
		$map=[
			'name'=>'Name',
			'email'=>'Mailadresse',
			'phone'=>'Telefonnummer',
			'birthdate'=>'Geburtsdatum',
			'street_and_house'=>'Straße & Hausnummer',
			'plz_and_ort'=>'PLZ & Ort',
			'product'=>'Gewünschtes Produkt',
			'type'=>'Type',
            'type_of_change'=>'Art des Wechsels',
			'consumption'=>'Verbrauch in kWh',
		];
		
		foreach($map as $k=>$name){
			$val=$data[$k];
			if($k=='type'){
				if($data[$k]=='gas') $val='Erdgas';
				elseif($data[$k]=='strom') $val='Strom';
				else $val='Kombi';
			}
			$message[]=$name.': '.$val;
		}
		
		$result=wp_mail($to,__('New request from the site','medl'),implode("\r\n\r\n",$message),$headers);
		if($result===false) return $this->ajaxResult('error',__('An error occured, please try again later','edc'));
		return $this->ajaxResult('success',__('Thank you for your request. We will contact you soon.','medl'));
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
		}elseif($data['type']=='combi'){
			$args['options'][]=array('name'=>'min_electricity_delivery_per_year','value'=>$data['annual_consumption_el'],'compare'=>'<=','compare_type'=>'numeric','strict'=>false);
			$args['options'][]=array('name'=>'max_electricity_delivery_per_year','value'=>$data['annual_consumption_el'],'compare'=>'>=','compare_type'=>'numeric','strict'=>false);
			$args['options'][]=array('name'=>'min_gas_delivery_per_year','value'=>$data['annual_consumption_gas'],'compare'=>'<=','compare_type'=>'numeric','strict'=>false);
			$args['options'][]=array('name'=>'max_gas_delivery_per_year','value'=>$data['annual_consumption_gas'],'compare'=>'>=','compare_type'=>'numeric','strict'=>false);			
		}
		if(EDCH::is(EDCH::opts('get','edc_use_type_select','settings')) && $data['edc_tariff_type']){
			$types=EDCH::$clients_types;
			if(isset($types[$data['edc_tariff_type']])){
				$args['options'][]=array('name'=>'tariff_clients_type','value'=>$types[$data['edc_tariff_type']],'compare'=>'=','compare_type'=>'numeric');
			}
		}
		if(!EDCH::is($data['lower_30'])){
			$args['options'][]=array('name'=>'edc_available_lower_30','value'=>1,'compare'=>'<>','compare_type'=>'numeric','strict'=>false);
		}
		if(EDCH::is($data['landing'])){			
			$args['options'][]=array('name'=>'edc_landing_tariff','value'=>1,'compare'=>'=','compare_type'=>'numeric');
		}else{
			$args['options'][]=array('name'=>'edc_landing_tariff','value'=>1,'compare'=>'<>','compare_type'=>'numeric','strict'=>false);
		}
		if($data['tariff_ids']) $args['ids']=$data['tariff_ids'];
		if($data['lp']){
			$args['options'][]=array('name'=>'landing_page_uniq','value'=>$data['lp'],'compare'=>'=');			
		}
		//var_dump($args);
		return EDCH::trfs('get_list',$args);
	}
	protected function validateFirstStep($data=array()){
		EDCH::trimArray($data);
		do_action('edc_before_first_step_validation',$data);
		if(!isset(EDCH::$types[$data['type']])) return $this->ajaxResult('error',__('An error occured, please try again later','edc'));
		$ac=false;
		if($data['type']=='electricity' || $data['type']=='gas'){
			$ac=!is_numeric($data['annual_consumption']);
		}else{
			$ac=!is_numeric($data['annual_consumption_gas']) || !is_numeric($data['annual_consumption_el']);
		}
		if(!is_numeric($data['postcode']) || !is_numeric($data['districts']) || $ac) return $this->ajaxResult('error',__('Please complete all required fields.','edc'));
		
		$cons_res=true;
		if($data['type']=='electricity'){
			if($data['annual_consumption']>EDCH::opts('get','edc_max_kwh_electricity','settings')){
				$cons_res=false;
			}
		}elseif($data['type']=='gas'){
			if($data['annual_consumption']>EDCH::opts('get','edc_max_kwh_gas','settings')){
				$cons_res=false;
			}			
		}else{
			if($data['annual_consumption_el']>EDCH::opts('get','edc_max_kwh_electricity','settings') || $data['annual_consumption_gas']>EDCH::opts('get','edc_max_kwh_gas','settings')){
				$cons_res=false;
			}
		}
		if(!$cons_res){
			$text='Der gewählte Wunschverbrauch liegt außerhalb unserer standardisierten Produkte. <a href="/strom-gk/#kontakt">Zur Beratung</a> und Kalkualtion eines optimalen Angebotes helfen unsere Energieberater gern weiter.';//EDCH::opts('get','edc_outside_area_text','settings')=='' ?  __('Your consumption is too high for us, sorry','medl') : EDCH::opts('get','edc_outside_area_text','settings');
			return $this->ajaxResult('special',do_shortcode($text));
		}
		
		if(!($postcode=EDC_POSTCODES::exists($data['districts']))) return $this->ajaxResult('error',__("We have not this postal code in our database.",'edc'));
		list($tariffs,$total)=$this->getTariffsByStepsData($data);
		if($total==0 || sizeOf($tariffs)==0) return $this->ajaxResult('error',__("Sorry but we have not any tariffs for your parameters",'edc'));
		
		do_action('edc_after_first_step_validation',$data);
		return apply_filters('edc_first_step_result',$this->ajaxResult('success',''));
	}
	protected function processFirstStep($data=array()){
		if($data['type']=='combi'){
			if(!isset(EDCH::$types[$data['type']]) || !is_numeric($data['postcode']) || !is_numeric($data['districts']) || !is_numeric($data['annual_consumption_el']) || !is_numeric($data['annual_consumption_gas'])) return false;
		}else{
			if(!isset(EDCH::$types[$data['type']]) || !is_numeric($data['postcode']) || !is_numeric($data['districts']) || !is_numeric($data['annual_consumption'])) return false;
		}
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
                'street'=>$data['street'],
				'annual_consumption'=>$data['annual_consumption'],
				'annual_consumption_el'=>$data['annual_consumption_el'],
				'annual_consumption_gas'=>$data['annual_consumption_gas'],
				'lower_30'=>$data['lower_30'],
			),
		);
		if($data['square']) $this->first_step_data['square']=$data['square'];
		if($data['people']) $this->first_step_data['people']=$data['people'];
		session_id() || session_start();
		$_SESSION['edc_finished']=0;
		do_action('edc_after_first_step_validation',$data);
	}
	protected function processSecondStep($data=array()){
		do_action('edc_before_second_step_process',$data);	
		$this->is_order_step=true;
		$data['steps_data']=stripslashes($data['steps_data']);
		$this->steps_data=json_decode($data['steps_data'],true);
		$this->tariff=EDCH::trfs('get',$data['id_tariff']);
		$this->steps_data['second']=array(
			'id_tariff'=>$data['id_tariff'],
			'goodies'=>$data['goodies'],
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
		if(sizeOf($errors)>0){ return $this->ajaxResult('error',$errors); }
		$_SESSION['edc_confirmation_code']='';
		do_action('edc_after_third_step_validation',$data);
		return apply_filters('edc_third_step_result',$this->ajaxResult('success',''));
	}
	protected function processThirdStep($data=array()){
		session_id() || session_start();
		if($_SESSION['edc_finished']==1 && strpos($_SERVER['HTTP_HOST'],'.lo')===false) return false;
		$_SESSION['edc_finished']=1;
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
		$options['goodies']=$this->steps_data['second']['goodies'];
		$params['id_tariff']=$this->tariff->id;
		$pdf=[];
		$pdf['primary_fields']=$params;
		$pdf['options']=$options;
		$pdf['tariff']=$this->tariff;
		$pdf['annual_consumption']=$this->steps_data['first']['annual_consumption'];
		$params['pdf_path']=EDCH::order('create_pdf',$pdf);
		$oid=EDCH::order('add',$params,$options);
		//var_dump($oid);
		EDC_ORDERS::createCSV($oid,$data);
		EDCH::order('send',$oid);
		do_action('edc_after_third_step_process',$data);
	}
	/*! FORM PORCESSING !*/
	public function getPersons($type=''){
		$opt=EDCH::opts('get','edc_'.$type.'_persons','settings');
		if($opt=='') return [[],''];
		$selected=0;
		$result=[];
		$items=explode("\n",$opt);
		EDCH::trimArray($items);
		foreach($items as $k=>$item){
			list($c,$val)=explode('-',$item);
			if(($k+1)==ceil(sizeOf($items)/2)) $selected=$val;
			$result[]=[$c,$val];
		}
		return [$result,$selected];
	}
}
?>