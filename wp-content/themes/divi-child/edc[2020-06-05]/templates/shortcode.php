<?php
	global $edc_counter;
	$edc_counter=is_numeric($edc_counter) ? ++$edc_counter : 0;
	
	list($el_ps,$el_sel)=EDC_Extension::inst()->getPersons('electricity');
	list($gas_ps,$gas_sel)=EDC_Extension::inst()->getPersons('gas');
	list($c_ps,$c_sel)=EDC_Extension::inst()->getPersons('combi');
?>
<div class="edc">
	<div class="edc_tabs <?=isset($data['dop_class']) ? $data['dop_class'] : ''?>" id="edc_<?=$edc_counter?>" data-tab="<?=isset($data['active']) ? $data['active'] : ''?>">
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
					<span>Gas</span>
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
				<div class="edc_form_row">
					<div class="edc_persons">
						<?php foreach($el_ps as $k=>$el) : ?>
							<input type="radio" id="st_preset<?=$k?>_<?=$edc_counter?>" name="persons" value="1" <?=($el[1]==$el_sel ? 'checked="checked"' : '')?>>
							<label for="st_preset<?=$k?>_<?=$edc_counter?>" onclick="edc.setAnnual(event,this);" data-value="<?=$el[1]?>">
								<div><?=$el[0]?></div>
							</label>
						<?php endforeach; ?>
						<div class="text">Personen</div>
					</div>
				</div>
				<div class="edc_form_row annual_consumption">
					<div class="minus" onclick="edc.addConsumption(this,-1000);">-</div>
					<div>
						<input type="text" name="annual_consumption" oninput="edc.fixFontSize(this);" value="<?=$el_sel?>" placeholder="1000">
						<div class="subtext">kWh Verbrauch</div>
					</div>
					<div class="plus" onclick="edc.addConsumption(this,1000);">+</div>
				</div>
				<div class="edc_form_row postal_code">
					<input type="text" name="postcode" placeholder="PLZ" data-required="numeric" oninput="edc.getPostalCodesList(event,this);" >
				</div>
				<div class="edc_form_row location">
					<select name="districts" data-required="1">
						<option value=""><?=__('Stadt auswählen','medl')?></option>
					</select>
				</div>
				<div class="edc_form_row checkbox">					
					<input type="checkbox" id="st_lower_30_<?=$edc_counter?>" name="lower_30" value="1">
					<label for="st_lower_30_<?=$edc_counter?>" class="edc_checkbox">Ich bin unter 30 Jahre alt</label>
				</div>
				<div class="submit_group">
					<button type="submit" class="btn" onclick="edc.submit(this,{callback:'afterStepProcess'});"><svg aria-hidden="true" class="svg_inline edc_spinner" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z"></path></svg>Jetzt berechnen</button><br><br>
					<a type="button" class="close_btn" href="javascript:void(0);" onclick="edc.closeTabs(this);">Schließen</a>
					<input type="hidden" name="edc_processing" value="1">
					<input type="hidden" name="validate" value="1">
					<input type="hidden" name="step_1" value="1">
					<input type="hidden" name="type" value="electricity">
				</div>
				<div class="edc_ajax_result"></div>
			</form>
			<form class="edc_tab" action="<?=EDCH::getTariffsPageURL()?>" method="POST" onsubmit="return false;">
				<div class="edc_form_row">
					<div class="edc_persons">
						<?php foreach($gas_ps as $k=>$el) : ?>
							<input type="radio" id="g_preset<?=$k?>_<?=$edc_counter?>" name="persons" value="1" <?=($el[1]==$gas_sel ? 'checked="checked"' : '')?>>
							<label for="g_preset<?=$k?>_<?=$edc_counter?>" onclick="edc.setAnnual(event,this);" data-value="<?=$el[1]?>">
								<div><?=$el[0]?></div>
							</label>
						<?php endforeach; ?>
						<div class="text">Wohnfläche in m&sup2;</div>
					</div>
				</div>
				<div class="edc_form_row annual_consumption">
					<div class="minus" onclick="edc.addConsumption(this,-1000);">-</div>
					<div>
						<input type="text" name="annual_consumption" oninput="edc.fixFontSize(this);" placeholder="1000" value="<?=$gas_sel?>">
						<div class="subtext">kWh Verbrauch</div>
					</div>
					<div class="plus" onclick="edc.addConsumption(this,1000);">+</div>
				</div>
				<div class="edc_form_row postal_code">
					<input type="text" name="postcode" placeholder="PLZ" data-required="numeric" oninput="edc.getPostalCodesList(event,this);" >
				</div>
				<div class="edc_form_row location">
					<select name="districts" data-required="1">
						<option value=""><?=__('Stadt auswählen','medl')?></option>
					</select>
				</div>
				<div class="edc_form_row checkbox">					
					<input type="checkbox" id="g_lower_30_<?=$edc_counter?>" name="lower_30" value="1">
					<label for="g_lower_30_<?=$edc_counter?>" class="edc_checkbox">Ich bin unter 30 Jahre alt</label>
				</div>
				<div class="submit_group">
					<button type="submit" class="btn" onclick="edc.submit(this,{callback:'afterStepProcess'});"><svg aria-hidden="true" class="svg_inline edc_spinner" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z"></path></svg>Jetzt berechnen</button><br><br>
					<a type="button" class="close_btn" href="javascript:void(0);" onclick="edc.closeTabs(this);">Schließen</a>
					<input type="hidden" name="edc_processing" value="1">
					<input type="hidden" name="validate" value="1">
					<input type="hidden" name="step_1" value="1">
					<input type="hidden" name="type" value="gas">
				</div>
				<div class="edc_ajax_result"></div>
			</form>
			<form class="edc_tab" action="<?=EDCH::getTariffsPageURL()?>" method="POST" onsubmit="return false;">
				<div class="edc_form_row annual_holder">
					<div class="edc_persons">
						<?php foreach($el_ps as $k=>$el) : ?>
							<input type="radio" id="cst_preset<?=$k?>_<?=$edc_counter?>" name="st_persons" value="1" <?=($el[1]==$el_sel ? 'checked="checked"' : '')?>>
							<label for="cst_preset<?=$k?>_<?=$edc_counter?>" onclick="edc.setAnnualCombi(event,this);" data-value="<?=$el[1]?>">
								<div><?=$el[0]?></div>
							</label>
						<?php endforeach; ?>
						<div class="text">Personen</div>
					</div>
					<div class="annual_consumption">
						<input type="text" name="annual_consumption_el" placeholder="1000" value="<?=$el_sel?>">
						<div class="subtext">kWh Verbrauch (Strom)</div>
					</div>
				</div>
				<div class="edc_form_row annual_holder">
					<div class="edc_persons">
						<?php foreach($gas_ps as $k=>$el) : ?>
							<input type="radio" id="cg_preset<?=$k?>_<?=$edc_counter?>" name="g_persons" value="1" <?=($el[1]==$gas_sel ? 'checked="checked"' : '')?>>
							<label for="cg_preset<?=$k?>_<?=$edc_counter?>" onclick="edc.setAnnualCombi(event,this);" data-value="<?=$el[1]?>">
								<div><?=str_replace('m&sup2;','',$el[0])?></div>
							</label>
						<?php endforeach; ?>
						<div class="text">Wohnfläche in m&sup2;</div>
					</div>
					<div class="annual_consumption">
						<input type="text" name="annual_consumption_gas" placeholder="1000" value="<?=$gas_sel?>">
						<div class="subtext">kWh Verbrauch (Gas)</div>
					</div>
				</div>
				<div class="edc_form_row postal_code">
					<input type="text" name="postcode" placeholder="PLZ" data-required="numeric" oninput="edc.getPostalCodesList(event,this);" >
				</div>
				<div class="edc_form_row location">
					<select name="districts" data-required="1">
						<option value=""><?=__('Stadt auswählen','medl')?></option>
					</select>
				</div>
				<div class="edc_form_row checkbox">					
					<input type="checkbox" id="c_lower_30_<?=$edc_counter?>" name="lower_30" value="1">
					<label for="c_lower_30_<?=$edc_counter?>" class="edc_checkbox">Ich bin unter 30 Jahre alt</label>
				</div>
				<div class="submit_group">
					<button type="submit" class="btn" onclick="edc.submit(this,{callback:'afterStepProcess'});"><svg aria-hidden="true" class="svg_inline edc_spinner" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z"></path></svg>Jetzt berechnen</button><br><br>
					<a type="button" class="close_btn" href="javascript:void(0);" onclick="edc.closeTabs(this);">Schließen</a>
					<input type="hidden" name="edc_processing" value="1">
					<input type="hidden" name="validate" value="1">
					<input type="hidden" name="step_1" value="1">
					<input type="hidden" name="type" value="combi">
				</div>
				<div class="edc_ajax_result"></div>
			</form>
		</div>
	</div>
	<script type="text/javascript">
		window.addEventListener('load',function(){
			<?php if(EDCH::getTabsType()=='tabs') :?>
				edc.initializeTabs(document.querySelector('#edc_<?=$edc_counter?>'),document.querySelector('#edc_<?=$edc_counter?>').dataset.tab);
			<?php else : ?>
				edc.initializeNoTabs(document.querySelector('#edc_<?=$edc_counter?>'),document.querySelector('#edc_<?=$edc_counter?>').dataset.tab);		
			<?php endif; ?>
		});
	</script>
</div>