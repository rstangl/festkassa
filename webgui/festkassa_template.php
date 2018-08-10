<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>FestKassa <?php echo FESTKASSA_VERSION ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style type="text/css">
	body {
		background-position:center;
		background-repeat:no-repeat;
		background-color:#222;
		color:#eee;
	}

	input.add_btn {
		background-color:#333;
		border:1px solid black;
		width:<?php echo $config->addButtonWidth() ?>px;
		height:<?php echo $config->buttonHeight() ?>px;
		font-size:<?php echo $config->buttonFontSize() ?>px;
		text-align:left;
		color:#eee;
	}

	input.subt_btn {
		background-color:#333;
		border:1px solid black;
		width:<?php echo $config->subtButtonWidth() ?>px;
		height:<?php echo $config->buttonHeight() ?>px;
		font-size:<?php echo $config->buttonFontSize() ?>px;
		color:red;
		font-weight:bold;
	}

	input.del_btn {
		background-color:#333;
		width:150px;
		height:55px;
		font-size:20px;
		color:red;
		font-weight:bold;
		border:1px solid #000000;
	}

	input.confirm_btn {
		background-color:#333;
		width:260px;
		height:55px;
		font-size:20px;
		color:lightgreen;
		font-weight:bold;
		border:1px solid #000000;
	}

	input.stk {
		height:<?php echo $config->buttonHeight() ?>px;
		background-color:#224;
		color:#eee;
		font-size:<?php echo $config->lineeditFontSize() ?>px;
		text-align:right;
		border:1px solid #000000;
	}

	input.stk0 {
		height:<?php echo $config->buttonHeight() ?>px;
		background-color:#111;
		color:#eee;
		font-size:<?php echo $config->lineeditFontSize() ?>px;
		text-align:right;
		border:1px solid #000000;
	}
	</style>
	<script type="text/javascript" src="webgui/festkassa.js"></script>
</head>

<body>

<form name="kassierfrm" method="post" action="index.php">

<table border="0" width="100%">
	<tr>
		<td align="left">
			<input type="submit" value="N&auml;chste Bestellung" class="confirm_btn" />
			<input type="button" value="L&ouml;schen" class="del_btn" onclick="loeschen()" />
		</td>
		<td align="right">
			<span style="font-size:18px"><i>Betrag:</i>&nbsp;&nbsp;&nbsp;</span>
			<span style="font-size:26px">&euro;&nbsp;&nbsp;</span>
			<input type="text" size="5" name="erg" value="<?php printf("%.2f", $lastSumPrice) ?>"
			readonly="yes" style="font-size:44px; background-color:#111; color:red; height:55px; border:solid black 1px" />
			<input type="hidden" name="last_erg" value="1" />
			<input type="hidden" name="action" value="confirm" />
		</td>
	</tr>
</table>

<hr/>

<div align="center">
<table border="0" cellpadding="4" cellspacing="0">
<tr>
<td valign="top">

<!-- BEGIN SPALTE -->
<table cellpadding="5" cellspacing="0"
style="border-width:1px; border-color:black; border-style:solid">
	<tr>
		<td><b>&nbsp;</b></td>
		<td><b>Menge</b></td>
		<td><b>Bezeichnung</b></td>
		<td><b>StkPreis</b></td>
		<td><b>Zeilensum</b></td>
	</tr>
<!-- BEGIN ARTIKELLISTE -->

<?php
$line = 1;	// Erste Zeile
foreach ($priceList as $artikel) {
	if ($line > $config->linesPerCol()) {
		// Neue Spalte beginnen
		?>
<!-- END ARTIKELLISTE -->
</table>
<!-- END SPALTE -->

</td>
<td valign="top">

<!-- BEGIN SPALTE -->
<table cellpadding="5" cellspacing="0"
style="border-width:1px; border-color:black; border-style:solid">
	<tr>
		<td><b>&nbsp;</b></td>
		<td><b>Menge</b></td>
		<td><b>Bezeichnung</b></td>
		<td><b>StkPreis</b></td>
		<td><b>Zeilensum</b></td>
	</tr>
<!-- BEGIN ARTIKELLISTE -->

		<?php
		$line = 1;	// Wieder erste Zeile der neuen Spalte
	}
	?>
	
	<tr>
		<td>
			<input type="button" onclick="subt(<?php echo $artikel->artNr() ?>)"
			class="subt_btn" value="-" />
		</td>
		<td>
			<input type="text" name="artikel_stk[<?php echo $artikel->artNr() ?>]" value="0"
			size="2" readonly="yes" class="stk0" id="artikel_stk" />
		</td>
		<td>
			<input type="button" onclick="add(<?php echo $artikel->artNr() ?>)" class="add_btn"
			value="<?php echo $artikel->artName() ?>" />
		</td>
		<td align="right">
			<span style="font-family:helvetica; font-size:<?php echo $config->lineeditFontSize() ?>px;">
			<?php echo $artikel->artStkPrice() ?></span>
			<input type="hidden" name="artikel_stk_preis[<?php echo $artikel->artNr() ?>]"
			value="<?php echo $artikel->artStkPrice() ?>" id="artikel_stk_preis" />
		</td>
		<td>
			<input type="text" name="artikel_ges_preis[<?php echo $artikel->artNr() ?>]"
			value="0.00" size="5" class="stk0" readonly="yes" id="artikel_ges_preis" />
		</td>
	</tr>

	<?php
	$line++;
}

// Fuß ausgeben
?>

<!-- END ARTIKELLISTE -->
</table>
<!-- END SPALTE -->

</td>
</tr>
</table>

<form method="post">
	<table border="0" cellspacing="0" cellpadding="1">
		<tr>
			<td></td>
			<td>Menge</td>
			<td></td>
			<td>Bezeichnung</td>
			<td>StkPreis</td>
			<td>SortNr</td>
			<td>Zeilensum</td>
		</tr>
		<tr>
			<td><input type="button" onclick="subt_extra()" class="subt_btn" value="-" /></td>
			<td><input type="text" name="extra_artikel_stk" value="0" size="2" readonly="yes"
					class="stk0" /></td>
			<td><input type="button" onclick="add_extra()" class="subt_btn" value="+"
					style="color:lightgreen;" /></td>
			<td><input type="text" name="extra_artikel_name" value="" class="stk0" size="40"
					style="text-align:left; background-color:#111; color:#eee;" /></td>
			<td><input type="text" name="extra_artikel_stk_preis" value="0.00" class="stk0"
					size="5" style="background-color:#111; color:#eee;" /></td>
			<td><input type="text" name="extra_artikel_sort_nr" value="0" class="stk0" size="2"
					style="background-color:#111; color:#eee;" /></td>
			<td><input type="text" name="extra_artikel_ges_preis" value="0.00" size="5"
					class="stk0" readonly="yes" size="5" /></td>
		</tr>
	</table>

</div>

</form>

</body>
</html>
