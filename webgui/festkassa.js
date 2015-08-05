/**
 * FestKassa v. 1.2.0
 * 
 * Kassa- und Abrechnungssystem für das jährliche
 * Sommerfest der FF-Prebuch
 *
 * @author Richard Stangl
 * @copyright 2004-2007 by Freiwillige Feuerwehr Prebuch
 * 
 * @package ff.festkassa.webgui
 */

/**
 * Lädt die FestKassa Web GUI neu um alle Felder zu leeren.
 */
function loeschen() {
	window.location.href="index.php";
}

/**
 * Erhöht die Stückzahl des angegebenen Artikels um 1.
 *
 * @param	int		artikel_nr		Artikelnummer
 */
function add(artikel_nr) {
	var stk = 0;

	stk = parseInt(window.document.kassierfrm.artikel_stk[artikel_nr].value);
	stk += 1;
	window.document.kassierfrm.artikel_stk[artikel_nr].value = stk;

	aktual();
}

/**
 * Erniedrigt die Stückzahl des angegebenen Artikels um 1.
 *
 * @param	int		artikel_nr		Artikelnummer
 */
function subt(artikel_nr) {
	var stk = 0;

	stk = parseInt(window.document.kassierfrm.artikel_stk[artikel_nr].value);
	stk -= 1;
	window.document.kassierfrm.artikel_stk[artikel_nr].value = stk;

	aktual();
}

/**
 * Erhöht die Stückzahl des Extra-Artikels um 1.
 */
function add_extra() {
	var stk = 0;

	stk = parseInt(window.document.kassierfrm.extra_artikel_stk.value);
	stk += 1;
	window.document.kassierfrm.extra_artikel_stk.value = stk;

	aktual();
}

/**
 * Erniedrigt die Stückzahl des Extra-Artikels um 1.
 */
function subt_extra() {
	var stk = 0;

	stk = parseInt(window.document.kassierfrm.extra_artikel_stk.value);
	stk -= 1;
	window.document.kassierfrm.extra_artikel_stk.value = stk;

	aktual();
}

/**
 * Berechnet alles neu.
 */
function aktual() {
	var ges_betrag = 0;
	var zeilen_summe = 0;

	var stk = 0;
	var stk_preis = 0;
	var ges_preis = 0;

	// Zeilensummen ausrechnen
	for (var i = 0; i < window.document.kassierfrm.artikel_stk.length; i++) {
		stk = parseInt(window.document.kassierfrm.artikel_stk[i].value);
		stk_preis = parseFloat(window.document.kassierfrm.artikel_stk_preis[i].value);
		ges_preis = stk * stk_preis;
		ges_preis = Math.round(ges_preis * 100) / 100;	// Auf 2 Dezimalstellen runden
		window.document.kassierfrm.artikel_ges_preis[i].value = ges_preis.toFixed(2);

		zeilen_summe = parseFloat(window.document.kassierfrm.artikel_ges_preis[i].value);
		ges_betrag += Math.round(zeilen_summe * 100) / 100;

		if (parseInt(window.document.kassierfrm.artikel_stk[i].value) == 0) {
			window.document.kassierfrm.artikel_stk[i].className = "stk0";
			window.document.kassierfrm.artikel_ges_preis[i].className = "stk0";
		}
		else {
			window.document.kassierfrm.artikel_stk[i].className = "stk";
			window.document.kassierfrm.artikel_ges_preis[i].className = "stk";
		}
	}

	// Zeilensumme des Extra-Artikels
	stk = parseInt(window.document.kassierfrm.extra_artikel_stk.value);
	stk_preis = parseFloat(window.document.kassierfrm.extra_artikel_stk_preis.value);
	ges_preis = stk * stk_preis;
	ges_preis = Math.round(ges_preis * 100) / 100;	// Auf 2 Dezimalstellen runden
	window.document.kassierfrm.extra_artikel_ges_preis.value = ges_preis.toFixed(2);

	zeilen_summe = parseFloat(window.document.kassierfrm.extra_artikel_ges_preis.value);
	ges_betrag += Math.round(zeilen_summe * 100) / 100;

	if (parseInt(window.document.kassierfrm.extra_artikel_stk.value) == 0) {
		window.document.kassierfrm.extra_artikel_stk.className = "stk0";
		window.document.kassierfrm.extra_artikel_ges_preis.className = "stk0";
	}
	else {
		window.document.kassierfrm.extra_artikel_stk.className = "stk";
		window.document.kassierfrm.extra_artikel_ges_preis.className = "stk";
	}

	// Gesamtbetrag
	ges_betrag = Math.round(ges_betrag * 100) / 100;

	// Wird noch die Summe der vorigen Bestellung angezeigt?
	if (window.document.kassierfrm.last_erg.value == "1") {
		window.document.kassierfrm.last_erg.value = "0";
		window.document.kassierfrm.erg.value = "0.00";
	}

	window.document.kassierfrm.erg.value = ges_betrag.toFixed(2);
}

