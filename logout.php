<?php
require_once 'include/obslugaSesji.php';
require_once 'include/settings.php';

$LOKALIZACJA="Logout";
unset($_SESSION['username']);
$TRESC="Zostałeś wylogowany";

//Przetworzenie szablonów
require_once 'szablony/witryna.php';
?>