<?php
	$edc=EDC::getInstance(); 
	if(!($edc instanceof EDC)) return;
?>
<div class="edc">
	<div class="edc_fixed_data">
		<ul class="items">
			<li><?=sprintf(__('Your location: %s (%s)','edc'),$data['location'],$data['postal_code'])?></li>
			<li><?=sprintf(__('Your annual consumption: %s kWh','edc'),$data['annual_consumption'])?></li>
			<?php if($edc->is_order_step) : ?>
			<li><?=sprintf(__('Chosen tariff: %s','edc'),'<a href="javascript:void(0);" onclick="edc.popup(\'#edc_tariff_popup\');">'.$edc->tariff->title.'</a>')?></li>
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