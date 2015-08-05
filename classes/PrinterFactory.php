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
 * Fabrik für die Erzeugung von Drucker-Objekten.
 * @package ff.festkassa
 */
class PrinterFactory {
	
	/**
	 * Erzeugt einen Drucker auf Basis des angegebenen Treiberklassen-Namens.
	 * 
	 * @param	string	$printerDriver	Name des Druckertreibers
	 * @return	Printer
	 * 
	 * @exception	UnknownPrinterException
	 * @exception	PrinterException
	 */
	public static function create($printerDriver) {
		$className = "Printer_$printerDriver";
		
		if (! class_exists($className)) {
			throw new UnknownPrinterException($className);
		}
		
		$printer = new $className ();
		
		if (! $printer instanceof Printer) {
			throw new UnknownPrinterException($className);
		}
		
		return $printer;
	}
	
}

?>