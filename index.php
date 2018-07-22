<?php
require_once 'include/obslugaSesji.php';
require_once 'include/settings.php';

$LOKALIZACJA="Witaj";

//Generacja treści
$tresc=<<<TRESC
	To jest strona powitalna
TRESC;
$work="";
for($i=1;$i<50;$i++) $work.=$tresc.PHP_EOL."<br/>";
$TRESC=$work;

//Przetworzenie szablonów
require_once 'szablony/witryna.php';
?>
