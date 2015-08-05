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
 * Repräsentiert eine Bestellung.
 * @package ff.festkassa
 */
class Order implements Iterator {
	
	/**
	 * Default-Konstruktor.
	 */
	public function __construct() {}
	
	/**
	 * Fügt eine neue Bestellungs-Position hinzu.
	 * 
	 * @param	OrderItem	$orderItem	Bestellungs-Position
	 */
	public function addOrderItem(OrderItem $orderItem) {
		$this->orderItems_[] = $orderItem;
	}
	
	/**
	 * Berechnet die Gesamtsumme der Bestellung.
	 * 
	 * @return	float
	 */
	public function sumPrice() {
		$sum = 0.0;
		
		foreach ($this->orderItems_ as $item) {
			$sum += $item->sumPrice();
		}
		
		return $sum;
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
		if (! isset($this->orderItems_[$this->pos_])) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Gibt die Bestellungs-Position an der aktuellen Position im Iterator zurück.
	 * 
	 * @return	OrderItem
	 * @exception	OutOfBoundsException
	 */
	public function current() {
		if (! isset($this->orderItems_[$this->pos_])) {
			throw new OutOfBoundsException();
		}
		
		return $this->orderItems_[$this->pos_];
	}
	
	
	/**
	 * @var OrderItem[]
	 */
	protected $orderItems_	= array();
	
	/**
	 * @var int
	 */
	protected $pos_			= 0;
	
}

?>