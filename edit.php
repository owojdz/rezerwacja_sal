<?php 
/**Generowanie formularza edycji
 * @param $action - akcja do wykonania po submit
 * @param $data - data rezerwacji
 * @param $start - czas rozpoczęcia rezerwacji
 * @param $stop - czas zakończenia rezerwacji
 * @param $sala - nazwa sali
 * @param $id - id pracowanika
 * @return string - zwraca kod HTML formularza 
 */
function formGenerate($action,$data,$start,$stop,$sala,$id){
    $form="<form  name='formularz' id='formularz' action='$action' method='post' >";//onsubmit=\"return checkform_edit(this);\"
    $form.="<fieldset><legend>Edycja rezerwacji</legend>";
    $form.="<p id='warning' type='hidden'></p>";
    $form.="<label class='col-form-label' for='inputDefault'>Data:</label>";
    $form.="<input type='date' name='data' id='data' min='2010-01-01' max='2020-12-31' value='$data' required class='form-control' placeholder='Default input'>";
    $form.="<label class='col-form-label' for='inputDefault'>Początek spotkania:</label>";
    $form.="<input type='time' name='timestart' id='timestart' min='07:00' max='16:00' step='1800' value='$start' onfocusout='my_round_start()' onchange='add_one()' required class='form-control' placeholder='Default input'>";
    $form.="<label class='col-form-label' for='inputDefault'>Koniec spotkania:</label>";
    $form.="<input type='time' name='timefinish' id='timefinish' min='07:00' max='16:00' step='1800' value='$stop' onfocusout='my_round_stop()' required class='form-control' placeholder='Default input'><br/>";
    $form.="<div><input type='button' value='Pokaż dostępne sale' name='dostepne' onClick=salaCheckEditJS() class='btn btn-secondary btn-lg btn-block'/></div>";
    $form.="<label class='col-form-label' for='inputDefault'>Dostępne sala:</label>";
    $form.="<select name='sale' id='sale' class='form-control' required></select><br />";
    $form.="<input type='button' value='Popraw rezerwacje' name='submi' id='submi' onclick='checkform_edit()' class='btn btn-primary btn-lg btn-block'><br/>";
    $form.="<input type='hidden' name='wartosc' id='wartosc'><br/>";
    $form.="<input type='hidden' name='wynik' id='wynik'/> <br />";
    $form.="<input type='hidden' id='id_rez' value='$id' name='id' />";
    $form.="<input type='hidden' value='$sala' name='sala' id='sala'/>";
    $form.="</fieldset></form>";
    return $form;
}
require_once 'include/obslugaSesji.php';
require_once 'include/settings_db.php';
require_once 'include/settings.php';

$TRESC='';
$TRESC1='';

//weryfikacja zalogowania, jeśli nie to odesłanie do strony logowania
if(!isset($_SESSION['username'])){
    header("Location: login.php");
}
//kontrola, czy znane jest id rezerwacji przed otwarciem formularza
if(isset($_GET['id'])){
    try{
        //połączenie z bazą danych
        $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	//Konfiguracja zgłaszania błędów poprzez wyjątki
        
        //zapewnienie poprawngo kodowania polskich zanków
        $stmt = $pdo->query("SET CHARSET utf8");
        $stmt = $pdo->query("SET NAMES `utf8` COLLATE `utf8_general_ci`");
        
        //pobranie do formularza danych z bazy 
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
//kontrola, czy formularz był już wypełniany
if (isset($_POST['submit'])) {
    try {
        //połączenie z bazą danych
        $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	//Konfiguracja zgłaszania błędów poprzez wyjątki
        
        //zapewnienie poprawngo kodowania polskich zanków
        $stmt = $pdo->query("SET CHARSET utf8");
        $stmt = $pdo->query("SET NAMES `utf8` COLLATE `utf8_general_ci`");
        
        //update rezerwacji w bazie danych
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
        $TRESC1.="Poprawiono dane rezerwacji";
        $TRESC.=formGenerate(basename(__FILE__),$_POST['data'],$_POST['timestart'],$_POST['timefinish'],$_POST['sale'],$_POST['id']);
    } catch (PDOException $e){
        echo 'Błąd wprowadzania do bazy: ' . $e->getMessage();
    }
}

//Przetworzenie szablonów
require_once 'szablony/witryna.php';

?>