<?php if(EDCH::is($data['filled'])) : ?>
	<a class="et_pb_button et_pb_pricing_table_button" href="javascipt:void(0);" onclick="edc.showTariffsPopup(<?=$data['example_annual_consumption']?>,'<?=$data['type']?>','<?=$data['tariff_ids']?>',<?=$data['young']?>);">Her damit!</a>
<?php else : ?>
	<a class="et_pb_button" href="javascipt:void(0);" onclick="edc.showTariffsPopup(<?=$data['example_annual_consumption']?>,'<?=$data['type']?>','<?=$data['tariff_ids']?>',<?=$data['young']?>);">Her damit!</a>
<?php endif; ?>