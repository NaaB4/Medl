<?php
class EDCReset{	
	function __construct(){}
	function resetTariffsData(){
		EDCH::DB()->query('TRUNCATE '.EDCH::table('tariffs'));
		EDCH::DB()->query('ALTER TABLE '.EDCH::table('tariffs').' auto_increment=1');
		EDCH::DB()->query('TRUNCATE '.EDCH::table('tariff_postcodes'));
		EDCH::DB()->query('TRUNCATE '.EDCH::table('tariff_postcodes_exclude'));
		EDCH::DB()->query('TRUNCATE '.EDCH::table('tariff_options'));
		return true;
	}
	function resetPostcodes(){
		EDCH::DB()->query('TRUNCATE '.EDCH::table('postcodes'));
		EDCH::DB()->query('ALTER TABLE '.EDCH::table('postcodes').' auto_increment=1');
		EDCH::DB()->query('TRUNCATE '.EDCH::table('tariff_postcodes'));
		EDCH::DB()->query('TRUNCATE '.EDCH::table('tariff_postcodes_exclude'));
		return true;
	}
	function resetOrders(){
		EDCH::DB()->query('TRUNCATE '.EDCH::table('orders'));
		EDCH::DB()->query('ALTER TABLE '.EDCH::table('orders').' auto_increment=1');
		EDCH::DB()->query('TRUNCATE '.EDCH::table('order_options'));
		$this->removeFolder(EDC_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'orders');
		mkdir(EDC_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'orders');
		return true;
	}
	function removeFolder($dir){
		$files=array_diff(scandir($dir),array('.','..'));
		foreach($files as $file){
			$path=$dir. DIRECTORY_SEPARATOR . $file;
			if(is_dir($path)){
				$this->removeFolder($path);
			}else unlink($path);
		}
		return rmdir($dir);
	}
	function setDefaults(){
		
		return true;
	}
}

?>