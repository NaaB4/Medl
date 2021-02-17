<?php
	$edc=EDC_Extension::getInstance(); 
	
	if(is_numeric($edc->steps_data['second']['goodies'])){
		$goodie=EDC_GOODIES::item($edc->steps_data['second']['goodies']);
	}
	if(!($edc instanceof EDC_Extension)) return;
?>
<div class="edc">
	<div class="edc_fixed_data">
		<ul class="items">
			<li><?=sprintf(__('Dein Standort: %s (%s)','medl'),$data['location'],$data['postal_code'])?></li>
			<?php if($data['type']!='combi') : ?>
				<li><?=sprintf(__('Dein Jahresverbrauch: %s kWh','medl'),$data['annual_consumption'])?></li>
			<?php else : ?>
				<li><?=sprintf(__('Dein Jahresverbrauch: %s kWh','medl'),$data['annual_consumption_el'])?> für Strom</li>			
				<li><?=sprintf(__('Dein Jahresverbrauch: %s kWh','medl'),$data['annual_consumption_gas'])?> für Erdgas</li>			
			<?php endif; ?>
			<?php if($goodie) : ?>
				<li><?=sprintf(__('Dein gewählter Boni:: %s','medl'),$goodie->name)?></li>
			<?php endif; ?>
			<?php if($edc->is_order_step) : ?>
			<li><?=sprintf(__('Chosen tariff: %s','edc'),$edc->tariff->title)?></li>
			<?php endif; ?>
		</ul>
	</div>
	<?php if($edc->is_order_step) : ?>
		<div class="edc_popup" id="edc_tariff_popup">		
			<div class="edc edc_popup_wrapper">
				<div class="edc_close" onclick="edc.popup(this,true);">&times;</div>
				<div class="edc_popup_content">
					<?=EDCH::loadTemplate("tariffs_single",array('tariff'=>$data['tariff'],'skip_submit'=>true))?>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>