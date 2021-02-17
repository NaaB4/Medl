<?php
class TariffsImporter{	
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
		if(!$fext  && strpos($this->file,'/tmp/')===false){ $this->error='incorrect_extension'; return false; }
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
		$fields=[
			'title',
			'option_tariff_subtitle',
			'type',
			'active',
			'code',
			'price_per_kwh',
			'price_per_period',
			'goodies',
			'valid_from',
			'valid_to',
			'postcodes',
			'exclude_postcodes',
			'option_tariff_description',
			'option_tariff_features',
			'option_edc_available_lower_30',
			'option_edc_landing_tariff',
			'option_tariff_price_details',
			'option_min_delivery_per_year',
			'option_max_delivery_per_year',
			'option_tariff_agb_link',
			'option_tariff_price_link',
			'option_landing_page_uniq',
			'option_product_id',
			'option_product_preiseid',
			'option_tariff_order',
		];
		
		foreach($sheet->toArray() as $row){
			EDCH::trimArray($row);
			if($this->skip>0 && ++$i<=$this->skip) continue;
			if($row[0]!=''){ $k=is_numeric($k) ? ++$k : 0;	}
			foreach($fields as $n=>$f) if($row[$n]!=''){				
				if($f=='valid_from' || $f=='valid_to'){
					$date=array_map('intval',explode('/',$row[$n]));
					$date=($date[0]>10 ? $date[0] : '0'.$date[0]).'.'.($date[1]>10 ? $date[1] : '0'.$date[1]).'.'.$date[2];
					$this->data[$k][$f]=EDCH::dateToDatabase($date);
				}elseif($f=='postcodes'){
					$row[$n]=str_replace('.',',',$row[$n]);
					$this->data[$k][$f]=is_array($this->data[$k][$f]) ? array_merge($this->data[$k][$f],explode(',',$row[$n])) : explode(',',$row[$n]);
				}elseif($f=='exclude_postcodes'){
					$row[$n]=str_replace('.',',',$row[$n]);
					$this->data[$k][$f]=is_array($this->data[$k][$f]) ? array_merge($this->data[$k][$f],explode(',',$row[$n])) : explode(',',$row[$n]);
				}elseif(substr($f,0,7)=='option_'){
					if($f=='option_edc_available_lower_30' || $f=='option_edc_landing_tariff'){
						$this->data[$k][$f]=EDCH::is($row[$n]);
					}elseif($f=='option_min_delivery_per_year'){
						if($this->data[$k]['type']=='strom' || $this->data[$k]['type']=='electricity'){
							$this->data[$k]['option_min_electricity_delivery_per_year']=str_replace(',','.',$row[$n]);
						}elseif($this->data[$k]['type']=='gas'){
							$this->data[$k]['option_min_gas_delivery_per_year']=str_replace(',','.',$row[$n]);							
						}else{							
							list($this->data[$k]['option_min_electricity_delivery_per_year'],$this->data[$k]['option_min_gas_delivery_per_year'])=explode('/',str_replace(',','.',$row[$n]));
						}
					}elseif($f=='option_max_delivery_per_year'){
						if($this->data[$k]['type']=='strom' || $this->data[$k]['type']=='electricity'){
							$this->data[$k]['option_max_electricity_delivery_per_year']=str_replace(',','.',$row[$n]);
						}elseif($this->data[$k]['type']=='gas'){
							$this->data[$k]['option_max_gas_delivery_per_year']=str_replace(',','.',$row[$n]);;						
						}else{							
							list($this->data[$k]['option_max_electricity_delivery_per_year'],$this->data[$k]['option_max_gas_delivery_per_year'])=explode('/',str_replace(',','.',$row[$n]));
						}
					}else{
						$this->data[$k][$f]=$row[$n];
					}
				}elseif($f=='price_per_kwh'){
					if($this->data[$k]['type']=='strom' || $this->data[$k]['type']=='electricity'){
						$this->data[$k]['price_per_kwh']=str_replace(',','.',$row[$n]);
					}elseif($this->data[$k]['type']=='gas'){
						$this->data[$k]['price_per_kwh']=str_replace(',','.',$row[$n]);							
					}else{							
						list($this->data[$k]['price_per_kwh'],$this->data[$k]['option_price_per_kwh'])=explode('/',str_replace(',','.',$row[$n]));
					}
				}elseif($f=='price_per_period'){
					if($this->data[$k]['type']=='strom' || $this->data[$k]['type']=='electricity'){
						$this->data[$k]['price_per_period']=str_replace(',','.',$row[$n]);
					}elseif($this->data[$k]['type']=='gas'){
						$this->data[$k]['price_per_period']=str_replace(',','.',$row[$n]);							
					}else{
						list($this->data[$k]['price_per_period'],$this->data[$k]['option_price_per_period'])=explode('/',str_replace(',','.',$row[$n]));
					}
				}else $this->data[$k][$f]=$row[$n];
			}
			if($this->data[$k]['type']=='strom') $this->data[$k]['type']='electricity';
		}
		//var_dump($this->data);
		//die();
	}
	
	private function insertInfo(){
		if(!$this->data || sizeOf($this->data)==0) return false;
		$res=true;
		foreach($this->data as $data){
			EDCH::trimArray($data);
			if($data['title']=='') continue;
			$d=$o=[];
			foreach($data as $k=>$v){
				if(substr($k,0,7)=='option_'){
					$o[substr($k,7)]=is_array($v) ? json_encode($v) : (is_bool($v) ? ($v ? 1 : 0) : $v);
				}else{
					if($k=='goodies'){
						$o['tariff_goodies']=[];
						$goodies=explode(';',$v);
						foreach($goodies as $g){
							list($gds,$ttl)=EDC_GOODIES::items(['name'=>$g]);
							foreach($gds as $gd) $o['tariff_goodies'][]=$gd->id;
						}
						$o['tariff_goodies']=sizeOf($o['tariff_goodies'])>0 ? json_encode($o['tariff_goodies']) : '';
					}else $d[$k]=$v;
				}
			}
			$res=EDCH::trfs('add',$d,$o);
			if($res===false) break;
		}
		return $res!==false;
	}
}
?>