<?php
	global $edc_admin;
	if(!$edc_admin) die('Plugin corrupted');
?>
	<div class="wrap edc_admin_page tariffs_page">
		<div class="page_title"><?=__('EDCalculator Tariffs','edc')?> <a href="<?=add_query_arg('tariff','new')?>"class="page-title-action"><?=__('Add new tariff','edc')?></a> <a href="javascript:void(0);" onclick="edc_admin.importTariffs();" class="page-title-action"><?=__('Import tariffs list','edc')?></a> <a href="javascript:void(0);" onclick="edc_admin.removeSelectedTariffs();" class="page-title-action"><?=__('Remove selected tariffs','edc')?></a></div>
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
	</div>
<?php

?>