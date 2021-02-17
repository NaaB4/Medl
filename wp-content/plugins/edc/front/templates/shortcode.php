<?php
	global $edc_counter;
	$edc_counter=is_numeric($edc_counter) ? ++$edc_counter : 0;
?>
<div class="edc">
	<div class="edc_tabs <?=isset($data['dop_class']) ? $data['dop_class'] : ''?>" id="edc_<?=$edc_counter?>" data-tab="<?=isset($data['active']) ? $data['active'] : ''?>">
		<ul class="navigation">
			<li data-class="theme_gas"><a href="<?=EDCH::getGasPageURL()?>"><?=__('Gas','edc')?></a></li>
			<li data-class="theme_electricity"><a href="<?=EDCH::getElectricityPageURL()?>"><?=__('Electricity','edc')?></a></li>
		</ul>
		<div class="items">
			<form class="edc_tab" action="<?=EDCH::getTariffsPageURL()?>" method="POST" onsubmit="return false;">
				<?php if(EDCH::is(EDCH::opts('get','edc_use_type_select','settings'))) : ?>
				<div class="col_2">
					<div>
						<div class="label">
							<div class="field edc_radio active">
								<input type="radio" id="edc_gas_business_<?=$edc_counter?>" name="edc_tariff_type" value="business" checked>
								<label for="edc_gas_business_<?=$edc_counter?>">Geschäftskunde</label>
							</div>
						</div>
					</div>
					<div>
						<div class="label">
							<div class="field edc_radio">
								<input type="radio" id="edc_gas_private_<?=$edc_counter?>" name="edc_tariff_type" value="private">
								<label for="edc_gas_private_<?=$edc_counter?>">Privatkunde</label>
							</div>
						</div>
					</div>
				</div>
				<?php endif; ?>
				<div class="col_2">
					<div>
						<div class="label">
							<label class="name"><?=__('Address','edc')?></label>
							<div class="field">
								<input oninput="edc.getPostalCodesList(event,this);" type="text" name="postcode" placeholder="<?=__('Postal code','edc')?>" data-required="numeric">
								<div class="required_text"><?=__('Must be a number','edc')?></div>
							</div>
						</div>
					</div>
					<div>
						<div class="label">
							<label class="name"><?=__('Location','edc')?></label>
							<div class="field">
								<select name="districts" data-required="1">
									<option value=""><?=__('Choose location','edc')?></option>
								</select>
								<div class="required_text"><?=__('This field required','edc')?></div>	
							</div>			
						</div>
					</div>
				</div>
				<div class="col_2">
					<div>
						<div class="label">
							<label class="name"><?=__('Annual consumption','edc')?></label>
							<div class="field">
								<input type="text" name="annual_consumption" placeholder="2500" data-required="numeric">
								<div class="required_text"><?=__('Must be a number','edc')?></div>
							</div>
						</div>
					</div>
					<div>
						<div class="label">
							<label class="name">&nbsp;</label>
							<div class="field">
								<div class="icons_list">
									<input type="radio" id="g_preset1_<?=$edc_counter?>" name="preset" value="1" >
									<label for="g_preset1_<?=$edc_counter?>" class="icon" onclick="edc.setAnnual(event,this);" data-value="<?=EDCH::opts('get','edc_per_year_1_gas','settings')?>">
										<div class="people_1"></div>
										<span class="value">1P.</span>
									</label>
									<input type="radio" id="g_preset2_<?=$edc_counter?>" name="preset" value="2">
									<label for="g_preset2_<?=$edc_counter?>" class="icon" onclick="edc.setAnnual(event,this);" data-value="<?=EDCH::opts('get','edc_per_year_2_gas','settings')?>">
										<div class="people_2"></div>
										<span class="value">2P.</span>
									</label>
									<input type="radio" id="g_preset3_<?=$edc_counter?>" name="preset" value="3">
									<label for="g_preset3_<?=$edc_counter?>" class="icon" onclick="edc.setAnnual(event,this);" data-value="<?=EDCH::opts('get','edc_per_year_3_gas','settings')?>">
										<div class="people_3"></div>
										<span class="value">3P.</span>
									</label>
									<input type="radio" id="g_preset4_<?=$edc_counter?>" name="preset" value="4">
									<label for="g_preset4_<?=$edc_counter?>" class="icon" onclick="edc.setAnnual(event,this);" data-value="<?=EDCH::opts('get','edc_per_year_4_gas','settings')?>">
										<div class="people_4"></div>
										<span class="value">4P.</span>
									</label>
								</div>
							</div>					
						</div>
					</div>
				</div>
				<div class="submit_group">
					<button type="submit" class="btn" onclick="edc.submit(this,{callback:'afterStepProcess'});"><svg aria-hidden="true" class="svg_inline edc_spinner" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z"></path></svg><?=__('Calculate gas price','edc')?></button>
					<input type="hidden" name="edc_processing" value="1">
					<input type="hidden" name="validate" value="1">
					<input type="hidden" name="step_1" value="1">
					<input type="hidden" name="type" value="gas">
				</div>
				<div class="edc_ajax_result"></div>
			</form>
			<form class="edc_tab" action="<?=EDCH::getTariffsPageURL()?>" method="POST" onsubmit="return false;">
				<?php if(EDCH::is(EDCH::opts('get','edc_use_type_select','settings'))) : ?>
				<div class="col_2">
					<div>
						<div class="label">
							<div class="field edc_radio active">
								<input type="radio" id="edc_strom_business_<?=$edc_counter?>" name="edc_tariff_type" value="business" checked>
								<label for="edc_strom_business_<?=$edc_counter?>">Geschäftskunde</label>
							</div>
						</div>
					</div>
					<div>
						<div class="label">
							<div class="field edc_radio">
								<input type="radio" id="edc_strom_private_<?=$edc_counter?>" name="edc_tariff_type" value="private">
								<label for="edc_strom_private_<?=$edc_counter?>">Privatkunde</label>
							</div>
						</div>
					</div>
				</div>
				<?php endif; ?>
				<div class="col_2">
					<div>
						<div class="label">
							<label class="name"><?=__('Address','edc')?></label>
							<div class="field">
								<input oninput="edc.getPostalCodesList(event,this);" type="text" name="postcode" placeholder="<?=__('Postal code','edc')?>" data-required="numeric">
								<div class="required_text"><?=__('Must be a number','edc')?></div>
							</div>
						</div>
					</div>
					<div>
						<div class="label">
							<label class="name"><?=__('Location','edc')?></label>
							<div class="field">
								<select name="districts" data-required="1">
									<option value=""><?=__('Choose location','edc')?></option>
								</select>
								<div class="required_text"><?=__('This field required','edc')?></div>
							</div>					
						</div>
					</div>
				</div>
				<div class="col_2">
					<div>
						<div class="label">
							<label class="name"><?=__('Annual consumption','edc')?></label>
							<div class="field">
								<input type="text" name="annual_consumption" placeholder="2500" data-required="numeric">
								<div class="required_text"><?=__('Must be a number','edc')?></div>
							</div>
						</div>
					</div>
					<div>
						<div class="label">
							<label class="name">&nbsp;</label>
							<div class="field">
								<div class="icons_list">
									<input type="radio" id="e_preset1_<?=$edc_counter?>" name="preset" value="1">
									<label for="e_preset1_<?=$edc_counter?>" class="icon small" onclick="edc.setAnnual(event,this);" data-value="<?=EDCH::opts('get','edc_per_year_1_electricity','settings')?>">
										<div class="house_1"></div>
										<span class="value">1P.</span>
									</label>
									<input type="radio" id="e_preset2_<?=$edc_counter?>" name="preset" value="2">
									<label for="e_preset2_<?=$edc_counter?>" class="icon small" onclick="edc.setAnnual(event,this);" data-value="<?=EDCH::opts('get','edc_per_year_2_electricity','settings')?>">
										<div class="house_2"></div>
										<span class="value">2P.</span>
									</label>
									<input type="radio" id="e_preset3_<?=$edc_counter?>" name="preset" value="3">
									<label for="e_preset3_<?=$edc_counter?>" class="icon small" onclick="edc.setAnnual(event,this);" data-value="<?=EDCH::opts('get','edc_per_year_3_electricity','settings')?>">
										<div class="house_3"></div>
										<span class="value">3P.</span>
									</label>
									<input type="radio" id="e_preset4_<?=$edc_counter?>" name="preset" value="4">
									<label for="e_preset4_<?=$edc_counter?>" class="icon small" onclick="edc.setAnnual(event,this);" data-value="<?=EDCH::opts('get','edc_per_year_4_electricity','settings')?>">
										<div class="house_4"></div>
										<span class="value">4P.</span>
									</label>
								</div>
							</div>					
						</div>
					</div>
				</div>
				<div class="submit_group">
					<button type="submit" class="btn" onclick="edc.submit(this,{callback:'afterStepProcess'});"><svg aria-hidden="true" class="svg_inline edc_spinner" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z"></path></svg><?=__('Calculate electricity price','edc')?></button>
					<input type="hidden" name="edc_processing" value="1">
					<input type="hidden" name="validate" value="1">
					<input type="hidden" name="step_1" value="1">
					<input type="hidden" name="type" value="electricity">
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