<?php

class EDCAdmin{
	protected static $_instance;
	var $statuses="";
	var $page=null;
	var $result='';	public function getInstance(){
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
		$this->setCurrentPage();
		add_filter('map_meta_cap', array($this,'addMetaCaps'),10,4);
		add_action('admin_menu',array($this,'addMenuItems'),9,0);
		if($this->isEDCPage()){
			wp_enqueue_style('edc_admin_style', EDC_PLUGIN_URL . '/admin/assets/edc.css');
			wp_enqueue_style('edc_chosen_style', EDC_PLUGIN_URL . '/admin/assets/chosen/chosen.min.css');
			wp_enqueue_style('edc_datepickerui_style', EDC_PLUGIN_URL . '/admin/assets/datepicker/jquery-ui.min.css');
			wp_enqueue_style('edc_datepickerstruc_style', EDC_PLUGIN_URL . '/admin/assets/datepicker/jquery-ui.structure.min.css');
			wp_enqueue_style('edc_datepickertheme_style', EDC_PLUGIN_URL . '/admin/assets/datepicker/jquery-ui.theme.min.css');
			wp_enqueue_script('edc_admin_script', EDC_PLUGIN_URL . '/admin/assets/edc.js',array('jquery'),'1.0',true);
			wp_enqueue_script('edc_chosen_script', EDC_PLUGIN_URL . '/admin/assets/chosen/chosen.jquery.min.js',array('jquery'),'1.0',true);
			wp_enqueue_script('edc_datepicker_script', EDC_PLUGIN_URL . '/admin/assets/datepicker/jquery-ui.min.js',array('jquery'),'1.0',true);
			wp_enqueue_media();
			$this->getResult();
		}
	}
	/* FORM PROCESSING */
	private function requestProcessing(){
		$this->canAccessEDC();		
		if(method_exists($this->page,'requestProcessing')) $this->page->requestProcessing();
	}
	/*! FORM PROCESSING !*/
	private function setCurrentPage(){
		$page=$_GET['page'];
        if($page=='edc_orders'){
			include_once EDC_PLUGIN_PATH.'/admin/pages/orders_page.php';
			$this->page=new EDC_PAGE_ORDERS();
		}elseif($page=='edc_postcodes'){
			include_once EDC_PLUGIN_PATH.'/admin/pages/postcodes_page.php';
			$this->page=new EDC_PAGE_POSTCODES();
		}elseif($page=='edc_tariffs'){
			include_once EDC_PLUGIN_PATH.'/admin/pages/tariffs_page.php';
			$this->page=new EDC_PAGE_TARIFFS();
		}elseif($page=='edc_documentation'){
			include_once EDC_PLUGIN_PATH.'/admin/pages/docs_page.php';
			$this->page=new EDC_PAGE_DOCS();
		}else{
			include_once EDC_PLUGIN_PATH.'/admin/pages/settings_page.php';
			$this->page=new EDC_PAGE_SETTINGS();
		}
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
	public function isEDCPage(){
		return substr($_GET['page'],0,4)=='edc_';
	}
	public function addMetaCaps($caps,$cap,$user_id,$args){
		$meta_caps=array(
			'manage_edc'=>'administrator',
		);
		$caps=array_diff($caps,array_keys($meta_caps));
		if(isset($meta_caps[$cap])){
			$caps[]=$meta_caps[$cap];
		}
		return $caps;
	}
	public function addMenuItems(){
		add_menu_page(__('EDCalculator','edc'), __('EDCalculator','edc'), 'manage_edc', 'edc_settings', array($this,'createContent'), 'dashicons-admin-multisite', 5);
		add_submenu_page('edc_settings', __('EDCalculator Settings','edc'), __('EDCalculator Settings','edc'), 'manage_edc', 'edc_settings', array($this,'createContent'));
		add_submenu_page('edc_settings', __('EDCalculator Orders','edc'), __('EDCalculator Orders','edc'), 'manage_edc', 'edc_orders', array($this,'createContent'));
		add_submenu_page('edc_settings', __('EDCalculator Postcodes','edc'), __('EDCalculator Postcodes','edc'), 'manage_edc', 'edc_postcodes', array($this,'createContent'));
		add_submenu_page('edc_settings', __('EDCalculator Tariffs','edc'), __('EDCalculator Tariffs','edc'), 'manage_edc', 'edc_tariffs', array($this,'createContent'));
		add_submenu_page('edc_settings', __('EDCalculator Documentation','edc'), __('EDCalculator Documentation','edc'), 'manage_edc', 'edc_documentation', array($this,'createContent'));
	}
	public function getResult(){
		$res=urldecode($_COOKIE['edc_result']);
		$res=str_replace(array('\\\\','\\"'),array('\\','"'),$res);
		$this->result=json_decode($res,true);
		if(!isset($this->result['text'])) $this->result['text']=$this->result['success_text'] ? $this->result['success_text'] : $this->result['error_text'];
		setcookie('edc_result','',null,'/');		
	}
	public function createContent(){
		$this->canAccessEDC();
		if(method_exists($this->page,'showContent')) $this->page->showContent();
		else echo $this->page->content;
	}
	
	public function canAccessEDC(){
		$this->user=wp_get_current_user();		return $this->user->roles && in_array('administrator',$this->user->roles);
	}
	public function canEditEDC(){
		return apply_filters('edc_can_access',$this->canAccessEDC(),$_GET['page']);
	}
	public function getPagesList(){
		$query=new WP_Query(array(
			'post_type'=>'page',
			'orderby'=>'post_title',
			'order'=>'ASC',
			'posts_per_page'=>-1,
		));
		return $query->posts;
	}
	public function pagenavigation($total,$per_page){
		if(is_numeric($total)) $count=$total;
		elseif(is_string($total)){
			$total=EDCH::DB()->get_results($total);
			$count=$total[0]->cnt;
		}elseif($total instanceof WP_Query){
			$count=$total->found_posts;
		}
		$page_links=paginate_links(
			array(
				'base'      => add_query_arg( 'paged', '%#%' ),
				'format'    => '',
				'prev_text' => '&laquo;',
				'next_text' => '&raquo;',
				'total'     => ceil( $count / $per_page ),
				'current'   => is_numeric($_GET['paged']) ? $_GET['paged'] : 1,
			)
		);
		return $page_links ? '<div class="edc_navigation"><div class="links">'.$page_links.'</div></div>' : '';
	}
	public function getSample($file=''){
		if($file=='') return '#';
		if(file_exists(EDCH::getThemePath().'/edc/samples/'.$file)) return EDCH::getThemeUrl().'/edc/samples/'.$file;
		return file_exists(EDC_PLUGIN_PATH.'/samples/'.$file) ? EDC_PLUGIN_URL.'/samples/'.$file : '#';
	}
	public function getEmptyImage(){
		return EDC_PLUGIN_URL . '/admin/images/no-image.jpg';
	}
	public function drawField($key,$data=array(),$value=null){
		$data['key']=$key;
		$data['value']=$value===null ? $data['default'] : $value;
		if($data['type']=='image'){
			$data['image_url']=is_numeric($data['value']) ? wp_get_attachment_image_url($data['value']) : '';
			if(!$data['image_url'] || is_wp_error($data['image_url'])) $data['image_url']=EDCAdmin::inst()->getEmptyImage();
			return EDCH::adminTemplate('fields/image',$data);
		}elseif(EDCH::adminTemplateFile('fields/'.$data['type'])!==false){
			return EDCH::adminTemplate('fields/'.$data['type'],$data);			
		}else{
			return EDCH::adminTemplate('fields/text',$data);
		}
	}
}

function createEDCAdminInstance(){
	global $edc_admin;
	$edc_admin=EDCAdmin::getInstance();
}
add_action('init','createEDCAdminInstance',30);

?>