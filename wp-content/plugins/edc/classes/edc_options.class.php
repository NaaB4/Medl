<?php
class EDC_OPTIONS extends EDCH{
	static $types=array('settings'=>0,'tariff'=>1,'order'=>2);
	protected static $_options_cache;
	static function proceedType(&$type,$name=false){
		if(is_numeric($type)){
			if(!in_array($type,self::$types)) return false;
			return $type;
		}
		if(!isset(self::$types[$type])) return false;
		$type=self::$types[$type];
		return self::$types[$type];
	}
	static function add($name,$type,$default=''){
		$t_res=self::proceedType($type);
		if($t_res===false) return false;
		$ex=self::exists($name,$type,false);
		if($ex) return $ex->id;
		$arr=array();
		$arr['type']=$type;
		$arr['name']=$name;
		$arr['default']=$default;
		$res=EDCH::DB()->insert(self::table('options'),$arr);
		if($res===false) return false;
		$arr['id']=EDCH::DB()->insert_id;
		return $arr;
	}
	static function update($name,$type,$default){
		$t_res=self::proceedType($type);
		if($t_res===false) return false;
		$oid=self::add($name,$type,$default);
		if($oid===false) return false;
		$arr=array();
		$arr['default']=$default;
		$res=EDCH::DB()->update(self::table('options'),$arr,array('id'=>$oid));
		return $res;
	}
	static function getValue($name,$type,$empty=''){
		$t_res=self::proceedType($type);
		if($t_res===false) return false;
		$res=EDCH::DB()->get_results("SELECT * FROM `".self::table('options')."` WHERE `type`='".EDCH::DB()->escape($type)."' and `name`='".EDCH::DB()->escape($name)."' LIMIT 1");
		if(sizeOf($res)!=1) return $empty;
		return $res[0]->default=='' ? $empty : $res[0]->default;
	}
	static function get($name,$type){
		$ex=self::exists($name,$type);
		if(is_numeric($ex)) $id=$ex;
		else $id=is_array($ex) ? $ex['id'] : $ex->id;
		return $id;
	}
	static function exists($name,$type,$add=true){
		$sign=$name.'_'.$type;
		if(!isset(self::$_options_cache[$sign])){
			$t_res=self::proceedType($type);
			if($t_res===false) return false;
			$res=EDCH::DB()->get_results("SELECT * FROM `".self::table('options')."` WHERE `type`='".EDCH::DB()->escape($type)."' and `name`='".EDCH::DB()->escape($name)."' LIMIT 1");
			if(sizeOf($res)!=1){
				if(!$add) return false;
				self::$_options_cache[$sign]=self::add($name,$type,'');
			}else{
				self::$_options_cache[$sign]=$res[0];
			}
		}
		return self::$_options_cache[$sign];
	}
}
?>