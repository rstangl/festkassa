<?php
/**
 * FestKassa v. 1.3.0
 * 
 * Kassa- und Abrechnungssystem für das jährliche
 * Sommerfest der FF-Prebuch
 *
 * @author Richard Stangl
 * @copyright 2004-2015 by Freiwillige Feuerwehr Prebuch
 * 
 * @package ff.festkassa.printer
 */


/**
 * Star SCP 700 Bon-Drucker.
 * @package ff.festkassa.printer
 */
class Printer_StarSCP700 implements Printer {
	
	public function __construct() {}
	
	public function setPort($port) {
		$this->port_ = $port;
	}
	
	public function setFontSize($fontSize) {
		$this->buffer_ .= "\x1b\x14";			// Höhe zurücksetzen
		$this->buffer_ .= "\x1b:";				// 16 Pt
		
		if ($fontSize == Printer::BIG_FONT) {
			$this->buffer_ .= "\x1b\x0e";		// Höhe verdoppeln
			$this->fontSize_ = $fontSize;
		} else {
			$this->fontSize_ = Printer::NORMAL_FONT;
		}
	}
	
	public function newPage() {
		$this->buffer_ .= "\n\n\n\n\n\n\n\x1bd1";	// Perforation
	}
	
	public function cut() {
		$this->buffer_ .= "\n\n\n\n\n\n\n\x1bd0";
		
		$fp = fopen($this->port_, 'w');
		if ($fp == false) {
			throw new PrinterException("Port »{$this->port_}« lässt sich nicht öffnen");
		}
		
		if (fwrite($fp, $this->buffer_) == false) {
			throw new PrinterException("Fehler beim Senden der Daten an den Drucker");
		}
		
		fclose($fp);
		$this->buffer_ = '';
	}
	
	public function printHeadLine($headline) {
		$this->buffer_ .= "$headline\n\n";
	}
	
	public function printOrderLine($amount, $artName) {
		$this->buffer_ .= sprintf("%-5d %s\n", $amount, $artName);
	}
	
	public function printGrpSum($grpSum) {
		$sum = sprintf("EUR  %01.2f", $grpSum);
		$this->buffer_ .= "\n                         \x1bE{$sum}\x1bF\n";
	}
	
	public function printSum($sum) {
		$psum = sprintf("EUR  %01.2f", $sum);
		$this->buffer_ .= "\n\nGesamt:                 \x1bE{$psum}\x1bF\n";
	}
	
	
	/**
	 * @var string
	 */
	protected $port_		= '';
	
	/**
	 * @var int
	 */
	protected $fontSize_	= Printer::NORMAL_FONT;
	
	/**
	 * @var string
	 */
	protected $buffer_		= '';
	
}

?>