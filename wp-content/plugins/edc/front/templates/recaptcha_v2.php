<div class="recaptcha_holder<?=(is_array($data['classes']) ? ' '.implode(' ',$data['classes']) : '')?>">
	<script src="https://www.google.com/recaptcha/api.js?render=explicit" async defer></script>
	<div class="recaptcha" id="recaptcha_<?=$data['uniq']?>"></div>
</div>