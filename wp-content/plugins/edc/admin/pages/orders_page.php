<?php
	class EDC_PAGE_ORDERS{
		private $data=array();
		private $db;
		var $pagecount=12;
		public function __construct(){
			$this->db=EDCH::DB();
		}
		public function requestProcessing(){
			if(isset($_POST['get_order_info'])) die($this->getOrderInfo($_POST['get_order_info']));
			if(isset($_POST['get_order_pdf'])) die($this->getOrderPDF($_POST['get_order_pdf']));
			if(isset($_GET['download'])) die(EDCH::pdf('download',$_GET['download']));
		}
		protected function getOrderInfo($oid=''){
			if(!$order=EDCH::order('exists',$oid)) return EDCAdmin::inst()->ajaxResult('error',__('An error occured, please try again later','edc'));
			$html=EDCH::adminTemplate('order_details',array('order'=>$order));
			return EDCAdmin::inst()->ajaxResult('success',$html);
		}
		protected function getOrderPDF($oid=''){
			if(!$order=EDCH::order('exists',$oid)) return EDCAdmin::inst()->ajaxResult('error',__('An error occured, please try again later','edc'));
			$data=array('order'=>$order);			
			$data['pdf_url']=EDC_PLUGIN_URL . 'orders/'.substr($order->pdf_path,strpos($order->pdf_path,'/orders/')+7);
			$data['download_link']=admin_url('admin.php?page=edc_orders&download='.$oid);
			$html=EDCH::adminTemplate('order_pdf',$data);
			return EDCAdmin::inst()->ajaxResult('success',$html);
		}
		public function showContent($echo=true){
			$data=array();
			$args=array();
			$args['page']=$_GET['paged'];
			if(!is_numeric($args['page'])) $args['page']=1;
			$args['per_page']=$this->pagecount;
			if(isset($_GET['search'])){
				if($_GET['search']!='') $args['search']=$_GET['search'];
				if($_GET['date_from']!='') $args['date_from']=$_GET['date_from'];
				if($_GET['date_to']!='') $args['date_to']=$_GET['date_to'];				
			}
			list($data['orders'],$data['total'])=EDCH::order('get_list',$args);
			$data['pagination']=EDCAdmin::inst()->pagenavigation($data['total'],$this->pagecount);
			$tpl=EDCH::adminTemplate('orders',$data);			
			if($echo) echo $tpl;
			else return $tpl;
		}
	}	
?>