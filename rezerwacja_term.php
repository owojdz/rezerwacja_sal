<?php 
/**Generowanie formularza wyboru dnia i sali do rezerwacji z okna terminarza
 * @param $action - akcja do wykonania po submit
 * @return string - zwraca kod HTML formularza
 */
function formGenerate($action){
    $form="<form  class='form-group' name='formularz' id='formularz' action='$action' method='post'>";
    $form.="<fieldset><legend>Wybór rezerwacji z okna terminarza</legend>";
    $form.="<p id='warning' style='color:red;'></p>";
    $form.="<label class='col-form-label' for='data'>Data:</label>";
    $form.="<input type='date' name='data' id='data' min='2010-01-01' max='2020-12-31' value='".date('Y-m-d')."' required class='form-control' onclick='show_reservations_single()' onchange='show_reservations_single()'><br/>";
    $form.="<div><input type='button' value='Pokaż sale' name='dostepne' onClick='saleList()' class='btn btn-secondary btn-lg btn-block'/></div>";
    $form.="<label class='col-form-label' for='sale'>Wybierz sale:</label>";
    $form.="<select name='sale' id='sale' size='5' class='form-control' onclick='show_reservations_single()' onchange='show_reservations_single()' onfocus='saleList()' required>";
    $form.="</select><br />";
    $form.="<p id='warning1' style='color:black;'>Zaznacz pole początku lub końca rezerwacji i wybierz godzinę na terminarzu</p>";
    $form.="<label class='col-form-label' for='timestart'>Początek spotkania:</label>";
    $form.="<input type='time' name='timestart' id='timestart' onfocus='setcount(1)' required class='form-control'>";
    $form.="<label class='col-form-label' for='timefinish'>Koniec spotkania:</label>";
    $form.="<input type='time' name='timefinish' id='timefinish' onfocus='setcount(2)' required class='form-control'><br/>";
    $form.="<input type='button' value='Rezerwuj' name='submi' id='submi' onclick='checkform()' class='btn btn-primary btn-lg btn-block'><br/>";
    $form.="<input type='hidden' name='wartosc' id='wartosc'><br/>";
    $form.="</fieldset></form>";
    return $form;
}

require_once 'include/obslugaSesji.php';
require_once 'include/settings.php';
require_once 'include/settings_db.php';
//require_once 'include/functions.php';

//generowanie treści strony przy użyciu bootstrap - tabela zajętości dla jednej sali
$TRESC1="";
        //nagłówek tabeli
        $TRESC1 .= '<div class="container-fluid" id="container">';
        $TRESC1 .= '<div class="row">';
        $TRESC1 .= '<div class="col-lg-3 col-sm-4">od - do</div>';
        $TRESC1 .= '<div class="col-lg-9 col-sm-8" id="nazwaSali"></div>';
        $TRESC1 .= '</div>';
        //godziny
        for($i=7;$i<16;$i++){
            //pełne
            $TRESC1 .= '<div class="row">';
            $TRESC1 .= '<div class="mdiv col-lg-3 col-sm-4" id="0'.$i.'00" onclick=get_select('.$i.'00)>'.$i.':00-'.$i.':30</div>';
            $TRESC1 .= '<div class="mdiv cal col-lg-9 col-sm-8" id="'.$i.'001" onclick=get_select('.$i.'00)></div>';
            $TRESC1 .= '</div>';
            $TRESC1 .= '<div class="row">';
            $plus=$i+1;
            //połówki
            $TRESC1 .= '<div class="mdiv col-lg-3 col-sm-4" id="0'.$i.'50" onclick=get_select('.$i.'50)>'.$i.':30-'.$plus.':00</div>';
            $TRESC1 .= '<div class="mdiv cal col-lg-9 col-sm-8" id="'.$i.'501" onclick=get_select('.$i.'50)></div>';
            $TRESC1 .= '</div>';
        }
        $TRESC1 .= '</div>';


//weryfikacja zalogowania, jeśli nie to odesłanie do strony logowania
if(!isset($_SESSION['username'])){
    header("Location: login.php");
} else {
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
    
    //generowanie treści strony
    $TRESC="";
    $TRESC.=formGenerate(basename(__FILE__));
    $TRESC1 .= '<script type="text/javascript" src="js/ajax.js"></script>';
    $TRESC1 .= '<script type="text/javascript" src="js/javascript.js"></script>';
    
    //Przetworzenie szablonów
    require_once 'szablony/witryna.php';
}
?>
