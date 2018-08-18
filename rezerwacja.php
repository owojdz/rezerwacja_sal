<?php 
function formGenerate($action,$warning){
    $form="<form  class='form-group' name='formularz' id='formularz' action='$action' method='post' >" ;//onSubmit='return checkform(this);'
//    $form="<form  class='form-group' name='formularz' id='formularz' action='$action' method='post' onSubmit='return checkform();'>";//onSubmit='return checkform(this);'
    $form.="<fieldset><legend>Dodawanie rezerwacji</legend>";
    $form.="<p id='warning' type='hidden'></p>";
    $form.="<label class='col-form-label' for='inputDefault'>Data:</label>";
    $form.="<input type='date' name='data' id='data' min='2010-01-01' max='2020-12-31' value='".date('Y-m-d')."' required class='form-control' placeholder='Default input'>";
    $form.="<label class='col-form-label' for='inputDefault'>Początek spotkania:</label>";
    $form.="<input type='time' name='timestart' id='timestart' min='07:00' max='16:00' step='1800' value='07:00' onfocusout='my_round_start()' onchange='add_one()' required class='form-control' placeholder='Default input'>";
    $form.="<label class='col-form-label' for='inputDefault'>Koniec spotkania:</label>";
    $form.="<input type='time' name='timefinish' id='timefinish' min='07:00' max='16:00' step='1800' value='08:00' onfocusout='my_round_stop()' required class='form-control' placeholder='Default input'><br/>";
    $form.="<div><input type='button' value='Pokaż dostępne sale' name='dostepne' onClick='salaCheckJS()' class='btn btn-secondary btn-lg btn-block'/></div>";
    $form.="<label class='col-form-label' for='inputDefault'>Dostępne sale:</label>";
    $form.="<select name='sale' id='sale' class='form-control' required></select><br />";
//    $form.="<div><input type='submit' value='Rezerwuj' name='sub' class='btn btn-primary btn-lg btn-block' /></div><br/>";
    $form.="<input type='button' value='Rezerwuj' name='submi' id='submi' onclick='checkform()' class='btn btn-primary btn-lg btn-block'><br/>";
    $form.="<input type='hidden' name='wartosc' id='wartosc'><br/>";
    $form.="</fieldset></form>";
    return $form;
}

