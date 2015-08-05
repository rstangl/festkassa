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
 * Repräsentiert eine Position einer Bestellung.
 * @package ff.festkassa
 */
class OrderItem {
	
	/**
	 * Konstruktor.
	 * 
	 * @param	Article	$article	Artikel
	 * @param	int		$amount		Bestellte Menge
	 */
	public function __construct(Article $article, $amount) {
		$this->article_ = $article;
		$this->amount_  = $amount;
	}
	
	/**#@+
	 * Getter-Methoden.
	 * 
	 * @return mixed
	 */
	public function article()	{	return $this->article_;		}
	public function amount()	{	return $this->amount_;		}
	/**#@- */
	
	/**
	 * Berechnet die Zeilensumme der Bestellungs-Position.
	 * 
	 * @return float
	 */
	public function sumPrice() {
		return $this->article()->artStkPrice() * $this->amount();
	}
	
	
	/**
	 * @var Article
	 */
	protected $article_	= null;
	
	/**
	 * @var int
	 */
	protected $amount_	= 0;
	
}

?>