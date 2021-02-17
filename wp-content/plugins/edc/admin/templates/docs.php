<?php
	global $edc_admin;
	if(!$edc_admin) die('Plugin corrupted');
?>
<div class="wrap edc_admin_page settings_page">
	<div class="page_title"><?=__('EDCalculator Examples','edc')?></div>
	<div class="text"><?=__('On this page you can download examples how to use special functions of this plugin. E.g. importing tables.','edc')?></div>
	<ul class="samples">
		<li><a href="<?=$edc_admin->getSample('postcodes.xlsx')?>"><?=__('Postcode importer Excel file','edc')?></a></li>
		<li><a href="<?=$edc_admin->getSample('tariffs.xlsx')?>"><?=__('Tariffs importer Excel file','edc')?></a></li>
	</ul>
</div>