<div class="edc">
	<div class="container">
		<div class="edc_list">
			<div class="items">
				<?php foreach($data['tariffs'] as $tariff) : ?>
					<?=EDCH::loadTemplate('tariffs_single',array('tariff'=>$tariff))?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
<form method="POST" action="<?=EDCH::getOrderFormURL()?>" name="edc_tariff_form" style="display:none;">
	<input type="hidden" name="steps_data" value="<?=$data['step_data']?>">
	<input type="hidden" name="id_tariff" value="">	
	<input type="hidden" name="edc_processing" value="1">
	<input type="hidden" name="validate" value="1">
	<input type="hidden" name="step_2" value="1">
	<input type="hidden" name="goodies" value="">
</form>