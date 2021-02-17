<?php
	$groups=EDC_ORDERS::getFields();	
		//if(!($order=self::exists($oid))) return '';
		$output='';
		//$edc=EDC_Extension::getInstance();
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
				'Zählerstand:'=>'edc_electriс_value',
				'Vertragskündigung durch medl:'=>'sollen_yes',
				'Ab wann soll geliefert werden:'=>'edc_electriс_date',
				'Vorversorger:'=>'edc_previous',
				'Kundennummer Vorversorger:'=>'edc_contract',
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
				'Angegebener Verbrauch Strom:'=>'step_first_annual',
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
		
		//$tg=json_decode($order->tariff->options['tariff_goodies'],true);
		//if(!is_array($tg) || sizeOf($tg)==0 || !in_array($edc->steps_data['second']['goodies'],$tg)) $edc->steps_data['second']['goodies']='';
		$d=$data['order']->options;
		//var_dump($d);
		//die();
		//var_dump($data['order']->tariff);
		foreach($items as $gname=>$group){
			$output.='<br><b>'.$gname.'</b><br>';
			$output.='<table style="width:100%;"><tbody>';
			foreach($group as $name=>$f){				
				$val='';
				if($f!=''){
					if(in_array($f,$gender)){
						$val=$d[$f]=='m' ? __('Man','edc') : __('Woman','edc');
					}elseif($f=='order_id'){
						$val="#".$oid;
					}elseif($f=='edc_street_edc_house'){
						if($d['edc_street']!='' || $d['edc_house']!=''){
							$val=$d['edc_street'].', '.$d['edc_house'];
						}
					}elseif($f=='edc_postal_code_edc_location'){
						if($d['edc_postal_code']!='' || $d['edc_location']!=''){
							$val=$d['edc_postal_code'].', '.$d['edc_location'];
						}
					}elseif($f=='change_street_change_house'){
						if($d['change_street']!='' || $d['change_house']!=''){
							$val=$d['change_street'].', '.$d['change_house'];
						}
					}elseif($f=='change_postal_code_change_location'){
						if($d['change_postal_code']!='' || $d['change_location']!=''){
							$val=$d['change_postal_code'].', '.$d['change_location'];
						}
					}elseif($f=='edc_etc_street_edc_etc_house'){
						if($d['edc_etc_street']!='' || $d['edc_etc_house']!=''){
							$val=$d['edc_etc_street'].', '.$d['edc_etc_house'];
						}
					}elseif($f=='edc_etc_zip_edc_etc_city'){
						if($d['edc_etc_zip']!='' || $d['edc_etc_city']!=''){
							$val=$d['edc_etc_zip'].', '.$d['edc_etc_city'];
						}
					}elseif(substr($f,0,5)=='step_'){
						if($f=='step_first_annual_el'){
							$val=$d['annual_el'];
							if($data['order']->tariff->type==2 && !is_numeric($val)){
								$val=$d['edc_consumption'];
							}
						}elseif($f=='step_first_annual_gas'){
							$val=$d['annual_gas'];
							if($data['order']->tariff->type==1 && !is_numeric($val)){
								$val=$d['edc_consumption'];				
							}
						}elseif($f=='step_first_annual'){
							$val=$d['edc_consumption'];
						}
					}elseif(substr($f,0,7)=='tariff_'){
						if($f=='tariff_type'){
							if($data['order']->tariff->type==2){
								$val='Strom';
							}elseif($data['order']->tariff->type==1){
								$val='Erdgas';
							}else{
								$val='Kombi';
							}
						}elseif($f=='tariff_name'){
							$val=$data['order']->tariff->title;
						}elseif($f=='tariff_code'){
							$val=$data['order']->tariff->code;
						}elseif($f=='tariff_product_id'){
							$val=$data['order']->tariff->options['product_id'];
						}elseif($f=='tariff_preise_id'){
							$val=$data['order']->tariff->options['product_preiseid'];
						}
					}elseif($f=='order_date'){
						$val=date('d.m.Y');
					}elseif($f=='change'){
						$val=$d[$f]=='change' ? 'Versorgerwechsel' : 'Neueinzug';
					}elseif($f=='edc_bereits'){
						$val=$d['cancel_old']==0 ? 'nein' : '';
					}elseif($f=='edc_debit_type'){
						$val=$d['edc_sepa_direct_debit']==1 ? 'SEPA-Lastschriftmandat' : 'Überweisung';
					}elseif($f=='is_sepa'){
						$val=$d['edc_sepa_direct_debit']==1 ? 'ja' : '';
					}elseif($f=='sollen_yes'){
						$val=$d['cancel_old']==1 ? 'ja' : '';
					}elseif($f=='edc_mobile_checkbox' || $f=='edc_email_checkbox'){
						$val=$d[$f]==1 ? 'ja' : 'nein';
					}elseif($f=='goodies'){
						$val='';
						if(is_numeric($d['goodies'])){
							$goodie=EDC_GOODIES::item($d['goodies']);
							$val=$goodie->name;
						}
						//var_dump($val);
						//$val=$d['edc_sepa_direct_debit']==1 ? 'SEPA-Lastschriftmandat' : 'Überweisung';
					}elseif($f=='edc_IBAN' || $f=='edc_BIC'){
						$d[$f]=json_decode($d[$f],true);
						$val=is_array($d[$f]) ? implode('',$d[$f]) : $d[$f];
					}elseif($f!='' && isset($d[$f])){
						$val=is_array($d[$f]) ? implode('',$d[$f]) : $d[$f];					
					}else{
						$val=$f;
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
?>
<div class="order_details">
	<div class="title small active"><?=$data['order']->title?> (#<?=$data['order']->id?>) <?=__('from','edc')?> <?=EDCH::dateToHum($data['order']->date)?></div>
	<div class="data"><?=$output?>
		<!--<?php foreach($groups as $g) : ?>
			<div class="group_holder">
				<div class="group_title"><?=$g['title']?></div>
				<ul>
					<?php
						foreach($g['items'] as $k=>$item) : if($item['name']!='') :
						$value=$item['field']=='option' ? $d['order']->options[$k] : $d['order']->{$k};
						$value=json_decode($value,true) ? json_decode($value,true) : $value;
						if($k=='goodies'){
							$goodie=EDC_GOODIES::item($value);
							if($goodie) $value=$goodie->name;
						}
					?>
					<li class="<?=$k?>">
						<span class="name"><?=$item['name']?>:</span>
						<span class="value">
							<?php if(is_array($item['values'])) : ?>
								<?=$item['values'][$value]?>
							<?php else : ?>
								<?=(is_array($value) ? implode('',$value) : $value)?>
							<?php endif; ?>
						</span>
					</li>
					<?php endif; endforeach; ?>
				</ul>
			</div>
		<?php endforeach; ?>-->
	</div>
</div>