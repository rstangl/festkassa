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
 * Repräsentiert einen Artikel.
 * @package ff.festkassa
 */
class Article {
	
	/**
	 * Konstruktor.
	 * 
	 * @param	int		$artNr			Artikelnummer (wird automatisch durchnummeriert)
	 * @param	string	$artName		Bezeichnung des Artikels
	 * @param	float	$artStkPrice	Stückpreis
	 * @param	int		$artGrpNr		Gruppennummer für Sortierung/Aufteilung beim Drucken
	 */
	public function __construct($artNr, $artName, $artStkPrice, $artGrpNr) {
		$this->artNr_		= $artNr;
		$this->artName_		= $artName;
		$this->artStkPrice_	= $artStkPrice;
		$this->artGrpNr_	= $artGrpNr;
	}
	
	/**#@+
	 * Getter-Methoden.
	 * 
	 * @return mixed
	 */
	public function artNr()			{	return $this->artNr_;			}
	public function artName()		{	return $this->artName_;			}
	public function artStkPrice()	{	return $this->artStkPrice_;		}
	public function artGrpNr()		{	return $this->artGrpNr_;		}
	/**#@- */
	
	/**
	 * @var int
	 */
	protected $artNr_		= 0;
	
	/**
	 * @var string
	 */
	protected $artName_		= '';
	
	/**
	 * @var float
	 */
	protected $artStkPrice_	= 0.0;
	
	/**
	 * @var int
	 */
	protected $artGrpNr_	= 0;
	
}

?>