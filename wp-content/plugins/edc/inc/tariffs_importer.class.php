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
		$fields=apply_filters('edc_tariff_importer_options',array(
			'title',
			'type',
			'active',
			'code',
			'price_per_kwh',
			'price_per_period',
			'delivery_price',
			'valid_from',
			'valid_to',
			'linked_tariff',
			'tariff_image',
			'legal_terms',
			'work_term',
			'notice_period',
			'terms_and_conditions',
			'postcodes',
		));
		$options=apply_filters('edc_tariff_type_importer_options',array(
			'any'=>array(
				sizeOf($fields)=>'tariff_subtitle',
				sizeOf($fields)+1=>'tariff_description',
				sizeOf($fields)+2=>'tariff_clients_type',
				//sizeOf($fields)+3=>'tariff_monthly',
			),
			'gas'=>array(
				sizeOf($fields)+3=>'min_gas_delivery_per_year',
				sizeOf($fields)+4=>'max_gas_delivery_per_year',
			),
			'electricity'=>array(			
				sizeOf($fields)+3=>'min_electricity_delivery_per_year',
				sizeOf($fields)+4=>'max_electricity_delivery_per_year',
			),
		));
		
		foreach ($sheet->toArray() as $row){
			EDCH::trimArray($row);
			if($this->skip>0 && ++$i<=$this->skip) continue;
			if($row[0]!=''){ $k=is_numeric($k) ? ++$k : 0;	}
			foreach($fields as $n=>$f) if($row[$n]!=''){				
				if($f=='valid_from' || $f=='valid_to'){
					if($row[$n]!=''){
						$date=explode('/',$row[$n]);
						if(sizeOf($date)==3) $row[$n]=($date[0]>10 ? $date[0] : '0'.$date[0]).'.'.($date[1]>10 ? $date[1] : '0'.$date[1]).'.'.$date[2];
					}
				}
				if($f=='postcodes'){
					$row[$n]=str_replace('.',',',$row[$n]);
					$this->data[$k][$f]=is_array($this->data[$k][$f]) ? array_merge($this->data[$k][$f],explode(',',$row[$n])) : explode(',',$row[$n]);
				}else $this->data[$k][$f]=$row[$n];
			}
			if($this->data[$k]['type']=='strom') $this->data[$k]['type']='electricity';
			foreach($options['any'] as $n=>$opt) if($row[$n]!=''){
				$this->data[$k]['option_'.$opt]=$row[$n];
			}
			if(is_array($options[$this->data[$k]['type']])) foreach($options[$this->data[$k]['type']] as $n=>$opt) if($row[$n]!=''){
				$this->data[$k]['option_'.$opt]=$row[$n];
			}
		}
	}
	
	private function insertInfo(){
		if(!$this->data || sizeOf($this->data)==0) return false;
		$res=true;
		$images=apply_filters('edc_tariff_importer_image_keys',array('tariff_image'));
		foreach($this->data as $data){
			EDCH::trimArray($data);
			if($data['title']=='' || ($data['type']!='gas' && $data['type']!='electricity')) continue;
			$options=array();
			foreach($data as $k=>$v){
				if(substr($k,0,7)=='option_'){
					$options[substr($k,7)]=is_array($v) ? json_encode($v) : $v;
					unset($data[$k]);
				}
			}
			foreach($images as $img) if(isset($data[$img]) && !is_numeric($data[$img])){
				$data[$img]=$this->getImageFromUrl($data[$img]);
				if(!is_numeric($data[$img])) $data[$img]='';
			}
			$res=EDCH::trfs('add',$data,$options);
			if($res===false) break;
		}
		return $res!==false;
	}
	private function getImageFromUrl($url,$timeout_seconds=5){
		require_once(ABSPATH.'wp-admin/includes/file.php');
		require_once(ABSPATH.'wp-admin/includes/media.php');
		require_once(ABSPATH.'wp-admin/includes/image.php');
		$type='';
		$temp_file=download_url($url,$timeout_seconds);
		if(mb_strpos($url,'.jpg',0,'UTF-8') || mb_strpos($url,'.jpeg',0,'UTF-8')) $type='image/jpeg';
		elseif(mb_strpos($url,'.png',0,'UTF-8')) $type='image/png';
		elseif(mb_strpos($url,'.gif',0,'UTF-8')) $type='image/gif';
		if($type=='') return false;
		if(!is_wp_error($temp_file)){
			$file=array(
				'name'     => basename($url),
				'type'     => $type,
				'tmp_name' => $temp_file,
				'error'    => 0,
				'size'     => filesize($temp_file),
			);
			$overrides=array(
				'test_form' => false,
				'test_size' => true,
			);
			$results=wp_handle_sideload($file,$overrides);
			
			if(!empty($results['error'])) return false;
			else{
				$file=$results['file'];
				$url=$results['url'];
				$type=$results['type']; 
			}
			$title=preg_replace('/\.[^.]+$/', '', basename($file));
			$content='';
			if($image_meta=wp_read_image_metadata($file)){
				if(trim($image_meta['title']) && !is_numeric(sanitize_title($image_meta['title'])))	$title = $image_meta['title'];
				if(trim($image_meta['caption'])) $content = $image_meta['caption'];
			}
			if(isset($desc)) $title = $desc;		
			
			$attachment = array(
				'post_mime_type' => $type,
				'guid' => $url,
				'post_parent' => 0,
				'post_title' => $title,
				'post_content' => $content,
			);
			unset($attachment['ID']);

			$id=wp_insert_attachment($attachment,$file,0);
			if(!is_wp_error($id)) wp_update_attachment_metadata($id,wp_generate_attachment_metadata($id,$file));
			else return false;
			return $id;
		}
		return false;
	}
}
?>