<?php
	global $edc_counter;
	$edc_counter=is_numeric($edc_counter) ? ++$edc_counter : 0;	
	list($el_ps,$el_sel)=EDC_Extension::inst()->getPersons('electricity');
	list($gas_ps,$gas_sel)=EDC_Extension::inst()->getPersons('gas');
	list($c_ps,$c_sel)=EDC_Extension::inst()->getPersons('combi');
?>
<div class="edc">
	<div class="edc_popup" id="edc_popup">
		<div class="edc_popup_wrapper">
			<div class="edc_close" onclick="edc.popup(this,true);">&times;</div>
			<div class="edc_popup_content">
				<form action="" method="POST" onsubmit="return false;">
					<div class="form_row">
						<div class="label">Name <span class="red">*</span></div>
						<div class="field to_top">
							<input type="text" name="name" data-required="1">
							<div class="required_text">Dieses Feld ist Pflichtfeld</div>
						</div>
					</div>
					<div class="form_row">
						<div class="label">Mailadresse <span class="red">*</span></div>
						<div class="field to_top">
							<input type="text" name="email" data-required="1">
							<div class="required_text">Dieses Feld ist Pflichtfeld</div>
						</div>
					</div>
					<div class="form_row">
						<div class="label">Telefonnummer <span class="red">*</span></div>
						<div class="field to_top">
							<input type="text" name="phone" data-required="1">
							<div class="required_text">Dieses Feld ist Pflichtfeld</div>
						</div>
					</div>
					<div class="form_row">
						<div class="label">Geburtsdatum <span class="red">*</span></div>
						<div class="field to_top">
							<input type="text" name="birthdate" class="datepicker" data-required="1">
							<div class="required_text">Dieses Feld ist Pflichtfeld</div>
						</div>
					</div>
					<div class="form_row">
						<div class="label">Straße &amp; Hausnummer <span class="red">*</span></div>
						<div class="field to_top">
							<input type="text" name="street_and_house" data-required="1">
							<div class="required_text">Dieses Feld ist Pflichtfeld</div>
						</div>
					</div>
					<div class="form_row">
						<div class="label">PLZ &amp; Ort <span class="red">*</span></div>
						<div class="field to_top">
							<input type="text" name="plz_and_ort" data-required="1">
								<div class="required_text">Dieses Feld ist Pflichtfeld</div>
						</div>
					</div>
					<div class="form_row">
						<div class="field to_top">							
							<div class="field type2 edc_radio">
								<input type="radio" name="type" id="edc_tariff_type_1" style="display:none;" value="gas" data-required="1" onchange="edc.popupTariffChanged(this);" checked>
								<label for="edc_tariff_type_1">Gas</label>
								<input type="radio" name="type" id="edc_tariff_type_2" style="display:none;" value="strom" data-required="1" onchange="edc.popupTariffChanged(this);">
								<label for="edc_tariff_type_2">Strom</label>
								<input type="radio" name="type" id="edc_tariff_type_3" style="display:none;" value="combi" data-required="1" onchange="edc.popupTariffChanged(this);">
								<label for="edc_tariff_type_3">Kombi</label>
								<div class="required_text">Dieses Feld ist Pflichtfeld</div>
							</div>
						</div>
					</div>
					<div id="edc_popup_square">
						<div class="form_row">
							<div class="label">Art des Wechsels <span class="red">*</span></div>
							<div class="field to_top">
								<select name="type_of_change">
                                    <option value="Neueinzug">Neueinzug</option>
                                    <option value="Lieferantenwechsel">Lieferantenwechsel</option>
                                </select>
									<div class="required_text">Dieses Feld ist Pflichtfeld</div>
							</div>
						</div>
					</div>
					<div id="edc_popup_consumption" style="display:none;">
						<div class="form_row">
							<div class="label">Verbrauch in kWh <span class="red">*</span></div>
							<div class="field to_top">
								<input type="text" name="consumption">
									<div class="required_text">Dieses Feld ist Pflichtfeld</div>
							</div>
						</div>
					</div>
					<div class="submit_group">
						<button type="submit" class="btn" onclick="edc.submit(this,{callback:'edcPopupProcess',validate:'edcPopupValidate'});"><svg aria-hidden="true" class="svg_inline edc_spinner" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z"></path></svg>Senden</button>
						<input type="hidden" name="edc_popup_form" value="1">
					</div>
					<div class="edc_ajax_result"></div>
				</form>
			</div>
		</div>
	</div>
</div>