function getActors($pdo){
    try {
        $stmt = $pdo->query("SET CHARSET utf8");
        $stmt = $pdo->query("SET NAMES `utf8` COLLATE `utf8_general_ci`");
  //      TIME_FORMAT("19:30:10", "%H %i %s")
        $stmt = $pdo->query('   SELECT rezerwacje.id_rezerwacji, pracownicy.username, rezerwacje.data, rezerwacje.czas_start, rezerwacje.czas_stop, rezerwacje.nazwa_sali, pracownicy.imie, pracownicy.nazwisko, pracownicy.username FROM rezerwacje JOIN pracownicy WHERE rezerwacje.id_pracownika=pracownicy.id_pracownika
ORDER BY data,czas_start;');
        $stmt->execute();                            
        $HTMLreturnSequence='<b>Lista rezerwacji:</b></br>';
        $HTMLreturnSequence.='<ul>';
        foreach ($stmt as $row) {
            if(($_SESSION['username']==$row['username']) or ($_SESSION['username']=='admin')) {
                $HTMLreturnSequence.="<input type='button' class='btn btn-info btn-sm' value='Edytuj' onclick='edit(".$row['id_rezerwacji'].")' />";
            } else {
                $HTMLreturnSequence.="<input type='button' class='btn btn-info btn-sm' value='Edytuj' disabled='true' onclick='edit(".$row['id_rezerwacji'].")' />";
            }
            if(($_SESSION['username']==$row['username']) or ($_SESSION['username']=='admin')) {
                $HTMLreturnSequence.="<input type='button' class='btn btn-danger btn-sm' value='Usuń' onclick='del(".$row['id_rezerwacji'].")' />";
            } else {
                $HTMLreturnSequence.="<input type='button' class='btn btn-danger btn-sm' value='Usuń' disabled='true' onclick='del(".$row['id_rezerwacji'].")' />";
            }
            $HTMLreturnSequence.=/*'<li>' .*/'  '. $row['nazwa_sali'].': '.$row['data'] . ' - ' .substr($row['czas_start'],0,5). ' - ' .substr($row['czas_stop'],0,5). ' - ' .  $row['imie'].' '.$row['nazwisko'].'  <br/>';
        }
        $stmt->closeCursor();
        $HTMLreturnSequence.='</ul>';
        return $HTMLreturnSequence;
    } catch (PDOException $e) {
        echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
    }
}

require_once 'include/obslugaSesji.php';
require_once 'include/settings.php';
require_once 'include/settings_db.php';
//require_once 'include/functions.php';

$warning="";

if(!isset($_SESSION['username'])){
    header("Location: login.php");
}
try{
    $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	//Konfiguracja zgłaszania błędów poprzez wyjątki
    
} catch (PDOException $e){
    echo "Nie można się połączyć do bazy".$e->getMessage();
    die();
}
if (isset($_POST['wartosc'])) {
        if ($_POST['timestart']>=$_POST['timefinish']){
        $warning="<font color='red'>PHP Zakończenie rezerwacji nie jest później niż jej początek. Spróbuj jeszcze raz</font><br />";
    }else{
        try {
            $warning="<font color='red'>Insert</font><br />";
            $user_id=$_SESSION['username'];
            $stmt = $pdo->query('   SELECT
                                    id_pracownika
                                FROM
                                    pracownicy
                                WHERE
                                    username="'.$user_id.'";');
            $stmt->execute();
            foreach ($stmt as $row) {
                $user_id=$row['id_pracownika'];
            }
            $stmt = $pdo->query("SET CHARSET utf8");
            $stmt = $pdo->query("SET NAMES `utf8` COLLATE `utf8_general_ci`");
            
            $stmt1 = $pdo->prepare('SELECT data, czas_start, czas_stop, id_pracownika, nazwa_sali FROM rezerwacje
                                    WHERE data=:data AND czas_start=:czas_start AND czas_stop=:czas_stop AND id_pracownika=:id_p AND nazwa_sali=:n_sali;');
            $stmt1->bindValue(':data', $_POST['data'], PDO::PARAM_STR);
            $stmt1->bindValue(':czas_start', $_POST['timestart'], PDO::PARAM_STR);
            $stmt1->bindValue(':czas_stop', $_POST['timefinish'], PDO::PARAM_STR);
            $stmt1->bindValue(':id_p', $user_id, PDO::PARAM_STR);
            $stmt1->bindValue(':n_sali', $_POST['sale'], PDO::PARAM_STR);
            $stmt1->execute();
            if($stmt1->rowCount()==0){
                $stmt = $pdo->prepare('  INSERT INTO
                                        rezerwacje (data, czas_start, czas_stop, id_pracownika, nazwa_sali)
                                    VALUES
                                        (:data, :czas_start, :czas_stop, :id_p, :n_sali);');
                $stmt->bindValue(':data', $_POST['data'], PDO::PARAM_STR);
                $stmt->bindValue(':czas_start', $_POST['timestart'], PDO::PARAM_STR);
                $stmt->bindValue(':czas_stop', $_POST['timefinish'], PDO::PARAM_STR);
                $stmt->bindValue(':id_p', $user_id, PDO::PARAM_STR);
                $stmt->bindValue(':n_sali', $_POST['sale'], PDO::PARAM_STR);
                $stmt->execute();
                $stmt->closeCursor();
            }
            $stmt1->closeCursor();
        } catch (PDOException $e){
            echo 'Błąd wprowadzania do bazy: ' . $e->getMessage();
        }
    }
}
$TRESC="";
$TRESC1="";

$TRESC.=formGenerate(basename(__FILE__),$warning);
$TRESC1=getActors($pdo);

//Przetworzenie szablonów
require_once 'szablony/witryna.php';

?>
