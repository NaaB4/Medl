<?php
	global $edc_admin;
	if(!$edc_admin) die('Plugin corrupted');
?>
	<div class="wrap edc_admin_page tariffs_page">
		<div class="page_title"><?=$data['page_title']?></div>
		<form method="POST" action="" enctype="multipart/form-data" onsubmit="return false;">
			<div class="group_wrapper col_2">
				<div class="edc_tariff_group main_group" id="main">
					<div class="group_title"><?=__('Main information','edc')?></div>
					<div class="fields_wrapper">
						<div class="image_block">
							<div class="label image">
								<div class="name"><?=__('Image','edc')?></div>
								<div class="attachment_holder" style="background-image:url('<?=htmlspecialchars($data['tariff_image_url'])?>')" data-empty="<?=EDC_PLUGIN_URL . '/admin/images/no-image.jpg'?>"></div>
								<div class="field">
									<button type="button" class="button edc_media_loader" data-field="tariff_image"><?=__('Attach image','edc')?></button>
									<button type="button" class="button edc_media_remover" data-field="tariff_image">&times;</button>
								</div>
							</div>
						</div>
						<div class="fields_block">
							<div class="label">
								<div class="field"><input id="active" type="checkbox" name="active" value="1" <?php echo $data['active']==1 ? 'checked' : '' ;?>><label for="active"><?=__('Tariff active','edc')?></label></div>
							</div>
							<div class="label">
								<div class="name"><?=__('Title','edc')?></div>
								<div class="field"><input type="text" name="title" value="<?=$data['title']?>"></div>
							</div>
							<div class="label">
								<div class="name"><?=__('Tariff type','edc')?></div>
								<div class="field">
									<select class="with_chosen" name="type" onchange="edc_admin.loadCustomOptions(this);">							
										<option value=""><?=__('Choose','edc')?></option>
										<?=EDCH::simpleOptions($data['tariff_types'],$data['type'])?>
									</select>
								</div>
							</div>
							<div class="label">
								<div class="name"><?=__('Code','edc')?></div>
								<div class="field"><input type="text" name="code" value="<?=$data['code']?>"></div>
							</div>
							<div class="label">
								<div class="col_2">
									<div class="field"><div class="name"><?=__('Valid from','edc')?></div><input type="text" placeholder="<?=__('after','edc')?>" name="valid_from" class="with_calendar" value="<?=EDCH::dateToHum($data['valid_from'])?>"></div>
									<div class="field"><div class="name"><?=__('Valid to','edc')?></div><input type="text" placeholder="<?=__('until','edc')?>" name="valid_to" class="with_calendar" value="<?=EDCH::dateToHum($data['valid_to'])?>"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="edc_tariff_group" id="price">
					<div class="group_title"><?=__('Sale information','edc')?></div>
					<div class="fields_wrapper">
						<?php if($data['type']!=3) : ?>
							<div class="label">
								<div class="name"><?=__('Price per period','edc')?></div>
								<div class="field"><input type="text" name="price_per_period" value="<?=$data['price_per_period']?>"></div>
							</div>
							<div class="label">
								<div class="name"><?=__('Price period','edc')?></div>
								<select class="with_chosen" name="option_tariff_base_price_type">
									<?=EDCH::simpleOptions([
										['name'=>__('Per month','edc'),'value'=>'monthly'],
										['name'=>__('Per year','edc'),'value'=>'yearly'],
									],$data['tariff_options']['tariff_base_price_type'])?>
								</select>
							</div>
							<div class="label">
								<div class="name"><?=__('Cent price per kWh','edc')?></div>
								<div class="field"><input type="text" name="price_per_kwh" value="<?=$data['price_per_kwh']?>"></div>
							</div>
							<div class="label">
								<div class="name"><?=__('Calculation formula','edc')?></div>
								<select class="with_chosen" name="option_tariff_calculation_formula">
									<?=EDCH::simpleOptions([
										['name'=>__('Static base price','edc'),'value'=>'static'],
										['name'=>__('Dynamic base price','edc'),'value'=>'dynamic'],
									],$data['tariff_options']['tariff_calculation_formula'])?>
								</select>
							</div>
						<?php else : ?>
							<div class="label">
								<div class="name"><?=__('Strom Grundpreis / Jahr','medl')?></div>
								<div class="field"><input type="text" name="option_price_per_period" value="<?=$data['tariff_options']['price_per_period']?>"></div>
							</div>
							<div class="label">
								<div class="name"><?=__('Gas Grundpreis / Jahr','medl')?></div>
								<div class="field"><input type="text" name="price_per_period" value="<?=$data['price_per_period']?>"></div>
							</div>
							<div class="label">
								<div class="name"><?=__('Price period','edc')?></div>
								<select class="with_chosen" name="option_tariff_base_price_type">
									<?=EDCH::simpleOptions([
										['name'=>__('Per month','edc'),'value'=>'monthly'],
										['name'=>__('Per year','edc'),'value'=>'yearly'],
									],$data['tariff_options']['tariff_base_price_type'])?>
								</select>
							</div>
							<div class="label">
								<div class="name"><?=__('Strom Arbeitspreis (Cent / kWh)','medl')?></div>
								<div class="field"><input type="text" name="option_price_per_kwh" value="<?=$data['tariff_options']['price_per_kwh']?>"></div>
							</div>
							<div class="label">
								<div class="name"><?=__('Gas Arbeitspreis (Cent / kWh)','medl')?></div>
								<div class="field"><input type="text" name="price_per_kwh" value="<?=$data['price_per_kwh']?>"></div>
							</div>
							<div class="label">
								<div class="name"><?=__('Calculation formula','edc')?></div>
								<select class="with_chosen" name="option_tariff_calculation_formula">
									<?=EDCH::simpleOptions([
										['name'=>__('Static base price','edc'),'value'=>'static'],
										['name'=>__('Dynamic base price','edc'),'value'=>'dynamic'],
									],$data['tariff_options']['tariff_calculation_formula'])?>
								</select>
							</div>						
						<?php endif; ?>
						<div class="label">
							<div class="name"><?=__('Tariff goodies','medl')?></div>
							<div class="field">
								<select class="with_chosen" name="option_tariff_goodies[]" multiple>							
									<option value=""><?=__('Choose','edc')?></option>
									<?=EDCH::simpleOptions($data['goodies_options'],$data['tariff_goodies'])?>
								</select>
							</div>
						</div>
						<div class="label">
							<?php if($data['clients_type_option']['name']) : ?>
								<div class="name">
									<?=$data['clients_type_option']['name']?> <?php if(isset($data['clients_type_option']['desciption'])) : ?> <span class="icon"><span><?=$data['clients_type_option']['desciption']?></span></span><?php endif; ?>
								</div>
							<?php endif; ?>
							<div class="field">
						<?=EDCAdmin::inst()->drawField('option_tariff_clients_type',$data['clients_type_option'],$data['tariff_options']['tariff_clients_type'])?>
							</div>
						</div>
					</div>
				</div>
				<div class="edc_tariff_group" id="postal">
					<div class="group_title"><?=__('Postal codes','edc')?></div>
					<div class="fields_wrapper">
						<div class="label">
							<div class="name"><?=__('Included in postcodes','edc')?></div>
							<div class="field">
								<select class="with_chosen" name="postcodes[]" multiple>							
									<option value=""><?=__('Choose','edc')?></option>
									<?=EDCH::simpleOptions($data['postcodes_options'],$data['postcodes'])?>
								</select>
							</div>
						</div>
						<div class="label">
							<div class="name"><?=__('Excluded in postcodes','edc')?></div>
							<div class="field">
								<select class="with_chosen" name="exclude_postcodes[]" multiple>							
									<option value=""><?=__('Choose','edc')?></option>
									<?=EDCH::simpleOptions($data['postcodes_options'],$data['excluded_postcodes'])?>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="edc_tariff_group" id="params">
					<div class="group_title"><?=__('Parameters','edc')?></div>
					<div class="fields_wrapper">
						<div class="label">
							<div class="name"><?=__('Legal terms','edc')?></div>
							<div class="field">
								<select class="with_chosen" name="legal_terms">							
									<option value=""><?=__('Choose','edc')?></option>
									<?=EDCH::simpleOptions($data['pages_options'],$data['legal_terms'])?>
								</select>
							</div>
						</div>				
						<div class="label">
							<div class="name"><?=__('Attached documents in E-Mail','edc')?></div>
							<div class="attachment_text"><?=$data['terms_and_conditions_file']?></div>
							<div class="field">
								<button type="button" class="button edc_media_loader" data-multiple="1" data-field="terms_and_conditions" data-type="file"><?=__('Attach file','edc')?></button>
								<button type="button" class="button edc_media_remover" data-field="terms_and_conditions" data-type="file">&times;</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="edc_tariff_group" id="custom_options">
				<div class="group_title"><?=__('Custom options','edc')?></div>
				<div class=" fields_wrapper holder"><?=$data['custom_options']?></div>
			</div>			
			<div class="submit_group">
				<button type="submit" class="edc_submit" onclick="edc_admin.submit(this,{callback:'tariffSubmit'});">
					<svg aria-hidden="true" class="svg_inline edc_spinner" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z"></path></svg>
					<?=__('Submit','edc')?>
				</button>
				<div class="result"></div>
				<input type="hidden" name="terms_and_conditions" value="<?=$data['terms_and_conditions']?>">
				<input type="hidden" name="tariff_image" value="<?=$data['tariff_image']?>">
				<input type="hidden" name="edc_tariff" value="<?=$data['get_tariff']?>">
			</div>
		</form>
	</div>
<?php

?>