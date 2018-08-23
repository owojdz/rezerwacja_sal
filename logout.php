<?php
require_once 'include/obslugaSesji.php';
require_once 'include/settings.php';

//zamkniecie sesji dla użytkownika
unset($_SESSION['username']);
$TRESC="Zostałeś wylogowany";
$TRESC1="";

//Przetworzenie szablonów
require_once 'szablony/witryna.php';
?>