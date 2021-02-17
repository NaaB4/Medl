<?php

if(is_admin()){
	remove_action('init','createEDCAdminInstance',30);
	include_once get_stylesheet_directory().'/edc/admin.class.php';
	add_action('init','createEDCAdminExtensionInstance',30);
	function createEDCAdminExtensionInstance(){
		global $edc_admin;
		$edc_admin=EDCAdmin_Extension::getInstance();
	}
}else{
	remove_action('init','createEDCInstance',30);
	include_once get_stylesheet_directory().'/edc/front.class.php';
	add_action('init','createEDCExtensionInstance',30);	
	function createEDCExtensionInstance(){
		EDC_Extension::getInstance();
	}
}

new class(){
	public function __construct(){
		add_filter('edc_tariff_options',[$this,'extendTariffSettings']);
		add_filter('edc_order_fields',[$this,'extendOrderOptions']);
	}
	public function extendTariffSettings($options=[]){
		$options[]=array(
			'key'=>'tariff_order',
			'type'=>'text',
			'name'=>__('Tarife ordnen','medl'),
		);
		$options[]=array(
			'key'=>'edc_available_lower_30',
			'type'=>'checkbox',
			'label'=>__('Verfügbar für Junge Leute (unter 30 Jahre)','medl'),
		);
		$options[]=array(
			'key'=>'edc_landing_tariff',
			'type'=>'checkbox',
			'label'=>__('Landingpage-Tarif','medl'),
		);
		$options[]=array(
			'key'=>'tariff_goodies',
			'type'=>'select',
			'visible'=>false,
		);
		$options[]=array(
			'key'=>'price_per_kwh',
			'type'=>'text',
			'visible'=>false,
		);
		$options[]=array(
			'key'=>'price_per_period',
			'type'=>'text',
			'visible'=>false,
		);
		$options[]=array(
			'key'=>'tariff_features',
			'type'=>'textarea',
			'name'=>__('Tarif Features','medl'),
		);
		$options[]=array(
			'key'=>'tariff_price_details',
			'type'=>'textarea',
			'name'=>__('Preisdetails','medl'),
		);
		$options[]=array(
			'key'=>'tariff_agb_link',
			'type'=>'text',
			'name'=>__('ABG URL','medl'),
		);
		$options[]=array(
			'key'=>'tariff_price_link',
			'type'=>'text',
			'name'=>__('Produktdetails URL','medl'),
		);
		$options[]=array(
			'key'=>'landing_page_uniq',
			'type'=>'text',
			'name'=>__('Landingpage ID','medl'),
		);
		$options[]=array(
			'key'=>'product_id',
			'type'=>'text',
			'name'=>__('ProduktID','medl'),
		);
		$options[]=array(
			'key'=>'product_preiseid',
			'type'=>'text',
			'name'=>__('Preissschluessel','medl'),
		);
		$options[]=array(
			'key'=>'thankyou_page',
			'type'=>'pages_dropdown',
			'name'=>__('Abschlussseite','medl'),
		);
		$options[]=array(
			'key'=>'edc_sollen_text',
			'type'=>'rich',
			'name'=>__('Text für "Sollen wir Ihren alten Vertrag kündigen"','medl'),
		);
		$options[]=array(
			'key'=>'edc_first_legal_cbx',
			'type'=>'rich',
			'name'=>__('Checkbox-Text AGBs','medl'),
		);
		
		return $options;
	}
	public function extendOrderOptions($options=[]){
		$options['change']=[
			'title'=>__('Art des Wechsels','medl'),
			'items'=>array(
				'change'=>array(
					'name'=>__('Art des Wechsels','medl'),
					'values'=>array(
						'change'=>__('Versorgerwechsel','medl'),
						'new'=>__('Neueinzug','medl'),
					),
					'field'=>'option',
				),
				'change_street'=>array(
					'name'=>__('Street','edc'),
					'field'=>'option',
				),
				'change_house'=>array(
					'name'=>__('House','edc'),
					'field'=>'option',
				),
				'change_postal_code'=>array(
					'name'=>__('Postal code','edc'),
					'field'=>'option',
				),
				'change_location'=>array(
					'name'=>__('Location','edc'),
					'field'=>'option',
				),
				'cancel_old'=>array(
					'name'=>__('Sollen wir Ihren alter Vertag kündigen','edc'),
					'values'=>array(
						'1'=>__('ja','medl'),
						'0'=>__('nein','medl'),
					),
					'field'=>'option',
				),
				'start_supply'=>array(
					'name'=>__('Ab wann sollen wir Sie beliefern','edc'),
					'values'=>array(
						'right_now'=>__('schnellstmöglich','medl'),
						'desired'=>__('Gewünschter Lieferbeginn','medl'),
					),
					'field'=>'option',
				),
				'edc_mobile_checkbox'=>array(
					'name'=>__('Mobile checkbox','medl'),
					'field'=>'option',
				),
				'edc_email_checkbox'=>array(
					'name'=>__('Email checkbox','medl'),
					'field'=>'option',
				),
				'legal4'=>array(
					'name'=>__('Fourth legal checkbox','medl'),
					'field'=>'option',
				),
				'goodies'=>array(
					'name'=>__('Order Goodies','medl'),
					'field'=>'option',
				),
				'annual_el'=>array(
					'name'=>__('Annual consumption (Strom)','medl'),
					'field'=>'option',
				),
				'edc_consumption'=>array(
					'name'=>__('Annual consumption','medl'),
					'field'=>'option',
				),
				'annual_gas'=>array(
					'name'=>__('Annual consumption (Erdgas)','medl'),
					'field'=>'option',
				),
				'edc_house_zuratc'=>array(
					'name'=>__('Hausnummrenzusatz','medl'),
					'field'=>'option',
				),
			),
		];
		
		return $options;
	}
};