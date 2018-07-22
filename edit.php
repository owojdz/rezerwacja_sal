<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Educja rezerwacji</title>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<script type="text/javascript" src="js/ajax.js"></script>

<SCRIPT LANGUAGE="JavaScript">
function checkform ( form )
{
    if (form.timestart.value >= form.timefinish.value) {
        alert( "Czas końca nie jest późniejszy niż czas początku spotkania"+form.timestart.value+"  "+form.timefinish.value );
        return false ;
    }

    
    return true;
}
function enable() {
    document.getElementById("timefinish").disabled=false;
}
</script>
<?php 
function formGenerate($action,$data,$start,$stop,$sala,$id){
    $form="<form  name'formularz' action='$action' method='post' onsubmit=\"return checkform(this);\">";
    $form.="<fieldset><legend>Dodawanie rezerwacji</legend>";
//    $form.=$warning;
    $form.="<p id='warning' type='hidden'></p>";
    $form.="Data: <input type='date' name='data' id='data' min='2010-01-01' max='2020-12-31' value='$data' required /><br />";
    $form.="Początek: <input type='time' name='timestart' id='timestart' min='07:00' max='16:00' step='1800' value='$start' /> <br />";
    $form.="Koniec: <input type='time' name='timefinish' id='timefinish' min='07:00' max='16:00' step='1800' value='$stop' /> <br />";
    $form.="<input type='button' value='Pokaż dostępne sale' name='dostepne' onClick=salaCheckEditJS(1,1,0) /><br/>";
    $form.="Sala: <select name='sale' id='sale'></select><br />";
    $form.="wynik: <input type='text' name='wynik' id='wynik'/> <br />";
    $form.="<input type='hidden' value='$id' name='id' />";
    $form.="<input type='hidden' value='$sala' name='sala' id='sala'/>";
    
    $form.="<input type='submit' value='Rezerwuj' name='submit' />";
    $form.="</fieldset></form>";
    return $form;
}
?>
</head>
<body>
<?php
require_once 'include/obslugaSesji.php';
require_once 'include/settings.php';

$LOKALIZACJA="Edycja rezerwacji";
$TRESC='';

if(!isset($_SESSION['username'])){
    header("Location: login.php");
}
if(isset($_GET['id'])){
    try{
        $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	//Konfiguracja zgłaszania błędów poprzez wyjątki
        
        $stmt = $pdo->query("SET CHARSET utf8");
        $stmt = $pdo->query("SET NAMES `utf8` COLLATE `utf8_general_ci`");
        
        $stmt = $pdo->prepare('   SELECT data, czas_start, czas_stop, nazwa_sali FROM rezerwacje WHERE id_rezerwacji=:id;');
        $stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        $TRESC=formGenerate(basename(__FILE__),$row['data'],$row['czas_start'],$row['czas_stop'],$row['nazwa_sali'],$_GET['id']);
        $stmt->closeCursor();
    } catch (PDOException $e){
        echo "Nie można się połączyć do bazy".$e->getMessage();
        die();
    }
}
if (isset($_POST['submit'])) {
 //   if (($_POST['imie'] != "") && ($_POST['nazwisko'] != "")) {
        try {
            $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	//Konfiguracja zgłaszania błędów poprzez wyjątki
            
            $stmt = $pdo->prepare('  UPDATE
                                        rezerwacje
                                    SET 
                                        data=:data, czas_start=:start, czas_stop=:stop, nazwa_sali=:sala
                                    WHERE
                                        id_rezerwacji=:id;');
            $stmt->bindValue(':data', $_POST['data'], PDO::PARAM_STR);
            $stmt->bindValue(':start', $_POST['timestart'], PDO::PARAM_STR);
            $stmt->bindValue(':stop', $_POST['timefinish'], PDO::PARAM_STR);
            $stmt->bindValue(':sala', $_POST['sale'], PDO::PARAM_STR);
            $stmt->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            $TRESC.="Poprawiono dane rezerwacji";
            $TRESC.=formGenerate(basename(__FILE__),$_POST['data'],$_POST['timestart'],$_POST['timefinish'],$_POST['sale'],$_POST['id']);
            //            header('Location: aktorzy.php');
        } catch (PDOException $e){
            echo 'Błąd wprowadzania do bazy: ' . $e->getMessage();
        }
//    }
}

//Przetworzenie szablonów
require_once 'szablony/witryna.php';

?>
</body>
</html>