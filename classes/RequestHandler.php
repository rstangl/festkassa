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
 * Behandelt HTTP-Requests von der FestKassa Web GUI.
 * @package ff.festkassa
 */
class RequestHandler {
	
	/**
	 * Eintritts-Methode.
	 */
	public static function main() {
		// FestKassa-Konfiguration laden
		$config = Configuration::instance();
		
		// Presiliste laden
		self::$priceList_ = new PriceList($config->priceList());
		
		// Wurde auf "Nächste Bestellung" geklickt?
		if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'confirm' &&
			isset($_REQUEST['erg']) && $_REQUEST['erg'] != 0) {
			
			$order = null;
			
			// Bestellung aus den Formulardaten generieren
			try {
				$order = self::buildOrderFromFormData_();
			} catch (Exception $e) {
				echo "Fehler beim Auswerten der Eingaben: ". $e->getMessage() ."<br/>\n";
				return;
			}
			
			if (bccomp(''. $order->sumPrice(), ''. $_REQUEST['erg']) != 0) {
				echo "Preisliste hat sich geändert. Bitte aktualisieren.<br/>\n";
				return;
			}
			
			try {
				// Abrechnung schreiben
				self::writeAccountingForOrder_($order);
			} catch (IOException $ioe) {
				echo "Fehler beim Schreiben der Abrechnung: ". $ioe->getMessage() ."<br/>\n";
			}
	
			// Ausdrucken
			try {
				self::printOrder_($order);
				
			} catch (UnknownPrinterException $ue) {
				echo "Fehler beim Drucken: Unbekannter Drucker: »"
					. $config->printerDriver() ."«<br/>\n";
			} catch (PrinterException $pe) {
				echo "Fehler beim Drucken: ". $pe->getMessage() ."<br/>\n";
			}
		}
		
