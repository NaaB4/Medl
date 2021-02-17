<div class="order_details">
	<div class="title small active">
		<?=$data['order']->title?> (#<?=$data['order']->id?>) <?=__('from','edc')?> <?=EDCH::dateToHum($data['order']->date)?>
		<div class="title_actions"><a href="<?=$data['download_link']?>"><?=__('Download','edc')?></a></div>
	</div>
	<iframe src="<?=$data['pdf_url']?>" style="width:100%;height:600px;"></iframe>
</div>