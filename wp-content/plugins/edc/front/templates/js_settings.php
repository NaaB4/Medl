<script type="text/javascript">
	if(typeof EDC!='undefined'){
		if(!edc) var edc=new EDC();
		edc.setSetting('use_email_confirmation',<?=EDCH::is(EDCH::opts('get','edc_use_confirmation','settings')) ? 'true' : 'false'?>);
		<?php if(self::opts('get','edc_use_recaptcha','settings')=='v2') : ?>
			edc.setSetting('recaptcha_key','<?=self::opts('get','edc_grecaptcha_v2_public','settings')?>');
			edc.setSetting('recaptcha_version','2.0');
		<?php elseif(self::opts('get','edc_use_recaptcha','settings')=='v3') : ?>
			edc.setSetting('recaptcha_key','<?=self::opts('get','edc_grecaptcha_v3_public','settings')?>');
			edc.setSetting('recaptcha_version','3.0');
		<?php endif; ?>
	}
</script>