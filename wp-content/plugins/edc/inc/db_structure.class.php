<?php
class DBStructure{
	protected $_db=null;
	function __construct(){
		$this->_db=EDCH::DB();
	}
	function EDCDBStructure(){
		$structure=array(
			EDCH::table('options')=>array(
				'fields'=>array(				
					array('name'=>'id', 'type'=>'int(11)', 'collation'=>NULL, 'default'=>NULL, 'null'=>false, 'ai'=>true,),
					array('name'=>'type', 'type'=>'tinyint(4)', 'collation'=>NULL, 'default'=>NULL, 'null'=>false, 'ai'=>false,),
					array('name'=>'name', 'type'=>'varchar(255)', 'collation'=>EDCH::DB()->get_charset_collate(), 'default'=>'', 'null'=>false, 'ai'=>false,),
					array('name'=>'default', 'type'=>'text', 'collation'=>EDCH::DB()->get_charset_collate(), 'default'=>'', 'null'=>false, 'ai'=>false,),
					array('name'=>'value', 'type'=>'text', 'collation'=>EDCH::DB()->get_charset_collate(), 'default'=>'', 'null'=>false, 'ai'=>false,),
				),
				'indexes'=>array(
					'PRIMARY'=>array('name'=>'PRIMARY','type'=>'primary','fields'=>array('id')),				
				),
			),
			EDCH::table('orders')=>array(
				'fields'=>array(				
					array('name'=>'id', 'type'=>'int(11)', 'collation'=>NULL, 'default'=>NULL, 'null'=>false, 'ai'=>true,),
					array('name'=>'pdf_path', 'type'=>'varchar(255)', 'collation'=>EDCH::DB()->get_charset_collate(), 'default'=>'', 'null'=>false, 'ai'=>false,),
					array('name'=>'pdf_name', 'type'=>'varchar(255)', 'collation'=>EDCH::DB()->get_charset_collate(), 'default'=>'', 'null'=>false, 'ai'=>false,),
					array('name'=>'title', 'type'=>'varchar(255)', 'collation'=>EDCH::DB()->get_charset_collate(), 'default'=>'', 'null'=>false, 'ai'=>false,),
					array('name'=>'status', 'type'=>'tinyint(10)', 'collation'=>NULL, 'default'=>NULL, 'null'=>false, 'ai'=>false,),
					array('name'=>'date', 'type'=>'varchar(16)', 'collation'=>EDCH::DB()->get_charset_collate(), 'default'=>'', 'null'=>false, 'ai'=>false,),
					array('name'=>'id_tariff', 'type'=>'int(11)', 'collation'=>NULL, 'default'=>NULL, 'null'=>false, 'ai'=>false,),
					array('name'=>'price_per_period', 'type'=>'double', 'collation'=>NULL, 'default'=>NULL, 'null'=>false, 'ai'=>false,),
					array('name'=>'price_per_kwh', 'type'=>'double', 'collation'=>NULL, 'default'=>NULL, 'null'=>false, 'ai'=>false,),
					//array('name'=>'delivery_price', 'type'=>'double', 'collation'=>NULL, 'default'=>NULL, 'null'=>false, 'ai'=>false,),
					//array('name'=>'html_path', 'type'=>'varchar(255)', 'collation'=>EDCH::DB()->get_charset_collate(), 'default'=>'', 'null'=>false, 'ai'=>false,),
					//array('name'=>'html_name', 'type'=>'varchar(255)', 'collation'=>EDCH::DB()->get_charset_collate(), 'default'=>'', 'null'=>false, 'ai'=>false,),
					array('name'=>'total_price', 'type'=>'double', 'collation'=>NULL, 'default'=>NULL, 'null'=>false, 'ai'=>false,),
				),
				'indexes'=>array(
					'PRIMARY'=>array('name'=>'PRIMARY','type'=>'primary','fields'=>array('id')),
					'id_tariff'=>array('name'=>'id_tariff','type'=>'index','fields'=>array('id_tariff')),
					'status'=>array('name'=>'status','type'=>'index','fields'=>array('status')),
					'date'=>array('name'=>'date','type'=>'index','fields'=>array('date')),
				),
			),
			EDCH::table('order_options')=>array(
				'fields'=>array(				
					array('name'=>'id_order', 'type'=>'int(11)', 'collation'=>NULL, 'default'=>NULL, 'null'=>false, 'ai'=>false,),
					array('name'=>'id_option', 'type'=>'int(11)', 'collation'=>NULL, 'default'=>NULL, 'null'=>false, 'ai'=>false,),
					array('name'=>'value', 'type'=>'text', 'collation'=>EDCH::DB()->get_charset_collate(), 'default'=>'', 'null'=>false, 'ai'=>false,),
				),
				'indexes'=>array(
					'PRIMARY'=>array('name'=>'PRIMARY','type'=>'primary','fields'=>array('id_order','id_option')),				
				),
			),
			EDCH::table('postcodes')=>array(
				'fields'=>array(				
					array('name'=>'id', 'type'=>'int(11)', 'collation'=>NULL, 'default'=>NULL, 'null'=>false, 'ai'=>true,),
					array('name'=>'code', 'type'=>'int(11)', 'collation'=>NULL, 'default'=>NULL, 'null'=>false, 'ai'=>false,),
					array('name'=>'name', 'type'=>'varchar(255)', 'collation'=>EDCH::DB()->get_charset_collate(), 'default'=>'', 'null'=>false, 'ai'=>false,),
					array('name'=>'type', 'type'=>'tinyint(4)', 'collation'=>NULL, 'default'=>NULL, 'null'=>false, 'ai'=>false,),
				),
				'indexes'=>array(
					'PRIMARY'=>array('name'=>'PRIMARY','type'=>'primary','fields'=>array('id')),
				),
			),
			EDCH::table('tariffs')=>array(
				'fields'=>array(
					array('name'=>'id', 'type'=>'int(11)', 'collation'=>NULL, 'default'=>NULL, 'null'=>false, 'ai'=>true,),
					array('name'=>'title', 'type'=>'varchar(255)', 'collation'=>EDCH::DB()->get_charset_collate(), 'default'=>'', 'null'=>false, 'ai'=>false,),
					array('name'=>'type', 'type'=>'tinyint(4)', 'collation'=>NULL, 'default'=>NULL, 'null'=>false, 'ai'=>false,),
					array('name'=>'code', 'type'=>'varchar(255)', 'collation'=>EDCH::DB()->get_charset_collate(), 'default'=>'', 'null'=>false, 'ai'=>false,),
					array('name'=>'price_per_period', 'type'=>'double', 'collation'=>NULL, 'default'=>NULL, 'null'=>false, 'ai'=>false,),
					array('name'=>'price_per_kwh', 'type'=>'double', 'collation'=>NULL, 'default'=>NULL, 'null'=>false, 'ai'=>false,),
					//array('name'=>'delivery_price', 'type'=>'double', 'collation'=>NULL, 'default'=>NULL, 'null'=>false, 'ai'=>false,),
					array('name'=>'valid_from', 'type'=>'varchar(16)', 'collation'=>EDCH::DB()->get_charset_collate(), 'default'=>'', 'null'=>false, 'ai'=>false,),
					array('name'=>'valid_to', 'type'=>'varchar(16)', 'collation'=>EDCH::DB()->get_charset_collate(), 'default'=>'', 'null'=>false, 'ai'=>false,),
					//array('name'=>'linked_tariff', 'type'=>'int(11)', 'collation'=>NULL, 'default'=>NULL, 'null'=>false, 'ai'=>false,),
					array('name'=>'tariff_image', 'type'=>'int(11)', 'collation'=>NULL, 'default'=>NULL, 'null'=>false, 'ai'=>false,),
					array('name'=>'legal_terms', 'type'=>'int(11)', 'collation'=>NULL, 'default'=>NULL, 'null'=>false, 'ai'=>false,),
					//array('name'=>'work_term', 'type'=>'text', 'collation'=>EDCH::DB()->get_charset_collate(), 'default'=>'', 'null'=>false, 'ai'=>false,),
					//array('name'=>'notice_period', 'type'=>'text', 'collation'=>EDCH::DB()->get_charset_collate(), 'default'=>'', 'null'=>false, 'ai'=>false,),
					array('name'=>'terms_and_conditions', 'type'=>'text', 'collation'=>EDCH::DB()->get_charset_collate(), 'default'=>'', 'null'=>false, 'ai'=>false,),
					array('name'=>'active', 'type'=>'tinyint(4)', 'collation'=>NULL, 'default'=>NULL, 'null'=>false, 'ai'=>false,),
					array('name'=>'status', 'type'=>'tinyint(10)', 'collation'=>NULL, 'default'=>NULL, 'null'=>false, 'ai'=>false,),
				),
				'indexes'=>array(
					'PRIMARY'=>array('name'=>'PRIMARY','type'=>'primary','fields'=>array('id')),
					//'linked_tariff'=>array('name'=>'linked_tariff','type'=>'index','fields'=>array('linked_tariff')),
					'valid_from'=>array('name'=>'valid_from','type'=>'index','fields'=>array('valid_from','valid_to')),
					'valid_to'=>array('name'=>'valid_to','type'=>'index','fields'=>array('valid_to')),
				),
			),
			EDCH::table('tariff_options')=>array(
				'fields'=>array(				
					array('name'=>'id_tariff', 'type'=>'int(11)', 'collation'=>NULL, 'default'=>NULL, 'null'=>false, 'ai'=>false,),
					array('name'=>'id_option', 'type'=>'int(11)', 'collation'=>NULL, 'default'=>NULL, 'null'=>false, 'ai'=>false,),
					array('name'=>'value', 'type'=>'text', 'collation'=>EDCH::DB()->get_charset_collate(), 'default'=>'', 'null'=>false, 'ai'=>false,),
				),
				'indexes'=>array(
					'PRIMARY'=>array('name'=>'PRIMARY','type'=>'primary','fields'=>array('id_tariff','id_option')),				
				),
			),
			EDCH::table('tariff_postcodes')=>array(
				'fields'=>array(				
					array('name'=>'id_tariff', 'type'=>'int(11)', 'collation'=>NULL, 'default'=>NULL, 'null'=>false, 'ai'=>false,),
					array('name'=>'id_postcode', 'type'=>'int(11)', 'collation'=>NULL, 'default'=>NULL, 'null'=>false, 'ai'=>false,),
				),
				'indexes'=>array(
					'PRIMARY'=>array('name'=>'PRIMARY','type'=>'primary','fields'=>array('id_tariff','id_postcode')),				
				),
			),
			EDCH::table('tariff_postcodes_exclude')=>array(
				'fields'=>array(				
					array('name'=>'id_tariff', 'type'=>'int(11)', 'collation'=>NULL, 'default'=>NULL, 'null'=>false, 'ai'=>false,),
					array('name'=>'id_postcode', 'type'=>'int(11)', 'collation'=>NULL, 'default'=>NULL, 'null'=>false, 'ai'=>false,),
				),
				'indexes'=>array(
					'PRIMARY'=>array('name'=>'PRIMARY','type'=>'primary','fields'=>array('id_tariff','id_postcode')),				
				),
			),
		);
		return apply_filters('edc_db_structure',$structure);
	}
	function removeDBStructure(){
		// TODO add DB structure removing
	}
	function proceedDBStructure(){
		$db_structure=$this->EDCDBStructure();
		$res=true;
		foreach($db_structure as $table=>$data){
			$res_cur=$this->syncTableStructure($table,$data);
			$res=$res && $res_cur;
		}
		return $res;
	}
	function getDbStructure(){
		$db=array("tables"=>array());
		$tables=$this->_db->get_results("SHOW TABLES");
		foreach($tables as $t){
			list($key,$table)=each($t);
			$db['tables'][$table]=$this->getTableStructure($table);
		}
		return $db;
	}

