<div class="edc theme_<?=EDCH::proceedType($data['tariff']->type,true)?>">
    <section class="order_wrapper">
		<div class="container">
			<form class="order_form" name="edc_order_form" action="<?=EDCH::getResultPageURL($data['tariff']->id)?>" method="POST" onsubmit="return false;">
				<div class="form_wrapper">
					<div class="required_info"><?=__('Fields marked with * are required','edc')?></div>
					<div class="fieldset">
						<div class="col_1_2">
							<div class="name nowrap"><?=__('Gender','edc')?> <span class="red">*</span>:</div>
							<div class="field">
								<select name="edc_anrede"><?=EDCH::simpleOptions(array(
									array('name'=>__('Man','edc'),'value'=>'m'),
									array('name'=>__('Woman','edc'),'value'=>'w'),
								))?>
								</select>
								<div class="required_text"><?=__('Choose you gender','edc')?></div>
							</div>
						</div>
						<div class="col_1_2">
							<div class="name nowrap"><?=__('Birth date','edc')?> <span class="red">*</span>:</div>
							<div class="field to_bottom">
								<input type="text" name="edc_date_of_birth" value="" class="birthdate" data-required="age">
								<div class="required_text"><?=__('Choose your birth date','edc')?></div>
							</div>
						</div>
					</div>
					<div class="fieldset">
						<div class="col_1_2">
							<div class="name"><?=__('Name','edc')?> <span class="red">*</span>:</div>
							<div class="field">
								<input type="text" name="edc_first_name" data-required="1" value="">
								<div class="required_text"><?=__('Enter your name','edc')?></div>
							</div>
						</div>
						<div class="col_1_2">
							<div class="name"><?=__('Last name','edc')?> <span class="red">*</span>:</div>
							<div class="field">
								<input name="edc_name" value=""  data-required="1" type="text">
								<div class="required_text"><?=__('Enter your last name','edc')?></div>
							</div>
						</div>
					</div>
					<div class="fieldset">
						<div class="col_1_2">
							<div class="name"><?=__('Street','edc')?>  <span class="red">*</span>:</div>
							<div class="field">								
								<select name="edc_street" value="" data-required="1">
									<option value=""><?=__('Choose','edc')?></option>
									<?=$data['streets_options']?>
								</select>
								<div class="required_text"><?=__('Enter your street','edc')?></div>
							</div>
						</div>
						<div class="col_1_2">
							<div class="name"><?=__('House number','edc')?> <span class="red">*</span>:</div>
							<div class="field">
								<input type="text" data-required="1" name="edc_house" value="" class="only_numeric">
								<div class="required_text"><?=__('Enter your house','edc')?></div>
							</div>
						</div>
					</div>
					<div class="fieldset">
						<div class="col_1_2">
							<div class="name"><?=__('Postal code','edc')?> <span class="red">*</span>:</div>
							<div class="field">
								<input type="text" name="edc_postal_code" value="<?=$data["postal_code"]?>" <?=($data['skiped_first'] ? '' : 'disabled')?> data-required="numeric">
								<div class="required_text"><?=__('Enter postal code','edc')?></div>
							</div>
						</div>
						<div class="col_1_2">
							<div class="name"><?=__('Location','edc')?> <span class="red">*</span>:</div>
							<div class="field">
								<input type="text" name="edc_location" value="<?=$data['district_name']?>" <?=($data['skiped_first'] ? '' : 'disabled')?> data-required="1">
								<div class="required_text"><?=__('Enter location','edc')?></div>
							</div>
						</div>
					</div>

					<div class="fieldset">
						<div class="col_2_6">
							<div class="name"><?=__('Phone','edc')?> <span class="red">*</span>:</div>
							<div class="field">
								<input type="text" name="edc_phone" value="" data-required="1">
								<div class="required_text"><?=__('Required field','edc')?></div>
							</div>
						</div>
						<div class="col_2_6">
							<div class="name"><?=__('E-mail','edc')?> <span class="red">*</span>:</div>
							<div class="field">
								<input type="text" name="edc_email" value="" data-required="email">
								<div class="required_text"><?=__('Enter e-mail','edc')?></div>
							</div>
						</div>
						<div class="col_2_6">
							<div class="name"><?=__('E-mail confirmation','edc') ?> <span class="red">*</span>:</div>					
							<div class="field">
								<input type="text" name="edc_email_confirm" value="" data-required="email">
								<div class="required_text"><?=__('Confirm e-mail','edc')?></div>
							</div>
						</div>
					</div>
					<div class="form_title"><?=__('Abweichende Rechnungsadresse','medl')?></div>
					<div class="fieldset tleft inline near">					
						<div>
							<div class="field type2 edc_radio">
								<input type="radio" name="edc_other_debit" id="etc" style="display:none;" value="1" data-required="1">
								<label for="etc">Ja</label>
								<div class="required_text">Dieses Feld ist Pflichtfeld</div>
							</div>
						</div>
						<div>
							<div class="field type2 edc_radio">
								<input type="radio" name="edc_other_debit" id="etc2" style="display:none;" value="0" data-required="1" checked>
								<label for="etc2">Nein</label>
							</div>
						</div>
					</div>
					<fieldset id="etc_fields">			
						<div class="fieldset">
							<div class="col_2_6">
								<div class="name"><?=__('Gender','edc')?> <span class="red">*</span>:</div>
								<div class="field">
									<select name="edc_etc_gender"><?=EDCH::simpleOptions(array(
										array('name'=>__('Man','edc'),'value'=>'m'),
										array('name'=>__('Woman','edc'),'value'=>'w'),
									))?>
									</select>
									<div class="required_text"><?=__('Choose you gender','edc')?></div>
								</div>
							</div>
							<div class="col_2_6">
								<div class="name"><?=__('Name','edc')?> <span class="red">*</span>:</div>
								<div class="field">
									<input type="text" name="edc_etc_firstname" value="">
									<div class="required_text"><?=__('Enter your name','edc')?></div>
								</div>
							</div>
							<div class="col_2_6">
								<div class="name"><?=__('Last name','edc')?> <span class="red">*</span>:</div>
								<div class="field">
									<input name="edc_etc_name" value="" type="text">
									<div class="required_text"><?=__('Enter your last name','edc')?></div>
								</div>
							</div>
						</div>
						<div class="fieldset">
							<div class="col_1_2">
								<div class="name"><?=__('Street','edc')?> <span class="red">*</span>:</div>
								<div class="field">
									<input type="text" name="edc_etc_street" value="">
									<div class="required_text"><?=__('Enter your street','edc')?></div>
								</div>
							</div>
							<div class="col_1_2">
								<div class="name"><?=__('House number','edc')?> <span class="red">*</span>:</div>
								<div class="field">
									<input type="text" name="edc_etc_house" value="" class="only_numeric">
									<div class="required_text"><?=__('Enter your house','edc')?></div>
								</div>
							</div>
						</div>
						<div class="fieldset">
							<div class="col_1_2">
								<div class="name"><?=__('Postal code','edc')?> <span class="red">*</span>:</div>
								<div class="field">
									<input type="text" name="edc_etc_zip" value="">
									<div class="required_text"><?=__('Enter postal code','edc')?></div>
								</div>
							</div>
							<div class="col_1_2">
								<div class="name"><?=__('Location','edc')?> <span class="red">*</span>:</div>
								<div class="field">
									<input type="text" name="edc_etc_city" value="">
									<div class="required_text"><?=__('Enter location','edc')?></div>
								</div>
							</div>
						</div>
					</fieldset>	
					<div class="form_title"><?=__('Zahlungsmethode','medl')?> <span class="red">*</span></div>
					<div class="fieldset near">
						<div class="col_1_6">
							<div class="field type2 to_top">
							<input type="checkbox" id="debit" name="edc_sepa_direct_debit" value="1">
								<label for="debit" class="edc_checkbox"><?=__('SEPA direct debit mandate','edc')?></label>
								<div class="required_text"><?=__('Required field','edc')?></div>
							</div>
						</div>
						<div class="col_1_6">
							<div class="field type2 to_top">
							<input type="checkbox" id="transfer" name="edc_transfer" value="1">
								<label for="transfer" class="edc_checkbox"><?=__('Transfer','edc')?></label>
							</div>
						</div>
					</div>

					<fieldset id="direct_debit_form">
					
						<div class="fieldset">
							<div class="field type2 to_top">
								<input type="checkbox" name="sepa_check" id="sepa_check" style="display:none;" value="1">
								<label class="edc_checkbox" for="sepa_check"><?=$data['direct_debit_text']?></label>
								<div class="required_text"><?=__('This field required','edc')?></div>
							</div>
						</div>
						
						<div class="fieldset">
							<div>
								<div>
									<div class="name"><?=__('Account holder (first name / surname)','edc')?>:</div>
									<div class="field">
										<input type="text" name="edc_holder" value="">
									</div>
								</div>
							</div>
						</div>

						<div class="fieldset">
							<div>
								<div class="name"><?=__('Bank','edc')?>:</div>
								<div class="field">
									<input type="text" name="edc_credit" value="">
								</div>
							</div>
						</div>

						<div class="fieldset">
							<div>
								<div class="name"><?=__('IBAN','edc')?>:</div>
								<div class="field flex jumping_wrapper to_top iban">
									<input type="text" name="edc_IBAN[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_IBAN[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_IBAN[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_IBAN[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_IBAN[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_IBAN[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_IBAN[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_IBAN[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_IBAN[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_IBAN[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_IBAN[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_IBAN[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_IBAN[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_IBAN[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_IBAN[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_IBAN[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_IBAN[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_IBAN[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_IBAN[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_IBAN[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_IBAN[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_IBAN[]" value="" class="small" maxlength="1">
									<div class="required_text"><?=__('IBAN nicht korrekt!','medl')?></div>
								</div>
							</div>
						</div>
						<div class="fieldset">
							<div>
								<div class="name"><?=__('BIC','edc')?>:</div>
								<div class="field flex jumping_wrapper">
									<input type="text" name="edc_BIC[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_BIC[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_BIC[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_BIC[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_BIC[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_BIC[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_BIC[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_BIC[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_BIC[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_BIC[]" value="" class="small" maxlength="1">
									<input type="text" name="edc_BIC[]" value="" class="small" maxlength="1">
								</div>
							</div>
						</div>
					</fieldset>				
					<div class="form_title"><?=__('Art des Wechsels','medl')?> <span class="red">*</span></div>
					<div class="fieldset tleft inline near">					
						<div>
							<div class="field type2 edc_radio">
								<input type="radio" name="change" id="change_1" style="display:none;" value="change" data-required="1" onchange="edc.displayChangeFields(this);">
								<label for="change_1">Versorgerwechsel</label>
								<div class="required_text">Dieses Feld ist Pflichtfeld</div>
							</div>
						</div>
						<div>
							<div class="field type2 edc_radio">
								<input type="radio" name="change" id="change_2" style="display:none;" value="new" data-required="1" onchange="edc.displayChangeFields(this);">
								<label for="change_2">Neueinzug</label>
							</div>
						</div>
					</div>
					<fieldset id="change_fields" style="display:none;">
						<div class="form_title"><?=__('Alte Adresse','medl')?></div>					
						<div class="fieldset">
							<div class="col_1_2">
								<div class="name"><?=__('Street','edc')?>  <span class="red">*</span>:</div>
								<div class="field">
									<input type="text" name="change_street" value="">
									<div class="required_text"><?=__('Enter your street','edc')?></div>
								</div>
							</div>
							<div class="col_1_2">
								<div class="name"><?=__('House number','edc')?> <span class="red">*</span>:</div>
								<div class="field">
									<input type="text" name="change_house" value="" class="only_numeric">
									<div class="required_text"><?=__('Enter your house','edc')?></div>
								</div>
							</div>
						</div>
						<div class="fieldset">
							<div class="col_1_2">
								<div class="name"><?=__('Postal code','edc')?> <span class="red">*</span>:</div>
								<div class="field">
									<input type="text" name="change_postal_code">
									<div class="required_text"><?=__('Enter postal code','edc')?></div>
								</div>
							</div>
							<div class="col_1_2">
								<div class="name"><?=__('Location','edc')?> <span class="red">*</span>:</div>
								<div class="field">
									<input type="text" name="change_location">
									<div class="required_text"><?=__('Enter location','edc')?></div>
								</div>
							</div>
						</div>
					</fieldset>
					<div class="change_fields">
						<div class="fieldset">						
							<div>
								<div class="name"><?=__('Vorversorger','medl')?> <span class="red">*</span>:</div>
								<div class="field">
									<select name="edc_previous" value="" data-required="1">
										<option value=""><?=__('Choose','edc')?></option>
										<?=$data['suppliers_options']?>
									</select>
									<div class="required_text"><?=__('This field required','edc')?></div>
								</div>
							</div>
						</div>
						<div class="holder">
							<div class="form_title"><?=__('Sollen wir Ihren alten Vertrag kündigen?','medl')?> <span class="red">*</span>:</div>
							<div class="fieldset tleft inline near">					
								<div>
									<div class="field type2 edc_radio">
										<input type="radio" name="cancel_old" id="cancel_old_1" style="display:none;" value="1" data-required="1" onchange="edc.displayCancelFields(this);">
										<label for="cancel_old_1">Ja</label>
										<div class="required_text">Dieses Feld ist Pflichtfeld</div>
									</div>
								</div>
								<div>
									<div class="field type2 edc_radio">
										<input type="radio" name="cancel_old" id="cancel_old_2" style="display:none;" value="0" data-required="1" onchange="edc.displayCancelFields(this);">
										<label for="cancel_old_2">Nein</label>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="cancel_old" style="display:none;">
						<div class="fieldset">
							<div class="field type2 to_top">
								<input type="checkbox" name="cancel_old_check" id="cancel_old_check" style="display:none;" value="1">
								<label class="edc_checkbox" for="cancel_old_check">Der Kunde beauftragt die medl GmbH mit der Belieferung von Strom. Der Kunde bevollmächtigt die medl GmbH, für die genannte Lieferstelle den bestehenden Stromliefervertrag zu kündigen und den für die Lieferung erforderlichen Vertrag mit dem örtlichen Netzbetreiber abzuschließen. Der Vertrag kommt mit der Vertragsbestätigung der medl GmbH in Textform zustande. <span class="red">*</span></label>
								<div class="required_text"><?=__('This field required','edc')?></div>
							</div>
						</div>
					</div>
					<div class="change_fields">
						<div class="form_title"><?=__('Ab wann sollen wir Sie beliefern?','medl')?> <span class="red">*</span>:</div>
						<div class="fieldset tleft inline near">					
							<div>
								<div class="field type2 edc_radio">
									<input type="radio" name="start_supply" id="start_supply_1" style="display:none;" value="right_now" data-required="1" onchange="edc.displaySupplyFields(this);">
									<label for="start_supply_1">Schnellstmöglich</label>
									<div class="required_text">Dieses Feld ist Pflichtfeld</div>
								</div>
							</div>
							<div>
								<div class="field type2 edc_radio">
									<input type="radio" name="start_supply" id="start_supply_2" style="display:none;" value="desired" data-required="1" onchange="edc.displaySupplyFields(this);">
									<label for="start_supply_2">Gewünschter Lieferbeginn</label>
								</div>
							</div>
						</div>
					</div>
					<div class="fieldset" style="display:none;" id="desired_date">
						<div>
							<div class="name"><?=__('Gewünschter Lieferbeginn','medl')?></div>
							<div class="field">
								<input type="text" name="edc_electriс_date" value="" class="datepicker_no_past">
							</div>
						</div>
					</div>
					<div class="change_fields">
						<div class="fieldset">
							<div>
								<div class="name"><?=__('Kundennummer Vorversorger','medl')?> <span class="red">*</span>:</div>
								<div class="field">
									<input type="text" name="edc_contract" value="" data-required="1">
									<div class="required_text"><?=__('This field required','edc')?></div>
								</div>
							</div>
						</div>
					</div>
					<?php
						$hint1=EDCH::opts('get','edc_hint_1','settings');
						$hint2=EDCH::opts('get','edc_hint_2','settings');
					?>
					<div class="fieldset" id="counter_fields_holder">
						<?php if(EDCH::proceedType($data['tariff']->type,true)!='combi') : ?>
							<div class="col_1_2">
								<div class="name"><?=__('Counter number','edc')?> <span class="red">*</span>: <?php if($hint1) : ?><i class="edc_hint"><div class="text"><?=$hint1?></div></i><?php endif; ?></div>
								<div class="field">
									<input type="text" name="edc_electriс" value="" data-required="1">
									<div class="required_text"><?=__('Enter counter number','edc')?></div>
								</div>
							</div>
							<div class="col_1_2">
								<div class="name"><?=__('Meter reading','edc')?>: <?php if($hint2) : ?><i class="edc_hint"><div class="text"><?=$hint2?></div></i><?php endif; ?></div>
								<div class="field">
									<input type="text" name="edc_electriс_value" value=""  class="only_numeric">
								</div>
							</div>
						<?php else : ?>
							<div class="col_1_2">
								<div class="name"><?=__('Zählerstand Gas','medl')?> <span class="red">*</span>: <?php if($hint1) : ?><i class="edc_hint"><div class="text"><?=$hint1?></div></i><?php endif; ?></div>
								<div class="field">
									<input type="text" name="edc_electriс" value="" data-required="1">
									<div class="required_text"><?=__('Enter counter number','edc')?></div>
								</div>
							</div>
							<div class="col_1_2">
								<div class="name"><?=__('Zählernummer Gas','medl')?>: <?php if($hint2) : ?><i class="edc_hint"><div class="text"><?=$hint2?></div></i><?php endif; ?></div>
								<div class="field">
									<input type="text" name="edc_electriс_value" value=""  class="only_numeric">
								</div>
							</div>
							<div class="col_1_2">
								<div class="name"><?=__('Zählerstand Strom','medl')?> <span class="red">*</span>: <?php if($hint1) : ?><i class="edc_hint"><div class="text"><?=$hint1?></div></i><?php endif; ?></div>
								<div class="field">
									<input type="text" name="edc_electriс2" value="" data-required="1">
									<div class="required_text"><?=__('Enter counter number','edc')?></div>
								</div>
							</div>
							<div class="col_1_2">
								<div class="name"><?=__('Zählernummer Strom','medl')?>: <?php if($hint2) : ?><i class="edc_hint"><div class="text"><?=$hint2?></div></i><?php endif; ?></div>
								<div class="field">
									<input type="text" name="edc_electriс_value2" value=""  class="only_numeric">
								</div>
							</div>						
						<?php endif; ?>
					</div>
					<div class="fieldset">
						<div>
							<div class="name" id="date_field_title"><?=__('Datum der Zählerablesung','medl')?></div>
							<div class="field">
								<input type="text" name="edc_read_date" value="" class="datepicker2">
							</div>
						</div>
					</div>
					<div class="fieldset">							
						<button type="button" style="display:none;" class="btn" onclick="edc.editOrderForm(this);"><?=__('Eingaben korrigieren','edc')?></button>
					</div>
					<div class="only_on_submit" style="display:none;">
						<?php if(EDCH::opts('get','edc_first_legal_text','settings')!='') : ?>
							<div class="fieldset">
								<div class="field type2 to_top">
									<input type="checkbox" class="on_submit" name="legal1" id="legal1" style="display:none;" value="1" data-set-required="1">
									<label class="edc_checkbox on_submit" for="legal1"><?=EDCH::opts('get','edc_first_legal_text','settings')?></label>
									<div class="required_text"><?=__('This field required','edc')?></div>
								</div>
							</div>
						<?php endif; ?>
						<?php if(EDCH::opts('get','edc_fourth_checkbox','settings')!='') : ?>
							<div class="fieldset">
								<div class="field type2 to_top">
									<input type="checkbox" class="on_submit" name="legal4" id="legal4" style="display:none;" value="1" data-set-required="1">
									<label class="edc_checkbox on_submit" for="legal4"><?=EDCH::opts('get','edc_fourth_checkbox','settings')?></label>
									<div class="required_text"><?=__('This field required','edc')?></div>
								</div>
							</div>
						<?php endif; ?>
						<?php if(EDCH::opts('get','edc_second_legal_text','settings')!='') : ?>
							<div class="fieldset">
								<div class="field type2 to_top">
									<input type="checkbox" class="on_submit" name="legal2" id="legal2" style="display:none;" value="1" data-set-required="1">
									<label class="edc_checkbox on_submit" for="legal2"><?=EDCH::opts('get','edc_second_legal_text','settings')?></label>
									<div class="required_text"><?=__('This field required','edc')?></div>
								</div>
							</div>
						<?php endif; ?>
						<?php if(EDCH::opts('get','edc_third_legal_text','settings')!='') : ?>
							<div class="fieldset">
								<div class="field type2 to_top">
									<input type="checkbox" name="legal3" id="legal3" class="on_submit" style="display:none;" value="1" data-set-required="1">
									<label class="edc_checkbox edc_checkbox" for="legal3"><?=EDCH::opts('get','edc_third_legal_text','settings')?></label>
									<div class="required_text"><?=__('This field required','edc')?></div>
								</div>
							</div>
						<?php endif; ?>						
						<?=EDCH::loadTemplate('recaptcha')?>
					</div>
					<div class="submit_group">
						<!--<button type="button" class="btn check" onclick="edc.checkOrderForm(this);"><?=__('Eingaben prüfen','edc')?></button>-->
						<button type="submit" class="btn" onclick="if(edc.orderStepValidate(this)) edc.submit(this,{callback:'afterStepProcess',validate:'orderStepValidate'});"><svg aria-hidden="true" class="svg_inline edc_spinner" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z"></path></svg><?=__('Send order','edc')?></button>
					</div>
					<div style="display:none;">
						<input type="hidden" name="steps_data" value="<?=$data['step_data']?>">
						<input type="hidden" name="id_tariff" value="<?=$data['tariff']->id?>">	
						<input type="hidden" name="edc_processing" value="1">
						<input type="hidden" name="validate" value="1">
						<input type="hidden" name="confirmation" value="">
						<input type="hidden" name="step_3" value="1">
					</div>
				</div>
			</form>
		</div>
    </section>
</div>

<div class="edc edc_popup" id="edc_email_confirmation">
	<div class="edc edc_popup_wrapper">
		<div class="edc_close" onclick="edc.popup(this,true);">&times;</div>
		<div class="edc_popup_content">
			<form method="POST" action="" onsubmit="return false;">
				<div class="description"><?=__('The code has been sent to your e-mail address. If you did not get it, please check spam folder or try to re-send code.')?></div>
				<div class="label">
					<label class="name"><?=__('Confirmation code','edc')?></label>
					<div class="field">
						<input type="text" name="confirmation_code">
						<div class="required_text"><?=__('The confirmation code is not correct','edc')?></div>
					</div>
				</div>
				<div class="description repeat_confirmation_code" data-text="<?=__("Click",'edc')?>"><?=__('Send confirmation code again')?></div>
				<div class="submit_group">
					<button type="submit" class="btn" onclick="edc.submit(this,{callback:'confirmationCode'});"><svg aria-hidden="true" class="svg_inline edc_spinner" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z"></path></svg><?=__('Confirm','edc')?></button>
				</div>
			</form>
		</div>
	</div>
</div>