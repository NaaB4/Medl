<html style="font-family: Arial,sans-serif;">
	<?php
		$color='7fb741';
		if($data['tariff']->type==3 || $data['tariff']->type=='combi'){
			$color='ba1172';
		}elseif($data['tariff']->type==1 || $data['tariff']->type=='gas'){
			$color='ff6600';
		}
		if(is_numeric($data['options']['goodies'])){
			$goodie=EDC_GOODIES::item($data['options']['goodies']);
		}
	?>
	<style>
		@font-face{
			font-family: 'IBMPlexSerif-Regular';
			font-style: normal;
			font-weight: normal;
			src: url(/wp-content/uploads/et-fonts/IBMPlexSerif-Regular.ttf) format('truetype');
		}
		@font-face{
			font-family: 'IBMPlexSerif-Bold';
			font-style: normal;
			font-weight: normal;
			src: url(/wp-content/uploads/et-fonts/IBMPlexSerif-Bold.ttf) format('truetype');
		}
		
		@page{ margin-top: 95px; margin-left: 30px; margin-right: 30px; margin-bottom: 40px; }
		*{ font-family: 'IBMPlexSerif-Regular', Helvetica, Arial, Lucida, sans-serif; box-sizing: border-box; }
		body { margin: 0px; font-size: 14px; line-height: 14px; }
		.logo{ position: fixed; right: 30px; top: -90px; width: 100px; height: 78px; }
		h1,h2,h3{ font-family: 'IBMPlexSerif-Bold', Helvetica, Arial, Lucida, sans-serif !important; }
		h1{ font-size: 20px; margin-bottom: 15px; }
		h2{ font-size: 18px; margin-bottom: 10px; }
		h3{ font-size: 16px; }
		.cgreen{ color: #<?=$color?>; }
		.bgreen{ background: #<?=$color?>; }
		.bgray{ background: #eeefef; }
		.bbottom{ border-bottom: 2px solid #fff; }
		.row{ width: 100%; position: relative; }
		.row_cell{ vertical-align: top; line-height: 20px; background: #eeefef; padding: 2px 8px; position: relative; }
		.row_cell.bgreen{ background: #<?=$color?>; color: #fff; font-weight: bold; }
		.small .row_cell{ font-size: 12px; line-height: 14px; }
		.small.smallfs .row_cell{ font-size: 10px; line-height: 14px; }
		.smaller .row_cell{ font-size: 10px; line-height: 12px; }
		.row_cell::after{ content: ""; display: block; position: absolute; right: 0; top: 0; height: 100%; width: 1px; background: #fff; }
		.row_cell:last-child::after{ display: none; }
		.cell_1_5{ width: 18%; }
		.left_cell{ width: 10%; }
		.center_cell{ width: 63%; }
		.right_cell{ width: 27%; }
		.left_cell2{ width: 46%; }
		.center_cell2{ width: 18%; }
		.right_cell2{ width: 36%; }
		.left_cell3{ width: 35%; }
		.center_cell3{ width: 40%; }
		.right_cell3{ width: 25%; }
		.left_cell4{ width: 65%; }
		.right_cell4{ width: 35%; }
		.full_cell{ width: 100%; }
		.cell_half{ width: 50%; }
		.subtitle{ margin-bottom: 20px; }
		table{ width: 100%; cellpadding: 0; cellspasing: 0; border-collapse: collapse; }
		table td{ border-bottom: 2px solid #fff; }
		.checkbox{ border: 1px solid #<?=$color?>; margin-right: 4px; vertical-align: middle; display: inline-block; width: 12px; height: 12px; margin-top: 2px; text-align: center; font-size: 14px; line-height: 8px; }
	</style>
	<body style="margin: auto">
		<div>
			<img class="logo" src="<?=get_stylesheet_directory()?>/edc/assets/images/medl-logo.jpg">
			<h1>Ich möchte den Tarif <span class="cgreen"><?=$data['tariff']->title?></span> abschließen.</h1>
			<h2>A Kundendaten/Lieferstelle</h2>
			<div class="subtitle cgreen">
				Die vollständigen Angaben sind erforderlich, damit die medl GmbH die Neuanmeldung in Ihrem Interesse reibungslos und schnell ausführen kann. (*Angaben optional)
			</div>
			<div style="color:#fff;padding: 3px 8px;font-weight:bold;font-size:12px;" class="bgreen bbottom">
				1. Kundendaten bzw. Rechnungsadresse
			</div>
			<table>
				<tr class="bbottom bgray row small">
					<td class="row_cell left_cell"><span><?=($data['options']['edc_anrede']=='m' ? 'Herr' : 'Frau')?></span></td>
					<td class="row_cell center_cell"><span><?=$data['options']['edc_first_name']?> <?=$data['options']['edc_name']?></span></td>
					<td class="row_cell right_cell"><span>&nbsp;</span></td>
				</tr>
			</table>
			<table>
				<tr class="bbottom bgray row small smallfs">
					<td class="row_cell left_cell"><span>Anrede</span></td>
					<td class="row_cell center_cell"><span>Vorname Name </span></td>
					<td class="row_cell right_cell"><span>Kundennummer</span></td>
				</tr>
			</table>
			<table>
				<tr class="bbottom bgray row small">
					<td class="row_cell left_cell2"><span><?=$data['options']['edc_street'].' '.$data['options']['edc_house']?></span></td>
                    <td class="row_cell left_cell2"><span><?=$data['options']['edc_house_zuratc']?></span></td>
					<td class="row_cell center_cell2"><span><?=$data['options']['edc_postal_code']?></span></td>
					<td class="row_cell right_cell2"><span><?=$data['options']['edc_location']?></span></td>
				</tr>
			</table>
			<table>
				<tr class="bbottom bgray row small smallfs">
                    <td class="row_cell left_cell2"><span>Straße Hausnummer</span></td>
                    <td class="row_cell left_cell2"><span>Hausnummerzusatz</span></td>
					<td class="row_cell center_cell2"><span>PLZ</span></td>
					<td class="row_cell right_cell2"><span>Ort</span></td>
				</tr>
			</table>
			<table>
				<tr class="bbottom bgray row small">
					<td class="row_cell left_cell3"><span><?=$data['options']['edc_phone']?></span></td>
					<td class="row_cell center_cell3"><span><?=$data['options']['edc_email']?></span></td>
					<td class="row_cell right_cell3"><span><?=$data['options']['edc_date_of_birth']?></span></td>
				</tr>
			</table>
			<table>
				<tr class="bbottom bgray row small smallfs">
					<td class="row_cell left_cell3"><span>Telefon</span></td>
					<td class="row_cell center_cell3"><span>E-Mail-Adresse*</span></td>
					<td class="row_cell right_cell3"><span>Geburtsdatum</span></td>
				</tr>
			</table>
			<div style="color:#fff;padding: 3px 8px;font-weight:bold;font-size:12px;" class="bgreen bbottom">
				2. Lieferstelle
			</div>
			<table>
				<tr class="bbottom bgray row small">
                    <td class="row_cell left_cell2"><span><?=$data['options']['edc_street'].' '.$data['options']['edc_house']?></span></td>
                    <td class="row_cell left_cell2"><span><?=$data['options']['change_house_zuratc']?></span></td>
					<td class="row_cell center_cell2"><span><?=$data['options']['edc_postal_code']?></span></td>
					<td class="row_cell right_cell2"><span><?=$data['options']['edc_location']?></span></td>
				</tr>
			</table>
			<table>
				<tr class="bbottom bgray row small">
                    <td class="row_cell left_cell2"><span>Straße Hausnummer</span></td>
                    <td class="row_cell left_cell2"><span>Hausnummerzusatz</span></td>
					<td class="row_cell center_cell2"><span>PLZ</span></td>
					<td class="row_cell right_cell2"><span>Ort</span></td>
				</tr>
			</table>
			<div style="color:#fff;padding: 3px 8px;font-weight:bold;font-size:12px;" class="bgreen bbottom">
				3. Umzugsangaben
			</div>
			<table>
				<tr class="bbottom bgray row small">
					<td class="row_cell"><span><?=$data['options']['edc_read_date']?></span></td>
				</tr>
			</table>
			<table>
				<tr class="bbottom bgray row small">
					<td class="row_cell"><span>Einzugsdatum</span></td>
				</tr>
			</table>
			<div style="color:#fff;padding: 3px 8px;font-weight:bold;font-size:12px;" class="bgreen bbottom">
				4. Angaben zum Zähler
			</div>
			<table>
				<?php if(EDCH::proceedType($data['tariff']->type,true)=='electricity' || EDCH::proceedType($data['tariff']->type,true)=='gas') : ?>
				<tr class="bbottom bgray row smaller">
					<?php
						if(EDCH::proceedType($data['tariff']->type,true)=='electricity') $type='Strom';
						elseif(EDCH::proceedType($data['tariff']->type,true)=='gas') $type='Gas';
						else $type='Kombi';
					?>
					<td class="row_cell left_cell bgreen" rowspan="2" style="vertical-align:middle;text-align:center;"><span><?=$type?></span></td>
					<td class="row_cell cell_1_5"><span><?=$data['options']['edc_electriс']?></span></td>
					<td class="row_cell cell_1_5"><span><?=$data['options']['edc_electriс_value']?></span></td>
					<td class="row_cell cell_1_5"><span><?=$data['annual_consumption']?></span></td>
				</tr>
				<tr class="bbottom bgray row smaller">
					<td class="row_cell cell_1_5"><span>Vollständige Zählernummer</span></td>
					<td class="row_cell cell_1_5"><span>Zählerstand bei Einzug</span></td>
					<td class="row_cell cell_1_5"><span>Geschätzter Jahresverbrauch in kWh</span></td>
				</tr>
				<?php else : ?>
				<tr class="bbottom bgray row smaller">
					<td class="row_cell left_cell bgreen" rowspan="2" style="vertical-align:middle;text-align:center;"><span>Strom</span></td>
					<td class="row_cell cell_1_5"><span><?=$data['options']['edc_electriс2']?></span></td>
					<td class="row_cell cell_1_5"><span><?=$data['options']['edc_electriс_value2']?></span></td>
					<td class="row_cell cell_1_5"><span><?=$data['options']['annual_el']?></span></td>
				</tr>
				<tr class="bbottom bgray row smaller">
					<td class="row_cell cell_1_5"><span>Vollständige Zählernummer</span></td>
					<td class="row_cell cell_1_5"><span>Zählerstand bei Einzug</span></td>
					<td class="row_cell cell_1_5"><span>Geschätzter Jahresverbrauch in kWh (Strom)</span></td>
				</tr>
				<tr class="bbottom bgray row smaller">
					<td class="row_cell left_cell bgreen" rowspan="2" style="vertical-align:middle;text-align:center;"><span>Gas</span></td>
					<td class="row_cell cell_1_5"><span><?=$data['options']['edc_electriс']?></span></td>
					<td class="row_cell cell_1_5"><span><?=$data['options']['edc_electriс_value']?></span></td>
					<td class="row_cell cell_1_5"><span><?=$data['options']['annual_gas']?></span></td>
				</tr>
				<tr class="bbottom bgray row smaller">
					<td class="row_cell cell_1_5"><span>Vollständige Zählernummer</span></td>
					<td class="row_cell cell_1_5"><span>Zählerstand bei Einzug</span></td>
					<td class="row_cell cell_1_5"><span>Geschätzter Jahresverbrauch in kWh (Erdgas)</span></td>
				</tr>
				</tr>				
				<?php endif; ?>
			</table>
			<div style="margin-top: 15px;">
				<b>Informationen zu Produkten und Dienstleistungen der medl GmbH</b><br>
				Die medl GmbH möchte Sie gerne über aktuelle Angebote und Produkte von medl aus den Bereichen Energieerzeugung (z.B. PVAnlagen), Energiebelieferung (z.B. Strom, Erdgas, Wärme), Energieeffizienz (z.B. Energieeinsparberatung, SmartHome),
				Elektromobilität (z.B. Verkauf von Ladeboxen) und sonstige energienahe Leistungen oder Services (z.B. Garantieleistungen)
				informieren und Sie zu Ihrer Meinung über Produkte von medl aus den o.g. Bereichen, neue Produktideen von medl aus dem
				Energiebereich und die Servicequalität von medl befragen (Marktforschung).<br>
				<div class="checkbox"><?=(EDCH::is($data['options']['edc_mobile_checkbox']) ? '&times;' : '')?></div> Ja, ich willige ein, telefonisch über meine genannte Telefon- oder Mobilrufnummer zu den vorstehend genannten Zwecken der
				Produktwerbung und Marktforschung von medl kontaktiert zu werden.<br>
				<div class="checkbox"><?=(EDCH::is($data['options']['edc_email_checkbox']) ? '&times;' : '')?></div> Ja, ich willige ein, per E-Mail über meine genannte E-Mail-Adresse zu den vorstehend genannten Zwecken der
				Produktwerbung und Marktforschung von medl kontaktiert zu werden.<br>
				<b><u>Ihr Werbewiderspruchsrecht:</u></b> Sie können der werblichen Nutzung Ihrer Daten oder der Nutzung zu Meinungsbefragungen jederzeit
				gegenüber der medl GmbH widersprechen: medl GmbH, Burgstr. 1, 45476 Mülheim an der Ruhr oder Tel. 0208 4501 333 oder
				service@medl.de. Auf dieses Widerrufsrecht wird Sie medl bei jeder werblichen Kontaktaufnahme erneut hinweisen.
			</div>
			<?php if($goodie) : ?>
			<div>
				<p><b>Ihr gewählter Bonus:</b> "<?=$goodie->name?>"</p>
			</div>
			<?php endif; ?>
			<div>
				<p><b>E SEPA-Basislastschrift</b><br>
				Ich/Wir, <?=$data['options']['edc_holder']?>, ermächtige/n die medl GmbH, Zahlungen von meinem/unserem Konto
				mittels der SEPA-Basislastschrift einzuziehen. Zugleich weise/n ich/wir mein/unser Kreditinstitut an, die von medl GmbH auf mein/
				unser Konto gezogenen SEPA-Basislastschriften einzulösen. Mein/unser Konto führe/n ich/wir bei der:</p>
			</div>
			<div style="color:#fff;padding: 3px 8px;font-weight:bold;font-size:12px;" class="bgreen bbottom">
				1. Bankverbindung
			</div>
			<table>
				<tr class="bbottom bgray row small">
					<td class="row_cell full_cell"><span><?=$data['options']['edc_credit']?></span></td>
				</tr>
			</table>
			<table>
				<tr class="bbottom bgray row small">
					<td class="row_cell full_cell"><span>Bank</span></td>
				</tr>
			</table>
			<table>
				<?php
					$iban=implode('',$data['options']['edc_IBAN']);
					$bic=implode('',$data['options']['edc_BIC']);
					$iban=substr($iban,0,strlen($iban)-6).'******';
					$bic=substr($bic,0,strlen($bic)-5).'*****';
				?>
				<tr class="bbottom bgray row small">
					<td class="row_cell left_cell4"><span><?=$iban?></span></td>
					<td class="row_cell right_cell4"><span><?=$bic?></span></td>
				</tr>
			</table>
			<table>
				<tr class="bbottom bgray row small smallfs">
					<td class="row_cell left_cell4"><span>IBAN</span></td>
					<td class="row_cell right_cell4"><span>BIC</span></td>
				</tr>
			</table>
			<table>
				<tr class="bbottom bgray row small">
					<td class="row_cell full_cell"><span>&nbsp;</span></td>
				</tr>
			</table>
			<table>
				<tr class="bbottom bgray row small">
					<td class="row_cell full_cell"><span>Abweichender Kontoinhaber</span></td>
				</tr>
			</table>
			<div class="cgreen">Gesonderte Zahlungsaufforderungen für die Bezahlung der monatlichen Abschläge erstellt die medl GmbH nicht.</div>
			<div style="margin-top: 20px; margin-bottom: 15px;">
				<b>Hinweis:</b> Ich kann/Wir können innerhalb von 8 Wochen, beginnend mit dem Belastungsdatum, die Erstattung des belasteten Betrages
				verlangen. Es gelten dabei die mit meinem/unserem Geldinstitut vereinbarten Bedingungen. Wenn mein/unser Konto die erforderliche
				Deckung nicht aufweist, besteht seitens des Geldinstitutes keine Verpflichtung zur Einlösung. Das SEPA-Basislastschriftverfahren kann
				eingestellt werden, falls Rechnungen vom Geldinstitut nicht eingelöst zurückgegeben werden. Mit ihrem Bestätigungsschreiben teilt die
				medl GmbH Ihnen die Mandatsreferenznummer sowie die Gläubiger-ID mit.
			</div>
			<div class="sign" style="margin-top: 30px; position:relative;height:35px;">
				<div style="position:absolute;left:0;top:-19px;width:30%;">
					<?=$data['options']['edc_location']?>, <?=date('d.m.Y')?>
				</div>
				<div style="position:absolute;left:0;top:0;width:30%;border-top:2px solid #000;">
					<b>Ort, Datum</b>
				</div>
			</div>
		</div>
	</body>
</html>