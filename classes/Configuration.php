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
 * @package ff.festkassa
 */

define('CONFIG_FILE', './festkassa.ini');


/**
 * Hält die Konfiguration für das Kassasystem.
 * @package ff.festkassa
 */
class Configuration {
	
	/**
	 * Singleton-Methode.
	 * 
	 * @return Configuration
	 */
	public static function instance() {
		if (self::$instance_ == null) {
			self::$instance_ = new Configuration();
		}
		
		return self::$instance_;
	}
	
	/**#@+
	 * Getter-Methoden.
	 * 
	 * @return mixed
	 */
	public function kassaName()			{	return $this->kassaName_;			}
	public function printerDriver()		{	return $this->printerDriver_;		}
	public function printerPort()		{	return $this->printerPort_;			}
	public function bigPrintFont()		{	return $this->bigPrintFont_;		}
	public function linesPerCol()		{	return $this->linesPerCol_;			}
	public function backgroundImage()	{	return $this->backgroundImage_;		}
	public function backgroundColor()	{	return $this->backgroundColor_;		}
	public function addButtonWidth()	{	return $this->addButtonWidth_;		}
	public function subtButtonWidth()	{	return $this->subtButtonWidth_;		}
	public function buttonHeight()		{	return $this->buttonHeight_;		}
	public function buttonFontSize()	{	return $this->buttonFontSize_;		}
	public function lineeditFontSize()	{	return $this->lineeditFontSize_;	}
	public function priceList()			{	return $this->priceList_;			}
	public function accountingFile()	{	return $this->accountingFile_;		}
	/**#@- */
	
	
	/**
	 * Singleton-Konstruktor.
	 * 
	 * @exception IOException
	 */
	private function __construct() {
		if (! file_exists(CONFIG_FILE)) {
			throw new IOException("Konfigurationsdatei »". CONFIG_FILE ."« nicht gefunden");
		}
		
		if (! is_readable(CONFIG_FILE)) {
			throw new IOException("Konfigurationsdatei »". CONFIG_FILE ."« nicht lesbar");
		}
		
		// Konfigurationsdatei laden und parsen
		$cfg = parse_ini_file(CONFIG_FILE, false);
		
		if ($cfg == false || ! is_array($cfg)) {
			throw new IOException("Syntaxfehler in Konfigurationsdatei »". CONFIG_FILE ."«");
		}
		
		// Speichern der Config-Werte
		$this->kassaName_			= $this->getVal_($cfg, 'kassa_name',		'FestKassa');
		$this->printerDriver_		= $this->getVal_($cfg, 'printer_driver',	'HtmlExporter');
		$this->printerPort_			= $this->getVal_($cfg, 'printer_port',		'/dev/null');
		$this->bigPrintFont_		= $this->getVal_($cfg, 'big_print_font',	false);
		$this->linesPerCol_			= $this->getVal_($cfg, 'lines_per_col',		10);
		$this->backgroundImage_		= $this->getVal_($cfg, 'background_image',	'');
		$this->backgroundColor_		= $this->getVal_($cfg, 'background_color',	'#ffffee');
		$this->addButtonWidth_		= $this->getVal_($cfg, 'add_button_width',	230);
		$this->subtButtonWidth_		= $this->getVal_($cfg, 'subt_button_width',	32);
		$this->buttonHeight_		= $this->getVal_($cfg, 'button_height',		33);
		$this->buttonFontSize_		= $this->getVal_($cfg, 'button_font_size',	14);
		$this->lineeditFontSize_	= $this->getVal_($cfg, 'lineedit_font_size',17);
		$this->priceList_			= $this->getVal_($cfg, 'price_list',		'');
		$this->accountingFile_		= $this->getVal_($cfg, 'accounting_file',	'');
	}
	
	/**
	 * Gibt den Wert mit dem angegebenen Index aus dem Array oder einen Default-Wert
	 * zurück.
	 * 
	 * @param	mixed[string]	$cfg		Konfigurations-Array
	 * @param	string			$index		Index im Konfigurations-Array
	 * @param	mixed			$default	Standardwert
	 * 
	 * @return	mixed
	 */
	private function getVal_(array $cfg, $index, $default) {
		if (isset($cfg[$index])) {
			return $cfg[$index];
		}
		
		return $default;
	}
	
	/**
	 * Singleton-Clone-Methode.
	 * 
	 * @exception Exception
	 */
	private function __clone() {
		throw new Exception("Klonen der »Configuration« nicht erlaubt");
	}
	
	
	/**
	 * @staticvar Configuration
	 */
	private static $instance_ = null;
	
	/**
	 * @var string
	 */
	private $kassaName_ = '';
	
	/**
	 * @var string
	 */
	private $printerDriver_ = '';
	
	/**
	 * @var string
	 */
	private $printerPort_ = '';
	
	/**
	 * @var bool
	 */
	private $bigPrintFont_ = false;
	
	/**
	 * @var int
	 */
	private $linesPerCol_ = 0;
	
	/**
	 * @var string
	 */
	private $backgroundImage_ = '';
	
	/**
	 * @var string
	 */
	private $backgroundColor_ = '';
	
	/**
	 * @var int
	 */
	private $addButtonWidth_ = 0;
	
	/**
	 * @var int
	 */
	private $subtButtonWidth_ = 0;
	
	/**
	 * @var int
	 */
	private $buttonHeight_ = 0;
	
	/**
	 * @var int
	 */
	private $buttonFontSize_ = 0;
	
	/**
	 * @var int
	 */
	private $lineeditFontSize_ = 0;
	
	/**
	 * @var string
	 */
	private $priceList_ = '';
	
	/**
	 * @var string
	 */
	private $accountingFile_ = '';
	
}

?>
