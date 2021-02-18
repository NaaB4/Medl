<?php
class PostcodesImporter{	
	var $db=null;
	var $data=null;
	var $result=null;
	var $error='';
	var $skip=1;
	var $pid=0;
	var $file='';
	var $filename='';
	function __construct($file='',$fname='',$params=array()){
		$this->db=EDCH::DB();
		$this->file=$file;
		$this->filename=$fname;
		if(is_numeric($params['skip']))$this->skip=$params['skip'];
	}
	public function import(){
		if(!$this->file){ $this->error='incorrect_file'; return false; }
		$fext='';
		if($this->filename!=''){ $fext=$this->getFileExt($this->filename); }
		if($fext==''){ $fext=$this->getFileExt($this->file); }
		if(!$fext){ $this->error='incorrect_extension'; return false; }
		$this->loadXLSData($this->file);
		if(!is_array($this->data) || sizeOf($this->data)==0){ $this->error='corrupt_data';  return false; }
		
		if(!$this->insertInfo()){ $this->error='insert_error'; return false; }
	}
	protected function getFileExt($f=''){		
		$name=basename($f);
		$res='';
		if(strpos($name,'.')!==false){
			preg_match('/\.(xlsx|xml|xls|tmp)/',$name,$ext);
			if($ext && $ext[1]) $res=$ext[1];
		}
		return $res;
	}
	private function loadXLSData($file){
		if(!file_exists(EDC_PLUGIN_PATH.'/inc/PHPExcel/PHPExcel/IOFactory.php')){ $this->error='library_missed'; return false; }
		require_once EDC_PLUGIN_PATH.'/inc/PHPExcel/PHPExcel/IOFactory.php';
		if(!class_exists('PHPExcel_IOFactory') || !method_exists('PHPExcel_IOFactory','load')){ $this->error='library_corrupt'; return false; }
		$xls = PHPExcel_IOFactory::load($file);
		$xls->setActiveSheetIndex(0);
		$sheet=$xls->getActiveSheet();
		if(!$sheet) return false;
		$i=0;
		foreach ($sheet->toArray() as $row){
			EDCH::trimArray($row);
			if($this->skip>0 && ++$i<=$this->skip) continue;
			if($row[0]!=''){ $k=is_numeric($k) ? ++$k : 0;	}
			if($row[0]!='') $this->data[$k]['name']=$row[0];
			if($row[1]!='') $this->data[$k]['code']=$row[1];
            if($row[2]!='') $this->data[$k]['type']=$row[2];
            if($row[3]!='') {
                $street_list = explode("\n", $row[3]);
                $this->data[$k]['street_list'] = json_encode($street_list, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT);
            }
		}
	}
	
	private function insertInfo(){
		if(!$this->data || sizeOf($this->data)==0) return false;
		$res=true;
        foreach($this->data as $data){
			EDCH::trimArray($data);
			if($data['name']=='' || !is_numeric($data['code'])) continue;
			if(!isset($data['type']) || $data['type']=='') $data['type']='any';
			$res=EDCH::codes('update','',$data['name'],$data['code'],$data['type'],$data['street_list']);
			if($res===false) break;
		}
		return $res!==false;
	}
	
}
?>