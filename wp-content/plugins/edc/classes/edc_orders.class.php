<?php
class EDC_ORDERS extends EDCH{
	static $statuses=array('removed'=>0,'not_confirmed'=>1,'confirmed'=>2,'default'=>1);
	static $filespath='/orders';
	protected static $_exists=array();
	static function exists($oid=''){
		if(!is_numeric($oid)) return false;
		if(!isset($_exists[$oid])){
			list($items,$total)=self::getList(array('id'=>$oid));
			if($total!=1 || sizeOf($items)!=1) return false;
			$_exists[$oid]=$items[0];
		}
		return $_exists[$oid];
	}
	static function add($data,$options=array()){
		$tariff=self::trfs('get',$data['id_tariff']);
		if(!$tariff) return false;
		$os=EDCH::DB()->get_results("SELECT `id` FROM `".self::table('orders')."` o ORDER BY `id` DESC LIMIT 1");
		$new_oid=sizeOf($os)!=1 ? 1 : ++$os[0]->id;
		$arr=array();
		$arr['title']=isset($data['title']) ? $data['title'] : __('Order','edc').' #'.$new_oid;
		$arr['status']=is_numeric($data['status']) ? $data['status'] : self::$statuses['default'];
		$arr['date']=isset($data['date']) ? $data['date'] : date('Y.m.d H:i');
		$arr['id_tariff']=$tariff->id;
		$arr['price_per_period']=$tariff->prices['base_per_year'];
		$arr['price_per_kwh']=$tariff->prices['work_per_kwh'];
		$arr['total_price']=$tariff->prices['total_per_year'];
		$arr['pdf_path']=$data['pdf_path'];
		$arr['pdf_name']=isset($data['pdf_name']) ? $data['pdf_name'] : 'Order #'.$new_oid;
		$res=EDCH::DB()->insert(self::table('orders'),$arr);
		if($res===false) return false;
		$options['tariff_prices']=json_encode($tariff->prices);
		$oid=EDCH::DB()->insert_id;
		$res=self::updateOrderOptions($oid,$options);
		if($res===false) return false;
		return $oid;
	}
	static function update($oid,$data,$options){
		if(self::exists($oid)===false) return false;
		$arr=array();
		if(isset($data['title'])) $arr['title']=$data['title'];
		if(is_numeric($data['status'])) $arr['status']=$data['status'];
		$res=EDCH::DB()->update(self::table('orders'),$arr,array('id'=>$oid));
		if($res===false) return false;
		$res=self::updateOrderOptions($oid,$options);
		if($res===false) return false;
		return $oid;
	}
	static function remove($id){
		$ex=self::exists($id);
		if(!is_numeric($ex)) return false;
		$res=EDCH::DB()->update(self::table('orders'),array('status'=>0),array('id'=>$ex));
		if($result===false) return false;
		return $result;
	}
	static function updateOrderOptions($oid,$options){
		if(!is_numeric($oid)) return false;
		$order_options=self::getFields();
		$res=true;
		foreach($order_options as $group=>$opts) foreach($opts['items'] as $key=>$val){
			if(isset($options[$key])){
				$res=self::updateOpt($oid,self::opts('id',$key,'order'),$options[$key]);
				if($res===false) break;
			}
		}
		return $res;
	}
	static function addOpt($id,$oid,$val){		
		if(!is_numeric($id) || !is_numeric($oid)) return false;
		$arr=array();
		$arr['id_order']=$id;
		$arr['id_option']=$oid;
		$arr['value']=is_array($val) ? json_encode($val) : $val;
		$res=EDCH::DB()->insert(self::table('order_options'),$arr);
		return $res;
	}
	static function updateOpt($id,$oid,$val){
		if(!is_numeric($id) || !is_numeric($oid)) return false;
		$ex=EDCH::DB()->get_results("SELECT * FROM `".self::table('order_options')."` WHERE `id_order`='".EDCH::DB()->escape($id)."' and `id_option`='".EDCH::DB()->escape($oid)."' LIMIT 1");
		if(sizeOf($ex)!=1) return self::addOpt($id,$oid,$val);
		$arr=array();
		$arr['value']=is_array($val) ? json_encode($val) : $val;
		$res=EDCH::DB()->update(self::table('order_options'),$arr,array('id_order'=>$id,'id_option'=>$oid));
		return $res;
	}
	static function getList($params=array()){		
		self::trimArray($params);
		$cond=array();
		if(is_numeric($params['id'])) $cond[]="o.`id`='".EDCH::DB()->escape($params['id'])."'";
		if($params['removed']!==true) $cond[]="o.`status`<>'".self::$statuses['removed']."'";
		if($params['search']!='') $cond[]="(o.`title` LIKE '%".$params['search']."%' or o.`id`='".$params['search']."')";
		$sort_by='o.`id`';
		$sort_order=' DESC';
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
		$query="SELECT o.*,t.`title` as `tariff_name` FROM `".self::table('orders')."` o,`".self::table('tariffs')."` t WHERE t.`id`=o.`id_tariff` ".(sizeOf($cond)==0 ? '' : ' and '.implode(' and ',$cond))." ORDER BY ".$sort_by.$sort_order.' '.$limit;
		$result=EDCH::DB()->get_results($query);
		if($params['get_options']!==false) foreach($result as $k=>$res){
			$result[$k]->tariff=self::trfs('get',$res->id_tariff);
			$result[$k]->options=self::getOrderOptions($res->id);
		}
		if($limit!=''){
			$query_count="SELECT COUNT(*) as `cnt` FROM `".self::table('orders')."` o WHERE 1 ".(sizeOf($cond)==0 ? '' : ' and '.implode(' and ',$cond))."";
			$total=EDCH::DB()->get_results($query_count);
			$total=$total[0]->cnt;
		}else $total=sizeOf($result);
		
		return array($result,$total);
	}
	static function getOrderOptions($oid=''){
		if(!is_numeric($oid)) return false;
		$items=EDCH::DB()->get_results("SELECT `o`.`name`, `oo`.`value` FROM `".self::table('options')."` as `o`,`".self::table('order_options')."` as `oo` WHERE `oo`.`id_order`='".EDCH::DB()->escape($oid)."' and `o`.`id`=`oo`.`id_option` and `o`.`type`='2'");
		$result=array();
		foreach($items as $item) $result[$item->name]=$item->value;
		return $result;
	}
	static function createPDF($data=array()){
		$prices=self::trfs('price',$data['tariff']->id,$data['tariff']);
		$logo=self::opts('get','edc_pdf_logo','settings');
		if($logo) $logo=get_attached_file($logo);
		if(!$logo || is_wp_error($logo)) $logo='';
		$args=[
			'tariff'=>$data['tariff'],
			'prices'=>$prices,
			'form_data'=>$data['form_data'],
			'logo'=>$logo,
		];
		return self::pdf('create',self::loadTemplate('pdf',$args));
	}
	static function send($oid=''){
		if(!is_numeric($oid)) return false;
		$order=self::exists($oid);
		$subject=self::opts('get','edc_mail_subject','settings');
		$from=self::opts('get','edc_mail_from','settings');
		$to=self::opts('get','edc_mail_to','settings');
		$mailto=$order->options['edc_email'];
		$message=self::opts('get','edc_mail_text','settings');
		if($message=='') $message=__('Summary of your order in the attached PDF file.','edc');
		$attachments=$self_attachments=array();
		$pdf_buf=self::createTempPDF($order->pdf_path,$order->pdf_name);
		if($pdf_buf) $attachments[]=$pdf_buf;
		$self_attachments=$attachments;
		$tids=explode(',',$order->tariff->terms_and_conditions);
		foreach($tids as $k=>$tid) if(!is_numeric($tid)) unset($tids[$k]);
		if(sizeOf($tids)>0){
			foreach($tids as $tid){
				$att=get_attached_file($tid);
				if($att && !is_wp_error($att)) {
					$attachments[]=$att;
				}
			}
		}
		$headers=[];
		if(is_email($from)) $headers[]='From: '.$from.'<'.$from.'>';
		elseif($from!='') $headers[]='From: '.$from;
		self::setReplaces($subject,$oid);
		self::setReplaces($message,$oid);
		$result=wp_mail($mailto,apply_filters('edc_email_subject',$subject),apply_filters('edc_email_message',$message),$headers,$attachments);
		if(is_email($to)){
			$self_subject=self::opts('get','edc_self_mail_subject','settings');
			if($self_subject=='') $self_subject=sprintf(__('New order on the site %s','edc'),get_bloginfo('description'));
			$self_message=self::opts('get','edc_self_mail_message','settings');
			if($self_message=='') $self_message=' ';
			self::setReplaces($self_subject,$oid);
			self::setReplaces($self_message,$oid);
			$result=wp_mail($to,apply_filters('edc_self_email_subject',$self_subject,$oid),apply_filters('edc_self_email_message',$self_message,$oid),$headers,$self_attachments);
		}
		unlink($pdf_buf);
		return $result;
	}
	static function setReplaces(&$text='',$oid){
		if($text=='') return '';
		$order=self::exists($oid);
		$replacements=['{Current date}','{Gender}','{Name}','{Last name}','{Order ID}','{Tariff name}'];
		$tariff=EDCH::trfs('get',$order->id_tariff);
		$name=$tariff && isset($tariff->title) ? $tariff->title : '';
		$gender=$order->options['edc_anrede']=='m' ? __('Man','edc') : __('Woman','edc');
		$replaces=[date('d.m.Y'),$gender,$order->options['edc_first_name'],$order->options['edc_name'],'#'.$oid,$name];
		$text=str_replace($replacements,$replaces,$text);
	}
	static function createTempPDF($path='',$name=''){
		if(!$path || !$name) return false;
		$new_fname=preg_replace('/(.*)\.pdf/',$name.'.pdf',basename($path));
		$new_path=str_replace(basename($path),$new_fname,$path);
		copy($path,$new_path);
		return $new_path;
	}
	static function getFields(){
		$fields=array();
		$fields['main']=self::getOrderMainFields();
		$fields['sepa_transfer']=self::getOrderSepaTransferFields();
		$fields['billing']=self::getOrderBillingFields();
		$fields['current']=self::getOrderCurrentFields();
		return apply_filters('edc_order_fields',$fields);
	}
	static function getOrderMainFields(){
		$fields=array(
			'title'=>__('Main order fields','edc'),
			'items'=>array(
				'edc_anrede'=>array(
					'name'=>__('Gender','edc'),
					'required'=>true,
					'values'=>array(
						'm'=>__('Man','edc'),
						'w'=>__('Woman','edc'),
					),
					'field'=>'option',
				),
				'edc_first_name'=>array(
					'name'=>__('Name','edc'),
					'required'=>true,
					'field'=>'option',
				),
				'edc_name'=>array(
					'name'=>__('Last name','edc'),
					'required'=>true,
					'field'=>'option',
				),
				'edc_date_of_birth'=>array(
					'name'=>__('Birth date','edc'),
					'required'=>true,
					'field'=>'option',
				),
				'edc_street'=>array(
					'name'=>__('Street','edc'),
					'required'=>true,
					'field'=>'option',
				),
				'edc_house'=>array(
					'name'=>__('House','edc'),
					'required'=>true,
					'field'=>'option',
				),
				'edc_postal_code'=>array(
					'name'=>__('Postal code','edc'),
					'required'=>true,
					'field'=>'option',
				),
				'edc_location'=>array(
					'name'=>__('Location','edc'),
					'required'=>true,
					'field'=>'option',
				),
				'edc_phone'=>array(
					'name'=>__('Phone','edc'),
					'field'=>'option',
				),
				'edc_email'=>array(
					'name'=>__('E-mail','edc'),
					'required'=>true,
					'validate'=>'email',
					'field'=>'option',
				),
				'edc_email_confirm'=>array(
					'field'=>false,
					'required'=>true,
					'validate'=>'email',
				),
			),
		);
		return apply_filters('edc_order_main_fields',$fields);
	}
	static function getOrderSepaTransferFields(){
		$fields=array(
			'title'=>__('Sepa and transfer fields','edc'),
			'items'=>array(
				'edc_sepa_direct_debit'=>array(
					'name'=>__('SEPA direct debit mandate','edc'),
					'field'=>'option',
					'type'=>'checkbox',
				),
				'edc_transfer'=>array(
					'name'=>__('Transfer','edc'),
					'field'=>'option',
					'type'=>'checkbox',
				),
				'edc_electriс_date'=>array(
					'name'=>__('Desired start of delivery / on entry: Date of key transfer','edc'),
					'field'=>'option',
				),
				'edc_holder'=>array(
					'name'=>__('Account holder (first name / surname)','edc'),
					'field'=>'option',
				),
				'edc_street_debit'=>array(
					'name'=>__('Street','edc'),
					'field'=>'option',
				),
				'edc_house_debit'=>array(
					'name'=>__('House number','edc'),
					'field'=>'option',
				),
				'edc_postalcode_debit'=>array(
					'name'=>__('Postal code','edc'),
					'field'=>'option',
				),
				'edc_location_debit'=>array(
					'name'=>__('Location','edc'),
					'field'=>'option',
				),
				'edc_credit'=>array(
					'name'=>__('Bank','edc'),
					'field'=>'option',
				),
				'edc_IBAN'=>array(
					'name'=>__('IBAN','edc'),
					'field'=>'option',
				),
				'edc_BIC'=>array(
					'name'=>__('BIC','edc'),
					'field'=>'option',
				),
			)
		);
		return apply_filters('edc_order_sepa_transfer_fields',$fields);
	}
	static function getOrderBillingFields(){
		$fields=array(
			'title'=>__('Different billing address','edc'),
			'items'=>array(
				'edc_other_debit'=>array(
					'name'=>__('Different billing address','edc'),
					'field'=>'option',
					'type'=>'checkbox',
				),
				'edc_etc_gender'=>array(
					'name'=>__('Gender','edc'),
					//'required'=>true,
					'values'=>array(
						'm'=>__('Man','edc'),
						'w'=>__('Woman','edc'),
					),
					'field'=>'option',
				),
				'edc_etc_firstname'=>array(
					'name'=>__('Name','edc'),
					//'required'=>true,
					'field'=>'option',
				),
				'edc_etc_name'=>array(
					'name'=>__('Last name','edc'),
					//'required'=>true,
					'field'=>'option',
				),
				'edc_etc_street'=>array(
					'name'=>__('Street','edc'),
					//'required'=>true,
					'field'=>'option',
				),
				'edc_etc_house'=>array(
					'name'=>__('House','edc'),
					//'required'=>true,
					'field'=>'option',
				),
				'edc_etc_zip'=>array(
					'name'=>__('Postal code','edc'),
					//'required'=>true,
					'field'=>'option',
				),
				'edc_etc_city'=>array(
					'name'=>__('Location','edc'),
					//'required'=>true,
					'field'=>'option',
				),
			)
		);
		return apply_filters('edc_order_billing_fields',$fields);
	}
	static function getOrderCurrentFields(){
		$fields=array(
			'title'=>__('Information on current supply and meter reading','edc'),
			'items'=>array(
				'edc_provider'=>array(
					'name'=>__('Current supplier','edc'),
					'field'=>'option',
				),
				'edc_contract'=>array(
					'name'=>__('Customer or contract account number','edc'),
					'field'=>'option',
				),
				'edc_electriс_value'=>array(
					'name'=>__('Meter reading','edc'),
					'field'=>'option',
				),
				'edc_electriс'=>array(
					'name'=>__('Counter number','edc'),
					'field'=>'option',
					'required'=>true,
				),
				'edc_previous'=>array(
					'name'=>__('Previous year consumption (kWh)','edc'),
					'field'=>'option',
				),
				'edc_read_date'=>array(
					'name'=>__('Date of meter reading / on collection: Date of key collection','edc'),
					'field'=>'option',
				),
			)
		);
		return apply_filters('edc_order_current_fields',$fields);
	}
}
?>