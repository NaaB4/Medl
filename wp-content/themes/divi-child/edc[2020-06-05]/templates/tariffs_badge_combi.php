<?php
	global $edc_counter;
	$edc_counter=is_numeric($edc_counter) ? ++$edc_counter : 0;
?>
<div class="edc tarifbox kombi">
	<div class="tarifbox-body">
		<div class="col-1 col gruenstrom">
			<img class="lazy-loaded" src="/wp-content/uploads/strom-icon.png" data-lazy-type="image" data-src="/wp-content/uploads/strom-icon.png"><noscript><img src="/wp-content/uploads/strom-icon.png"></noscript>
			<h4><span class="gruenstrominfo">Grünstrom</span></h4>
			<p class="arbeitspreis">Arbeitspreis pro kWh:<br><strong>ab <?=EDCH::displayPrice($data['st_per_kwh'],'',false)?> Cent</strong></p>
			<p class="grundpreis">Grundpreis pro Monat:<br><strong>ab <?=EDCH::displayPrice($data['st_per_month'],'',false)?> €</strong></p>
		</div>
		<div class="col-2 col erdgas">
			<img class="lazy-loaded" src="/wp-content/uploads/erdgas-icon.png" data-lazy-type="image" data-src="/wp-content/uploads/erdgas-icon.png"><noscript><img src="/wp-content/uploads/erdgas-icon.png"></noscript>
			<h4>Erdgas</h4>
			<p class="arbeitspreis">Arbeitspreis pro kWh:<br><strong>ab <?=EDCH::displayPrice($data['g_per_kwh'],'',false)?> Cent</strong></p>
			<p class="grundpreis">Grundpreis pro Monat:<br><strong>ab <?=EDCH::displayPrice($data['g_per_month'],'',false)?> €</strong></p>
		</div>
		<div class="plus-icon">
			<img class="lazy-loaded" src="/wp-content/uploads/iconmonstr-plus-5.png" data-lazy-type="image" data-src="/wp-content/uploads/iconmonstr-plus-5.png"><noscript><img src="/wp-content/uploads/iconmonstr-plus-5.png"></noscript>
		</div>
	</div>
	<div class="kombi-beispiel-body">
		<div class="col-3 col kombiBeispiel">
			<p class="beispielrechnung"><span class="beispiel-lable">
				Beispiel:<br></span> Du verbrauchst <span class="green"><?=EDCH::displayPrice($data['st_example_annual_consumption'],'',false)?> kWh</span> Strom <br>und <span class="green"><?=EDCH::displayPrice($data['g_example_annual_consumption'],'',false)?> kWh</span> Erdgas pro Jahr .
			</p>
			<p class="beispielrechnung-ergebnis">
				Dann zahlst du <span class="et_pb_et_price"><span class="et_pb_sum"><?=EDCH::displayPrice($data['total_price'])?></span></span> brutto im Monat.
			</p>
		</div>
	</div>
	<div class="et_pb_button_wrapper tarifbox-button"><a class="et_pb_button et_pb_pricing_table_button" href="javascipt:void(0);" onclick="edc.showTariffsPopup([<?=$data['st_example_annual_consumption']?>,<?=$data['g_example_annual_consumption']?>],'<?=$data['type']?>','<?=$data['tariff_ids']?>',<?=$data['young']?>);">Her damit!</a></div>
	<div class="tarifbox-footer">
		<div class="download"><a href="<?=$data['price_link']?>">Preisblatt</a> | <a href="<?=$data['agb_link']?>">AGB</a></div>
	</div>
</div>