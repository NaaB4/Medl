<?php
class EDCActivate{	
	function __construct(){
		include_once EDC_PLUGIN_PATH .'/inc/db_structure.class.php';
		$struct=new DBStructure();
		$result=$struct->proceedDBStructure();
		if(!$result) die(__('While activation an error occured with creating database structure','edc'));
	}
}

?>