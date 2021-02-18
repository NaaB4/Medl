<?php
	global $edc_counter;
	$edc_counter=is_numeric($edc_counter) ? ++$edc_counter : 0;	
	list($el_ps,$el_sel)=EDC_Extension::inst()->getPersons('electricity');
	list($gas_ps,$gas_sel)=EDC_Extension::inst()->getPersons('gas');
	list($c_ps,$c_sel)=EDC_Extension::inst()->getPersons('combi');
?>
<div class="edc">
	<div class="edc_popup tariffs_popup" id="edc_tariffs_popup">
		<div class="edc_popup_wrapper">
			<div class="edc_close" onclick="edc.popup(this,true);">&times;</div>
			<div class="edc_popup_content">
				<div class="edc_tabs" id="edc_<?=$edc_counter?>">
					<ul class="navigation">
						<li data-class="theme_electricity" class="strom-link">
							<a>
								<img src="/wp-content/uploads/strom-icon-light.png">
								<span>Strom</span>
							</a>
						</li>
						<li data-class="theme_gas" class="erdgas-link">
							<a>
								<img src="/wp-content/uploads/erdgas-icon-light.png">
								<span>Erdgas</span>
							</a>
						</li>
						<li data-class="theme_combi" class="kombi-link">
							<a>
								<img src="/wp-content/uploads/kombi-icon-light.png">
								<span>Kombitarife</span>
							</a>
						</li>
					</ul>
					<div class="items">
						<form class="edc_tab" action="<?=EDCH::getTariffsPageURL()?>" method="POST" onsubmit="return false;">
							<div class="edc_form_row annual_consumption">
								<div class="minus" onclick="edc.addConsumption(this,-100);">-</div>
								<div>
									<input type="text" name="annual_consumption" value="<?=$el_sel?>" placeholder="1000">
									<div class="subtext">kWh Verbrauch</div>
								</div>
								<div class="plus" onclick="edc.addConsumption(this,100);">+</div>
							</div>
							<div class="edc_form_row postal_code">
								<input type="text" name="postcode" placeholder="<?=__('Postal code','edc')?>" data-required="numeric" oninput="edc.getPostalCodesList(event,this);" >
							</div>
                            <div class="edc_form_row location">
                                <select onchange="edc.getStreetList(event,this);" name="districts" data-required="1">
                                    <option value=""><?=__('Stadt auswählen','medl')?></option>
                                </select>
                            </div>
                            <div class="edc_form_row street">
                                <select name="street" data-required="1">
                                    <option value=""><?=__('Street','edc')?></option>
                                </select>
                            </div>
							<div class="edc_form_row checkbox young">					
								<input type="checkbox" id="st_lower_30_<?=$edc_counter?>" name="lower_30" value="1">
								<label for="st_lower_30_<?=$edc_counter?>" class="edc_checkbox">Ich bin unter 30 Jahre alt</label>
							</div>
							<div class="submit_group">
								<button type="submit" class="btn" onclick="edc.submit(this,{callback:'afterStepProcess'});"><svg aria-hidden="true" class="svg_inline edc_spinner" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z"></path></svg>Jetzt berechnen</button>
								<input type="hidden" name="edc_processing" value="1">
								<input type="hidden" name="validate" value="1">
								<input type="hidden" name="step_1" value="1">
								<input type="hidden" name="type" value="electricity">
								<input type="hidden" name="tariff_ids">
							</div>
							<div class="edc_ajax_result"></div>
						</form>
						<form class="edc_tab" action="<?=EDCH::getTariffsPageURL()?>" method="POST" onsubmit="return false;">
							<div class="edc_form_row annual_consumption">
								<div class="minus" onclick="edc.addConsumption(this,-100);">-</div>
								<div>
									<input type="text" name="annual_consumption" placeholder="1000" value="<?=$gas_sel?>">
									<div class="subtext">kWh Verbrauch</div>
								</div>
								<div class="plus" onclick="edc.addConsumption(this,100);">+</div>
							</div>
							<div class="edc_form_row postal_code">
								<input type="text" name="postcode" placeholder="<?=__('Postal code','edc')?>" data-required="numeric" oninput="edc.getPostalCodesList(event,this);" >
							</div>
                            <div class="edc_form_row location">
                                <select onchange="edc.getStreetList(event,this);" name="districts" data-required="1">
                                    <option value=""><?=__('Stadt auswählen','medl')?></option>
                                </select>
                            </div>
                            <div class="edc_form_row street">
                                <select name="street" data-required="1">
                                    <option value=""><?=__('Street','edc')?></option>
                                </select>
                            </div>
							<div class="edc_form_row checkbox young">					
								<input type="checkbox" id="g_lower_30_<?=$edc_counter?>" name="lower_30" value="1">
								<label for="g_lower_30_<?=$edc_counter?>" class="edc_checkbox">Ich bin unter 30 Jahre alt</label>
							</div>
							<div class="submit_group">
								<button type="submit" class="btn" onclick="edc.submit(this,{callback:'afterStepProcess'});"><svg aria-hidden="true" class="svg_inline edc_spinner" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z"></path></svg>Jetzt berechnen</button>
								<input type="hidden" name="edc_processing" value="1">
								<input type="hidden" name="validate" value="1">
								<input type="hidden" name="step_1" value="1">
								<input type="hidden" name="type" value="gas">
								<input type="hidden" name="tariff_ids">
							</div>
							<div class="edc_ajax_result"></div>
						</form>
						<form class="edc_tab" action="<?=EDCH::getTariffsPageURL()?>" method="POST" onsubmit="return false;">
							<div class="edc_form_row annual_holder">
								<div class="annual_consumption">
									<input type="text" name="annual_consumption_el" placeholder="1000" value="<?=$el_sel?>">
									<div class="subtext">kWh Verbrauch</div>
								</div>
								<div class="ac_type">Strom</div>
							</div>
							<div class="edc_form_row annual_holder">
								<div class="annual_consumption">
									<input type="text" name="annual_consumption_gas" placeholder="1000" value="<?=$gas_sel?>">
									<div class="subtext">kWh Verbrauch</div>
								</div>
								<div class="ac_type">Erdgas</div>
							</div>
							<div class="edc_form_row postal_code">
								<input type="text" name="postcode" placeholder="<?=__('Postal code','edc')?>" data-required="numeric" oninput="edc.getPostalCodesList(event,this);" >
							</div>
                            <div class="edc_form_row location">
                                <select onchange="edc.getStreetList(event,this);" name="districts" data-required="1">
                                    <option value=""><?=__('Stadt auswählen','medl')?></option>
                                </select>
                            </div>
                            <div class="edc_form_row street">
                                <select name="street" data-required="1">
                                    <option value=""><?=__('Street','edc')?></option>
                                </select>
                            </div>
							<div class="edc_form_row checkbox young">					
								<input type="checkbox" id="c_lower_30_<?=$edc_counter?>" name="lower_30" value="1">
								<label for="c_lower_30_<?=$edc_counter?>" class="edc_checkbox">Ich bin unter 30 Jahre alt</label>
							</div>
							<div class="submit_group">
								<button type="submit" class="btn" onclick="edc.submit(this,{callback:'afterStepProcess'});"><svg aria-hidden="true" class="svg_inline edc_spinner" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z"></path></svg>Jetzt berechnen</button>
								<input type="hidden" name="edc_processing" value="1">
								<input type="hidden" name="validate" value="1">
								<input type="hidden" name="step_1" value="1">
								<input type="hidden" name="type" value="combi">
								<input type="hidden" name="tariff_ids">
							</div>
							<div class="edc_ajax_result"></div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>