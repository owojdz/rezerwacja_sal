<?php
/**
 * Przykładowa funkcja generująca pozycje menu 
 * @param menu array <p>
 * Tablica zawierajaca nazwe strony jako klucz oraz wyswietlany tekst jako wartość;
 * </p>
 */
function menu($menu)
{
	if (!is_array($menu)) return FALSE;
	$tresc="";
	$tresc.="<dl>".PHP_EOL;
	$tresc.="<dt>Nawigacja</dt>".PHP_EOL;
    foreach ($menu['Public'] as $adres => $napis) {
        if (is_file($adres))
            $tresc .= "<dd><a href=\"$adres\">$napis</a></dd>" . PHP_EOL;
    }
    if (isset($_SESSION['username'])) {
        foreach ($menu['Private'] as $adres => $napis) {
            if (is_file($adres))
                $tresc .= "<dd><a href=\"$adres\">$napis</a></dd>" . PHP_EOL;
        }
        $tresc .= "<dd><a href=\"logout.php\">Wyloguj się</a></dd>" . PHP_EOL;
    }else{
        $tresc .= "<dd><a href=\"login.php\">Zaloguj się</a></dd>" . PHP_EOL;
    }
	$tresc.="</dl>".PHP_EOL;
	return $tresc;
}
?>