	function getTableStructure($table_name){
		$fields=$this->_db->get_results("SHOW full  COLUMNS FROM `".$table_name."`");
		if($fields===false || sizeOf($fields)==0) return false;
		$table=array("fields"=>array(),"indexes"=>array());
		foreach($fields as $f){
			$field=array();
			$field['name']=$f->Field;
			$field['type']=$f->Type;
			$field['collation']=$f->Collation;
			$field['default']=$f->Default;
			$field['null']=($f->Null!="NO");
			//$field['key']=$f->Key;
			$field['ai']=$f->Extra=="auto_increment";
			$table['fields'][]=$field;
		}
        $indexes=$this->_db->get_results("SHOW INDEX FROM `".$table_name."`");
        foreach($indexes as $i){
        	if(isset($table['indexes'][$i->Key_name])){
        		$table['indexes'][$i->Key_name]['fields'][]=$i->Column_name;
        	}else{
				$index=array();
				$index['name']=$i->Key_name;
				$index['type']=$i->Key_name=="PRIMARY" ? "primary" : ($i->Non_unique=="1"  ? "index" : "unique");
				$index['fields'][]=$i->Column_name;
				$table['indexes'][$i->Key_name]=$index;
        	}
        }
        return $table;
	}

	function findField($fields,$name){
		foreach($fields as $i=>$f) if($f['name']==$name) return $i;
		return false;
	}