/*
function print_bon() {
	var i;							// Laufvariable durch alle Artikel
	var s;							// Sortierungsnummer
	var max_s = 0;					// Höchste vorkommende Artikelsortierungsnummer
	var found = false;			// Trap zum Herausfinden, ob Sortierungsnummer min. 1 mal vorkommt
	var zwischen_sum = 0.00;	// Preis aller Getränke/Essen/...
	var zettel_anz = 0;			// Anzahl der Einzel-Zettel

	// Sicherheitshalber noch alles neu berechnen
	aktual();

	// Schriftart einstellen
	window.document.kassierfrm.print_string.value += "\x1b\x14";	// Höhe zurücksetzen
	window.document.kassierfrm.print_string.value += "\x1b:";		// 16 Pt
	<?php
		if ($cfg['big_print_font']) {
	?>
	window.document.kassierfrm.print_string.value += "\x1b\x0e";	// Höhe verdoppeln
	<?php
		}
	?>

	window.document.kassierfrm.print_string.value += "<?php echo $cfg['kassa_name'] ?>";
	window.document.kassierfrm.print_string.value += "\n\n";

	// Herausfinden, welche die höchste vorkommende Artikelsortierungsnummer ist
	for (i = 1; i < window.document.forms.length; i++) {
		if (parseInt(window.document.forms[i].artikel_stk.value) > 0 &&
			parseInt(window.document.forms[i].artikel_sort_nr.value) > max_s)
			max_s = parseInt(window.document.forms[i].artikel_sort_nr.value);
	}

	// Über alle vorkommenden Artikelsortierungsnummern iterieren
	for (s = 1; s <= max_s; s++) {
		if (found == true) {
			window.document.kassierfrm.print_string.value += "\n\n\n\n\n\n\n\x1bd1";	// Perforation
			window.document.kassierfrm.print_string.value += "<?php echo $cfg['kassa_name'] ?>\n\n";
		}

		found = false;		// Falle aufstellen
		zwischen_sum = 0.00;

		for (i = 1; i < window.document.forms.length; i++) {
			if (parseInt(window.document.forms[i].artikel_stk.value) > 0 &&
				parseInt(window.document.forms[i].artikel_sort_nr.value) == s) {
				
				found = true;
				window.document.kassierfrm.print_string.value += window.document.forms[i].artikel_stk.value;
				if (parseInt(window.document.forms[i].artikel_stk.value) < 10)
					window.document.kassierfrm.print_string.value += "    ";
				else
					window.document.kassierfrm.print_string.value += "   ";
				window.document.kassierfrm.print_string.value += window.document.forms[i].artikel_name.value;
				window.document.kassierfrm.print_string.value += "\n";

				zwischen_sum += parseFloat(window.document.forms[i].artikel_ges_preis.value);
			}
		}

		if (found == true) {
			window.document.kassierfrm.print_string.value += "\n                         \x1bEEUR  ";
			window.document.kassierfrm.print_string.value += zwischen_sum.toFixed(2) + "\x1bF\n";

			zettel_anz++;
		}
	}

	// Wenn nur 1 Einzelzettel --> keine Gesamtsumme auf eigenem Zettel
	if (zettel_anz > 1) {
		window.document.kassierfrm.print_string.value += "\n\n\n\n\n\n\n\x1bd1";
		window.document.kassierfrm.print_string.value += "\n\nGesamt:                 \x1bEEUR  ";
		window.document.kassierfrm.print_string.value += window.document.kassierfrm.erg.value + "\x1bF\n";
	}

	window.document.kassierfrm.print_string.value += "\n\n\n\n\n\n\n\x1bd0";	// Abschneidekommando
}
*/