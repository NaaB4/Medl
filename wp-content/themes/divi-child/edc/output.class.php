<?php
class EDCOutput{
	private $_edc=null;
	private $_wp_post=null;
	private $_output_done=false;
	public function __construct(){
		$this->_edc=EDC::getInstance();		
		add_action( 'wp_enqueue_scripts', [$this,'enqueueAssets']);		
		add_shortcode('EDCalculator',array($this,'shortcodeOutput'));
		add_shortcode('EDCBadge',array($this,'tariffBadgeOutput'));
		add_shortcode('EDCPopupLink',array($this,'tariffPopupLink'));
		add_shortcode('email_checkbox',array($this,'getEmailCheckbox'));
		add_shortcode('phone_checkbox',array($this,'getPhoneCheckbox'));
		add_shortcode('edc_popup',array($this,'getEDCPopup'));
		add_action('wp',array($this,'edcPages'));
		add_action('wp_footer',array($this,'edcJSSettings'),PHP_INT_MAX);
	}
	public function enqueueAssets(){		
		if(!$this->_edc->isIE()){
			wp_enqueue_script('edc_script', EDC_PLUGIN_URL . '/front/assets/edc.js',array('jquery'),'1.0',true);
		}else{
			wp_enqueue_script('edc_script', EDC_PLUGIN_URL . '/front/assets/edc-ie.js',array('jquery'),'1.0',true);			
		}
		wp_enqueue_style('edc_main_style', EDC_PLUGIN_URL . '/front/assets/edc.css');
		if(file_exists(EDCH::getThemePath().'/edc/assets/edc.css')) wp_enqueue_style('edc_theme_style', EDCH::getThemeUrl() . '/edc/assets/edc.css');
		if(file_exists(EDCH::getThemePath().'/edc/assets/edc.js')){
			wp_enqueue_script('edc_theme_script', EDCH::getThemeUrl() . '/edc/assets/edc.js',array('jquery','edc_script'),'1.0',true);
		}
		wp_enqueue_script('edc_iban_script', EDCH::getThemeUrl() . '/edc/assets/iban.lib.js',array('jquery','edc_script'),'1.0',true);
	}
	public function shortcodeOutput($args,$content,$tags){
		$output='';
		if($this->_edc->is_processed){
			if($this->_edc->is_tariffs_step){
				$output=$this->getTariffsStepContent();
			}elseif($this->_edc->is_order_step){
				$output=$this->getOrderStepContent();
			}elseif($this->_edc->is_result_step){
				$output=$this->getResultStepContent();
			}
			$this->addPreviousStepsData($output);
		}else{
			$this->addEDCPopup();
			$args['shortcode_content']=$content;
			if(EDCH::is($args['landing'])){
				$output=EDCH::loadTemplate("shortcode_landing",$args);				
			}else{
				$output=EDCH::loadTemplate("shortcode",$args);
			}
		}
		return $output;
	}
	public function tariffBadgeOutput($args,$content){
		$total_price=$args['total_price'];
		if(!is_numeric($total_price)) $total_price=$args['total_price'];
		$tariff_ids=$args['tariff_ids'];
		if(!$tariff_ids) $tariff_ids=$args['tariff_ids'];
		$type=$args['type'];
		if(!$type) $type=$args['type'];
		$agb_link=$args['agb_link'];
		if(!$agb_link) $agb_link=$args['agb_link'];
		$price_link=$args['price_link'];
		if(!$price_link) $price_link=$args['price_link'];
		$young=$args['young'];
		if(!$young) $young=$args['young'];
		if($type=='combi'){
			$st_eac=$args['st_example_annual_consumption'];
			if(!is_numeric($st_eac)) $st_eac=$args['st_example_annual_consumption'];
			$st_per_kwh=$args['st_per_kwh'];
			if(!is_numeric($st_per_kwh)) $st_per_kwh=$args['st_per_kwh'];
			$st_per_month=$args['st_per_month'];
			if(!is_numeric($st_per_month)) $st_per_month=$args['st_per_month'];
			$g_eac=$args['g_example_annual_consumption'];
			if(!is_numeric($g_eac)) $g_eac=$args['g_example_annual_consumption'];
			$g_per_kwh=$args['g_per_kwh'];
			if(!is_numeric($g_per_kwh)) $g_per_kwh=$args['g_per_kwh'];
			$g_per_month=$args['g_per_month'];
			if(!is_numeric($g_per_month)) $g_per_month=$args['g_per_month'];
			$data=[
				'st_example_annual_consumption'=>$st_eac,
				'st_per_kwh'=>$st_per_kwh,
				'st_per_month'=>$st_per_month,
				'g_example_annual_consumption'=>$g_eac,
				'g_per_kwh'=>$g_per_kwh,
				'g_per_month'=>$g_per_month,
				'total_price'=>$total_price,
				'tariff_ids'=>$tariff_ids,
				'type'=>$type,
				'agb_link'=>$agb_link,
				'price_link'=>$price_link,
				'young'=>$young,
			];
			$html=EDCH::loadTemplate('tariffs_badge_combi',$data);			
		}else{
			$fac=$args['from_annual_consumption'];
			if(!is_numeric($fac)) $fac=$args['from_annual_consumption'];
			$tac=$args['to_annual_consumption'];
			if(!is_numeric($tac)) $tac=$args['to_annual_consumption'];
			$eac=$args['example_annual_consumption'];
			if(!is_numeric($eac)) $eac=$args['example_annual_consumption'];
			$per_kwh=$args['per_kwh'];
			if(!is_numeric($per_kwh)) $per_kwh=$args['per_kwh'];
			$per_month=$args['per_month'];
			if(!is_numeric($per_month)) $per_month=$args['per_month'];
			$data=[
				'from_annual_consumption'=>$fac,
				'to_annual_consumption'=>$tac,
				'example_annual_consumption'=>$eac,
				'per_kwh'=>$per_kwh,
				'per_month'=>$per_month,
				'total_price'=>$total_price,
				'tariff_ids'=>$tariff_ids,
				'type'=>$type,
				'agb_link'=>$agb_link,
				'price_link'=>$price_link,
				'young'=>$young,
			];
			$html=EDCH::loadTemplate('tariffs_badge',$data);
		}
		$this->addTariffsPopup();
		return $html;
	}
	public function tariffPopupLink($args,$content){		
		$total_price=$args['total_price'];
		if(!is_numeric($total_price)) $total_price=$args['total_price'];
		$tariff_ids=$args['tariff_ids'];
		if(!$tariff_ids) $tariff_ids=$args['tariff_ids'];
		$type=$args['type'];
		if(!$type) $type=$args['type'];
		$agb_link=$args['agb_link'];
		if(!$agb_link) $agb_link=$args['agb_link'];
		$price_link=$args['price_link'];
		if(!$price_link) $price_link=$args['price_link'];
		$young=$args['young'];
		if(!$young) $young=$args['young'];
		if($type=='combi'){
			$st_eac=$args['st_example_annual_consumption'];
			if(!is_numeric($st_eac)) $st_eac=$args['st_example_annual_consumption'];
			$st_per_kwh=$args['st_per_kwh'];
			if(!is_numeric($st_per_kwh)) $st_per_kwh=$args['st_per_kwh'];
			$st_per_month=$args['st_per_month'];
			if(!is_numeric($st_per_month)) $st_per_month=$args['st_per_month'];
			$g_eac=$args['g_example_annual_consumption'];
			if(!is_numeric($g_eac)) $g_eac=$args['g_example_annual_consumption'];
			$g_per_kwh=$args['g_per_kwh'];
			if(!is_numeric($g_per_kwh)) $g_per_kwh=$args['g_per_kwh'];
			$g_per_month=$args['g_per_month'];
			if(!is_numeric($g_per_month)) $g_per_month=$args['g_per_month'];
			$data=[
				'st_example_annual_consumption'=>$st_eac,
				'st_per_kwh'=>$st_per_kwh,
				'st_per_month'=>$st_per_month,
				'g_example_annual_consumption'=>$g_eac,
				'g_per_kwh'=>$g_per_kwh,
				'g_per_month'=>$g_per_month,
				'total_price'=>$total_price,
				'tariff_ids'=>$tariff_ids,
				'type'=>$type,
				'agb_link'=>$agb_link,
				'price_link'=>$price_link,
				'young'=>$young,
				'filled'=>$args['filled'],
			];
			$link=EDCH::loadTemplate('tariffs_link_combi',$data);			
		}else{
			$fac=$args['from_annual_consumption'];
			if(!is_numeric($fac)) $fac=$args['from_annual_consumption'];
			$tac=$args['to_annual_consumption'];
			if(!is_numeric($tac)) $tac=$args['to_annual_consumption'];
			$eac=$args['example_annual_consumption'];
			if(!is_numeric($eac)) $eac=$args['example_annual_consumption'];
			$per_kwh=$args['per_kwh'];
			if(!is_numeric($per_kwh)) $per_kwh=$args['per_kwh'];
			$per_month=$args['per_month'];
			if(!is_numeric($per_month)) $per_month=$args['per_month'];
			$data=[
				'from_annual_consumption'=>$fac,
				'to_annual_consumption'=>$tac,
				'example_annual_consumption'=>$eac,
				'per_kwh'=>$per_kwh,
				'per_month'=>$per_month,
				'total_price'=>$total_price,
				'tariff_ids'=>$tariff_ids,
				'type'=>$type,
				'agb_link'=>$agb_link,
				'price_link'=>$price_link,
				'young'=>$young,
				'filled'=>$args['filled'],
			];
			$link=EDCH::loadTemplate('tariffs_link',$data);
		}
		$this->addTariffsPopup();
		return $link;
	}
	public function getEDCPopup($args,$content){
		$html='<a href="javascript:void(0);" onclick="edc.popup(\'#edc_popup\');">'.$content.'</a>';
		return $html;		
	}
	public function getEmailCheckbox($args,$content){
		$content=EDCH::opts('get','edc_email_checkbox','settings');
		if($content=='') return '';
		return EDCH::loadTemplate('checkbox',['content'=>$content,'name'=>'edc_email_checkbox']);
	}
	public function getPhoneCheckbox($args,$content){
		$content=EDCH::opts('get','edc_mobile_checkbox','settings');
		if($content=='') return '';
		return EDCH::loadTemplate('checkbox',['content'=>$content,'name'=>'edc_mobile_checkbox']);
	}
	public function addTariffsPopup(){
		if(isset($this->tariff_popup_added) && $this->tariff_popup_added) return;
		add_action('wp_footer',[$this,'drawTariffPopup']);
	}
	public function drawTariffPopup(){
		$this->tariff_popup_added=true;
		echo EDCH::loadTemplate('popup');
	}
	public function addEDCPopup(){
		if(isset($this->edc_popup_added) && $this->edc_popup_added) return;
		$this->enqueueDatePicker();
		add_action('wp_footer',[$this,'drawEDCPopup']);
	}
	public function drawEDCPopup(){
		$this->edc_popup_added=true;
		echo EDCH::loadTemplate('edc_popup');
	}
	public function edcPages(){
		global $post;
		$this->_wp_post=$post;
		$tpage=EDCH::opts('get','edc_tariffs_page','settings');
		$opage=EDCH::opts('get','edc_order_page','settings');
		if(!$opage) $opage=$tpage;
		$rpage=EDCH::opts('get','edc_results_page','settings');
		if(!$rpage) $rpage=$opage;
		if($this->_wp_post->ID==$tpage || $this->_wp_post->ID==$opage || $this->_wp_post->ID==$rpage || $this->_edc->is_processed){
			add_filter('the_content',array($this,'edcPageContent'));
		}
	}
	public function edcPageContent($content){
		global $post;
		$html='';
		if($post->ID==$this->_wp_post->ID && is_main_query()){
			if($this->_edc->is_tariffs_step){
				$html=$this->getTariffsStepContent();
				$pos=EDCH::opts('get','edc_tariffs_pos','settings');
			}elseif($this->_edc->is_order_step){
				$html=$this->getOrderStepContent();
				$pos=EDCH::opts('get','edc_order_pos','settings');			
			}elseif($this->_edc->is_result_step){
				$html=$this->getResultStepContent();
				$pos=EDCH::opts('get','edc_result_pos','settings');
				list($orders,$total)=EDC_ORDERS::getList(['per_page'=>1]);
				EDC_HELPER::setReplaces($content,$orders[0]->id);
			}
			if($html=='') return $content;
			$this->addPreviousStepsData($html);
		}
		if($html=='') return $content;
		return $this->addHTMLToContent($content,$html,$pos);
	}
	public function addPreviousStepsData(&$html){
		if(!($this->_edc->is_tariffs_step || $this->_edc->is_order_step)) return false;
		$data=[
			'location'=>$this->_edc->steps_data['first']['postcode_data']->name,
			'postal_code'=>$this->_edc->steps_data['first']['postcode'],
			'type'=>$this->_edc->steps_data['first']['type'],
			'annual_consumption'=>$this->_edc->steps_data['first']['annual_consumption'],
			'annual_consumption_el'=>$this->_edc->steps_data['first']['annual_consumption_el'],
			'annual_consumption_gas'=>$this->_edc->steps_data['first']['annual_consumption_gas'],
			'tariff'=>$this->_edc->tariff,
		];
		if(!($tpl=EDCH::loadTemplate('edc_steps_data',$data))) return false;
		$html.=$tpl;
	}
	public function getTariffsStepContent(){
		//if($this->_output_done) return '';
		global $post;
		$is_tariff_step=$post->ID==$this->_wp_post->ID || $this->_edc->is_tariffs_step===true;
		if(!apply_filters('edc_is_tariffs_step',$is_tariff_step)) return '';
		$html=EDCH::loadTemplate('tariffs_list',array(
			'tariffs'=>$this->_edc->tariffs,
			'step_data'=>htmlspecialchars(json_encode($this->_edc->steps_data)),
		));
		$this->_output_done=true;
		return $html;
	}
	public function getOrderStepContent(){
		//if($this->_output_done) return '';
		global $post;
		$is_order_step=$post->ID==$this->_wp_post->ID || $this->is_tariffs_step===true;
		if(!apply_filters('edc_is_order_step',$is_order_step)) return '';
		$this->enqueueDatePicker();
		list($code)=EDCH::codes('get',array('id'=>$this->_edc->steps_data['first']['districts']));
		$code=$code[0];
		$suppliers=array_map('trim',explode("\n",EDCH::opts('get','edc_suppliers_list','settings')));
		natcasesort($suppliers);
		//var_dump($suppliers);
		$options=[];
		foreach($suppliers as $s) if($s!='') $options[]=['name'=>$s,'value'=>htmlspecialchars($s)];
		$streets=array_map('trim',explode("\n",EDCH::opts('get','edc_streets_list','settings')));
		natcasesort($streets);
		$soptions=[];
		foreach($streets as $s) if($s!='') $soptions[]=['name'=>$s,'value'=>htmlspecialchars($s)];
		
		$html=EDCH::loadTemplate('order_form',array(
			'tariff'=>$this->_edc->tariff,
			'step_data'=>htmlspecialchars(json_encode($this->_edc->steps_data)),
			'postal_code'=>$this->_edc->steps_data['first']['postcode'],
			'annual_consumption'=>$this->_edc->steps_data['first']['annual_consumption'],
			'annual_consumption_el'=>$this->_edc->steps_data['first']['annual_consumption_el'],
			'annual_consumption_gas'=>$this->_edc->steps_data['first']['annual_consumption_gas'],
			'district_name'=>$code->name,
			'direct_debit_text'=>EDCH::opts('get','edc_sepa_text','settings'),
			'suppliers_options'=>EDCH::simpleOptions($options),
			'streets_options'=>EDCH::simpleOptions($soptions),
		));
		$this->_output_done=true;
		return $html;
	}
	public function getResultStepContent(){
		//if($this->_output_done) return '';
		global $post;
		$is_result_step=$post->ID==$this->_wp_post->ID || $this->_edc->is_result_step===true;
		if(!apply_filters('edc_is_result_step',$is_result_step)) return '';
		
		$html=EDCH::loadTemplate('result');
		$this->_output_done=true;
		return $html;
	}
	public function addHTMLToContent($content,$html,$pos){		
		if(strpos($content,'[EDC')===false){
			if($pos=='after'){
				$content.=$html;
			}else{				
				$content=$html.$content;
			}
		}else{ $content=preg_replace('/\[EDC(.+?)\]/Uis',$html,$content); }
		return $content;
	}
	public function edcJSSettings(){
		echo EDCH::loadTemplate('js_settings');
	}
	public function enqueueRangeSlider(){
		wp_enqueue_style('edc_rangeslider_style', EDC_PLUGIN_URL . '/front/assets/range-slider/rangeslider.css');
		wp_enqueue_script('edc_rangeslider_script', EDC_PLUGIN_URL . '/front/assets/range-slider/rangeslider.min.js',array('jquery'),'1.0',true);
		wp_enqueue_script('edc_rangeslidersettings_script', EDC_PLUGIN_URL . '/front/assets/range-slider/rangeslider-settings.js',array('jquery'),'1.0',true);
	}
	public function enqueueDatePicker(){
		wp_enqueue_style('edc_datepickerui_style', EDC_PLUGIN_URL . '/front/assets/datepicker/jquery-ui.min.css');
		wp_enqueue_style('edc_datepickerstruc_style', EDC_PLUGIN_URL . '/front/assets/datepicker/jquery-ui.structure.min.css');
		wp_enqueue_style('edc_datepickertheme_style', EDC_PLUGIN_URL . '/front/assets/datepicker/jquery-ui.theme.min.css');
		wp_enqueue_script('edc_datepicker_script', EDC_PLUGIN_URL . '/admin/assets/datepicker/jquery-ui.min.js',array('jquery'),'1.0',true);
	}
}
?>