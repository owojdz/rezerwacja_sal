<?php
$MENU=array(
    "Public"=>array(
		"index.php"=>"Witamy",
//		"test.php"=>"Nasza oferta",
//        "rezerwacje.php"=>"Rezerwacja",
        "rezerwacja.php"=>"Rezerwacja"),
    "Private"=>array( 
        "aktorzy.php"=>"Aktorzy"));

$NAZWA_STRONY = "Rezerwacja sal";

//dane logowania do bazy
$DBEngine = 'mysql';
$DBServer = 'localhost:3306';
$DBUser   = 'root';
$DBPass   = '';
$DBName   = 'firma';



$SALT = "moj klucz";
?>