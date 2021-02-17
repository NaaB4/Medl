<?php
/*
	Register autoload function, that will search for classes
*/
spl_autoload_register('edcAutoload');

function edcAutoload($className){
	$fileName=strtolower($className).'.class.php';
	$theme_path=get_stylesheet_directory()."/edc/classes/".$fileName;
	if(file_exists($theme_path)){
		include_once $theme_path;
	}else{
		$plugin_path=EDC_PLUGIN_PATH."/classes/".$fileName;
		if(file_exists($plugin_path)) include_once $plugin_path;
	}
}
/* END autoloader */
if(is_admin()){ include_once EDC_PLUGIN_PATH . '/admin/admin.php'; }
else{ include_once EDC_PLUGIN_PATH . '/edc.class.php'; }

?>