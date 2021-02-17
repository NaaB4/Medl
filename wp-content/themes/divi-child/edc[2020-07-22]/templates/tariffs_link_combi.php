<?php if(EDCH::is($data['filled'])) : ?>
	<a class="et_pb_button et_pb_pricing_table_button" href="javascript:void(0);" onclick="edc.showTariffsPopup([<?=$data['st_example_annual_consumption']?>,<?=$data['g_example_annual_consumption']?>],'<?=$data['type']?>','<?=$data['tariff_ids']?>',<?=$data['young']?>);">Her damit!</a>
<?php else : ?>
	<a class="et_pb_button" href="javascript:void(0);" onclick="edc.showTariffsPopup([<?=$data['st_example_annual_consumption']?>,<?=$data['g_example_annual_consumption']?>],'<?=$data['type']?>','<?=$data['tariff_ids']?>',<?=$data['young']?>);">Her damit!</a>
<?php endif; ?>