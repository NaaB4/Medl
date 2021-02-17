<?php
	class EDC_PAGE_DOCS{
		private $data=array();
		private $db;
		var $pagecount=10;
		public function __construct(){
			$this->db=EDCH::DB();
		}
		public function showContent($echo=true){
			$data=array();
			
			$tpl=EDCH::adminTemplate('docs',$data);
			
			if($echo) echo $tpl;
			else return $tpl;
		}
	}	
?>