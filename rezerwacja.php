<?php 


/*<div class='form-group'>
<label class='col-form-label' for='inputDefault'>Default input</label>
<input type='text' class='form-control' placeholder='Default input' id='inputDefault'>
</div>*/

function formGenerate($action,$warning){
    $form="<form  class='form-group' name'formularz' action='$action' method='post' onsubmit=\"return checkform(this);\">";
    $form.="<fieldset><legend>Dodawanie rezerwacji</legend>";
//    $form.=$warning;
    $form.="<p id='warning' type='hidden'></p>";
    $form.="<label class='col-form-label' for='inputDefault'>Data:</label>";
    $form.="<input type='date' name='data' id='data' min='2010-01-01' max='2020-12-31' value='".date('Y-m-d')."' required class='form-control' placeholder='Default input'>";
    $form.="<label class='col-form-label' for='inputDefault'>Początek spotkania:</label>";
    $form.="<input type='time' name='timestart' id='timestart' min='07:00' max='16:00' step='1800' value='07:00' onclick='enable()' onchange='add_one()' required class='form-control' placeholder='Default input'>";
    $form.="<label class='col-form-label' for='inputDefault'>Koniec spotkania:</label>";
    $form.="<input type='time' name='timefinish' id='timefinish' min='07:00' max='16:00' step='1800' value='08:00' required class='form-control' placeholder='Default input'><br/>";
    $form.="<div><input type='button' value='Pokaż dostępne sale' name='dostepne' onClick=salaCheckJS(1,1,0) class='btn btn-secondary btn-lg btn-block'/></div>";
    $form.="<label class='col-form-label' for='inputDefault'>Dostępne sala:</label>";
    $form.="<select name='sale' id='sale' class='form-control' required></select><br />";
    $form.="<div><input type='submit' value='Rezerwuj' name='submit' class='btn btn-primary btn-lg btn-block'/></div><br/>";
    
    
//    $form.="Data: <input type='date' name='data' id='data' min='2010-01-01' max='2020-12-31' value='" . date('Y-m-d') . "' required /><br />";
//    $form.="Początek: <input type='time' name='timestart' id='timestart' min='07:00' max='16:00' step='1800' value='07:00' onclick='enable()' onchange='add_one()'/> <br />";
//    $form.="Koniec: <input type='time' name='timefinish' id='timefinish' min='07:00' max='16:00' step='1800' value='08:00' /> <br />";//disabled='false'
//    $form.="<input type='button' value='Pokaż dostępne sale' name='dostepne' onClick=salaCheckJS(1,1,0) /><br/>";
//    $form.="Sala: <select name='sale' id='sale'></select><br />";
//    $form.="wynik: <input type='text' name='wynik' id='wynik'/> <br />";
 //   $form.="<input type='submit' value='Rezerwuj' name='submit' />";
    $form.="</fieldset></form>";
    return $form;
}

function getActors($pdo){
    try {
        $stmt = $pdo->query("SET CHARSET utf8");
        $stmt = $pdo->query("SET NAMES `utf8` COLLATE `utf8_general_ci`");
        
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
            $HTMLreturnSequence.=/*'<li>' .*/'  '. $row['nazwa_sali'].': '.$row['data'] . ' - ' . $row['czas_start'] . ' - ' . $row['czas_stop']  . ' - ' .  $row['imie'].' '.$row['nazwisko'].'  <br/>';
 //           $HTMLreturnSequence.='<a href=aktorzy_del.php?id='.$row['id_aktora'].'> usuń<a/>';
 //           if(($_SESSION['username']==$row['username']) or ($_SESSION['username']=='admin')) $HTMLreturnSequence.='<a href=edit.php?id='.$row['id_rezerwacji'].'> edytuj<a/>';
            
    //        $HTMLreturnSequence.='</li>';
        }
        $stmt->closeCursor();
        $HTMLreturnSequence.='</ul>';
        return $HTMLreturnSequence;
    } catch (PDOException $e) {
        echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
    }
}
?>


</head>
<body>
<?php
require_once 'include/obslugaSesji.php';
require_once 'include/settings.php';
require_once 'include/functions.php';

$LOKALIZACJA="aktorzy";
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
if (isset($_POST['submit'])) {
    if ($_POST['timestart']>=$_POST['timefinish']){
        //        echo "Zakończenie rezerwacji nie jest później niż jej początek";<font color="red">This is some text!</font>
        $warning="<font color='red'>Zakończenie rezerwacji nie jest później niż jej początek. Spróbuj jeszcze raz</font><br />";
    }else{
        try {
            $user_id=$_SESSION['username'];
//            echo $_POST['data'].'  '.$_POST['timestart'].'   '.$_POST['timefinish'].'    '.$_SESSION['username'].'    '.$user_id.'<br />';
            $stmt = $pdo->query('   SELECT
                                    id_pracownika
                                FROM
                                    pracownicy
                                WHERE
                                    username="'.$user_id.'";');//':username
//            $stmt->bindValue(':username', $_SESSION['username'], PDO::PARAM_STR);
            $stmt->execute();
            foreach ($stmt as $row) {
//                echo "id: ".$row['id_pracownika']."<br />";
                $user_id=$row['id_pracownika'];
            }
            
//            echo $_POST['data'].'  '.$_POST['timestart'].'   '.$_POST['timefinish'].'    '.$_SESSION['username'].'    '.$user_id;
   
            $stmt = $pdo->query("SET CHARSET utf8");
            $stmt = $pdo->query("SET NAMES `utf8` COLLATE `utf8_general_ci`");
            
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
        } catch (PDOException $e){
            echo 'Błąd wprowadzania do bazy: ' . $e->getMessage();
        }
    }
}
$TRESC="";
$TRESC1="";
//$TRESC.=availibilityCheck($pdo,1,'2018-07-20','07:30:00','08:30:00');

$TRESC.=formGenerate(basename(__FILE__),$warning);
$TRESC1=getActors($pdo);

//Przetworzenie szablonów
require_once 'szablony/witryna.php';

?>
