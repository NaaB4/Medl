<?php
$tariff_image=EDCH::trfs('image',$data['tariff']->id,$data['tariff']);
//$data['tariff']->prices=EDCH::trfs('price',$data['tariff']->id,$data['tariff']);
//var_dump($data['tariff']->prices);
?>
<div class="edc item">
    <div class="single_tariff theme_<?=EDCH::proceedType($data['tariff']->type,true)?>" id="edc_tariff_<?=$data['tariff']->id?>" data-tarif="<?=$data['tariff']->id?>">
		<div class="main_info">
			<div class="title"><?=$data['tariff']->title?></div>
			<?php if($data['tariff']->subtitle) : ?><div class="subtitlr"><?=$data['tariff']->subtitle?></div><?php endif; ?>
			<?php if($tariff_image) : ?><div class="image"><img src="<?=$tariff_image?>"></div><?php endif; ?>
		</div>
		<?php if($data['tariff']->options['tariff_description']) : ?>
			<div class="tariff_excerpt edc_text">
				<?=$data['tariff']->options['tariff_description']?>
			</div>
		<?php endif; ?>
		<div class="tariff_characteristics">
			<ul class="items">
				<li>
					<span class="edc_name"><?=__('Work price','edc')?></span>
					<span class="value"><?=EDCH::displayPrice($data['tariff']->prices['work_per_kwh'],'workprice',__('cent / kwh','edc'))/*EDCH::addCurrency('<span class="workprice">'.EDCH::formatPrice($work).'</span>')*/?></span>
				</li>
				<?php if($data['tariff']->options['tariff_base_price_type']=='yearly') : ?>
				<li>
					<span class="edc_name"><?=__('Price per year','edc')?></span>
					<span class="value"><?=EDCH::displayPrice($data['tariff']->prices['base_per_year'],'baseprice')?></span>
				</li>
				<?php else : ?>
				<li>
					<span class="edc_name"><?=__('Price per month','edc')?></span>
					<span class="value"><?=EDCH::displayPrice($data['tariff']->prices['base_per_month'],'baseprice')?></span>
				</li>
				<?php endif; ?>
				<li>
					<span class="edc_name"><?=__('Total price','edc')?></span>
					<span class="value"><?=EDCH::displayPrice($data['tariff']->prices['total_per_year'],'totalprice')/*EDCH::addCurrency('<span class="totalprice">'.EDCH::formatPrice($total).'</span>')*/?></span>
				</li>
			</ul>
		</div>
		<div class="tariff_info">			
			<ul class="items">
				<?php if($data['tariff']->options['edc_tariff_price_guarantee']) : ?>
				<li>
					<span class="hint">
						<?=__('Price Guarantee','edc')?> <i><span class="tooltip"><?=$data['tariff']->options['edc_tariff_price_guarantee']?></span></i>
					</span>
				</li>
				<?php endif; ?>
		</div>
		<div class="total_price price_per_period">
			<?=EDCH::displayPrice($data['tariff']->prices['total_per_month'],'ppm')/*EDCH::addCurrency('<span class="ppm">'.EDCH::formatPrice($total/12).'</span>')*/?>
		</div>
		<?php if($data['skip_submit']!==true) : ?>
		<div class="submit_group">
			<?php if(EDCH::is($data['tariff']->options['edc_bookable'])) : ?>
				<button type="submit" class="btn centered" onclick="edc.submitTariff(event,this,<?=$data['tariff']->id?>);"><svg aria-hidden="true" class="svg_inline edc_spinner" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z"></path></svg><?=__('Choose','edc')?></button>
			<?php else : ?>		
				<a href="<?=EDCH::getNoBookLink($data['tariff']->id)?>" class="btn centered"><?=__('Choose','edc')?></a>			
			<?php endif; ?>
		</div>
		<?php endif; ?>
	</div>
</div>