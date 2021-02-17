<?php
	global $edc_admin;
	if(!$edc_admin) die('Plugin corrupted');
?>
<script type="text/javascript">
	window.edc_goodies={};
</script>
	<div class="wrap edc_admin_page settings_page">
		<div class="page_title"><?=__('Goodies','medl')?> <a href="javascript:void(0);" onclick="edc_admin.addGoodie();" class="page-title-action"><?=__('Neues Goodie hinzufügen','medl')?></a> <a href="javascript:void(0);" onclick="edc_admin.removeSelectedGoodies();" class="page-title-action"><?=__('Entferne markierte Goodies','medl')?></a></div>
		<?php if(is_array($edc_admin->result)) : ?>
		<div class="edc_admin_result">
			<div class="result <?=$edc_admin->result['type']?>"><?=$edc_admin->result['text']?></div>
		</div>
		<?php endif; ?>
		<?php if(!is_array($data['goodies']) || sizeOf($data['goodies'])==0) :?>
		<div class="b i m30"><?=__('Es wurden noch keine Goodies hinzugefügt','medl')?></div>
		<?php else : ?>
			<table class="wp-list-table widefat fixed striped pages" id="goodies_table"><thead>
				<tr>
					<th class="manage-column column-cb check-column"><input type="checkbox"></th>
					<th class="manage-column"><?=__('Goodie Bezeichnung','medl')?></th>
					<th class="manage-column"><?=__('Preis','medl')?></th>
					<th class="manage-column"><?=__('Beschreibung','medl')?></th>
				</tr>
			</thead><tbody>
			<?php foreach($data['goodies'] as $goodie) : ?>
				<tr>
					<th class="check-column"><input type="checkbox" name="goodie_<?=$goodie->id?>"></th>
					<td>
						<strong><?=$goodie->name?></strong>
						<div class="row-actions">
							<span class="edit">
								<a href="javascript:void(0);" onclick="edc_admin.editGoodie(<?=$goodie->id?>);" aria-label="<?=__('Bearbeiten','medl')?>"><?=__('Bearbeiten','medl')?></a> | 
							</span>
							<span class="trash">
								<a href="javascript:void(0);" class="submitdelete" onclick="edc_admin.deleteGoodie(<?=$goodie->id?>,'<?=__('Are you sure? This action can not be undone!','medl')?>');" aria-label="<?=__('Entfernen','medl')?>"><?=__('Entfernen','medl')?></a>
							</span>
						</div>
					</td>
					<td><?=$goodie->price?></td>
					<td>
						<?=$goodie->description?>
						<script type="text/javascript">
							window.edc_goodies[<?=$goodie->id?>]=<?=json_encode($goodie)?>;
						</script>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody></table>
			<?=$data['pagination']?>
		<?php endif; ?>
		<div class="popup" id="goodie_popup" onclick="edc_admin.closeThisPopup(this,event);">
			<div class="popup_content">
				<div class="close" onclick="edc_admin.closePopup(this);">&times;</div>
				<div class="holder">
					<div class="title add_title"><?=__('Goodie hinzufügen','medl')?></div>
					<div class="title edit_title"><?=__('Goodie bearbeiten','medl')?></div>
					<form method="POST" action="" name="postcode_form">
						<div class="label">
							<div class="name"><?=__('Goodie Bezeichnung','medl')?></div>
							<div class="field"><input type="text" name="name"></div>
						</div>
						<div class="label">
							<div class="name"><?=__('Beschreibung','medl')?></div>
							<div class="field"><textarea name="description"></textarea></div>
						</div>
						<div class="label">
							<div class="name"><?=__('Preis / Wert','medl')?></div>
							<div class="field"><input type="text" name="price"></div>
						</div>
						<div class="label">
							<div class="field"><label><input type="checkbox" name="young" value="1"> <?=__('Für Junge Leute (unter 30 Jahre)','medl')?></label></div>
						</div>
						<div class="submit_group">
							<button type="submit" class="edc_submit"><?=__('Submit','edc')?></button>
							<input type="hidden" name="edc_goodie" value="new">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>