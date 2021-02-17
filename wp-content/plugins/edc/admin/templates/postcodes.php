<?php
	global $edc_admin;
	if(!$edc_admin) die('Plugin corrupted');
?>
	<div class="wrap edc_admin_page settings_page">
		<div class="page_title"><?=__('EDCalculator Postal Codes','edc')?> <a href="javascript:void(0);" onclick="edc_admin.addPostcode();" class="page-title-action"><?=__('Add new postcode','edc')?></a> <a href="javascript:void(0);" onclick="edc_admin.importPostcodes();" class="page-title-action"><?=__('Import postcodes list','edc')?></a> <a href="javascript:void(0);" onclick="edc_admin.removeSelectedPostcodes();" class="page-title-action"><?=__('Remove selected postcodes','edc')?></a> <a href="javascript:void(0);" onclick="edc_admin.getPostcodesForImporter();" class="page-title-action"><?=__('Get selected postcodes for tariffs importer','edc')?></a></div>
		<?php if(is_array($edc_admin->result)) : ?>
		<div class="edc_admin_result">
			<div class="result <?=$edc_admin->result['type']?>"><?=$edc_admin->result['text']?></div>
		</div>
		<?php endif; ?>
		<div class="edc_filter">
			<form method="GET" action="">
				<div class="label">
					<div class="field"><input type="text" placeholder="<?=__('Postal code','edc')?>" name="code" value="<?=$data['get_code']?>"></div>
				</div>
				<div class="label">
					<div class="field"><input type="text" name="name" placeholder="<?=__('Postal place','edc')?>" value="<?=$data['get_name']?>"></div>
				</div>
				<div class="submit_group">
					<button type="submit" class="edc_submit"><?=__('Search','edc')?></button>
					<?=$data['hidden_fields']?>
				</div>
			</form>
		</div>
		<?php if(!is_array($data['postcodes']) || sizeOf($data['postcodes'])==0) :?>
		<div class="b i m30"><?=__('No postal codes have been added yet','edc')?></div>
		<?php else : ?>
			<table class="wp-list-table widefat fixed striped pages" id="postcodes_table"><thead>
				<tr>
					<th class="manage-column column-cb check-column"><input type="checkbox"></th>
					<th class="manage-column"><?=__('Postal code','edc')?></th>
					<th class="manage-column"><?=__('Postal place','edc')?></th>
					<th class="manage-column"><?=__('Postal code type','edc')?></th>
				</tr>
			</thead><tbody>
			<?php foreach($data['postcodes'] as $code) : ?>
				<tr>
					<th class="check-column"><input type="checkbox" name="postcode_<?=$code->id?>"></th>
					<td>
						<strong><?=$code->code?></strong>
						<div class="row-actions">
							<span class="edit">
								<a href="javascript:void(0);" onclick="edc_admin.editPostcode(<?=$code->id?>,'<?=$code->code?>','<?=$code->name?>','<?=$code->type?>');" aria-label="<?=__('Edit','edc')?>"><?=__('Edit','edc')?></a> | 
							</span>
							<span class="trash">
								<a href="javascript:void(0);" class="submitdelete" onclick="edc_admin.deletePostcode(<?=$code->id?>,'<?=__('Are you sure? This action can not be undone!','edc')?>');" aria-label="<?=__('Remove','edc')?>"><?=__('Remove','idea_catalog')?></a>
							</span>
						</div>
					</td>
					<td><?=$code->name?></td>
					<td><?=EDCH::codes('type',$code->type)?></td>
				</tr>
			<?php endforeach; ?>
			</tbody></table>
			<?=$data['pagination']?>
		<?php endif; ?>
		<div class="popup" id="postcode_popup" onclick="edc_admin.closeThisPopup(this,event);">
			<div class="popup_content">
				<div class="close" onclick="edc_admin.closePopup(this);">&times;</div>
				<div class="holder">
					<div class="title add_title"><?=__('Add postcode','edc')?></div>
					<div class="title edit_title"><?=__('Edit postcode','edc')?></div>
					<form method="POST" action="" name="postcode_form">
						<div class="label">
							<div class="name"><?=__('Location','edc')?></div>
							<div class="field"><input type="text" name="name"></div>
						</div>
						<div class="label">
							<div class="name"><?=__('Postal code','edc')?></div>
							<div class="field"><input type="text" name="code"></div>
						</div>
						<div class="label">
							<div class="name"><?=__('Type','edc')?></div>
							<div class="field">
								<select class="with_chosen" name="type">
									<option value="0"><?=__('Any','edc')?></option>
									<option value="1"><?=__('Gas','edc')?></option>
									<option value="2"><?=__('Electricity','edc')?></option>
								</select>
							</div>
						</div>
						<div class="submit_group">
							<button type="submit" class="edc_submit"><?=__('Submit','edc')?></button>
							<input type="hidden" name="edc_postcode" value="new">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>