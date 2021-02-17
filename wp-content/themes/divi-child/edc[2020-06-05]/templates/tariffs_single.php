<?php
	$edc=EDC_Extension::getInstance();
	$features=explode("\n",$data['tariff']->options['tariff_features']);
	EDCH::trimArray($features);
	foreach($features as $k=>$v) if($v=='') unset($features[$k]);
	
	$links=[];
	if($data['tariff']->options['tariff_price_link']) $links[]=[$data['tariff']->options['tariff_price_link'],__('Produktdetails','medl')];
	if($data['tariff']->options['tariff_agb_link']) $links[]=[$data['tariff']->options['tariff_agb_link'],__('AGB','medl')];
	$gids=json_decode($data['tariff']->options['tariff_goodies'],true);
	//	var_dump($gids);
	if(!is_array($gids)) $gids=[];
	foreach($gids as $k=>$gid) if(!is_numeric($gid)) unset($gids[$k]);
	if(sizeOf($gids)>0){
		list($goodies,$total_goodies)=EDC_GOODIES::items(['ids'=>$gids]);
	}else $total_goodies=0;
?>	
<div class="edc item">
    <div class="single_tariff theme_<?=EDCH::proceedType($data['tariff']->type,true)?>" id="edc_tariff_<?=$data['tariff']->id?>" data-tarif="<?=$data['tariff']->id?>">
		<div class="main_info">
			<div class="title"><?=$data['tariff']->title?></div>
			<?php if($data['tariff']->options['tariff_subtitle']) : ?><div class="subtitle"><?=$data['tariff']->options['tariff_subtitle']?></div><?php endif; ?>
		</div>
		<?php if(sizeOf($features)>0): ?>
		<div class="tariff_characteristics">
			<ul class="items"><?php foreach($features as $f) : ?><li><?=$f?></li><?php endforeach; ?></ul>
		</div>
		<?php endif; ?>
		<?php if($data['tariff']->type!='combi') : ?>
			<div class="tariff_consumption">
				<div class="heading">Dein Jahresverbrauch (<?=($data['tariff']->type=='gas' ? 'Erdgas' : 'Strom')?>)</div>
				<span><?=EDCH::displayPrice($edc->steps_data['first']['annual_consumption'],'',false)?> kWh</span>
			</div>
			<div class="prices">
				<ul>
					<li>
						Arbeitspreis (brutto) pro <b>kWh</b>:
						<?=EDCH::displayPrice($data['tariff']->prices['work_per_kwh'],'ppk','Cent')?> 
					</li>
					<?php if($data['tariff']->options['tariff_base_price_type']=='yearly') : ?>
					<li>
						Bruttogrundpreis (brutto) pro <b>Jahr</b>:
						<?=EDCH::displayPrice($data['tariff']->prices['base_per_year'],'ppy','Euro')?> 
					</li>
					<?php else : ?>
					<li>
						Bruttogrundpreis (brutto) pro <b>Monat</b>:
						<?=EDCH::displayPrice($data['tariff']->prices['base_per_month'],'ppm','Euro')?> 
					</li>
					<?php endif; ?>
				</ul>
			</div>
			<?php if($data['tariff']->options['tariff_price_details']!='') : ?>
				<div class="price_details">
					<span>
						Preisdetails *
						<div class="hover_info"><?=$data['tariff']->options['tariff_price_details']?></div>
					</span>
				</div>
			<?php endif; ?>
			<?php if($data['tariff']->options['tariff_base_price_type']=='yearly') : ?>
				<div class="total_price">
					<?=EDCH::displayPrice(ceil($data['tariff']->prices['total_per_year']),'ppy','&euro;')?> im Jahr
				</div>
			<?php else : ?>			
				<div class="total_price">
					<?=EDCH::displayPrice(ceil($data['tariff']->prices['total_per_month']),'ppm','&euro;')?> im Monat
				</div>
			<?php endif; ?>
		<?php else : ?>
			<div class="tariff_consumption">
				<div class="heading">Dein Jahresverbrauch (Strom)</div>
				<span><?=EDCH::displayPrice($edc->steps_data['first']['annual_consumption_el'],'',false)?> kWh</span>
			</div>
			<div class="tariff_consumption">
				<div class="heading">Dein Jahresverbrauch (Erdgas)</div>
				<span><?=EDCH::displayPrice($edc->steps_data['first']['annual_consumption_gas'],'',false)?> kWh</span>
			</div>
			<div class="tariff_row"><div class="heading"><a href="javascript:void(0);" onclick="edc.changeCombiPricesDisplay(this);">Tarifdetails anzeigen</a></div></div>		
			<div class="combi_prices_holder">
				<div class="prices">
					<div class="heading">Strom Preis</div>
					<ul>
						<li>
							Arbeitspreis (brutto) pro <b>kWh</b>:
							<?=EDCH::displayPrice($data['tariff']->prices['work_per_kwh_el'],'ppk','Cent')?> 
						</li>
						<?php if($data['tariff']->options['tariff_base_price_type']=='yearly') : ?>
						<li>
							Bruttogrundpreis (brutto) pro <b>Jahr</b>:
							<?=EDCH::displayPrice($data['tariff']->prices['base_per_year_el'],'ppy','Euro')?> 
						</li>
						<?php else : ?>
						<li>
							Bruttogrundpreis (brutto) pro <b>Monat</b>:
							<?=EDCH::displayPrice($data['tariff']->prices['base_per_month_el'],'ppm','Euro')?> 
						</li>
						<?php endif; ?>
					</ul>
				</div>
				<div class="prices">
					<div class="heading">Erdgas Preis</div>
					<ul>
						<li>
							Arbeitspreis (brutto) pro <b>kWh</b>:
							<?=EDCH::displayPrice($data['tariff']->prices['work_per_kwh_gas'],'ppk','Cent')?> 
						</li>
						<?php if($data['tariff']->options['tariff_base_price_type']=='yearly') : ?>
						<li>
							Bruttogrundpreis (brutto) pro <b>Jahr</b>:
							<?=EDCH::displayPrice($data['tariff']->prices['base_per_year_gas'],'ppy','Euro')?> 
						</li>
						<?php else : ?>
						<li>
							Bruttogrundpreis (brutto) pro <b>Monat</b>:
							<?=EDCH::displayPrice($data['tariff']->prices['base_per_month_gas'],'ppm','Euro')?> 
						</li>
						<?php endif; ?>
					</ul>
				</div>
				<?php if($data['tariff']->options['tariff_price_details']!='') : ?>
					<div class="price_details">
						<span>
							Preisdetails *
							<div class="hover_info"><?=$data['tariff']->options['tariff_price_details']?></div>
						</span>
					</div>
				<?php endif; ?>
			</div>
			<?php if($data['tariff']->options['tariff_base_price_type']=='yearly') : ?>
				<div class="total_price">
					<?=EDCH::displayPrice(ceil($data['tariff']->prices['total_per_year']),'ppy','&euro;')?> im Jahr
				</div>
			<?php else : ?>			
				<div class="total_price">
					<?=EDCH::displayPrice(ceil($data['tariff']->prices['total_per_month']),'ppm','&euro;')?> im Monat
				</div>
			<?php endif; ?>
		<?php endif; ?>
		<?php if($data['skip_submit']!==true) : ?>
		<div class="submit_group">
			<button type="submit" class="btn centered" onclick="edc.submitTariff(event,this,<?=$data['tariff']->id?>);"><svg aria-hidden="true" class="svg_inline edc_spinner" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z"></path></svg><?=__('Her damit!','medl')?></button>
		</div>
		<?php endif; ?>
		<?php if(sizeOf($links)>0) : ?>
		<div class="links_group">
			<?php foreach($links as $n=>$l) : ?>
				<a href="<?=$l[0]?>" target="_blank"><?=$l[1]?></a>
				<?php if($n<sizeOf($links)-1) : ?> | <?php endif; ?>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
		<?php if($total_goodies>0) : ?>
			<div class="goodies_list">
			<?php foreach($goodies as $goodie) : ?>
				<div class="checkbox">					
					<input type="radio" id="goodie_<?=$goodie->id?>_<?=$data['tariff']->id?>" name="goodies" value="<?=$goodie->id?>">
					<label for="goodie_<?=$goodie->id?>_<?=$data['tariff']->id?>" class="edc_checkbox"><?=$goodie->name?></label>
				</div>
			<?php endforeach; ?>
			</div>
		<?php endif; ?>				
	</div>
</div>