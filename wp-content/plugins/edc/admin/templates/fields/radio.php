<?php foreach($data['items'] as $i=>$item) : ?>
	<div>
		<label class="edc_radio edc_field_<?=$data['key']?>_<?=$i?>">
			<input type="radio" value="<?=$item['value']?>" name="<?=$data['key']?>" <?php echo ($item['value']==$data['value'] ? 'checked="checked"' : '');?>> 
			<?=$item['name']?>
		</label>
	</div>
<?php endforeach; ?>	