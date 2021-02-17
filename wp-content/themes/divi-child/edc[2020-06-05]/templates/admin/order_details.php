<?php
	$groups=EDC_ORDERS::getFields();
	//var_dump($options);
?>
<div class="order_details">
	<div class="title small active"><?=$data['order']->title?> (#<?=$data['order']->id?>) <?=__('from','edc')?> <?=EDCH::dateToHum($data['order']->date)?></div>
	<div class="data">
		<?php foreach($groups as $g) : ?>
			<div class="group_holder">
				<div class="group_title"><?=$g['title']?></div>
				<ul>
					<?php
						foreach($g['items'] as $k=>$item) : if($item['name']!='') :
						$value=$item['field']=='option' ? $data['order']->options[$k] : $data['order']->{$k};
						$value=json_decode($value,true) ? json_decode($value,true) : $value;
						if($k=='goodies'){
							$goodie=EDC_GOODIES::item($value);
							if($goodie) $value=$goodie->name;
						}
					?>
					<li class="<?=$k?>">
						<span class="name"><?=$item['name']?>:</span>
						<span class="value">
							<?php if(is_array($item['values'])) : ?>
								<?=$item['values'][$value]?>
							<?php else : ?>
								<?=(is_array($value) ? implode('',$value) : $value)?>
							<?php endif; ?>
						</span>
					</li>
					<?php endif; endforeach; ?>
				</ul>
			</div>
		<?php endforeach; ?>
	</div>
</div>