		// FestKassa Web GUI an den Browser senden
		$lastSumPrice = isset($_REQUEST['erg']) ? $_REQUEST['erg'] : 0.0;
		WebInterface::render(self::$priceList_, $lastSumPrice);
	}
	
	/**
	 * Generiert eine Bestellung aus den Formulardaten der FestKassa Web GUI.
	 * 
	 * @return Order
	 * 
	 * @exception	Exception
	 * @exception	OutOfBoundsException
	 */
	protected static function buildOrderFromFormData_() {
		if (! isset($_REQUEST['artikel_stk']) || ! is_array($_REQUEST['artikel_stk']) ||
			! isset($_REQUEST['extra_artikel_stk']) || ! isset($_REQUEST['erg'])
		) {
			throw new Exception("Ungültiger Request");
		}
		
		$order = new Order();
		$i = 0;
		
		foreach ($_REQUEST['artikel_stk'] as $stk) {
			if ($stk != 0) {
				$article = self::$priceList_->getArticle($i);
				$order->addOrderItem(new OrderItem($article, $stk));
			}
			$i++;
		}
		
		if (isset($_REQUEST['extra_artikel_stk']) &&
			$_REQUEST['extra_artikel_stk'] != 0 &&
			isset($_REQUEST['extra_artikel_name']) &&
			trim($_REQUEST['extra_artikel_name']) != '' &&
			isset($_REQUEST['extra_artikel_stk_preis']) &&
			$_REQUEST['extra_artikel_stk_preis'] > 0.00 &&
			isset($_REQUEST['extra_artikel_sort_nr'])
		) {
			$article = new Article(
					$i,
					$_REQUEST['extra_artikel_name'],
					$_REQUEST['extra_artikel_stk_preis'],
					$_REQUEST['extra_artikel_sort_nr']
			);
			
			$order->addOrderItem(new OrderItem($article, $_REQUEST['extra_artikel_stk']));
		}
		
		return $order;
	}
	
	/**
	 * Generiert die Abrechnungsdaten aus einer Bestellung und protokolliert diese.
	 * 
	 * @param	Order	$order		Bestellung
	 * @exception	IOException
	 */
	protected static function writeAccountingForOrder_(Order $order) {
		$config = Configuration::instance();
		
		if ($config->accountingFile() == '') {
			return;
		}
		
		$fp = @fopen($config->accountingFile(), "a");
		if (!$fp) {
			echo "Die Abrechnungsdatei '". $config->accountingFile() ."' konnte nicht ge&ouml;ffnet werden.<br>";
			echo "Das Abrechnungssystem wurde automatisch deaktiviert.";
			return;
		}
		
		date_default_timezone_set($config->getTimeZone());
		$date = date('Y-m-d H:i:s');
		
		foreach ( $order as $orderItem ) {
			@fprintf($fp, "%s|%d|%s|%.2f|%.2f\n",
					$date,
					$orderItem->amount(),
					$orderItem->article()->artName(),
					$orderItem->article()->artStkPrice(),
					$orderItem->sumPrice());
		}
		
		@fclose($fp);
	}
	
	/**
	 * Druckt eine Bestellung aus.
	 * 
	 * @param	Order	$order		Bestellung
	 * 
	 * @exception	UnknownPrinterException
	 * @exception	PrinterException
	 */
	protected static function printOrder_(Order $order) {
		// Drucker holen und konfigurieren
		$config = Configuration::instance();
		$printer = PrinterFactory::create($config->printerDriver());
		
		self::printOrderOrWaiterReceipt_($order, $printer, false);
		
		if ($config->waiterReceipt() == true) {
			self::printOrderOrWaiterReceipt_($order, $printer, true);
		}
	}
	
	protected static function printOrderOrWaiterReceipt_(Order $order, Printer $printer, $waiterReceipt) {
		// Drucker holen und konfigurieren
		$config = Configuration::instance();
		
		$printer->setPort($config->printerPort());
		if ($config->bigPrintFont() == true && $waiterReceipt == false) {
			$printer->setFontSize(Printer::BIG_FONT);
		} else {
			$printer->setFontSize(Printer::NORMAL_FONT);
		}
		
		/*
		 * Bestellung ausdrucken
		 */
		
		if ($waiterReceipt == false) {
			$printer->printHeadLine($config->kassaName());
		}
		
		// Höchste Artikel-Sortierungsnummer suchen
		$maxArtGrpNr = 0;
		foreach ($order as $orderItem) {
			if ($orderItem->article()->artGrpNr() > $maxArtGrpNr) {
				$maxArtGrpNr = $orderItem->article()->artGrpNr();
			}
		}
		
		// Über alle Sortierungsnummern iterieren
		$found = false;
		$grpSum = 0.00;
		$pageCount = 0;
		for ($artGrpNr = 0; $artGrpNr <= $maxArtGrpNr; $artGrpNr++) {
			if ($found == true) {
				// Bestellungspositionen mit der vorigen SortNr gefunden --> perforieren
				if ($waiterReceipt == false) {
					$printer->newPage();
					$printer->printHeadLine($config->kassaName());
				}
			}
			
			$found = false;
			$grpSum = 0.00;
			
			// Über alle Bestellungen mit der aktuellen SortNr iterieren
			foreach ($order as $orderItem) {
				if ($orderItem->article()->artGrpNr() == $artGrpNr) {
					$found = true;
					
					if ($waiterReceipt == false) {
						$printer->printOrderLine(
								$orderItem->amount(),
								$orderItem->article()->artName()
						);
					} else {
						$printer->printOrderLineWithSum(
								$orderItem->amount(),
								$orderItem->article()->artName(),
								$orderItem->sumPrice());
					}
					
					$grpSum += $orderItem->sumPrice();
				}
			}
			
			if ($found == true) {
				$printer->printGrpSum($grpSum);
				$pageCount++;
			}
		}
		
		// Nur wenn mehr als 1 Seite, Gesamtsumme auf eigene Seite drucken
		if ($pageCount > 1) {
			if ($waiterReceipt == false) {
				$printer->newPage();
			}
			$printer->printSum($order->sumPrice());
		}
		
		$printer->cut();
	}
	
	
	/**
	 * @staticvar PriceList
	 */
	protected static $priceList_	= null;
}

?>