	function syncTableStructure($table,$data){
		$current=$this->getTableStructure($table);
		if($current===false){ /* no such table - create new one*/
			return $this->createTable($table,$data);
		}
		//drop fields
		foreach($current['fields'] as $f){
            if($this->findField($data['fields'],$f['name'])===false){
            	$query="ALTER TABLE `".$this->_db->escape($table)."` DROP `".$this->_db->escape($f['name'])."`";
            	//echo $query."\n";
            	$this->_db->query($query);
            }
		}
		//add new fields
		foreach($data['fields'] as $f){
			$cur_i=$this->findField($current['fields'],$f['name']);
            if($cur_i===false){
            	$query="ALTER TABLE `".$this->_db->escape($table)."` ADD `".$this->_db->escape($f['name'])."` ".$f['type'].($f['null'] ? "" : " NOT NULL ").($f['default']!=="" && $f['default']!==false && $f['default']!==null ? " DEFAULT '".$this->_db->escape($f['default'])."'" : "").($f['ai'] ? "  AUTO_INCREMENT" : "");
            	//echo $query."\n";
            	$this->_db->query($query);
            }else{ /* update field */
				if($f!=$current['fields'][$cur_i]){
					$query="ALTER TABLE `".$this->_db->escape($table)."` CHANGE `".$this->_db->escape($f['name'])."` `".$this->_db->escape($f['name'])."` ".$f['type'].($f['null'] ? "" : " NOT NULL ").($f['default']!=="" && $f['default']!==false && $f['default']!==null ? " DEFAULT '".$this->_db->escape($f['default'])."'" : "").($f['ai'] ? "  AUTO_INCREMENT" : "");
					//echo $query."\n";
					$this->_db->query($query);
				}
			}
		}
		//drop indexes
		foreach($current['indexes'] as $index=>$i){
			if(!isset($data['indexes'][$index])){
				$query="ALTER TABLE `".$this->_db->escape($table)."` DROP ".($i['type']=="primary" ? "PRIMARY KEY" : "INDEX")." `".$this->_db->escape($index)."`";
				//echo $query."\n";
            	$this->_db->query($query);
			}
		}
		//add indexes
		foreach($data['indexes'] as $index=>$i){
			if(!isset($current['indexes'][$index])){
				$query="ALTER TABLE `".$this->_db->escape($table)."` ADD ".($i['type']=="primary" ? "PRIMARY KEY" : ($i['type']=="unique" ? "UNIQUE" : "INDEX"))." `".$this->_db->escape($index)."` (`".implode("`,`",$i['fields'])."`)";
				//echo $query."\n";
            	$this->_db->query($query);
			}else{ /* update index */
				if($i!=$current['indexes'][$index]){
					if ($i['type']=="primary")  $query="ALTER TABLE `".$this->_db->escape($table)."` DROP PRIMARY KEY , ADD PRIMARY KEY (`".implode("`,`",$i['fields'])."`)";
					else $query="ALTER TABLE `".$this->_db->escape($table)."` DROP INDEX `".$this->_db->escape($index)."` , ADD ".($i['type']=="unique" ? "UNIQUE" : "INDEX")." `".$this->_db->escape($index)."` (`".implode("`,`",$i['fields'])."`)";
					echo $query."\n";
					$this->_db->query($query);
				}
			}
		}

        return true;
	}

