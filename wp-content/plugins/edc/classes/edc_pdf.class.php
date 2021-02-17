<?php
require EDC_PLUGIN_PATH.'/inc/dompdf/lib/html5lib/Parser.php';
require EDC_PLUGIN_PATH.'/inc/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
require EDC_PLUGIN_PATH.'/inc/dompdf/lib/php-svg-lib/src/autoload.php';
require EDC_PLUGIN_PATH.'/inc/dompdf/src/Autoloader.php';
Dompdf\Autoloader::register();
use Dompdf\Dompdf;
class EDC_PDF{
	static function getOrdersPath(){
		return EDC_PLUGIN_PATH.'/orders/';
	}
	static function create($pdf=''){
		if($pdf=='') return false;
		$dompdf = new Dompdf();
		$dompdf->loadHtml($pdf);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		$output=$dompdf->output();
		$path=self::createPath();
		$fname=wp_generate_password(mt_rand(30,50),false).'.pdf';
		$res=file_put_contents($path.$fname, $output);
		if(!$res) return false;
		return $path.$fname;
	}
	static function createPath(){
		$dirname=mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9);
		if(!file_exists(self::getOrdersPath().$dirname)) mkdir(self::getOrdersPath().$dirname);
		return self::getOrdersPath().$dirname.'/';
	}
	static function downloadFromOrder($oid=''){
		if(!is_numeric($oid)) return false;
		if(!$order=EDCH::order('exists',$oid)) return false;
		/*if($total!=1) return false;
		$order=$order[0];*/
		if(!file_exists($order->pdf_path)) return false;
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=' . basename($order->pdf_name.'.pdf'));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($order->pdf_path));
		readfile($order->pdf_path);
		exit;
	}
}
?>