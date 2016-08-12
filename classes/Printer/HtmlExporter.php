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
 * Pseudo-Drucker für den HTML-Export.
 * @package ff.festkassa.printer
 */
class Printer_HtmlExporter implements Printer {
	
	public function __construct() {
		$this->buffer_ .= "<html>\n";
		$this->buffer_ .= "<head>\n";
		$this->buffer_ .= "<title>FestKassa HTML Printer</title>\n";
		$this->buffer_ .= "</head>\n\n";
		$this->buffer_ .= "<body>\n";
		$this->buffer_ .= "<div style=\"width: 400px; border: 1px solid black;\">\n";
	}
	
	public function setPort($port) {
		$this->port_ = $port;
	}
	
	public function setFontSize($fontSize) {
		if ($fontSize == Printer::BIG_FONT) {
			$this->fontSize_ = $fontSize;
		} else {
			$this->fontSize_ = Printer::NORMAL_FONT;
		}
	}
	
	public function newPage() {
		$this->buffer_ .= "\n<hr/>\n\n";
	}
	
	public function cut() {
		$this->buffer_ .= "\n<br/><br/>\n";
		$this->buffer_ .= "<span style=\"color: red;\">END OF PRINT</span>\n";
		$this->buffer_ .= "</div>\n</body>\n</html>";
		
		$fp = fopen($this->port_, 'w');
		if ($fp == false) {
			throw new PrinterException("Datei »{$this->port_}« lässt sich nicht öffnen");
		}
		
		if (fwrite($fp, $this->buffer_) == false) {
			throw new PrinterException("Fehler beim Schreiben in HTML-Datei");
		}
		
		fclose($fp);
	}
	
	public function printHeadLine($headline) {
		$this->buffer_ .= "<h1>$headline</h1>\n\n";
	}
	
	public function printOrderLine($amount, $artName) {
		$line = sprintf("%-5d %s", $amount, $artName);
		$this->buffer_ .= "<pre>$line</pre>\n";
	}
	
	public function printOrderLineWithSum($amount, $artName, $lineSum) {
		$line = sprintf("%-2d %-26s %6.2f\n", $amount, $artName, $lineSum);
		$this->buffer_ .= "<pre>$line</pre>\n";
	}
	
	public function printGrpSum($grpSum) {
		$sum = sprintf("EUR %01.2f", $grpSum);
		$this->buffer_ .= "<div style=\"text-align: right;\">";
		$this->buffer_ .= "Zwischensumme: <b>$sum</b>";
		$this->buffer_ .= "</div>\n";
	}
	
	public function printSum($sum) {
		$psum = sprintf("EUR %01.2f", $sum);
		$this->buffer_ .= "<div style=\"text-align: right; font-size: 16pt;\">";
		$this->buffer_ .= "Gesamtsumme: <b>$psum</b>";
		$this->buffer_ .= "</div>\n";
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