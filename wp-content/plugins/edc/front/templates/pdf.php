<html style="font-family: Arial,sans-serif;">
	<style>
		*{ font-family: Arial,sans-serif; }
	</style>
	<body style="margin: auto">
		<div>
			<?php if($data["logo"]) : ?>
				<div style="height: 130px; width: 100%;margin-bottom:20px;">
					<div colspan="12" width="50%"><img style="float: left; height: 100%;" src="<?=$data["logo"]?>"/></div>
				</div>
			<?php endif; ?>
			<?php if($data["img"]) : ?>
			<div style="height: 130px; width: 100%;">
				<div colspan="12" width="50%"><img style="float: left; height: 100%;" src="<?=$data["img"]?>"/></div>
			</div>
			<?php endif; ?>
			<div>
				<div colspan="12" style="font-size: 15px; margin-bottom: 10px;">
					<p style="margin-bottom: 15px;"><strong><?=__('Order from','edc')?> <span style="color: #1c94ff;"><?=$data["title"]?></span></strong></p>
				</div>
			</div>
			<div style="position:relative; height: 170px">
				<div style="width: 48%; position: absolute; left: 0; top: 0" colspan="6">
					<div style="font-size: 13px;">
						<strong><?=__('Customer / Delivery address','edc')?></strong>
					</div>
					<div>
						<div>
							<div style="font-size: 13px;"><?= $data["customer"]["name"] ?>&nbsp;&nbsp;&nbsp;<?= $data["customer"]["surname"] ?></div>
							<div style="border-top: 2px solid #000; font-size: 11px; margin-bottom: 15px;"><?=__('Name and second name','edc')?>
							</div>
						</div>
					</div>
					<div>
						<div>
							<div style="font-size: 15px;"><?=$data["customer"]["street"]?>&nbsp;&nbsp;&nbsp;<?= $data["customer"]["house"] ?></div>
							<div style="border-top: 2px solid #000; font-size: 11px; margin-bottom: 15px;">
								<?=__('Address (Street,Housenumber','edc')?>
							</div>
						</div>
					</div>
					<div>
						<div style="width: 20%; float: left; height: 30px;">
							<div style="font-size: 13px; height: 15px;"><?= $data["customer"]["plz"] ?></div>
							<div style="border-top: 2px solid #000; font-size: 11px; margin-bottom: 15px;"><?=__('Postal code','edc')?></div>
						</div>
						<div style="width: 100%;">
							<div style="font-size: 13px; height: 15px;"><?= $data["customer"]["ort"] ?></div>
							<div style="border-top: 2px solid #000; font-size: 11px; margin-bottom: 15px;"><?=__('Location','edc')?></div>
						</div>
					</div>
					<div>
						<div>
							<div style="font-size: 13px;"><?= $data["customer"]["email"] ?></div>
							<div style="border-top: 2px solid #000; font-size: 11px; margin-bottom: 15px;"><?=__('E-mail','edc')?></div>
						</div>
					</div>
				</div>
				<div colspan="6" style="width: 48%; position: absolute; right: 0; top: 0; clear: both;">
					<div style="margin-bottom: 5px; font-size: 13px; margin-top: -5px;">
						<strong><?=__('Billing address, if different from delivery address','edc')?></strong>
					</div>
					<div>
						<div>
							<div style="font-size: 13px; height: 15px;"><?=$data["different_billing_address"]["name"]?>&nbsp;&nbsp;&nbsp;<?=$data["different_billing_address"]["surname"]?></div>
							<div style="border-top: 2px solid #000; font-size: 11px; margin-bottom: 15px;">
								<?=__('Name and second name','edc')?>
							</div>
						</div>
					</div>
					<div>
						<div>
							<div style="font-size: 13px; height: 15px;"><?=$data["different_billing_address"]["street"]?>&nbsp;&nbsp;&nbsp;<?=$data["different_billing_address"]["house"]?></div>
							<div style="border-top: 2px solid #000; font-size: 11px; margin-bottom: 15px;">
								<?=__('Address (Street, Housenumber)','edc')?>
							</div>
						</div>
					</div>
					<div style="display: flex;">
						<div style="width: 20%; float: left; height: 30px;">
							<div style="font-size: 13px; height: 15px;"><?=$data["different_billing_address"]["plz"]?></div>
							<div style="border-top: 2px solid #000; font-size: 11px; margin-bottom: 15px;"><?=__('Postal code','edc')?></div>
						</div>
						<div style="width: 100%;">
							<div style="font-size: 13px; height: 15px;"><?=$data["different_billing_address"]["ort"]?></div>
							<div style="border-top: 2px solid #000; font-size: 11px; margin-bottom: 15px;"><?=__('Location','edc')?></div>
						</div>
					</div>
					<div>
						<div>
							<div style="font-size: 13px;"><?= $data["customer"]["phone"] ?>&nbsp;</div>
							<div style="border-top: 2px solid #000; font-size: 11px; margin-bottom: 15px;"><?=__('Phone','edc')?></div>
						</div>
					</div>
				</div>
			</div>
			<?php if( $data["SERA"]["transfer"] ) : ?>
				<div>
					<div colspan="12">
						<p style="margin-bottom: 15px;margin-top: 20px;">
							<strong><?=__('Transfer','edc')?></strong>
						</p>
					</div>
				</div>
			<?php endif; ?>
			<?php if( $data["SERA"]["value"] ) : ?>
				<div>
					<div colspan="12">
						<p style="margin-bottom: 15px;margin-top: 20px;">
							<strong><?=__('SEPA direct debit mandate','edc')?></strong>
						</p>
					</div>
				</div>
				<div style="position:relative; height: 90px">
					<div style="width: 48%; position: absolute; left: 0; top: 0" colspan="6">
						<div>
							<div>
								<div style="font-size: 13px;"><?= $data["SERA"]["owner"] ?></div>
								<div style="border-top: 2px solid #000; font-size: 11px; margin-bottom: 15px;">
									<?=__('Account owner','edc')?>
								</div>
							</div>
						</div>
						<div>
							<div style="width: 100%;">
								<div style="font-size: 13px;"><?= implode('&nbsp;', $data["SERA"]["IBAN"]) ?></div>
								<div style="border-top: 2px solid #000; font-size: 11px; margin-bottom: 15px;"><?=__('IBAN','edc')?></div>
							</div>
						</div>
					</div>
					<div style="width: 48%; position: absolute; right: 0; top: 0" colspan="6">
						<div>
							<div>
								<div style="font-size: 15px;"><?=$data["SERA"]["credit"]?></div>
								<div style="border-top: 2px solid #000; font-size: 11px; margin-bottom: 15px;">
									<?=__('Credit institution','edc')?>
								</div>
							</div>
						</div>
						<div>
							<div style="width: 100%;">
								<div style="font-size: 13px;"><?= implode('&nbsp;', $data["SERA"]["BIC"]) ?></div>
								<div style="border-top: 2px solid #000; font-size: 11px; margin-bottom: 15px;"><?=__('BIC','edc')?></div>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<div>
				<div colspan="12">
					<p style="margin-bottom: 15px;margin-top: 20px;">
						<strong><?=__('Information on current supply and meter reading','edc')?></strong>
					</p>
				</div>
			</div>
			<div style="position: relative; height: 150px;">
				<div style="position: absolute; left: 0; top: 0; width: 65%;">
					<div>
						<div style="width: 47%; float: left">
							<div style="font-size: 13px; height: 14px;"><?=$data["current_information"]["supplier"]?></div>
							<div style="border-top: 2px solid #000; font-size: 11px; margin-bottom: 15px;">
								<?=__('Current supplier','edc')?>
							</div>
						</div>
						<div style="width: 100%;">
							<div style="font-size: 13px; height: 14px;"><?=$data["current_information"]["account"]?></div>
							<div style="border-top: 2px solid #000; font-size: 11px; margin-bottom: 15px;">
								<?=__('Customer or Contract Account Number','edc')?>
							</div>
						</div>
					</div>
					<div style="clear: both;">
						<div style="width: 47%; float: left">
							<div style="font-size: 13px; height: 14px;"><?=$data["current_information"]["el_number"]?></div>
							<div style="border-top: 2px solid #000; font-size: 11px; margin-bottom: 15px;"><?=__('Counter number','edc')?></div>
						</div>
						<div style="width: 100%;">
							<div style="font-size: 13px; height: 14px;"><?=$data["current_information"]["kWh2"]?> <span style="float: right;"><?=__('in kWh','edc')?></span></div>
							<div style="border-top: 2px solid #000; font-size: 11px; margin-bottom: 15px;">
								<?=__('last year\'s consumption','edc')?>
							</div>
						</div>
					</div>
					<div style="clear: both;">
						<div style="width: 47%; float: left">
							<div style="font-size: 13px; height: 14px;"><?=$data["current_information"]["kWh"]?></div>
							<div style="border-top: 2px solid #000; font-size: 11px; margin-bottom: 15px;"><?=__('meter reading','edc')?></div>
						</div>
						<div style="width: 100%;">
							<div style="font-size: 13px; height: 14px;"><?=$data["current_information"]["date2"]?></div>
							<div style="border-top: 2px solid #000; font-size: 11px; margin-bottom: 15px;">
								<?=__('date of Meter reading','edc')?>
							</div>
						</div>
					</div>
				</div>
				<div colspan="4" style="clear: both; vertical-align: top; width: 30%; position: absolute; right: 0; top: 0;">
					<div style="margin-top: -30px; margin-bottom: 5px;">
						<strong><?=__('Price and delivery start','edc')?></strong>
					</div>
					<div style="line-height: 40px; font-size: 13px;">
						<b style="color: #1c94ff"><?=__('Base Order Price','edc')?> <?=$data["gp"]?> <?=__('EUR / month','edc')?></b>
					</div>
					<div>
						<b style="color: #1c94ff; font-size: 13px;"><?=__('Working Order Price','edc')?> <?=$data["ap"]?> <?=__('ct / kWh','edc')?></b>
					</div>
					<div style="line-height: 20px; padding-top: 20px; font-size: 13px;">
						<?=__('The delivery start you want is the','edc')?> <b style="color: #1c94ff"><?=(trim($data["current_information"]["date"]) ? $data["current_information"]["date"] : __('Next possible appointment','edc'))?>.</b>
					</div>
				</div>
				<?php if($data['additional_text']) : ?>
				<div style="margin-top: 5px;line-height:normal;">
					<?=$data['additional_text']?>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</body>
</html>