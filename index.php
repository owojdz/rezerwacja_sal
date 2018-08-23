<?php
require_once 'include/obslugaSesji.php';
require_once 'include/settings.php';

//Generacja treści strony startowej
$tresc=<<<TRESC
	To jest strona powitalna projektu nr 8 - rezerwacja sal w firmie <br/><br/>

    Skład osobowy grupy:<br/><b>
        Katarzyna Kowalczyk<br/>
        Olaf Wojdziak<br/>
        Marcin Kowalczyk<br/></b>
TRESC;
$TRESC1=$tresc;
$TRESC="";

//Przetworzenie szablonów
require_once 'szablony/witryna.php';
?>
