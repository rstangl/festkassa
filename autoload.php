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

spl_autoload_register(function ($class) {
	$class = str_replace('_', '/', $class);
	$fileName = "./classes/{$class}.php";
	
	if (! file_exists($fileName)) {
		throw new IOException("Klasse »{$fileName}« konnte nicht geladen werden");
	}
	
	include ($fileName);
});
