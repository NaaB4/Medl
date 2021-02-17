<?php
class EDC_GOODIES extends EDCH{
	static function items($params=array()){
		self::trimArray($params);
		$cond=array();
		
		if($params['name']!='') $cond[]="g.`name`='".EDCH::DB()->escape($params['name'])."'";
		if($params['search']!='') $cond[]="g.`name` LIKE '%".EDCH::DB()->escape($params['name'])."%'";
		if(is_array($params['ids'])) $cond[]="g.`id` in(".implode(",",$params['ids']).")";
		
		$sort_by='g.`name`';
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
		$query="SELECT * FROM `".self::table('goodies')."` g WHERE 1 ".(sizeOf($cond)==0 ? '' : ' and '.implode(' and ',$cond))." ORDER BY ".$sort_by.$sort_order.' '.$limit;
		$result=EDCH::DB()->get_results($query);
		
		if($limit!=''){
			$query_count="SELECT COUNT(*) as `cnt` FROM `".self::table('goodies')."` g WHERE 1 ".(sizeOf($cond)==0 ? '' : ' and '.implode(' and ',$cond))."";
			$total=EDCH::DB()->get_results($query_count);
			$total=$total[0]->cnt;
		}else $total=sizeOf($result);
		
		return array($result,$total);
	}
	static function item($id){
		$query="SELECT * FROM `".self::table('goodies')."` WHERE `id`='".EDCH::DB()->escape($id)."' LIMIT 1";
		$ex=EDCH::DB()->get_results($query);
		if($ex && !is_wp_error($ex) && sizeOf($ex)==1) return $ex[0];
		return false;
	}
	static function add($data=[]){
		$arr=array();
		$arr['name']=$data['name'];
		$arr['description']=$data['description'];
		$arr['price']=$data['price'];
		$arr['young']=$data['young']==1 ? 1 : 0;
		//var_dump($arr);
		$res=EDCH::DB()->insert(self::table('goodies'),$arr);
		if($res===false) return false;
		return EDCH::DB()->insert_id;
	}
	static function update($id,$data){
		$oid=self::item($id);
		if($oid===false) return false;
		$oid=$oid->id;
		$arr=array();
		$arr['name']=$data['name'];
		$arr['description']=$data['description'];
		$arr['price']=$data['price'];
		$arr['young']=$data['young']==1 ? 1 : 0;
		$res=EDCH::DB()->update(self::table('goodies'),$arr,array('id'=>$oid));
		if($res===false) return false;
		return $oid;
	}
	static function remove($id){
		$ex=self::item($id);
		if($ex===false) return false;
		$result=EDCH::DB()->delete(self::table('goodies'),array('id'=>$ex->id));
		if($result===false) return false;
		return $result;
	}
}
?>