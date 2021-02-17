<?php
class EDCOutput{
	private $_edc=null;
	private $_wp_post=null;
	private $_output_done=false;
	public function __construct(){
		$this->_edc=EDC::getInstance();
		if(!$this->_edc->isIE()){
			wp_enqueue_script('edc_script', EDC_PLUGIN_URL . '/front/assets/edc.js',array('jquery'),'1.0',true);
		}else{
			wp_enqueue_script('edc_script', EDC_PLUGIN_URL . '/front/assets/edc-ie.js',array('jquery'),'1.0',true);			
		}
		wp_enqueue_style('edc_main_style', EDC_PLUGIN_URL . '/front/assets/edc.css');
		if(file_exists(EDCH::getThemePath().'/edc/assets/edc.css')) wp_enqueue_style('edc_theme_style', EDCH::getThemeUrl() . '/edc/assets/edc.css');
		if(file_exists(EDCH::getThemePath().'/edc/assets/edc.js')) wp_enqueue_script('edc_theme_script', EDCH::getThemeUrl() . '/edc/assets/edc.js',array('jquery','edc_script'),'1.0',true);
		
		add_shortcode('EDCalculator',array($this,'shortcodeOutput'));
		add_action('wp',array($this,'edcPages'));
		add_action('wp_footer',array($this,'edcJSSettings'),PHP_INT_MAX);
	}
	function shortcodeOutput($args,$content,$tags){
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
			$args['shortcode_content']=$content;
			$output=EDCH::loadTemplate("shortcode",$args);
		}
		return $output;
	}
	public function edcPages(){
		global $post;
		$this->_wp_post=$post;
		$tpage=EDCH::opts('get','edc_tariffs_page','settings');
		$opage=EDCH::opts('get','edc_order_page','settings');
		if(!$opage) $opage=$tpage;
		$rpage=EDCH::opts('get','edc_results_page','settings');
		if(!$rpage) $rpage=$opage;
		if($this->_wp_post->ID==$tpage || $this->_wp_post->ID==$opage || $this->_wp_post->ID==$rpage){
			add_filter('the_content',array($this,'edcPageContent'));
		}
	}
	public function edcPageContent($content){
		if($this->_edc->is_tariffs_step){
			$html=$this->getTariffsStepContent();
			$pos=EDCH::opts('get','edc_tariffs_pos','settings');
		}elseif($this->_edc->is_order_step){
			$html=$this->getOrderStepContent();
			$pos=EDCH::opts('get','edc_order_pos','settings');			
		}elseif($this->_edc->is_result_step){
			$html=$this->getResultStepContent();
			$pos=EDCH::opts('get','edc_result_pos','settings');				
		}
		if($html=='') return $content;
		$this->addPreviousStepsData($html);
		return $this->addHTMLToContent($content,$html,$pos);
	}
	public function addPreviousStepsData(&$html){
		if(!($this->_edc->is_tariffs_step || $this->_edc->is_order_step)) return false;
		//var_dump($this->_edc->steps_data['first']['postcode_data']);
		$data=[
			'location'=>$this->_edc->steps_data['first']['postcode_data']->name,
			'postal_code'=>$this->_edc->steps_data['first']['postcode'],
			'annual_consumption'=>$this->_edc->steps_data['first']['annual_consumption'],
			'tariff'=>$this->_edc->tariff,
		];
		if(!($tpl=EDCH::loadTemplate('edc_steps_data',$data))) return false;
		//var_dump($tpl);
		$html.=$tpl;
	}
	public function getTariffsStepContent(){
		if($this->_output_done) return '';
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
		if($this->_output_done) return '';
		global $post;
		$is_order_step=$post->ID==$this->_wp_post->ID || $this->is_tariffs_step===true;
		if(!apply_filters('edc_is_order_step',$is_order_step)) return '';
		$this->enqueueDatePicker();
		list($code)=EDCH::codes('get',array('id'=>$this->_edc->steps_data['first']['districts']));
		$code=$code[0];		
		$html=EDCH::loadTemplate('order_form',array(
			'tariff'=>$this->_edc->tariff,
			'step_data'=>htmlspecialchars(json_encode($this->_edc->steps_data)),
			'postal_code'=>$this->_edc->steps_data['first']['postcode'],
			'district_name'=>$code->name,
		));
		$this->_output_done=true;
		return $html;
	}
	public function getResultStepContent(){
		if($this->_output_done) return '';
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