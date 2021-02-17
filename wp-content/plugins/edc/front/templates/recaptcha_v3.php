<div class="recaptcha_holder<?=(is_array($data['classes']) ? ' '.implode(' ',$data['classes']) : '')?>">
	<script src="https://www.google.com/recaptcha/api.js?render=<?=$data['key']?>"></script>
	<div class="recaptcha" id="recaptcha_<?=$data['uniq']?>"></div>
	<?php if(EDCH::opts('get','edc_recaptcha_v3_hide_badge','settings')) : ?>
		<style>.grecaptcha-badge{ display: none !important; }</style>
	<?php endif; ?>
</div>