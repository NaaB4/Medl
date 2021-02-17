<?php
class EDC_PRICE extends EDCH{
	static function getPrice($ppr,$ppk,$data=[]){
		$prices=[
			'base_per_year'=>0,
			'base_per_month'=>0,
			'work_per_kwh'=>(is_numeric($ppk) ? $ppk : 0),
			'work_total'=>0,
			'total_per_year'=>0,
			'total_per_month'=>0,
		];
		$prices['base_per_year']=$data['price_type']=='per_year' ? $ppr : $ppr*12;
		$prices['base_per_month']=$prices['base_per_year']/12;
		
		if(class_exists('EDC')){
			$edc=EDC::getInstance();
			if(($edc instanceof EDC) && is_numeric($edc->getData('annual_consumption'))){
				$ac=floatval($edc->getData('annual_consumption'));
			}
		}elseif(is_numeric($data['annual_consumption'])) $ac=floatval($data['annual_consumption']);
		if(is_numeric($ac) && $ac>0){
			$prices['work_total']=$prices['work_per_kwh']*$ac/100.00;
			if($data['formula']=='dynamic'){
				$prices['total_per_year']=$prices['base_per_year']*$ac+$prices['work_total'];
			}else{
				$prices['total_per_year']=$prices['base_per_year']+$prices['work_total'];
			}
			$prices['total_per_year']=apply_filters('edc_total_price_before_nds',$prices['total_per_year'],$ppr,$ppk,$data);
		}
		foreach($prices as $k=>$v) if($v>0){
			self::addNDS($prices[$k],$data['clients_type']);
		}
		$prices['total_per_year']=apply_filters('edc_total_price',$prices['total_per_year'],$ppr,$ppk,$data);
		if($prices['total_per_year']>0) $prices['total_per_month']=$prices['total_per_year']/12;

		return $prices;
	}
	static function addNDS(&$price=0,$type=''){
		$use_nds=self::opts('get','edc_use_nds','settings');
		if(self::isNot($use_nds)) return $price;
		$nds=self::opts('get','edc_nds_value','settings');
		if(!is_numeric($nds) || $nds<0) return $price;
		if($use_nds=='both'){
			$price*=$nds;
		}elseif($use_nds=='private' && $type==1){
			$price*=$nds;
		}elseif($use_nds=='business' && $type==2){
			$price*=$nds;
		}elseif($type==$use_nds){
			$price*=$nds;
		}
		return $price;
	}
	static function formatPrice($price=0){
		if(!is_numeric($price)) return $price;
		$format=self::opts('get','edc_price_format','settings');
		$separators=array('dot'=>'.','comma'=>',','space'=>'&nbsp;','default'=>'');
		list($dec,$dec_p,$th_sep)=explode('_',$format);
		if(!is_numeric($dec) || $dec<0) $dec=2;
		if(!isset($th_sep) || !isset($separators[$th_sep])) $th_sep='space';
		if(!isset($dec_p) || !isset($separators[$dec_p])) $dec_p='comma';
		//var_dump($dec);
		//var_dump($separators[$dec_p]);
		//var_dump($separators[$th_sep]);
		$formatted=number_format($price,$dec,$separators[$dec_p],$separators[$th_sep]);		
		return $formatted;
	}
	static function addCurrency($text='',$sym='',$above=false){
		if($text=='') return '';
		if($sym=='') $sym='&euro;';		
		$html='';
		$sym='<span class="edc_currency above">'.$sym.'</span>';
		$html=($above ? $sym.'&nbsp;' : '').$text.(!$above ? '&nbsp;'.$sym : '');
		return $html;
	}
	static function displayPrice($price='',$class='',$sym='',$above=false){
		if(!is_numeric($price)) return '';
		$html='<span '.($class ? 'class="'.$class.'"' : '').'>'.self::formatPrice($price).'</span>';
		/*if($sym!=''){
			$sym='<span class="edc_currency above">'.$sym.'</span>';
			$html=($above ? $sym.'&nbsp;' : '').$html.(!$above ? '&nbsp;'.$sym : '');
		}else */$html=self::addCurrency($html,$sym,$above);
		return '<span class="edc_price">'.$html.'</span>';
	}
}
?>