	function syncDbStructure($data){
		$db=$this->getDbStructure();
		foreach($db['tables'] as $table=>$table_structure){
			if(!isset($data['tables'][$table])) $this->dropTable($table);
		}

		foreach($data['tables'] as $table=>$table_structure){
			if(!isset($db['table'][$table])) $this->createTable($table,$table_structure); //create new table
			else $this->syncTableStructure($table,$table_structure);
		}
	}

	function dropTable($table){
		//we will not remove any tables
	}

	function createTable($table,$data){
		$fields=array();
		$indexes=array();

		foreach($data['fields'] as $f){
			$fields[]="`".$this->_db->escape($f['name'])."` ".$f['type'].($f['null'] ? "" : " NOT NULL ").($f['default']!=="" && $f['default']!==false && $f['default']!==null ? " DEFAULT '".$this->_db->escape($f['default'])."'" : "").($f['ai'] ? "  AUTO_INCREMENT" : "");
		}
		foreach($data['indexes'] as $index=>$i){
			if($i['type']=="primary") $type="PRIMARY KEY";
			elseif($i['type']=="unique") $type="UNIQUE KEY";
			else $type="KEY";
			$indexes[]=$type." ".($i['type']=="primary" ? "" : "`".$this->_db->escape($index)."`")." (`".implode("`,`",$i['fields'])."`)";
		}

  		$query="CREATE TABLE IF NOT EXISTS `".$table."` (";
  		$query.=implode(",\n",$fields);
  		if(sizeOf($indexes)>0) $query.=",\n".implode(",\n",$indexes);
		$query.="\n) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		$res=$this->_db->query($query);
		return $res;
	}

	function syncData($table,$unique,$data){
        $res=true;
        foreach($data as $r){
            //check if record exists
            $cond="";
            if(sizeOf($unique)>0){
            	foreach ($unique as $field_name) $cond.=" and `".$this->_db->escape($field_name)."`='".$this->_db->escape($r[$field_name])."'";
            }else{
            	foreach ($r as $field_name=>$value) $cond.=" and `".$this->_db->escape($field_name)."`='".$this->_db->escape($value)."'";
            }
            if($cond!="") $cond=substr($cond,4);
           	$query="select * from `".$this->_db->escape($table)."` where ".$cond." limit 1";
           	$c=$this->_db->get_results($query);
           	//insert data if it does not exist
           	if(sizeOf($c)==0){
           		$res_cur=$this->_db->insert($table,$r);
           		if ($res_cur===false) $res=false;
           	}
        }
        return $res;
	}

	function syncDbData($data){
		foreach($data['data'] as $table=>$d){
			$res_cur=$this->syncData($table,$d['unique'],$d['data']);
			$res=$res && $res_cur;
		}
		return $res;
	}
}

?>