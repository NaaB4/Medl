<div class="attachment_holder" style="background-image:url('<?=htmlspecialchars($data['image_url'])?>')" data-empty="<?=EDCAdmin::inst()->getEmptyImage()?>"></div>
<div class="field">
	<button type="button" class="button edc_media_loader" data-field="<?=$data['key']?>"><?=__('Attach image','edc')?></button>
	<button type="button" class="button edc_media_remover" data-field="<?=$data['key']?>">&times;</button>
</div>
<input type="hidden" name="<?=$data['key']?>" value="<?=($data['value'] ? $data['value'] : '')?>">