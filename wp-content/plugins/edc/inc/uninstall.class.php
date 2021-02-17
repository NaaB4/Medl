<?php
class EDCUninstall{
	function __construct(){
		include_once EDC_PLUGIN_PATH .'/inc/db_structure.class.php';
		$struct=new DBStructure();
		$struct->removeDBStructure();
	}
}

?>