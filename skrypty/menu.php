<?php
/**
 * Przykładowa funkcja generująca pozycje menu 
 * @param menu array <p>
 * Tablica zawierajaca nazwe strony jako klucz oraz wyswietlany tekst jako wartość;
 * </p>
 */
function menu1($menu)
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

function getname($DBEngine,$DBServer,$DBUser,$DBPass,$DBName){
    if ($_SESSION['username']!='admin'){
        try{
            $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	//Konfiguracja zgłaszania błędów poprzez wyjątki
        } catch (PDOException $e){
            echo "Nie można się połączyć do bazy".$e->getMessage();
            die();
        }
        try {
            $user_id=$_SESSION['username'];
            $stmt = $pdo->query("SET CHARSET utf8");
            $stmt = $pdo->query("SET NAMES `utf8` COLLATE `utf8_general_ci`");
            $stmt = $pdo->query('SELECT imie, nazwisko FROM pracownicy WHERE username="'.$user_id.'";');
                //':username
                //            $stmt->bindValue(':username', $_SESSION['username'], PDO::PARAM_STR);
            $stmt->execute();
            foreach ($stmt as $row) {
                $imie=$row['imie'];
                $nazwisko=$row['nazwisko'];
            }
            return $imie." ".$nazwisko;
        } catch (PDOException $e){
            echo "Nie można się połączyć do bazy".$e->getMessage();
            die();
        }
    } else {
        return $_SESSION['username'];
    }
    
}


function menu($menu,$DBEngine,$DBServer,$DBUser,$DBPass,$DBName)
{
	if (!is_array($menu)) return FALSE;
	$tresc="";
	$tresc.='<nav class="navbar navbar-expand-lg navbar-dark bg-primary"><a class="navbar-brand" href=index.php>Nawigacja</a>';
	$tresc.='<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">';
	$tresc.='<span class="navbar-toggler-icon"></span></button>';
	$tresc.='<div class="collapse navbar-collapse" id="navbarColor01">';
	$tresc.='<ul class="navbar-nav mr-auto">';
	
    foreach ($menu['Public'] as $adres => $napis) {
        if (is_file($adres))
            $tresc .='<li class="nav-item"><a class="nav-link" href='.$adres.'>'.$napis.'</a></li>';
    }
    if (isset($_SESSION['username'])) {
        $tresc .= '<li class="nav-item"><a class="nav-link" href=logout.php>Wyloguj</a></li>';
    }else{
        $tresc .= '<li class="nav-item"><a class="nav-link" href=login.php>Zaloguj</a></li>';
    }
    $tresc.='</ul>';
    if(isset($_SESSION['username'])){
        $tresc.='<form class="form-inline my-2 my-lg-0">';
        $tresc.='<p class="mr-sm-3">'.getname($DBEngine,$DBServer,$DBUser,$DBPass,$DBName).'</p>';
        $tresc.='</form>';
    }
    
    $tresc.='</div></nav>';
    
	return $tresc;
}


?>