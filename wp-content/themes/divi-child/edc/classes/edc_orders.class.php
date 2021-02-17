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
		$edc=EDC_Extension::getInstance();
		$arr=array();
		//$arr['title']=isset($data['title']) ? $data['title'] : 'Bestellung '.date('y-m-d').' '.$edc->getData('postcode').' #'.$new_oid;
		$arr['title']=isset($data['title']) ? $data['title'] : date('ym').$edc->getData('postcode').$new_oid;
		$arr['status']=is_numeric($data['status']) ? $data['status'] : self::$statuses['default'];
		$arr['date']=isset($data['date']) ? $data['date'] : date('Y.m.d H:i');
		$arr['id_tariff']=$tariff->id;
		if($tariff->type==3 || $tariff->type=='combi'){
			$arr['price_per_period']=$tariff->prices['base_per_year_gas'];
			$arr['price_per_kwh']=$tariff->prices['work_per_kwh_gas'];
			$arr['total_price']=$tariff->prices['total_per_year_gas'];
			$options['price_per_period']=$tariff->prices['base_per_year_el'];
			$options['price_per_kwh']=$tariff->prices['work_per_kwh_el'];
			$options['total_price']=$tariff->prices['total_per_year_el'];
		}else{
			$arr['price_per_period']=$tariff->prices['base_per_year'];
			$arr['price_per_kwh']=$tariff->prices['work_per_kwh'];
			$arr['total_price']=$tariff->prices['total_per_year'];
		}
		$arr['pdf_path']=$data['pdf_path'];
		//var_dump($data);
		//die();
		//Bestellung 20-06-26 45468 #18.pdf
		$arr['pdf_name']=isset($data['pdf_name']) ? $data['pdf_name'] : 'Bestellung '.date('y-m-d').' '.$edc->getData('postcode').' #'.$new_oid;
		//var_dump($arr);
		$res=EDCH::DB()->insert(self::table('orders'),$arr);
		//var_dump($res);
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
			$limit='LIMIT '.(($page-1)*$per_page).','.$per_page;
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
			'primary'=>$data['primary_fields'],
			'options'=>$data['options'],
			'annual_consumption'=>$data['annual_consumption'],
			'logo'=>$logo,
		];
		$pdf_tpl=$data['options']['change']=='new' ? 'pdf_new' : 'pdf';
		return self::pdf('create',self::loadTemplate($pdf_tpl,$args));
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
				if($att==''){
					$meta=get_post_meta($tid,'__wpdm_files',true);
					$dir=wp_upload_dir();
					if(is_array($meta)){
						foreach($meta as $m){
							$att=$dir['basedir'] .'/download-manager-files/'.$m;
							if(file_exists($att)){
								$attachments[]=$att;
							}
						}
					}
				}else if($att && !is_wp_error($att)){
					$attachments[]=$att;
				}
			}
		}
		$headers=[];
		if(is_email($from)) $headers[]='From: '.$from.'<'.$from.'>';
		elseif($from!='') $headers[]='From: '.$from;
		EDC_HELPER::setReplaces($subject,$oid);
		EDC_HELPER::setReplaces($message,$oid);
		if(self::isHTML($message)) $headers[]='Content-Type: text/html; charset=UTF-8';
		$result=wp_mail($mailto,apply_filters('edc_email_subject',$subject),apply_filters('edc_email_message',$message),$headers,$attachments);
		self::updateOpt($oid,self::opts('id','customer_mail_sent','order'),($result===false ? '0' : '1'));
		if(is_email($to)){
			$self_subject=self::opts('get','edc_self_mail_subject','settings');
			if($self_subject=='') $self_subject=sprintf(__('New order on the site %s','edc'),get_bloginfo('description'));
			//$self_message=self::opts('get','edc_self_mail_text','settings');
			$self_message=self::getSelfMessage($oid);
			if($self_message=='') $self_message=' ';
			EDC_HELPER::setReplaces($self_subject,$oid);
			EDC_HELPER::setReplaces($self_message,$oid);
			if(self::isHTML($self_message) && !in_array('Content-Type: text/html; charset=UTF-8',$headers)) $headers[]='Content-Type: text/html; charset=UTF-8';
			$result=wp_mail($to,apply_filters('edc_self_email_subject',$self_subject,$oid),apply_filters('edc_self_email_message',$self_message,$oid),$headers,$self_attachments);
			self::updateOpt($oid,self::opts('id','self_mail_sent','order'),($result===false ? '0' : '1'));
		}
		unlink($pdf_buf);
		return $result;
	}
	static function getSelfMessage($oid){		
		if(!($order=self::exists($oid))) return '';
		$output='';
		$edc=EDC_Extension::getInstance();
		$items=[
			'Persönliche Daten'=>[
				'Anrede:'=>'edc_anrede',
				'Name:'=>'edc_name',
				'Vorname:'=>'edc_first_name',
				'Straße, Hausnummer:'=>'edc_street_edc_house',
				'PLZ, Stadt:'=>'edc_postal_code_edc_location',
				'Telefonnummer:'=>'edc_phone',
				'E-Mail Adresse:'=>'edc_email',
				'Geburtsdatum:'=>'edc_date_of_birth',
			],
			'Alte Adresse'=>[
				'Straße, Hausnummer:'=>'change_street_change_house',
				'PLZ, Stadt:'=>'change_postal_code_change_location',				
			],
			'Lieferdaten'=>[
				'Art des Wechsels:'=>'change',
				'Zählernummer:'=>'edc_electriс',
				'Zählernummer (Strom):'=>'edc_electriс2',
				'Zählerstand:'=>'edc_electriс_value',
				'Zählerstand (Strom):'=>'edc_electriс_value2',
				'Vertragskündigung durch medl:'=>'sollen_yes',
				'Ab wann soll geliefert werden:'=>'edc_electriс_date',
				'Vorversorger:'=>'edc_previous',
				'Kundennummer Vorversorger:'=>'edc_contract',
				'Datum der Zähleranmeldung:'=>'edc_read_date',
			],
			'Rechnungsadresse'=>[
				'Anrede:'=>'edc_etc_gender',
				'Name:'=>'edc_etc_firstname',
				'Vorname:'=>'edc_etc_name',
				'Straße, Hausnummer:'=>'edc_etc_street_edc_etc_house',
				'PLZ, Stadt:'=>'edc_etc_zip_edc_etc_city',
			],
			'Zahlungsdaten'=>[			
				'Zahlungsmethode:'=>'edc_debit_type',
				'Name Kontoinhaber:'=>'edc_holder',
				'IBAN:'=>'edc_IBAN',
				'BIC:'=>'edc_BIC',
				'Zustimmung SEPA-Lastschriftmandat:'=>'is_sepa',
			],
			'Tarifdaten'=>[
				'Tarifart:'=>'tariff_type',
				'Tarifname:'=>'tariff_name',
				'Interner Tarifschlüssel:'=>'tariff_code',
				'Angegebener Verbrauch Strom:'=>'step_first_annual_el',
				'Angegebener Verbrauch Gas:'=>'step_first_annual_gas',
				'Gewählter Bonus:'=>'goodies',
			],
			'Zustimmungen'=>[
				'AGB zugestimmt:'=>'ja',
				'Widerrufsbelehrung zugestimmt:'=>'ja',
				'Kündigungseinwilligung Vorversorger zugestimmt:'=>'sollen_yes',
				'Bonitätsprüfung zugestimmt:'=>'ja',
				'OPT IN Kontakt via E-Mail:'=>'edc_email_checkbox',
				'OPT IN Kontakt via Telefon:'=>'edc_mobile_checkbox',
				'Datenschutz-Information zur Kenntnis genommen:'=>'ja',
			],
		];
		$gender=['edc_anrede','edc_etc_gender'];
		
		$tg=json_decode($order->tariff->options['tariff_goodies'],true);
		if(!is_array($tg) || sizeOf($tg)==0 || !in_array($edc->steps_data['second']['goodies'],$tg)) $edc->steps_data['second']['goodies']='';
		$data=$order->options;
		foreach($items as $gname=>$group){
			$output.='<br><b>'.$gname.'</b><br>';
			$output.='<table style="width:100%;"><tbody>';
			foreach($group as $name=>$f){				
				$val='';
				if($f!=''){
					if(in_array($f,$gender)){
						$val=$data[$f]=='m' ? __('Man','edc') : __('Woman','edc');
					}elseif($f=='order_id'){
						$val="#".$oid;
					}elseif($f=='edc_street_edc_house'){
						if($data['edc_street']!='' || $data['edc_house']!=''){
							$val=$data['edc_street'].', '.$data['edc_house'];
						}
					}elseif($f=='edc_postal_code_edc_location'){
						if($data['edc_postal_code']!='' || $data['edc_location']!=''){
							$val=$data['edc_postal_code'].', '.$data['edc_location'];
						}
					}elseif($f=='change_street_change_house'){
						if($data['change_street']!='' || $data['change_house']!=''){
							$val=$data['change_street'].', '.$data['change_house'];
						}
					}elseif($f=='change_postal_code_change_location'){
						if($data['change_postal_code']!='' || $data['change_location']!=''){
							$val=$data['change_postal_code'].', '.$data['change_location'];
						}
					}elseif($f=='edc_etc_street_edc_etc_house'){
						if($data['edc_etc_street']!='' || $data['edc_etc_house']!=''){
							$val=$data['edc_etc_street'].', '.$data['edc_etc_house'];
						}
					}elseif($f=='edc_etc_zip_edc_etc_city'){
						if($data['edc_etc_zip']!='' || $data['edc_etc_city']!=''){
							$val=$data['edc_etc_zip'].', '.$data['edc_etc_city'];
						}
					}elseif(substr($f,0,5)=='step_'){
						if($f=='step_first_annual_el'){
							$val=$edc->steps_data['first']['annual_consumption_el'];
							if($edc->steps_data['first']['type']=='electricity' && !is_numeric($val)){
								$val=$edc->steps_data['first']['annual_consumption'];	
							}
						}elseif($f=='step_first_annual_gas'){
							$val=$edc->steps_data['first']['annual_consumption_gas'];
							if($edc->steps_data['first']['type']=='gas' && !is_numeric($val)){
								$val=$edc->steps_data['first']['annual_consumption'];							
							}
						}elseif($f=='step_first_annual'){
							$val=$edc->steps_data['first']['annual_consumption'];
						}
					}elseif(substr($f,0,7)=='tariff_'){
						if($f=='tariff_type'){
							if($edc->steps_data['first']['type']=='electricity'){
								$val='Strom';
							}elseif($edc->steps_data['first']['type']=='gas'){
								$val='Erdgas';
							}else{
								$val='Kombi';
							}
						}elseif($f=='tariff_name'){
							$val=$order->tariff->title;
						}elseif($f=='tariff_code'){
							$val=$order->tariff->code;
						}elseif($f=='tariff_product_id'){
							$val=$order->tariff->options['product_id'];
						}elseif($f=='tariff_preise_id'){
							$val=$order->tariff->options['product_preiseid'];
						}
					}elseif($f=='order_date'){
						$val=date('d.m.Y');
					}elseif($f=='change'){
						$val=$data[$f]=='change' ? 'Versorgerwechsel' : 'Neueinzug';
					}elseif($f=='edc_bereits'){
						$val=$data['cancel_old']==0 ? 'nein' : '';
					}elseif($f=='edc_debit_type'){
						$val=$data['edc_sepa_direct_debit']==1 ? 'SEPA-Lastschriftmandat' : 'Überweisung';
					}elseif($f=='is_sepa'){
						$val=$data['edc_sepa_direct_debit']==1 ? 'ja' : '';
					}elseif($f=='sollen_yes'){
						$val=$data['cancel_old']==1 ? 'ja' : '';
					}elseif($f=='edc_mobile_checkbox' || $f=='edc_email_checkbox'){
						$val=$data[$f]==1 ? 'ja' : 'nein';
					}elseif($f=='edc_read_date'){
						$val=$data[$f]=='' ? date('d-m-Y',strtotime('+2 week')) : $data[$f];
					}elseif($f=='goodies'){
						$val='';
						if(is_numeric($edc->steps_data['second']['goodies'])){
							$goodie=EDC_GOODIES::item($edc->steps_data['second']['goodies']);
							$val=$goodie->name;
						}
						//var_dump($val);
						//$val=$data['edc_sepa_direct_debit']==1 ? 'SEPA-Lastschriftmandat' : 'Überweisung';
					}elseif($f=='edc_IBAN' || $f=='edc_BIC'){
						$data[$f]=json_decode($data[$f],true);
						$val=is_array($data[$f]) ? implode('',$data[$f]) : $data[$f];
					}elseif($f!='' && isset($data[$f])){
						$val=is_array($data[$f]) ? implode('',$data[$f]) : $data[$f];					
					}else{
						$val=strpos($f,'_')===false ? $f : '';
					}
				}	
				if($val!=''){
					$output.='<tr>';
					$output.='<td style="width:50%;">'.$name.'</td> <td style="width:50%;">'.$val.'</td>';
					$output.='</tr>';
				}
			}
			$output.='</tbody></table>';
		}
		//var_dump($output);
		//die();
		return $output;
	}
	static function isHTML($text=''){
		return strip_tags($text)!=$text;
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
					//'required'=>true,
				),
				'edc_previous'=>array(
					'name'=>__('Previous year consumption (kWh)','edc'),
					'field'=>'option',
				),
				'edc_electriс_value2'=>array(
					'name'=>__('Meter reading','edc'),
					'field'=>'option',
				),
				'edc_electriс2'=>array(
					'name'=>__('Counter number','edc'),
					'field'=>'option',
					//'required'=>true,
				),
				'edc_read_date'=>array(
					'name'=>__('Date of meter reading / on collection: Date of key collection','edc'),
					'field'=>'option',
				),
			)
		);
		return apply_filters('edc_order_current_fields',$fields);
	}
	static function getOrdersPath(){
		return EDC_PLUGIN_PATH.'/orders/';
	}
	static function createPath(){
		$dirname=mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9);
		if(!file_exists(self::getOrdersPath().$dirname)) mkdir(self::getOrdersPath().$dirname);
		return self::getOrdersPath().$dirname.'/';
	}
	static function createCSV($oid,$data){
		if(!is_numeric($oid)) return false;
		if(!$order=self::order('exists',$oid)) return false;
		//$output='Laufende Nummer;Anrede;Firma;Rechtsform;Umsatzsteuernummer;Name;Vorname;Liefer Strasse;Liefer Hausnummer;Liefer Hausnummer Zusatz;Liefer PLZ;Liefer Stadt;Telefonnummer;E-Mail;Geburtsdatum;Alt Strasse;Alt Hausnummer;Alt PLZ;Alt Stadt;Art des Wechsels;Zaehlernummer Strom;Zaehlernummer Gas;Zaehlerstand Strom;Zaehlerstand Gas;Bereits Gekuendigt;Lieferbeginn;Vorversorger Strom;Vorversorger Gas;Kundennummer VV Strom;Kundennummer VV Gas;Rechnung Anrede;Rechnung Name;Rechnung Vorname;Rechnung Strasse;Rechnung Hausnummer;Rechnung Hausnummer Zusatz;Rechnung PLZ;Rechnung Stadt;Zahlungsmethode;Name Kontoinhaber;IBAN;BIC;Zustimmung SEPA;Tarifart;Speicherart;Zaehlerart;Messung;Tarifname;Interner Tarifschluessel;Verbrauch Strom;Verbrauch Strom NT;Verbrauch Gas;Praemie;Zustimmung AGB;Zustimmung Widerruf;Zustimmung Kuendigung VV;Zustimmung Boni;Zustimmung Kontakt E-Mail;Zustimmung Kontakt Telefon;Bestelldatum;ProduktID;Preissschluessel';
		//$output.="\r\n";
		//$output.="#".$oid.';';
		// Zustimmung SEPA, Praemie, Zustimmung AGB,Zustimmung Widerruf, Zustimmung Kuendigung VV,Zustimmung Boni,Zustimmung Kontakt E-Mail,Zustimmung Kontakt Telefon, ProduktID, Preissschluessel
		// edc_anrede change
		$edc=EDC_Extension::getInstance();
		$headline='';
		$lines='';
		$fields=[
			'Laufende Nummer'=>'order_id',
			'Anrede'=>'edc_anrede',
			'Firma'=>'',
			'Rechtsform'=>'',
			'Umsatzsteuernummer'=>'',
			'Name'=>'edc_name',
			'Vorname'=>'edc_first_name',
			'Liefer Strasse'=>'edc_street',
			'Liefer Hausnummer'=>'edc_house',
			'Liefer Hausnummer Zusatz'=>'edc_house_zuratc',
			'Liefer PLZ'=>'edc_postal_code',
			'Liefer Stadt'=>'edc_location',
			'Telefonnummer'=>'edc_phone',
			'E-Mail'=>'edc_email',
			'Geburtsdatum'=>'edc_date_of_birth',
			'Alt Strasse'=>'change_street',
			'Alt Hausnummer'=>'change_house',
			'Alt PLZ'=>'change_postal_code',
			'Alt Stadt'=>'change_location',
			'Art des Wechsels'=>'change',
			'Zaehlernummer Strom'=>($edc->steps_data['first']['type']=='electricity' ? 'edc_electriс' : ($edc->steps_data['first']['type']=='combi' ? 'edc_electriс2' : '')),
			'Zaehlernummer Gas'=>($edc->steps_data['first']['type']=='gas' ? 'edc_electriс' : ($edc->steps_data['first']['type']=='combi' ? 'edc_electriс' : '')),
			'Zaehlerstand Strom'=>($edc->steps_data['first']['type']=='electricity' ? 'edc_electriс_value' : ($edc->steps_data['first']['type']=='combi' ? 'edc_electriс_value2' : '')),
			'Zaehlerstand Gas'=>($edc->steps_data['first']['type']=='gas' ? 'edc_electriс_value' : ($edc->steps_data['first']['type']=='combi' ? 'edc_electriс_value' : '')),
			'Bereits Gekuendigt'=>'edc_bereits',
			'Lieferbeginn'=>'edc_read_date',
			'Vorversorger Strom'=>($edc->steps_data['first']['type']=='electricity' && $data['change']!='new' ? 'edc_previous' : ''),
			'Vorversorger Gas'=>($edc->steps_data['first']['type']=='gas' && $data['change']!='new' ? 'edc_previous' : ''),
			'Kundennummer VV Strom'=>'',
			'Kundennummer VV Gas'=>'',
			'Rechnung Anrede'=>'edc_etc_gender',
			'Rechnung Name'=>'edc_etc_firstname',
			'Rechnung Vorname'=>'edc_etc_name',
			'Rechnung Strasse'=>'edc_etc_street',
			'Rechnung Hausnummer'=>'edc_etc_house',
			'Rechnung Hausnummer Zusatz'=>'',
			'Rechnung PLZ'=>'edc_etc_zip',
			'Rechnung Stadt'=>'edc_etc_city',
			'Zahlungsmethode'=>'edc_debit_type',
			'Name Kontoinhaber'=>'edc_holder',
			'IBAN'=>'edc_IBAN',
			'BIC'=>'edc_BIC',
			'Zustimmung SEPA'=>'is_sepa',
			'Tarifart'=>'tariff_type',
			'Speicherart'=>'',
			'Zaehlerart'=>'',
			'Messung'=>'',
			'Tarifname'=>'tariff_name',
			'Interner Tarifschluessel'=>'tariff_code',
			'Verbrauch Strom'=>'step_first_annual_el',
			'Verbrauch Strom NT'=>'',
			'Verbrauch Gas'=>'step_first_annual_gas',
			'Praemie'=>'goodies',
			'Zustimmung AGB'=>'ja',
			'Zustimmung Widerruf'=>'ja',
			'Zustimmung Kuendigung VV'=>'sollen_yes',
			'Zustimmung Boni'=>'',
			'Zustimmung Kontakt E-Mail'=>'edc_mobile_checkbox',
			'Zustimmung Kontakt Telefon'=>'edc_email_checkbox',
			'Bestelldatum'=>'order_date',
			'Produkt-ID'=>'tariff_product_id',
			'Preis-Schluessel'=>'tariff_preise_id'];
		$gender=['edc_anrede','edc_etc_gender'];
		//var_dump($edc->steps_data['second']['goodies']);
		$tg=json_decode($order->tariff->options['tariff_goodies'],true);
		if(!is_array($tg) || sizeOf($tg)==0 || !in_array($edc->steps_data['second']['goodies'],$tg)) $edc->steps_data['second']['goodies']='';
		
		foreach($fields as $name=>$f){
			$val='';
			if($f!=''){
				if(in_array($f,$gender)){
					$val=$data[$f]=='m' ? __('Man','edc') : __('Woman','edc');
				}elseif($f=='order_id'){
					$val="#".$oid;
				}elseif(substr($f,0,5)=='step_'){
					if($f=='step_first_annual_el'){
						$val=$edc->steps_data['first']['annual_consumption_el'];
						if($edc->steps_data['first']['type']=='electricity' && !is_numeric($val)){
							$val=$edc->steps_data['first']['annual_consumption'];	
						}
					}elseif($f=='step_first_annual_gas'){
						$val=$edc->steps_data['first']['annual_consumption_gas'];
						if($edc->steps_data['first']['type']=='gas' && !is_numeric($val)){
							$val=$edc->steps_data['first']['annual_consumption'];							
						}
					}elseif($f=='step_first_annual'){
						$v=$edc->steps_data['first']['annual_consumption'];
					}
				}elseif(substr($f,0,7)=='tariff_'){
					if($f=='tariff_type'){
						if($edc->steps_data['first']['type']=='electricity'){
							$val='Strom';
						}elseif($edc->steps_data['first']['type']=='gas'){
							$val='Erdgas';
						}else{
							$val='Kombi';
						}
					}elseif($f=='tariff_name'){
						$val=$order->tariff->title;
					}elseif($f=='tariff_code'){
						$val=$order->tariff->code;
					}elseif($f=='tariff_product_id'){
						$val=$order->tariff->options['product_id'];
					}elseif($f=='tariff_preise_id'){
						$val=$order->tariff->options['product_preiseid'];
					}
				}elseif($f=='order_date'){
					$val=date('d.m.Y');
				}elseif($f=='change'){
					$val=$data[$f]=='change' ? 'Versorgerwechsel' : 'Neueinzug';
				}elseif($f=='edc_bereits'){
					$val=$data['cancel_old']==0 ? 'nein' : '';
				}elseif($f=='edc_debit_type'){
					$val=$data['edc_sepa_direct_debit']==1 ? 'SEPA-Lastschriftmandat' : 'Überweisung';
				}elseif($f=='is_sepa'){
					$val=$data['edc_sepa_direct_debit']==1 ? 'ja' : '';
				}elseif($f=='sollen_yes'){
					$val=$data['cancel_old']==1 ? 'ja' : '';
				}elseif($f=='edc_mobile_checkbox' || $f=='edc_email_checkbox'){
					$val=$data[$f]==1 ? 'ja' : 'nein';
				}elseif($f=='goodies'){
					$val='';
					if(is_numeric($edc->steps_data['second']['goodies'])){
						$goodie=EDC_GOODIES::item($edc->steps_data['second']['goodies']);
						$val=$goodie->name;
					}
					//var_dump($val);
					//$val=$data['edc_sepa_direct_debit']==1 ? 'SEPA-Lastschriftmandat' : 'Überweisung';
				}elseif($f!='' && isset($data[$f])){
					$val=is_array($data[$f]) ? implode('',$data[$f]) : $data[$f];					
				}else{
					$val=$f;
				}
			}	
			$headline.=$name.';';
			$lines.=$val.';';
			//$output.=$val.';';
		}
		$output=$headline."\r\n".$lines;
		$wp_uploads=wp_get_upload_dir();
		$path=$wp_uploads['basedir'].'/edc/';//self::createPath();
		$fname='bestellung'.$oid.'.csv';//wp_generate_password(mt_rand(30,50),false).'.csv';
		$res=file_put_contents($path.$fname,$output);
		if(!$res) return false;
		$res=self::updateOpt($oid,self::opts('id','order_csv_file','order'),$path.$fname);
		return $path.$fname;
	}
	
	static function downloadCSV($oid=''){
		if(!is_numeric($oid)) return false;
		if(!$order=self::order('exists',$oid)) return false;
		//var_dump($order->options['order_csv_file']);
		//die();
		if(!file_exists($order->options['order_csv_file'])) return false;
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=Order #'.$oid.'.csv');
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($order->options['order_csv_file']));
		readfile($order->options['order_csv_file']);
		exit;
	}
}
?>