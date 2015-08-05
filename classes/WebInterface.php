<?php
/**
 * FestKassa v. 1.2.0
 * 
 * Kassa- und Abrechnungssystem für das jährliche
 * Sommerfest der FF-Prebuch
 *
 * @author Richard Stangl
 * @copyright 2004-2007 by Freiwillige Feuerwehr Prebuch
 * 
 * @package ff.festkassa
 */


/**
 * Die FestKassa Web GUI.
 * @package ff.festkassa
 */
class WebInterface {
	
	/**
	 * Generiert den HTML-Code für die Web GUI und sendet ihn an den Browser.
	 * 
	 * @param	PriceList	$priceList		Preisliste
	 * @param	float		$lastSumPrice	Summer der letzten Bestellung
	 */
	public static function render(PriceList $priceList, $lastSumPrice) {
		// Konfiguration holen
		$config = Configuration::instance();
		
		// Template einbinden
		$tplFile = 'webgui/festkassa_template.php';
		include ($tplFile);
	}
	
}

?>