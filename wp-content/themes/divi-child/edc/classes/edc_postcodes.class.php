<?php
class EDC_POSTCODES extends EDCH{
	static function get($params=array()){
		self::trimArray($params);
		$cond=array();
		if($params['code']!=''){
			if(!is_array($params['code'])) $cond[]="pc.`code`='".EDCH::DB()->escape($params['code'])."'";
			else $cond[]="pc.`code` IN ('".implode(',',$params['code'])."')";
		}
		$ids=self::getIdsForCond($params['ids']);
		if($ids!='') $cond[]="pc.`id` in (".$ids.")";
		if($params['name']!='') $cond[]="pc.`name` LIKE '%".EDCH::DB()->escape($params['name'])."%'";
		$t_res=self::proceedType($params['type']);
		if($t_res!=false) $cond[]="(pc.`type`='".EDCH::DB()->escape($params['type'])."' or pc.`type`='".self::$types['any']."')";
		
		$sort_by='pc.`name`';
		$sort_order=' ASC';
		if(isset($params['sort_by'])){
			$sort_by=$params['sort_by'];
			$sort_order='';
		}
		$limit='';
		if(is_numeric($params['per_page'])){
			$per_page=$params['per_page'];
			$page=is_numeric($params['page']) ? $params['page'] : 1;
			$limit='LIMIT '.(($params['page']-1)*$per_page).','.$per_page;
		}
		$query="SELECT * FROM `".self::table('postcodes')."` pc WHERE 1 ".(sizeOf($cond)==0 ? '' : ' and '.implode(' and ',$cond))." ORDER BY ".$sort_by.$sort_order.' '.$limit;
		$result=EDCH::DB()->get_results($query);
		
		if($limit!=''){
			$query_count="SELECT COUNT(*) as `cnt` FROM `".self::table('postcodes')."` pc WHERE 1 ".(sizeOf($cond)==0 ? '' : ' and '.implode(' and ',$cond))."";
			$total=EDCH::DB()->get_results($query_count);
			$total=$total[0]->cnt;
		}else $total=sizeOf($result);
		
		return array($result,$total);
	}
	static function exists($id,$name='',$code='',$type=''){
		if(is_numeric($id)){
			$query="SELECT `id` FROM `".self::table('postcodes')."` WHERE `id`='".EDCH::DB()->escape($id)."' LIMIT 1";
		}else{
			$t_res=self::proceedType($type);
			if($t_res===false) return false;
			$query="SELECT `id` FROM `".self::table('postcodes')."` WHERE (`type`='".EDCH::DB()->escape($type)."' or `type`='".self::$types['any']."') and `name`='".EDCH::DB()->escape($name)."' and `code`='".EDCH::DB()->escape($code)."' LIMIT 1";
		}
		$ex=EDCH::DB()->get_results($query);
		if($ex && !is_wp_error($ex) && sizeOf($ex)==1) return $ex[0]->id;
		return false;
	}
	static function add($name,$code,$type,$street_list){
		$t_res=self::proceedType($type);
		if($t_res===false) return false;
		$ex=self::exists('',$name,$code,$type);
		if(is_numeric($ex)) return $ex;
		$type=self::check($name,$code,$type);
		$arr=array();
		$arr['type']=$type;
		$arr['name']=$name;
		$arr['code']=$code;
        $arr['street_list']=$street_list;
		$res=EDCH::DB()->insert(self::table('postcodes'),$arr);
		if($res===false) return false;
		return EDCH::DB()->insert_id;
	}
	static function update($id,$name,$code,$type, $street_list){
	    $t_res=self::proceedType($type);
		if($t_res===false) return false;
		if(!is_numeric($id)){
			$oid=self::add($name,$code,$type,$street_list);
		}else $oid=self::exists($id);
		if($oid===false) return false;
		$arr=array();
		$arr['name']=$name;
		$arr['code']=$code;
		$arr['type']=$type;
		$arr['street_list']=$street_list;
		$res=EDCH::DB()->update(self::table('postcodes'),$arr,array('id'=>$oid));
		if($res===false) return false;
		return $oid;
	}
	static function check($name,$code,$type){
	    $t_res=self::proceedType($type);
		if($t_res===false) return false;
		$ex_el=self::exists('',$name,$code,self::$types['electricity']);
		$ex_gas=self::exists('',$name,$code,self::$types['gas']);
		$ex_any=self::exists('',$name,$code,self::$types['any']);
		if($type==self::$types['any']){
			self::remove($ex_el);
			self::remove($ex_gas);
		}elseif($type==self::$types['gas'] && is_numeric($ex_el)){
			self::remove($ex_el);
			self::remove($ex_gas);
			$type=self::$types['any'];
		}elseif($type==self::$types['electricity'] && is_numeric($ex_gas)){
			self::remove($ex_el);
			self::remove($ex_gas);
			$type=self::$types['any'];
		}
		if(is_numeric($ex_any)){
			self::remove($ex_el);
			self::remove($ex_gas);
			$type=self::$types['any'];
		}
		return $type;
	}
	static function remove($id){
		$ex=self::exists($id);
		if(!is_numeric($ex)) return false;
		$result=EDCH::DB()->delete(self::table('postcodes'),array('id'=>$ex));		
		if($result===false) return false;
		return $result;
	}
	static function getType($type){
		if(!is_numeric($type)) return $type;
		foreach(self::$types as $k=>$t) if($type==$t){
			if($k=='any') $type=__('Any','edc');
			elseif($k=='gas') $type=__('Gas','edc');
			elseif($k=='electricity') $type=__('Electricity','edc');
			elseif($k=='combi') $type=__('Combi','medl');
		}
		return $type;
	}
}
?>