<?php
require_once 'include/obslugaSesji.php';
require_once 'include/settings.php';

$LOKALIZACJA="Witaj";

//Generacja treści
$tresc=<<<TRESC
	To jest strona powitalna projektu nr 8 - rezerwacja sal w firmie
TRESC;
//$work="";
//for($i=1;$i<50;$i++) $work.=$tresc.PHP_EOL."<br/>";
//$TRESC=$work;
$TRESC=$tresc;
$TRESC1="";

//Przetworzenie szablonów
require_once 'szablony/witryna.php';
?>
