<?php 
/**Generowanie formularza rezerwacji
 * @param $action - akcja do wykonania po submit
 * @param $warning - informacja o błędach walidacji
 * @return string - zwraca kod HTML formularza 
 */
function formGenerate($action,$warning){
    $form="<form  class='form-group' name='formularz' id='formularz' action='$action' method='post' >" ;
    $form.="<fieldset><legend>Dodawanie rezerwacji</legend>";
    $form.="<p id='warning'>  </p>";
    $form.="<label class='col-form-label' for='data'>Data:</label>";
    $form.="<input type='date' name='data' id='data' min='2010-01-01' max='2020-12-31' value='".date('Y-m-d')."' required class='form-control'>";
    $form.="<label class='col-form-label' for='timestart'>Początek spotkania:</label>";
    $form.="<input type='time' name='timestart' id='timestart' min='07:00' max='16:00' step='1800' value='07:00' onblur='my_round_start()' onchange='add_one()' required class='form-control'>";
    $form.="<label class='col-form-label' for='timefinish'>Koniec spotkania:</label>";
    $form.="<input type='time' name='timefinish' id='timefinish' min='07:00' max='16:00' step='1800' value='08:00' onblur='my_round_stop()' required class='form-control'><br/>";
    $form.="<div><input type='button' value='Pokaż dostępne sale' name='dostepne' onClick='salaCheckJS()' class='btn btn-secondary btn-lg btn-block'/></div>";
    $form.="<label class='col-form-label' for='sale'>Dostępne sale:</label>";
    $form.="<select name='sale' id='sale' class='form-control' size='5' required></select><br />";
    $form.="<input type='button' value='Rezerwuj' name='submi' id='submi' onclick='checkform()' class='btn btn-primary btn-lg btn-block'><br/>";
    $form.="<input type='hidden' name='wartosc' id='wartosc'><br/>";
    $form.="</fieldset></form>";
    return $form;
}

/**Generuje listę rezerwacji (w formie listy z opcją edycji lub usunięcia)
 * @param $pdo - połączenie do bazy danych
 * @return string - kod HTML listy rezerwacji
 */
function show_reservations_list($pdo){
    try {
        //zapewnienie poprawngo kodowania polskich zanków
        $stmt = $pdo->query("SET CHARSET utf8");
        $stmt = $pdo->query("SET NAMES `utf8` COLLATE `utf8_general_ci`");
        $stmt = $pdo->query('   SELECT rezerwacje.id_rezerwacji, pracownicy.username, rezerwacje.data, rezerwacje.czas_start, rezerwacje.czas_stop, rezerwacje.nazwa_sali, pracownicy.imie, pracownicy.nazwisko, pracownicy.username FROM rezerwacje JOIN pracownicy WHERE rezerwacje.id_pracownika=pracownicy.id_pracownika
                                ORDER BY data,czas_start;');
        $stmt->execute();                            
        $HTMLreturnSequence='<b>Lista rezerwacji:</b><br/>';
    //    $HTMLreturnSequence.='<ul>';
        foreach ($stmt as $row) {
            if(($_SESSION['username']==$row['username']) or ($_SESSION['username']=='admin')) {
                $HTMLreturnSequence.="<input type='button' class='btn btn-info btn-sm' value='Edytuj' onclick='edit(".$row['id_rezerwacji'].")' />";
            } else {
                $HTMLreturnSequence.="<input type='button' class='btn btn-info btn-sm' value='Edytuj' disabled onclick='edit(".$row['id_rezerwacji'].")' />";
            }
            if(($_SESSION['username']==$row['username']) or ($_SESSION['username']=='admin')) {
                $HTMLreturnSequence.="<input type='button' class='btn btn-danger btn-sm' value='Usuń' onclick='del(".$row['id_rezerwacji'].")' />";
            } else {
                $HTMLreturnSequence.="<input type='button' class='btn btn-danger btn-sm' value='Usuń' disabled onclick='del(".$row['id_rezerwacji'].")' />";
            }
            $HTMLreturnSequence.='<a>'.'  '. $row['nazwa_sali'].': '.$row['data'] . ' - ' .substr($row['czas_start'],0,5). ' - ' .substr($row['czas_stop'],0,5). ' - ' .  $row['imie'].' '.$row['nazwisko'].'  </a><br/>';
        }
        $stmt->closeCursor();
    //    $HTMLreturnSequence.='</ul>';
        return $HTMLreturnSequence;
    } catch (PDOException $e) {
        echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
    }
}

require_once 'include/obslugaSesji.php';
require_once 'include/settings.php';
require_once 'include/settings_db.php';

$warning="";

//weryfikacja zalogowania, jeśli nie to odesłanie do strony logowania
if(!isset($_SESSION['username'])){
    header("Location: login.php");
}
//połączenie z bazą danych
try{
    $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	//Konfiguracja zgłaszania błędów poprzez wyjątki
    
} catch (PDOException $e){
    echo "Nie można się połączyć do bazy".$e->getMessage();
    die();
}
//kontrola, czy formularz był już wypełniany
if (isset($_POST['wartosc'])) {
        if ($_POST['timestart']>=$_POST['timefinish']){ //powtórna walidacja czy czas początku rezerwacji nie jest późniejszy niż czas końca
        $warning="<font color='red'>Zakończenie rezerwacji nie jest później niż jej początek. Spróbuj jeszcze raz</font><br />";
    }else{
        try {
            //wprowadzanie danych o rezerwacji do bazy
            
            //pobranie id pracowanika na podstawie username
            $user_name=$_SESSION['username'];
            $stmt = $pdo->query('   SELECT
                                    id_pracownika
                                FROM
                                    pracownicy
                                WHERE
                                    username="'.$user_name.'";');
            $stmt->execute();
            foreach ($stmt as $row) {
                $user_id=$row['id_pracownika'];
            }
            
            //kontrola, czy nie ma już tekiego wpisu w bazie (istotne przy wykonaniu reload po dokonaniu rezerwacji, gdy ustawiona jest już zmienna 'wartośc')
            //zapewnienie poprawngo kodowania polskich zanków
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
                //insert rezerwacji do bazy
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

//generacja treści strony
$TRESC.=formGenerate(basename(__FILE__),$warning);
$TRESC1=show_reservations_list($pdo);

//Przetworzenie szablonów
require_once 'szablony/witryna.php';

?>
