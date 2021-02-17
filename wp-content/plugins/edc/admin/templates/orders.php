<?php
	global $edc_admin;
	if(!$edc_admin) die('Plugin corrupted');
?>
<div class="wrap edc_admin_page orders_page">
	<div class="page_title"><?=__('EDCalculator Orders','edc')?></div>
	<div class="edc_filter">
		<form method="GET" action="">
			<?=$data['hidden_fields']?>
			<div class="label">
				<div class="field"><input type="text" placeholder="<?=__('Search...','edc')?>" name="keyword" value="<?=$data['get_keyword']?>"></div>
			</div>
			<div class="label">
				<div class="field col_2">
					<div><input type="text" name="date_from" placeholder="<?=__('Date from','edc')?>" value="<?=$data['get_date_from']?>"></div>
					<div><input type="text" name="date_to" placeholder="<?=__('Date to','edc')?>" value="<?=$data['get_date_to']?>"></div>
				</div>
			</div>
			<div class="submit_group">
				<button type="submit" class="edc_submit"><?=__('Submit','edc')?></button>
			</div>
		</form>
	</div>
	<?php if(!is_array($data['orders']) || sizeOf($data['orders'])==0) :?>
	<div class="b i m30"><?=__('No orders have been made yet','edc')?></div>
	<?php else : ?>
		<div class="items">
		<?php foreach($data['orders'] as $order) : ?>
			<div class="item">
				<div class="title">
					<?=$order->title?> (#<?=$order->id?>)
					<div class="date"><?=EDCH::dateToHum($order->date)?></div>
				</div>
				<div class="order_info">
					<ul>
						<li>
							<span class="name"><?=__('Tariff','edc')?></span>
							<span class="value"><?=$order->tariff->title?></span>
						</li>
						<li>
							<span class="name"><?=__('Customer','edc')?></span>
							<span class="value"><?=$order->options['edc_name']?></span>
						</li>
						<li>
							<span class="name"><?=__('Price per month','edc')?></span>
							<span class="value"><?=EDCH::addCurrency(EDCH::formatPrice($order->price_per_period))?></span>
						</li>
						<li>
							<span class="name"><?=__('Cent price per kWh','edc')?></span>
							<span class="value"><?=EDCH::addCurrency(EDCH::formatPrice($order->price_per_kwh))?></span>
						</li>
						<li>
							<span class="name"><?=__('Total price','edc')?></span>
							<span class="value"><?=EDCH::addCurrency(EDCH::formatPrice($order->total_price))?></span>
						</li>
					</ul>
				</div>
				<div class="details">
					<button type="button" class="btn" onclick="edc_admin.showOrderInfo(this,<?=$order->id?>);"><?=__('Details','edc')?></button>
					<button type="button" class="btn" onclick="edc_admin.showOrderPDF(this,<?=$order->id?>);"><?=__('PDF','edc')?></button>
				</div>
			</div>
		<?php endforeach; ?>
		</div>
		<?=$data['pagination']?>
	<?php endif; ?>	
	<div class="popup large" id="detailed_popup" onclick="edc_admin.closeThisPopup(this,event);">
		<div class="popup_content">
			<div class="close" onclick="edc_admin.closePopup(this);">&times;</div>
			<div class="holder"></div>
		</div>
	</div>
</div>