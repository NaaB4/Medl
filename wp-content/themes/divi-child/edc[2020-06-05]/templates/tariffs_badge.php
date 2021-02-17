<?php
	global $edc_counter;
	$edc_counter=is_numeric($edc_counter) ? ++$edc_counter : 0;
?>
<div class="edc">
	<div class="tarifbox strompur">
		<div class="tarifbox-body">
			<div class="col-1 col">
				<p class="kwhj"><?=EDCH::displayPrice($data['from_annual_consumption'],'',false)?> bis <?=EDCH::displayPrice($data['to_annual_consumption'],'',false)?> kWh/Jahr</p>
				<p class="arbeitspreis">Arbeitspreis pro kWh: <strong><?=EDCH::displayPrice($data['per_kwh'],'',false)?> Cent</strong></p>
				<p class="grundpreis">Grundpreis pro Monat: <strong>ab <?=EDCH::displayPrice($data['per_month'],'',false)?> €</strong></p>
				<p class="beispielrechnung"><span class="beispiel-lable">Beispiel:</span> Du verbrauchst <span class="green"><?=EDCH::displayPrice($data['example_annual_consumption'],'',false)?> kWh</span>/Jahr.</p>
				<p class="beispielrechnung-ergebnis">Dann zahlst du <span class="et_pb_et_price"><span class="et_pb_sum"><?=EDCH::displayPrice($data['total_price'])?></span></span> brutto im Monat.</p>
				<div class="et_pb_button_wrapper tarifbox-button"><a class="et_pb_button et_pb_pricing_table_button" href="javascipt:void(0);" onclick="edc.showTariffsPopup(<?=$data['example_annual_consumption']?>,'<?=$data['type']?>','<?=$data['tariff_ids']?>',<?=$data['young']?>);">Her damit!</a></div>
			</div>
		</div>
		<div class="tarifbox-footer">
			<div class="download"><a href="<?=$data['price_link']?>">Preisblatt</a> | <a href="<?=$data['agb_link']?>">AGB</a></div>
		</div>
	</div>
</div>