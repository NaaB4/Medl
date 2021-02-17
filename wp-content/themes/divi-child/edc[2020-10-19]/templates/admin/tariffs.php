<?php
	global $edc_admin;
	if(!$edc_admin) die('Plugin corrupted');
?>
	<div class="wrap edc_admin_page tariffs_page">
		<div class="page_title"><?=__('EDCalculator Tariffs','edc')?> <a href="<?=add_query_arg('tariff','new')?>"class="page-title-action"><?=__('Add new tariff','edc')?></a> <a href="javascript:void(0);" onclick="edc_admin.importTariffs();" class="page-title-action"><?=__('Import tariffs list','edc')?></a> <a href="javascript:void(0);" onclick="edc_admin.removeSelectedTariffs();" class="page-title-action"><?=__('Remove selected tariffs','edc')?></a> <a href="javascript:void(0);" onclick="edc_admin.shortcodeFromSelected();" class="page-title-action"><?=__('Shortcode für gewählten Tarif generieren','edc')?></a></div>
		<?php if(is_array($edc_admin->result)) : ?>
		<div class="edc_admin_result">
			<div class="result <?=$edc_admin->result['type']?>"><?=$edc_admin->result['text']?></div>
		</div>
		<?php endif; ?>
		<div class="edc_filter">
			<form method="GET" action="">
				<?=$data['hidden_fields']?>
				<div class="label">
					<div class="field"><input type="text" name="keyword" placeholder="<?=__('Keyword','edc')?>" value="<?=$data['get_keyword']?>"></div>
				</div>
				<div class="label">
					<div class="field"><select name="tariff_type" class="with_chosen"><?=$data['tariff_types']?></select></div>
				</div>
				<div class="submit_group">
					<button type="submit" class="edc_submit"><?=__('Search','edc')?></button>
				</div>
			</form>
		</div>
		<?php if(!is_array($data['tariffs']) || sizeOf($data['tariffs'])==0) :?>
		<div class="b i m30"><?=__('No tariffs have been added yet','edc')?></div>
		<?php else : ?>
			<table class="wp-list-table widefat fixed striped pages" id="tariffs_table"><thead>
				<tr>
					<th class="manage-column column-cb check-column"><input type="checkbox"></th>
					<th class="manage-column"><?=__('Title','edc')?></th>
					<th class="manage-column"><?=__('Tariff type','edc')?></th>
					<th class="manage-column"><?=__('Prices','edc')?></th>
					<th class="manage-column"><?=__('Valid (from - to)','edc')?></th>
				</tr>
			</thead><tbody>
			<?php $i=0; foreach($data['tariffs'] as $tariff) : ?>
				<tr>
					<th class="check-column"><input type="checkbox" name="tariff_<?=$tariff->id?>"></th>
					<td>
						<strong><?=$tariff->title?> <?=($tariff->code ? '('.$tariff->code.')' : '')?></strong>
						<div class="row-actions">
							<span class="edit">
								<a href="<?=add_query_arg('tariff',$tariff->id)?>" aria-label="<?=__('Edit','edc')?>"><?=__('Edit','edc')?></a> | 
							</span>
							<span class="trash">
								<a href="javascript:void(0);" class="submitdelete" onclick="edc_admin.deleteTariff(<?=$tariff->id?>,'<?=__('Are you sure? This action can not be undone!','edc')?>');" aria-label="<?=__('Remove','edc')?>"><?=__('Remove','edc')?></a>
							</span>
						</div>
					</td>
					<td>
						<?php 
							if($tariff->type==1){ echo __('Gas','edc'); } 
							elseif($tariff->type==2){ echo __('Electricity','edc'); }
							elseif($tariff->type==3){ echo __('Combi','medl'); }
						?>
					</td>
					<td>
						<ul>
							<li><?=__('Price per year','edc')?>: <strong><?=$tariff->price_per_period?></strong></li>
							<li><?=__('Price per kwh','edc')?>: <strong><?=$tariff->price_per_kwh?></strong></li>
						</ul>
					</td>
					<td>
						<?php
							$dates=array();
							if($tariff->valid_from!='') $dates[]=EDCH::dateToHum($tariff->valid_from);
							if($tariff->valid_to!='') $dates[]=EDCH::dateToHum($tariff->valid_to);
							echo implode(' - ',$dates);
						?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody></table>
			<?=$data['pagination']?>
		<?php endif; ?>
		
		<div class="popup" id="edc_shortcode_popup" onclick="edc_admin.closeThisPopup(this,event);">
			<div class="popup_content">
				<div class="close" onclick="edc_admin.closePopup(this);">&times;</div>
				<div class="holder">
					<div class="title active"><?=__('Shortcode für gewählten Tarif generieren','medl')?></div>
					<form method="POST" action="" name="postcode_form">
						<input type="hidden" name="tariff_ids" value="">
						<div class="label">
							<div class="shortcode_result b"></div>
						</div>
						<div class="label">
							<div class="name"><?=__('Tariftyp (sollte bei ausgewählten Tarifen gleich sein)','medl')?></div>
							<div class="field">
								<select name="tariff_type" onchange="edc_admin.redrawShortcode(this);edc_admin.showShortcodeType(this);">
									<option value=""><?=__('Choose','edc')?></option>
									<option value="gas"><?=__('Gas','edc')?></option>
									<option value="electricity"><?=__('Electricity','edc')?></option>
									<option value="combi"><?=__('Combi','medl')?></option>
								</select>
							</div>
						</div>
						<div class="label">
							<label><input type="checkbox" name="young" value="1" onchange="edc_admin.redrawShortcode(this);"> <?=__('Checkbox für Junge Leute anzeigen','medl')?></label>
						</div>
						<div class="label">
							<label><input type="checkbox" name="only_link" value="1" onchange="edc_admin.redrawShortcode(this);"> <?=__('Add only link','medl')?></label>
						</div>
						<div class="label">
							<div class="name"><?=__('Gesamtpreis (Euro)','medl')?></div>
							<div class="field"><input type="text" name="total_price" value="0" oninput="edc_admin.redrawShortcode(this);"></div>
						</div>
						<div class="label">
							<div class="name"><?=__('AGB URL','medl')?></div>
							<div class="field"><input type="text" name="agb_link" value="0" oninput="edc_admin.redrawShortcode(this);"></div>
						</div>
						<div class="label">
							<div class="name"><?=__('Preis URL','medl')?></div>
							<div class="field"><input type="text" name="price_link" value="0" oninput="edc_admin.redrawShortcode(this);"></div>
						</div>
						<div id="type_combi" style="display:none;">
							<div style="font-size:18px;margin-bottom:15px;"><b><?=__('Strom','medl')?></b></div>
							<div class="label">
								<div class="name"><?=__('Beispiel Jahresverbrauch','medl')?></div>
								<div class="field"><input type="text" name="st_example_annual_consumption" value="0" oninput="edc_admin.redrawShortcode(this);"></div>
							</div>
							<div class="label">
								<div class="name"><?=__('Preis pro kWh (Cent)','medl')?></div>
								<div class="field"><input type="text" name="st_per_kwh" value="0" oninput="edc_admin.redrawShortcode(this);"></div>
							</div>
							<div class="label">
								<div class="name"><?=__('Preis pro Monat (Euro)','medl')?></div>
								<div class="field"><input type="text" name="st_per_month" value="0" oninput="edc_admin.redrawShortcode(this);"></div>
							</div>
							<div style="font-size:18px;margin-bottom:15px;"><b><?=__('Erdgas','medl')?></b></div>
							<div class="label">
								<div class="name"><?=__('Beispiel Jahresverbrauch','medl')?></div>
								<div class="field"><input type="text" name="g_example_annual_consumption" value="0" oninput="edc_admin.redrawShortcode(this);"></div>
							</div>
							<div class="label">
								<div class="name"><?=__('Preis pro kWh (Cent)','medl')?></div>
								<div class="field"><input type="text" name="g_per_kwh" value="0" oninput="edc_admin.redrawShortcode(this);"></div>
							</div>
							<div class="label">
								<div class="name"><?=__('Preis pro Monat (Euro)','medl')?></div>
								<div class="field"><input type="text" name="g_per_month" value="0" oninput="edc_admin.redrawShortcode(this);"></div>
							</div>
						</div>
						<div id="type_other" style="display:none;">
							<div class="label">
								<div class="name"><?=__('Jahresverbrauch von','medl')?></div>
								<div class="field"><input type="text" name="from_annual_consumption" value="0" oninput="edc_admin.redrawShortcode(this);"></div>
							</div>
							<div class="label">
								<div class="name"><?=__('Jahresverbrauch bis','medl')?></div>
								<div class="field"><input type="text" name="to_annual_consumption" value="0" oninput="edc_admin.redrawShortcode(this);"></div>
							</div>
							<div class="label">
								<div class="name"><?=__('Beispiel Jahresverbrauch','medl')?></div>
								<div class="field"><input type="text" name="example_annual_consumption" value="0" oninput="edc_admin.redrawShortcode(this);"></div>
							</div>
							<div class="label">
								<div class="name"><?=__('Preis pro kWh (Cent)','medl')?></div>
								<div class="field"><input type="text" name="per_kwh" value="0" oninput="edc_admin.redrawShortcode(this);"></div>
							</div>
							<div class="label">
								<div class="name"><?=__('Preis pro Monat (Euro)','medl')?></div>
								<div class="field"><input type="text" name="per_month" value="0" oninput="edc_admin.redrawShortcode(this);"></div>
							</div>
						</div>
						<div class="label">
							<div class="shortcode_result b"></div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php

?>