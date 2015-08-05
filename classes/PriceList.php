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


/**
 * Repräsentiert eine Preisliste (Artikelliste).
 * @package ff.festkassa
 */
class PriceList implements Iterator {
	
	/**
	 * Liest alle Artikel aus der Preislistendatei.
	 * 
	 * @param	string	$csvFile	Preislistendatei
	 * @exception	IOException
	 */
	public function __construct($csvFile) {
		if (! file_exists($csvFile) || ! is_readable($csvFile)) {
			throw new IOException("Preislistendatei »{$csvFile}« konnte nicht gelesen werden");
		}
		
		// Alle Artikel auslesen
		$fp = fopen($csvFile, 'r');
		$idx = 0;
		$l = 1;
		
		while (false !== $line = fgetcsv($fp, 100+1, '|')) {
			if (count($line) != 3) {
				echo "Warnung: Lesefehler in Preislistendatei »{$csvFile}«, Zeile $l<br/>\n";
				$l++;
				continue;
			}
			
			$this->articles_[] = new Article(
					$idx,		// Artikelnummer
					$line[0],	// Bezeichnung
					$line[1],	// Stk Preis
					$line[2]	// Sortier-Gruppennummer
			);
			
			$idx++;
			$l++;
		}
		
		fclose($fp);
	}
	
	/**
	 * Liest den Artikel mit der angegebenen Artikelnummer aus.
	 * 
	 * @param	int		$artNr		Artikelnummer
	 * @return	Article
	 * @exception	OutOfBoundsException
	 */
	public function getArticle($artNr) {
		if (! isset($this->articles_[$artNr])) {
			throw new OutOfBoundsException();
		}
		
		return $this->articles_[$artNr];
	}
	
	/**
	 * Setzt den Iterator zurück.
	 */
	public function rewind() {
		$this->pos_ = 0;
	}
	
	/**
	 * Gibt die aktuelle Position des Iterators zurück.
	 * 
	 * @return int
	 */
	public function key() {
		return $this->pos_;
	}
	
	/**
	 * Setzt den Iterator weiter.
	 */
	public function next() {
		$this->pos_++;
	}
	
	/**
	 * Prüft, ob die aktuelle Position im Iterator gültig ist.
	 * 
	 * @return bool
	 */
	public function valid() {
		if (! isset($this->articles_[$this->pos_])) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Gibt den Artikel an der aktuellen Position im Iterator zurück.
	 * 
	 * @return	Article
	 * @exception	OutOfBoundsException
	 */
	public function current() {
		return $this->getArticle($this->pos_);
	}
	
	
	/**
	 * @var Article[]
	 */
	protected $articles_	= array();
	
	/**
	 * @var int
	 */
	protected $pos_			= 0;
	
}

?>