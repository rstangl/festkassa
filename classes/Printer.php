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
 * Interface für alle Druckertreiber-Klassen.
 * @package ff.festkassa
 */
interface Printer {
	
	const NORMAL_FONT	= 0;
	const BIG_FONT		= 1;
	
	
	/**
	 * Default-Konstruktor ohne Parameter.
	 * 
	 * @exception	PrinterException
	 */
	public function __construct();
	
	/**
	 * Konfiguriert den Druckerport.
	 * 
	 * @param	string	$port	Druckerschnittstelle
	 */
	public function setPort($port);
	
	/**
	 * Konfiguriert die Schriftgröße.
	 * 
	 * @param	int		$fontSize	Schriftgröße
	 */
	public function setFontSize($fontSize);
	
	/**
	 * Neuer Abschnitt (Perforation).
	 * 
	 * @exception	PrinterException
	 */
	public function newPage();
	
	/**
	 * Ende des Ausdrucks (Abschneiden).
	 * 
	 * @exception	PrinterException
	 */
	public function cut();
	
	/**
	 * Druckt eine Überschrift.
	 * 
	 * @param	string	$headline	Überschrift-Text
	 * @exception	PrinterException
	 */
	public function printHeadLine($headline);
	
	/**
	 * Druckt eine Bestellungs-Zeile.
	 * 
	 * @param	int		$amount		Menge
	 * @param	string	$artName	Artikel-Bezeichnung
	 * 
	 * @exception	PrinterException
	 */
	public function printOrderLine($amount, $artName);
	
	/**
	 * Druckt eine Zwischensumme.
	 * 
	 * @param	float	$grpSum		Zwischensumme
	 * @exception	PrinterException
	 */
	public function printGrpSum($grpSum);
	
	/**
	 * Druckt die Gesamtsumme.
	 * 
	 * @param	float	$sum	Gesamtsumme
	 * @exception	PrinterException
	 */
	public function printSum($sum);
	
}

?>