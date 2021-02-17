<?php
class EDC_TARIFFS extends EDCH{
	static $statuses=array('removed'=>0,'active'=>1);
	protected static $_tariffs=array();
	static function exists($tid=''){
		$ex=self::get($tid);
		return $ex ? $ex->id : false;
	}
	static function get($tid=''){
		if(!is_numeric($tid)) return false;
		if(!isset(self::$_tariffs[$tid])){
			list($items,$total)=self::getList(array('id'=>$tid));
			if($total!=1 || sizeOf($items)!=1) return false;
			self::$_tariffs[$tid]=$items[0];
		}
		return self::$_tariffs[$tid];
	}
	static function add($data,$options){
		$t_res=self::proceedType($data['type']);
		if($t_res===false) return false;
		$arr=array();
		$arr['title']=$data['title'];
		$arr['type']=$data['type'];
		$arr['active']=$data['active']==1 ? 1 : 0;
		$arr['code']=$data['code'];
		$arr['price_per_period']=$data['price_per_period'];
		$arr['price_per_kwh']=$data['price_per_kwh'];
		$arr['valid_from']=self::dateToDatabase($data['valid_from']);
		$arr['valid_to']=self::dateToDatabase($data['valid_to']);
		$arr['tariff_image']=is_numeric($data['tariff_image']) ? $data['tariff_image'] : 0;
		$arr['legal_terms']=$data['legal_terms'] ? $data['legal_terms'] : '';
		$arr['terms_and_conditions']=$data['terms_and_conditions'] ? $data['terms_and_conditions'] : 0;
		$arr['status']=isset($data['status']) ? $data['status'] : self::$statuses['active'];
		//var_dump($arr);
		$tid=EDCH::DB()->insert(self::table('tariffs'),$arr);
		if($tid===false) return false;
		$tid=EDCH::DB()->insert_id;
		$res=self::addTariffPostcodes($tid,$data['postcodes']);
		if($res===false) return false;
		$res=self::addTariffExcludePostcodes($tid,$data['exclude_postcodes']);
		if($res===false) return false;
		$res=self::updateTariffOptions($tid,$arr['type'],$options);
		if($res===false) return false;
		return $tid;
	}
	static function update($tid,$data,$options){
		if(self::exists($tid)===false) return false;
		if(isset($data['type'])){
			$t_res=self::proceedType($data['type']);
			if($t_res===false) return false;
		}
		$arr=array();
		if(isset($data['title'])) $arr['title']=$data['title'];
		if(isset($data['type'])) $arr['type']=$data['type'];
		if(isset($data['active'])) $arr['active']=$data['active']==1 ? 1 : 0;
		if(isset($data['code'])) $arr['code']=$data['code'];
		if(isset($data['price_per_period'])) $arr['price_per_period']=$data['price_per_period'];
		if(isset($data['price_per_kwh'])) $arr['price_per_kwh']=$data['price_per_kwh'];
		if(isset($data['valid_from'])) $arr['valid_from']=self::dateToDatabase($data['valid_from']);
		if(isset($data['valid_to'])) $arr['valid_to']=self::dateToDatabase($data['valid_to']);
		if(isset($data['tariff_image'])) $arr['tariff_image']=$data['tariff_image'];
		if(isset($data['legal_terms'])) $arr['legal_terms']=$data['legal_terms'];
		if(isset($data['terms_and_conditions'])) $arr['terms_and_conditions']=$data['terms_and_conditions'];
		$res=EDCH::DB()->update(self::table('tariffs'),$arr,array('id'=>$tid));
		if($res===false) return false;
		$res=self::addTariffPostcodes($tid,$data['postcodes']);
		if($res===false) return false;
		$res=self::addTariffExcludePostcodes($tid,$data['exclude_postcodes']);
		if($res===false) return false;
		$res=self::updateTariffOptions($tid,$arr['type'],$options);
		if($res===false) return false;
		return $tid;
	}
	static function remove($id){
		$ex=self::exists($id);
		if(!is_numeric($ex)) return false;
		$res=EDCH::DB()->update(self::table('tariffs'),array('status'=>0),array('id'=>$ex));
		if($result===false) return false;
		return $result;
	}
	static function addTariffPostcodes($tid,$postcodes){
		if(!is_numeric($tid)) return false;
		EDCH::DB()->delete(self::table('tariff_postcodes'),array('id_tariff'=>$tid));
		$res=true;
		if(is_array($postcodes) && sizeOf($postcodes)>0){
			list($postcodes)=self::codes('get',['ids'=>$postcodes]);
			$arr=array('id_tariff'=>$tid);
			if(is_array($postcodes)) foreach($postcodes as $p){
				$arr['id_postcode']=$p->id;
				$res=EDCH::DB()->insert(self::table('tariff_postcodes'),$arr);
				if($res===false) break;
			}
		}
		return $res;
	}
	static function addTariffExcludePostcodes($tid,$postcodes){
		if(!is_numeric($tid)) return false;
		EDCH::DB()->delete(self::table('tariff_postcodes_exclude'),array('id_tariff'=>$tid));
		$res=true;
		if(is_array($postcodes) && sizeOf($postcodes)>0){
			list($postcodes)=self::codes('get',['ids'=>$postcodes]);
			$arr=array('id_tariff'=>$tid);
			if(is_array($postcodes)) foreach($postcodes as $p){
				$arr['id_postcode']=$p->id;
				$res=EDCH::DB()->insert(self::table('tariff_postcodes_exclude'),$arr);
				if($res===false) break;
			}
		}
		return $res;
	}
	static function updateTariffOptions($tid,$type,$options){
		if(!is_numeric($tid)) return false;
		$tariff_options=self::getOptions($type);
		$res=true;
		foreach($tariff_options as $o){
			if($o['type']=='checkbox' && !$options[$o['key']]) $options[$o['key']]='';
			if(isset($options[$o['key']])){
				$res=self::updateOpt($tid,self::opts('id',$o['key'],'tariff'),$options[$o['key']]);
				if($res===false) break;
			}
		}
		return $res;
	}
	static function addOpt($tid,$oid,$val){		
		if(!is_numeric($tid) || !is_numeric($oid)) return false;
		$arr=array();
		$arr['id_tariff']=$tid;
		$arr['id_option']=$oid;
		$arr['value']=$val;
		$res=EDCH::DB()->insert(self::table('tariff_options'),$arr);
		return $res;
	}
	static function updateOpt($tid,$oid,$val){
		if(!is_numeric($tid) || !is_numeric($oid)) return false;
		$ex=EDCH::DB()->get_results("SELECT * FROM `".self::table('tariff_options')."` WHERE `id_tariff`='".EDCH::DB()->escape($tid)."' and `id_option`='".EDCH::DB()->escape($oid)."' LIMIT 1");
		if(sizeOf($ex)!=1) return self::addOpt($tid,$oid,$val);
		$arr=array();
		$arr['value']=$val;
		$res=EDCH::DB()->update(self::table('tariff_options'),$arr,array('id_tariff'=>$tid,'id_option'=>$oid));
		return $res;
	}
	static function getList($params=array()){
		self::trimArray($params);
		$compare_types=array("=","<>","<",">","<=",">=","in","between","like");
		$cond=array();
		$join=array();
		if(is_numeric($params['id'])) $cond[]="t.`id`='".EDCH::DB()->escape($params['id'])."'";
		$ids=self::getIdsForCond($params['ids']);
		//var_dump($ids);
		if($ids!='') $cond[]="t.`id` in (".$ids.")";
		self::proceedType($params['type']);
		$join[]="LEFT JOIN `".self::table('tariff_options')."` o_order ON (t.`id`=o_order.`id_tariff` and o_order.`id_option`='".self::opts('id','tariff_order','tariff')."')";
		$cond[]="(o_order.`value`>'-1'+0 or o_order.`value`='' or o_order.`value` IS NULL )";
		if($params['type']) $cond[]="t.`type`='".EDCH::DB()->escape($params['type'])."'";
		if($params['removed']!==true) $cond[]="t.`status`<>'".self::$statuses['removed']."'";
		if($params['valid']===true){
			$cond[]=" (t.`valid_from`<='".date('Y.m.d')."' and t.`valid_to`>='".date('Y.m.d'). "'
			or t.`valid_from`='' and t.`valid_to`=''
			or t.`valid_from`<='".date('Y.m.d')."' and t.`valid_to`=''
			or t.`valid_from`='' and t.`valid_to`>='".date('Y.m.d'). "'
			)";
		}
		if($params['search']!='') $cond[]="(t.`title` LIKE '%".$params['search']."%' or t.`code` LIKE '%".$params['search']."%')";
		if(isset($params['postcodes'])){
			if(!is_array($params['postcodes'])) $params['postcodes']=array($params['postcodes']);
			foreach($params['postcodes'] as $k=>$v) if(!is_numeric($v)) unset($params['postcodes'][$k]);
			if(sizeOf($params['postcodes'])>0){
				$cond[]=" (
					(SELECT COUNT(*) FROM `".self::table('tariff_postcodes')."` WHERE `id_tariff`=t.`id`)=0 
					or 
					(SELECT COUNT(*) FROM `".self::table('tariff_postcodes')."` WHERE `id_tariff`=t.`id` and `id_postcode` in (".implode(',',$params['postcodes'])."))>0
				)";
				$cond[]=" (
					(SELECT COUNT(*) FROM `".self::table('tariff_postcodes_exclude')."` WHERE `id_tariff`=t.`id`)=0 
					or 
					(SELECT COUNT(*) FROM `".self::table('tariff_postcodes_exclude')."` WHERE `id_tariff`=t.`id` and `id_postcode` in (".implode(',',$params['postcodes'])."))=0
				)";
			}
		}
		if(is_array($params['options']) && sizeOf($params['options'])>0){
			foreach($params['options'] as $i=>$o){
				$options_aliases[$o['name']]="o_".$i;
				$join[]="LEFT JOIN `".self::table('tariff_options')."` o_".$i." ON (t.`id`=o_".$i.".`id_tariff` and o_".$i.".`id_option`='".self::opts('id',$o['name'],'tariff')."')";
				$compare=isset($o['compare']) && in_array($o['compare'],$compare_types) ? $o['compare'] : "=";
				if($compare=="in"){
					$in="";
					foreach($o['value'] as $val) $in.=",'".EDCH::DB()->escape($val)."'";
					$cond[]="o_".$i.".`value` in (".($in=="" ? "''" : substr($in,1)).")";
				}elseif(is_array($o['value'])){
					$subconds="";
					foreach($o['value'] as $val){
						if($compare=="between"){
							if(!is_array($val)) $val=array_map("trim",explode(",",$val));
							if(sizeOf($val)<2) $subconds.=" or (o_".$i.".`value`='".EDCH::DB()->escape($val[0])."')";
							else{
								$b_from=isset($val['from']) ? $val['from'] : $val[0];
								$b_to=isset($val['to']) ? $val['to'] : $val[1];
								$subconds.=" or (o_".$i.".`value`>='".EDCH::DB()->escape($b_from)."'".($o['compare_type']=="numeric" ? "+0" : "")." and o_".$i.".`value`<='".EDCH::DB()->escape($b_to)."'".($o['compare_type']=="numeric" ? "+0" : "").")";
							}
						}
						elseif($compare=="like") $subconds.=" or (o_".$i.".`value` LIKE '%".EDCH::DB()->escape($val)."%')";
						else $subconds.=" or (o_".$i.".`value`".$compare."'".EDCH::DB()->escape($val)."'".($o['compare_type']=="numeric" ? "+0" : "").")";
					}
					$cond[]="(".substr($subconds,4).")";
				}
				elseif($compare=="like") $cond[]="o_".$i.".`value` LIKE '%".EDCH::DB()->escape($o['value'])."%'";
				else{
					$condstr="(";
					$condstr.="o_".$i.".`value`".$compare."'".EDCH::DB()->escape($o['value'])."'".($o['compare_type']=="numeric" ? "+0" : "");
					if($o['strict']===false){
						$condstr.=" or o_".$i.".`value`=''";
						$condstr.=" or o_".$i.".`value` IS NULL";
					}
					$condstr.=')';
					$cond[]=$condstr;
				}
			}
		}
		$sort_by='o_order.`value` DESC, t.`title`';
		$sort_order=' ASC';
		if(isset($params['sort_by'])){
			$sort_by=$params['sort_by'];
			$sort_order='';
		}
		$limit='';
		if(is_numeric($params['per_page'])){
			$per_page=$params['per_page'];
			$page=is_numeric($params['page']) ? $params['page'] : 1;
			$limit='LIMIT '.(($page-1)*$per_page).','.$per_page;
		}
		$query="SELECT * FROM `".self::table('tariffs')."` t".(sizeOf($join)>0 ? " ".implode(" ",$join) : '')." WHERE 1 ".(sizeOf($cond)==0 ? '' : ' and '.implode(' and ',$cond))." ORDER BY ".$sort_by.$sort_order.' '.$limit;
		//var_dump($query);
		$result=EDCH::DB()->get_results($query);
		if($params['get_options']!==false || $params['get_postcodes']!==false) foreach($result as $k=>$res){
			if($params['get_options']!==false) $result[$k]->options=self::getTariffOptions($res->id);
			if($params['get_postcodes']!==false) $result[$k]->postcodes=self::getTariffPostCodes($res->id);
			if($params['get_excluded_postcodes']!==false) $result[$k]->excluded_postcodes=self::getTariffExcludedPostCodes($res->id);
			if($params['get_prices']!==false) $result[$k]->prices=self::getPrices($res->id,$result[$k]);
		}
		if($limit!=''){
			$query_count="SELECT COUNT(*) as `cnt` FROM `".self::table('tariffs')."` t".(sizeOf($join)>0 ? " ".implode(" ",$join) : '')." WHERE 1 ".(sizeOf($cond)==0 ? '' : ' and '.implode(' and ',$cond))."";
			//var_dump($query_count);
			$total=EDCH::DB()->get_results($query_count);
			//var_dump($total);
			$total=$total[0]->cnt;
		}else $total=sizeOf($result);
		
		return array($result,$total);
	}
	static function getOptions($type){
		$options=array();
		$options[]=array(
			'key'=>'tariff_subtitle',
			'name'=>__('Subtitle','edc'),
			'type'=>'text',
		);
		if($type==1){
			$options[]=array(
				'key'=>'min_gas_delivery_per_year',
				'name'=>__('Min. power','edc'),
				'type'=>'text',
				'placeholder'=>'',
			);
			$options[]=array(
				'key'=>'max_gas_delivery_per_year',
				'name'=>__('Max. power','edc'),
				'type'=>'text',
				'placeholder'=>'',
			);
			
			apply_filters('edc_gas_tariff_options',$options);
		}elseif($type==2){
			$options[]=array(
				'key'=>'min_electricity_delivery_per_year',
				'name'=>__('Min. power','edc'),
				'type'=>'text',
				'placeholder'=>'',
			);
			$options[]=array(
				'key'=>'max_electricity_delivery_per_year',
				'name'=>__('Max. power','edc'),
				'type'=>'text',
				'placeholder'=>'',
			);
			
			apply_filters('edc_gas_tariff_options',$options);
		}elseif($type==3){
			$options[]=array(
				'key'=>'min_gas_delivery_per_year',
				'name'=>__('Min. power','edc'),
				'type'=>'text',
				'placeholder'=>'',
			);
			$options[]=array(
				'key'=>'max_gas_delivery_per_year',
				'name'=>__('Max. power','edc'),
				'type'=>'text',
				'placeholder'=>'',
			);
			$options[]=array(
				'key'=>'min_electricity_delivery_per_year',
				'name'=>__('Min. power','edc'),
				'type'=>'text',
				'placeholder'=>'',
			);
			$options[]=array(
				'key'=>'max_electricity_delivery_per_year',
				'name'=>__('Max. power','edc'),
				'type'=>'text',
				'placeholder'=>'',
			);
			
			apply_filters('edc_combi_tariff_options',$options);			
		}
		
		$options[]=array(
			'key'=>'tariff_description',
			'name'=>__('Description','edc'),
			'type'=>'textarea',
		);
		$options[]=array(
			'key'=>'tariff_clients_type',
			'name'=>__('For clients','edc'),
			'type'=>'radio',
			'items'=>array(
				array('value'=>1,'name'=>__('Private','edc')),
				array('value'=>2,'name'=>__('Business','edc')),
			),
			'default'=>1,
			'visible'=>false,
		);
		$options[]=array(
			'key'=>'tariff_base_price_type',
			'name'=>__('Price period','edc'),
			'type'=>'select',
			'items'=>array(
				array('value'=>'monthly','name'=>__('Per month','edc')),
				array('value'=>'yearly','name'=>__('Per year','edc')),
			),
			'default'=>'monthly',
			'visible'=>false,
		);
		$options[]=array(
			'key'=>'tariff_calculation_formula',
			'name'=>__('Calculation formula','edc'),
			'type'=>'select',
			'items'=>array(
				array('value'=>'static','name'=>__('Static base price','edc')),
				array('value'=>'dynamic','name'=>__('Dynamic base price','edc')),
			),
			'default'=>'static',
			'visible'=>false,
		);
		$options=apply_filters('edc_tariff_options',$options);
		return $options;
	}
	static function getTariffOptions($tid=''){
		if(!is_numeric($tid)) return false;
		$items=EDCH::DB()->get_results("SELECT `o`.`name`, `to`.`value` FROM `".self::table('options')."` as `o`,`".self::table('tariff_options')."` as `to` WHERE `to`.`id_tariff`='".EDCH::DB()->escape($tid)."' and `o`.`id`=`to`.`id_option` and `o`.`type`='1'");
		$result=array();
		foreach($items as $item) $result[$item->name]=$item->value;
		return $result;
	}
	static function getTariffPostCodes($tid=''){
		if(!is_numeric($tid)) return false;
		$items=EDCH::DB()->get_results("SELECT `id_postcode` FROM `".self::table('tariff_postcodes')."` WHERE `id_tariff`='".EDCH::DB()->escape($tid)."'");
		$result=array();
		foreach($items as $item) $result[]=$item->id_postcode;
		return $result;
	}
	static function getTariffExcludedPostCodes($tid=''){
		if(!is_numeric($tid)) return false;
		$items=EDCH::DB()->get_results("SELECT `id_postcode` FROM `".self::table('tariff_postcodes_exclude')."` WHERE `id_tariff`='".EDCH::DB()->escape($tid)."'");
		$result=array();
		foreach($items as $item) $result[]=$item->id_postcode;
		return $result;
	}
	static function getPrices($tid='',$data=null){
		if(!is_numeric($tid)) return 0;
		if(!$data) $data=self::get($tid);
		if(!isset($data->options)) $data->options=self::getTariffOptions($tid);
		$args=[
			'price_type'=>$data->options['tariff_base_price_type']=='yearly' ? 'per_year' : 'per_month',
			'formula'=>$data->options['tariff_calculation_formula']=='dynamic' ? 'dynamic' : 'static',
			'clients_type'=>$data->options['tariff_clients_type'],
			'tariff_type'=>$data->type,
		];
		if($args['tariff_type']!=3){
			$result=self::getPrice($data->price_per_period,$data->price_per_kwh,$args);
		}else{
			$result=self::getPrice([$data->options['price_per_period'],$data->price_per_period],[$data->options['price_per_kwh'],$data->price_per_kwh],$args);			
		}
		return $result;
	}
	static function getImage($tid='',$data=null){
		if(!is_numeric($tid)) return '';
		if(!$data) $data=self::exists($tid);
		if(!is_numeric($data->tariff_image) || $data->tariff_image==0) return '';
		$img=wp_get_attachment_image_url($data->tariff_image,'full');		
		return !$img || is_wp_error($img) ? '' : $img;
	}
	static function getImagePath($tid='',$data=null){
		if(!is_numeric($tid)) return '';
		if(!$data) $data=self::exists($tid);
		if(!is_numeric($data->tariff_image) || $data->tariff_image==0) return '';
		$img=get_attached_file($data->tariff_image);		
		return !$img || is_wp_error($img) ? '' : $img;
	}
}
?>