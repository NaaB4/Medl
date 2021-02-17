<?php
	global $edc_admin;
	if(!$edc_admin) die('Plugin corrupted');
?>
<div class="wrap edc_admin_page settings_page">
	<div class="page_title"><?=__('EDCalculator Settings','edc')?></div>
	<div class="settings_tabs edc_tabs" id="settings_tabs">
		<div class="headline"><a class="btn help_btn" href="mailto:support@ivato.de"><?=__('Need help?','edc')?></a></div>
		<div class="items_wrapper">
			<?php
				$nav=$options='';
				foreach($data['settings'] as $group=>$items) : ?>
				<?php $class=$items['grouped'] ? 'grouped' : 'col_'.sizeOf($items['items']); ?>
				<?php ob_start(); ?>
					<li><a href="javascript:void(0);"><?=$items['title']?></a></li>
				<?php $nav.=ob_get_clean(); ?>
				<?php ob_start(); ?>
				<div class="edc_tab">
					<form method="POST" action="" onsubmit="return false;">
						<div class="<?=$class?>">
						<?php foreach($items['items'] as $i=>$fields) : ?>		
							<div <?=($class=='grouped' ? 'class="group"' : '')?> onclick="edc_admin.switchGroup(event,this);">
								<?php foreach($fields as $key=>$field) : ?>
									<?php if(!is_array($field)) : ?>									
										<div class="form_title"><?=$field?></div>
									<?php else : ?>
										<div class="label">
											<?php if($field['name']) : ?>
											<div class="name">
												<?=$field['name']?> <?php if($field['description']) : ?><span class="icon"><span><?=$field['description']?></span></span><?php endif; ?>
											</div>
											<?php endif; ?>
											<div class="field">
												<?=EDCAdmin::inst()->drawField($key,$field,$data['values'][$key])?>
											</div>
										</div>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
						<?php endforeach; ?>					
							<div class="submit_group">
								<button type="submit" class="edc_submit" onclick="edc_admin.submit(this);">
									<svg aria-hidden="true" class="svg_inline edc_spinner" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z"></path></svg>
									<?=__('Submit','edc')?>
								</button>
								<div class="result"></div>
								<input type="hidden" name="edc_<?=$group?>_settings" value="1">
								<input type="hidden" name="settings_submitted" value="1">
							</div>
						</div>
					</form>
				</div>
				<?php $options.=ob_get_clean(); ?>
			<?php endforeach; ?>
			<div class="nav_holder"><ul class="navigation"><?=$nav?></ul></div>
			<div class="items"><?=$options?></div>
		</div>
	</div>
</div>
<script type="text/javascript">
	window.addEventListener('load',function(){
		edc_admin.initializeTabs(document.body.querySelector('#settings_tabs'));
	});
</script>