<?php

class EDCAdmin_Extension extends EDCAdmin{
	public function getInstance(){
		if(!self::$_instance){
			self::$_instance=new self();
			self::$_instance->requestProcessing();
		}
		return self::$_instance;
	}
	public function inst(){
		return self::getInstance();
	}
	public function __construct(){
		parent::__construct();
		add_filter('edc_settings_tariff',[$this,'changeMainSettings']);
		add_filter('edc_settings_order',[$this,'changeOrderSettings']);
		add_filter('edc_settings',[$this,'changeSettings']);
		$this->fixCurrentPage();
		add_action('admin_menu',array($this,'addNewMenuItems'),9,0);
		if($this->isEDCPage()){
			wp_enqueue_script('edc_ext_admin_script', get_stylesheet_directory_uri() . '/edc/assets/admin.js',array('jquery','edc_admin_script'),'1.0',true);
			wp_enqueue_style('edc_theme_style', EDCH::getThemeUrl() . '/edc/assets/admin.css');
		}
	}
	private function fixCurrentPage(){
		$page=$_GET['page'];		
		if(($this->page instanceof EDC_PAGE_TARIFFS)){
			include_once get_stylesheet_directory().'/edc/admin_pages/tariffs_page.php';
			$this->page=new EDC_PAGE_TARIFFS_Extension();
		}elseif($page=='edc_goodies'){
			include_once get_stylesheet_directory().'/edc/admin_pages/goodies.php';
			$this->page=new EDC_GOODIES_PAGE();
		}
	}
	/* FORM PROCESSING */
	private function requestProcessing(){
		$this->canAccessEDC();
		if(isset($_GET['csv']) && ($this->page instanceof EDC_PAGE_ORDERS)) EDC_ORDERS::downloadCSV($_GET['csv']);
		if(method_exists($this->page,'requestProcessing')) $this->page->requestProcessing();
	}
	/*! FORM PROCESSING !*/
	public function addNewMenuItems(){
		add_submenu_page('edc_settings', __('Goodies','medl'), __('Goodies','medl'), 'manage_edc', 'edc_goodies', array($this,'createContent'));
	}
	public function changeSettings($settings){
		$user=wp_get_current_user();
		if(strpos($user->user_email,'@ivato.de')===false){
			unset($settings['reset']);
		}
		return $settings;
	}
	public function changeMainSettings($settings){
		$settings['items']=[
			[
				'edc_max_kwh_electricity'=>[
					'name'=>__('Upper limit (kWh)','edc'),
					'placeholder'=>'10000',
					'type'=>'text',
					'validate'=>'numeric',
				],
				'edc_max_kwh_gas'=>[
					'name'=>__('Upper limit (kWh)','edc'),
					'placeholder'=>'10000',
					'type'=>'text',
					'validate'=>'numeric',
				],
				'edc_electricity_persons'=>[				
					'name'=>__('Werte Stromtarife','medl'),
					'placeholder'=>'10000',
					'type'=>'textarea',
				],
				'edc_gas_persons'=>[				
					'name'=>__('Werte Gastarife','medl'),
					'placeholder'=>'10000',
					'type'=>'textarea',
				],
				'edc_combi_persons'=>[				
					'name'=>__('Werte Kombitarife','medl'),
					'placeholder'=>'10000',
					'type'=>'textarea',
				],
			]
		];		
		return $settings;
	}
	public function changeOrderSettings($settings){
		$settings=array(
			'title'=>__('Order settings','edc'),
			'order'=>4,
			'grouped'=>true,
			'items'=>array(
				array(
					'title'=>__('Checkbox-Texte Bestellformular"','medl'),
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
					'edc_fourth_checkbox'=>[
						'name'=>__('Text der vierten Checkbox','medl'),
						'type'=>'rich',
					],
					'edc_mobile_checkbox'=>[
						'name'=>__('Text der Telefon Checkbox','medl'),
						'type'=>'rich',
					],
					'edc_email_checkbox'=>[
						'name'=>__('Text der Checkbox für die E-Mail Adresse','medl'),
						'type'=>'rich',
					],
				),
				array(
					'title'=>__('Texte / Logo für PDF Bestellformular','medl'),
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
				array(
					'title'=>__('Hinweise und Verzeichnisse im Bestellformular','medl'),
					'edc_sepa_text'=>[
						'name'=>__('SEPA Checkbox Text','medl'),
						'type'=>'rich',
					],
					'edc_suppliers_list'=>[
						'name'=>__('Lieferanten','medl'),
						'type'=>'textarea',
					],
					'edc_streets_list'=>[
						'name'=>__('Verzeichnis der Straßennamen','medl'),
						'type'=>'textarea',
					],
					'edc_hint_1'=>[
						'name'=>__('Hinweis für "Zählernummer"','medl'),
						'type'=>'rich',
					],
					'edc_hint_2'=>[
						'name'=>__('Hinweis für "Zählerstand"','medl'),
						'type'=>'rich',
					],
				),
			)
		);
		
		return $settings;
	}
}


?>