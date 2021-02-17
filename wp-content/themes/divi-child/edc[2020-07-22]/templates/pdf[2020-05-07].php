<html style="font-family: Arial,sans-serif;">
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
		.cgreen{ color: #7fb741; }
		.bgreen{ background: #7fb741; }
		.bgray{ background: #eeefef; }
		.bbottom{ border-bottom: 2px solid #fff; }
		.row{ width: 100%; position: relative; }
		.row_cell{ vertical-align: top; line-height: 20px; background: #eeefef; padding: 2px 8px; position: relative; }
		.row_cell.bgreen{ background: #7fb741; color: #fff; font-weight: bold; }
		.small .row_cell{ font-size: 12px; line-height: 14px; }
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
		.checkbox{ border: 1px solid #7fb741; margin-right: 4px; vertical-align: middle; display: inline-block; width: 12px; height: 12px; margin-top: 2px; }
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
					<td class="row_cell center_cell"><span><?=$data['options']['edc_name']?> <?=$data['options']['edc_first_name']?></span></td>
					<td class="row_cell right_cell"><span>&nbsp;</span></td>
				</tr>
			</table>
			<table>
				<tr class="bbottom bgray row small">
					<td class="row_cell full_cell"><span>&nbsp;</span></td>
				</tr>
			</table>
			<table>
				<tr class="bbottom bgray row small">
					<td class="row_cell full_cell"><span>&nbsp;</span></td>
				</tr>
			</table>
			<table>
				<tr class="bbottom bgray row small">
					<td class="row_cell left_cell"><span>Anrede</span></td>
					<td class="row_cell center_cell"><span>Vorname Name </span></td>
					<td class="row_cell right_cell"><span>Kundennummer</span></td>
				</tr>
			</table>
			<table>
				<tr class="bbottom bgray row small">
					<td class="row_cell left_cell2"><span><?=$data['options']['edc_street']?></span></td>
					<td class="row_cell center_cell2"><span><?=$data['options']['edc_postal_code']?></span></td>
					<td class="row_cell right_cell2"><span><?=$data['options']['edc_location']?></span></td>
				</tr>
			</table>
			<table>
				<tr class="bbottom bgray row small">
					<td class="row_cell left_cell2"><span>Straße Hausnummer</span></td>
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
				<tr class="bbottom bgray row small">
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
					<td class="row_cell left_cell2"><span><?=$data['options']['edc_street']?></span></td>
					<td class="row_cell center_cell2"><span><?=$data['options']['edc_postal_code']?></span></td>
					<td class="row_cell right_cell2"><span><?=$data['options']['edc_location']?></span></td>
				</tr>
			</table>
			<table>
				<tr class="bbottom bgray row small">
					<td class="row_cell left_cell2"><span>Straße Hausnummer</span></td>
					<td class="row_cell center_cell2"><span>PLZ</span></td>
					<td class="row_cell right_cell2"><span>Ort</span></td>
				</tr>
			</table>
			<div style="color:#fff;padding: 3px 8px;font-weight:bold;font-size:12px;" class="bgreen bbottom">
				3. Angaben zum Lieferantenwechsel
			</div>
			<table>
				<tr class="bbottom bgray row small">
					<td class="row_cell cell_half"><span class="cgreen"><div class="checkbox"></div> Ich habe bereits bei meinem aktuellen Lieferanten zu folgendem Datum gekündigt:</span></td>
					<td class="row_cell cell_half"><span class="cgreen"><div class="checkbox"></div> Ich habe noch nicht bei meinem aktuellen Lieferanten gekündigt und möchte zum schnellstmöglichen Termin zur medl GmbH wechseln.</span></td>
				</tr>
			</table>
			<div style="color:#fff;padding: 3px 8px;font-weight:bold;font-size:12px;" class="bgreen bbottom">
				4. Angaben zum Zähler
			</div>
			<table>
				<tr class="bbottom bgray row">
					<td class="row_cell left_cell bgreen" rowspan="2" style="vertical-align:middle;text-align:center;"><span>Strom</span></td>
					<td class="row_cell cell_1_5"><span>&nbsp;</span></td>
					<td class="row_cell cell_1_5"><span>&nbsp;</span></td>
					<td class="row_cell cell_1_5"><span><?=$data['options']['edc_electriс']?></span></td>
					<td class="row_cell cell_1_5"><span>&nbsp;</span></td>
				</tr>
				<tr class="bbottom bgray row smaller">
					<td class="row_cell cell_1_5"><span>Bisheriger Lieferant</span></td>
					<td class="row_cell cell_1_5"><span>Bisherige Kundennummer</span></td>
					<td class="row_cell cell_1_5"><span>Vollständige Zählernummer</span></td>
					<td class="row_cell cell_1_5"><span>Vorjahresverbrauch in kWh</span></td>
				</tr>
			</table>
			<div style="margin: 10px 0;">
			<b>B Preise des Sondervertrages</b><br>
			(Stand 01.06.2019)
			</div>			
			<table style="margin-bottom:15px;text-align: center;">
				<tr class="bbottom bgray row smaller">
					<td colspan="2" rowspan="2" class="row_cell left_cell bgreen"><span>&nbsp;</span></td>
					<td class="row_cell bgreen">Jahresverbrauch</td>
					<td class="row_cell bgreen" colspan="2">Arbeitspreis [ct/kWh]</td>
					<td class="row_cell bgreen" colspan="2">Grundpreis [€/Monat]</td>
				</tr>
				<tr class="bbottom bgray row smaller">
					<td class="row_cell bgreen">von [kWh] bis [kWh]</td>
					<td class="row_cell bgreen">netto</td>
					<td class="row_cell bgreen">brutto</td>
					<td class="row_cell bgreen">netto</td>
					<td class="row_cell bgreen">brutto</td>
				</tr>
				<tr class="bbottom bgray row smaller">
					<td class="row_cell bgreen" style="text-align: center; vertical-align: middle;" rowspan="4">Strom</td>
					<td style="text-align: center; vertical-align: middle;" class="row_cell bgreen">XS</td>
					<td>0 – 1.250</td>
					<td>25,20</td>
					<td>29,99</td>
					<td>7,00</td>
					<td>8,33</td>
				</tr>
				<tr class="bbottom bgray row smaller">
					<td style="text-align: center; vertical-align: middle;" class="row_cell bgreen">S</td>
					<td>1.251 – 2.500</td>
					<td>25,20</td>
					<td>29,99</td>
					<td>5,00</td>
					<td>5,95</td>
				</tr>
				<tr class="bbottom bgray row smaller">
					<td style="text-align: center; vertical-align: middle;" class="row_cell bgreen">M</td>
					<td>2.501 – 3.500</td>
					<td>23,95</td>
					<td>28,50</td>
					<td>5,00</td>
					<td>5,95</td>
				</tr>
				<tr class="bbottom bgray row smaller">
					<td style="text-align: center; vertical-align: middle;" class="row_cell bgreen">L</td>
					<td>3.501 – 100.000</td>
					<td>23,95</td>
					<td>28,50</td>
					<td>5,00</td>
					<td>5,95</td>
				</tr>
			</table>
			<div class="cgreen">
				<b>Informationen zu Produkten und Dienstleistungen der medl GmbH</b><br>
				Die medl GmbH möchte Sie gerne über aktuelle Angebote und Produkte von medl aus den Bereichen Energieerzeugung (z.B. PVAnlagen), Energiebelieferung (z.B. Strom, Erdgas, Wärme), Energieeffizienz (z.B. Energieeinsparberatung, SmartHome),
				Elektromobilität (z.B. Verkauf von Ladeboxen) und sonstige energienahe Leistungen oder Services (z.B. Garantieleistungen)
				informieren und Sie zu Ihrer Meinung über Produkte von medl aus den o.g. Bereichen, neue Produktideen von medl aus dem
				Energiebereich und die Servicequalität von medl befragen (Marktforschung).<br>
				<div class="checkbox"></div> Ja, ich willige ein, telefonisch über meine genannte Telefon- oder Mobilrufnummer zu den vorstehend genannten Zwecken der
				Produktwerbung und Marktforschung von medl kontaktiert zu werden.<br>
				<div class="checkbox"></div> Ja, ich willige ein, per E-Mail über meine genannte E-Mail-Adresse zu den vorstehend genannten Zwecken der
				Produktwerbung und Marktforschung von medl kontaktiert zu werden.<br>
				<b><u>Ihr Werbewiderspruchsrecht:</u></b> Sie können der werblichen Nutzung Ihrer Daten oder der Nutzung zu Meinungsbefragungen jederzeit
				gegenüber der medl GmbH widersprechen: medl GmbH, Burgstr. 1, 45476 Mülheim an der Ruhr oder Tel. 0208 4501 333 oder
				service@medl.de. Auf dieses Widerrufsrecht wird Sie medl bei jeder werblichen Kontaktaufnahme erneut hinweisen.
			</div>
			<div>
				<p><b>C Widerrufsrecht</b><br>
				Sie haben das Recht, binnen vierzehn Tagen ohne Angabe von Gründen diesen Vertrag zu widerrufen. Die Widerrufsfrist beträgt
				vierzehn Tage ab dem Tag des Vertragsschlusses. Um Ihr Widerrufsrecht auszuüben, müssen sie uns (medl GmbH, Burgstraße 1,
				45476 Mülheim an der Ruhr, Telefonnummer: 0208 4501 333, E-Mail: service@medl.de) mittels einer eindeutigen Erklärung (z.B. ein
				mit der Post versandter Brief oder E-Mail) über Ihren Entschluss, diesen Vertrag zu widerrufen, informieren. Sie können dafür das
				beigefügte Muster-Widerrufsformular verwenden, das jedoch nicht vorgeschrieben ist. Sie können das Muster-Widerrufsformular oder
				eine andere eindeutige Erklärung auch auf unserer Webseite www.medl.de elektronisch ausfüllen und übermitteln. Machen Sie von
				dieser Möglichkeit Gebrauch, so werden wir Ihnen unverzüglich (z.B. per E-Mail) eine Bestätigung über den Eingang eines solchen
				Widerrufs übermitteln. Zur Wahrung der Widerrufsfrist reicht es aus, dass Sie die Mitteilung über die Ausübung des Widerrufsrechts vor
				Ablauf der Widerrufsfrist absenden.</p>
				<p><b>Folgen des Widerrufs:</b> Wenn Sie diesen Vertrag widerrufen, haben wir Ihnen alle Zahlungen, die wir von Ihnen erhalten haben,
				einschließlich der Lieferkosten (mit Ausnahme der zusätzlichen Kosten, die sich daraus ergeben, dass Sie eine andere Art der
				Lieferung als die von uns angebotene, günstigste Standardlieferung gewählt haben), unverzüglich und spätestens binnen vierzehn
				Tagen ab dem Tag zurückzuzahlen, an dem die Mitteilung über Ihren Widerruf dieses Vertrags bei uns eingegangen ist. Für diese
				Rückzahlung verwenden wir dasselbe Zahlungsmittel, das Sie bei der ursprünglichen Transaktion eingesetzt haben, es sei denn, mit
				Ihnen wurde ausdrücklich etwas anderes vereinbart; in keinem Fall werden Ihnen wegen dieser Rückzahlung Entgelte berechnet.
				Haben Sie verlangt, dass die Dienstleistungen während der Widerrufsfrist beginnen sollen, so haben Sie uns einen angemessenen
				Betrag zu zahlen, der dem Anteil der bis zu dem Zeitpunkt, zu dem Sie uns von der Ausübung des Widerrufsrechts hinsichtlich dieses
				Vertrags unterrichten, bereits erbrachten Dienstleistungen im Vergleich zum Gesamtumfang der im Vertrag vorgesehenen
				Dienstleistungen entspricht.</p>
				<p><b>D Vertragsabschluss</b><br>
				Der Kunde beauftragt die medl GmbH mit der Belieferung von Strom. Der Kunde bevollmächtigt die medl GmbH, für die genannte
				Lieferstelle die für die Lieferung erforderlichen Verträge mit dem örtlichen Netzbetreiber abzuschließen. Die medl GmbH wird die ihr
				möglichen Maßnahmen treffen, um dem Kunden am Ende des Netzanschlusses, zu dessen Nutzung der Kunde nach der
				Niederspannungsanschlussverordnung berechtigt ist, Strom zur Verfügung zu stellen. Der Vertrag kommt mit der Vertragsbestätigung
				der medl GmbH zustande. Der Kunde bestätigt, die Allgemeinen Geschäftsbedingungen zum Sondervertrag <span class="cgreen"><?=$data['tariff']->title?></span> der medl
				GmbH (AGB) erhalten zu haben und als wesentlichen Bestandteil dieses Vertrags zu akzeptieren.</p>
			</div>
			<div class="sign" style="margin-top: 30px; position:relative;height:35px;">
				<div style="position:absolute;left:0;top:-19px;width:30%;">
					<?=date('Y.m.d')?>
				</div>
				<div style="position:absolute;left:0;top:0;width:30%;border-top:2px solid #000;">
					<b>Ort, Datum</b>
				</div>
				<div style="position:absolute;right:30px;top:0;width:30%;border-top:2px solid #000;">
					<b>Unterschrift Kunde</b>
				</div>
			</div>
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
					<td class="row_cell full_cell"><span>&nbsp;</span></td>
				</tr>
			</table>
			<table>
				<tr class="bbottom bgray row small">
					<td class="row_cell full_cell"><span>Bank</span></td>
				</tr>
			</table>
			<table>
				<tr class="bbottom bgray row small">
					<td class="row_cell left_cell4"><span><?=(implode('',$data['options']['edc_IBAN']))?></span></td>
					<td class="row_cell right_cell4"><span><?=(implode('',$data['options']['edc_BIC']))?></span></td>
				</tr>
			</table>
			<table>
				<tr class="bbottom bgray row small">
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
			<p><b>Um Doppelzahlungen zu vermeiden, bitten wir Sie, bei der Überschreitung des Fälligkeitstermins folgendes anzukreuzen:</b></p>
			<div>
				<div class="checkbox"></div> Der ausstehende Betrag wird/wurde bereits überwiesen.<br><br>
				<div class="checkbox"></div> Der ausstehende Betrag soll von der medl GmbH im SEPA-Basislastschriftverfahren eingezogen werden.
			</div>
			<div class="sign" style="margin-top: 30px; position:relative;height:35px;">
				<div style="position:absolute;left:0;top:-19px;width:30%;">
					<?=date('Y.m.d')?>
				</div>
				<div style="position:absolute;left:0;top:0;width:30%;border-top:2px solid #000;">
					<b>Ort, Datum</b>
				</div>
				<div style="position:absolute;right:30px;top:0;width:30%;border-top:2px solid #000;">
					<b>Unterschrift Kunde</b>
				</div>
			</div>
		</div>
	</body>
</html>