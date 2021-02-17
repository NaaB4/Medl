<?php
class EDC_PRICE extends EDCH{
	static function getPrice($ppr,$ppk,$data=[]){
		if($data['tariff_type']!=3){
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
			$discount=false;
			if(class_exists('EDC')){
				$edc=EDC::getInstance();
				if(($edc instanceof EDC) && is_numeric($edc->getData('annual_consumption'))){
					$ac=floatval($edc->getData('annual_consumption'));
				}
				$discount=$edc->getData('discount');
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
			if(self::is($discount) && $prices['total_per_year']-120>0){ $prices['total_per_year']-=120; }
			if($prices['total_per_year']>0) $prices['total_per_month']=$prices['total_per_year']/12;
		}else{
			$prices=[
				'base_per_year_el'=>0,
				'base_per_year_gas'=>0,
				'base_per_month_el'=>0,
				'base_per_month_gas'=>0,
				'work_per_kwh_el'=>(is_numeric($ppk[0]) ? $ppk[0] : 0),
				'work_per_kwh_gas'=>(is_numeric($ppk[1]) ? $ppk[1] : 0),
				'work_total_el'=>0,
				'work_total_gas'=>0,
				'total_per_year_el'=>0,
				'total_per_year_gas'=>0,
				'total_per_month_el'=>0,
				'total_per_month_gas'=>0,
				'total_per_year'=>0,
				'total_per_month'=>0,
			];
			$prices['base_per_year_el']=$data['price_type']=='per_year' ? $ppr[0] : $ppr[0]*12;
			$prices['base_per_year_gas']=$data['price_type']=='per_year' ? $ppr[1] : $ppr[1]*12;
			$prices['base_per_month_el']=$prices['base_per_year_el']/12;
			$prices['base_per_month_gas']=$prices['base_per_year_gas']/12;
			
			$discount=false;
			if(class_exists('EDC')){
				$edc=EDC_Extension::getInstance();
				if(($edc instanceof EDC_Extension) && is_numeric($edc->getData('annual_consumption_el'))){
					$ac_el=floatval($edc->getData('annual_consumption_el'));
					$ac_gas=floatval($edc->getData('annual_consumption_gas'));
				}
				$discount=$edc->getData('discount');
			}elseif(is_numeric($data['annual_consumption_el'])){
				$ac_el=floatval($data['annual_consumption_el']);
				$ac_gas=floatval($data['annual_consumption_gas']);
			}
			if(is_numeric($ac_el) && $ac_el>0){
				$prices['work_total_el']=$prices['work_per_kwh_el']*$ac_el/100.00;
				if($data['formula']=='dynamic'){
					$prices['total_per_year_el']=$prices['base_per_year_el']*$ac+$prices['work_total_el'];
				}else{
					$prices['total_per_year_el']=$prices['base_per_year_el']+$prices['work_total_el'];
				}
			}
			if(is_numeric($ac_gas) && $ac_gas>0){
				$prices['work_total_gas']=$prices['work_per_kwh_gas']*$ac_gas/100.00;
				if($data['formula']=='dynamic'){
					$prices['total_per_year_gas']=$prices['base_per_year_gas']*$ac+$prices['work_total_gas'];
				}else{
					$prices['total_per_year_gas']=$prices['base_per_year_gas']+$prices['work_total_gas'];
				}
			}
			foreach($prices as $k=>$v) if($v>0){
				self::addNDS($prices[$k],$data['clients_type']);
			}
			if($prices['total_per_year_el']>0) $prices['total_per_month_el']=$prices['total_per_year_el']/12;			
			if($prices['total_per_year_gas']>0) $prices['total_per_month_gas']=$prices['total_per_year_gas']/12;
			$prices['total_per_year']=$prices['total_per_year_el']+$prices['total_per_year_gas'];
			if(self::is($discount) && $prices['total_per_year']-120>0){ $prices['total_per_year']-=120; }
			$prices['total_per_month']=$prices['total_per_month_el']+$prices['total_per_month_gas'];
		}

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
		if(round($price)==$price) $dec=0;
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
		if($sym!==false) $html=self::addCurrency($html,$sym,$above);
		return '<span class="edc_price">'.$html.'</span>';
	}
}
?>