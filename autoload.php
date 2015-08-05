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

function __autoload($class) {
	$class = str_replace('_', '/', $class);
	$fileName = "./classes/{$class}.php";
	
	if (! file_exists($fileName)) {
		throw new IOException("Klasse »{$fileName}« konnte nicht geladen werden");
	}
	
	require ($fileName);
}

?>