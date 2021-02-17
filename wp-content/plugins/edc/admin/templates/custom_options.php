<?php if(!is_array($data['options']) || sizeOf($data['options'])==0) : ?>
	<div class="text"><?=__('There is no custom options','edc')?></div>
<?php else : foreach($data['options'] as $key=>$opt) : if($opt['visible']!==false) : ?>
	<div class="label">
		<?php if($opt['name']) : ?>
			<div class="name">
				<?=$opt['name']?> <?php if(isset($opt['desciption'])) : ?> <span class="icon"><span><?=$opt['desciption']?></span></span><?php endif; ?>
			</div>
		<?php endif; ?>
		<div class="field">
			<?=EDCAdmin::inst()->drawField('option_'.$opt['key'],$opt,$data[$opt['key']])?>
		</div>
	</div>
<?php endif; endforeach; endif; ?>