<select class="dropdown edc_field_<?=$data['key']?><?=($data['chosen']===false ? '' : ' with_chosen')?>" name="<?=$data['key']?>" <?=($data['multiple']===true ? 'multiple="multiple"' : '')?>>
	<option value=""><?=__('Choose','edc')?></option>
	<?=EDCH::simpleOptions($data['items'],$data['value'])?>
</select>