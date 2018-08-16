<?php 
function formGenerate($action){
    $form="<form  class='form-group' name'formularz' action='$action' method='post' onsubmit=\"return checkform(this);\">";
    $form.="<fieldset><legend>Pokaż rezerwacje</legend>";
    $form.="<p id='warning' type='hidden'></p>";
    $form.="<label class='col-form-label' for='inputDefault'>Data:</label>";
    $form.="<input type='date' name='data' id='data' min='2010-01-01' max='2020-12-31' value='".date('Y-m-d')."' required class='form-control' placeholder='Default input' onchange=show_reservations()>";
//    $form.="<div><input type='button' value='Pokaż dostępne sale' name='dostepne' onClick=salaCheckJS(1,1,0) class='btn btn-secondary btn-lg btn-block'/></div>";
//    $form.="<label class='col-form-label' for='inputDefault'>Dostępne sala:</label>";
 //   $form.="<select name='sale' id='sale' class='form-control' required></select><br />";
    $form.="<div><input type='submit' value='Pokaż' name='submit' class='btn btn-primary btn-lg btn-block'/></div><br/>";
    $form.="</fieldset></form>";
    return $form;
}

function showReservations1($pdo, $data)
{
    try {
        $stmt = $pdo->query("SET CHARSET utf8");
        $stmt = $pdo->query("SET NAMES `utf8` COLLATE `utf8_general_ci`");
        
        $stmt = $pdo->query('   SELECT rezerwacje.id_rezerwacji, pracownicy.username, rezerwacje.data, rezerwacje.czas_start, rezerwacje.czas_stop, rezerwacje.nazwa_sali, pracownicy.imie, pracownicy.nazwisko, pracownicy.username
                                FROM rezerwacje JOIN pracownicy WHERE rezerwacje.id_pracownika=pracownicy.id_pracownika and rezerwacje.data="' . $data . '" ORDER BY data,czas_start;');
        $stmt->execute();
        $HTMLreturnSequence = '<b>Lista rezerwacji:</b></br>';
        $HTMLreturnSequence .= '<ul>';
        foreach ($stmt as $row) {
            if (($_SESSION['username'] == $row['username']) or ($_SESSION['username'] == 'admin')) {
                $HTMLreturnSequence .= "<input type='button' class='btn btn-info btn-sm' value='Edytuj' onclick='edit(" . $row['id_rezerwacji'] . ")' />";
            } else {
                $HTMLreturnSequence .= "<input type='button' class='btn btn-info btn-sm' value='Edytuj' disabled='true' onclick='edit(" . $row['id_rezerwacji'] . ")' />";
            }
            if (($_SESSION['username'] == $row['username']) or ($_SESSION['username'] == 'admin')) {
                $HTMLreturnSequence .= "<input type='button' class='btn btn-danger btn-sm' value='Usuń' onclick='del(" . $row['id_rezerwacji'] . ")' />";
            } else {
                $HTMLreturnSequence .= "<input type='button' class='btn btn-danger btn-sm' value='Usuń' disabled='true' onclick='del(" . $row['id_rezerwacji'] . ")' />";
            }
            $HTMLreturnSequence .= '  ' . $row['nazwa_sali'] . ': ' . $row['data'] . ' - ' . $row['czas_start'] . ' - ' . $row['czas_stop'] . ' - ' . $row['imie'] . ' ' . $row['nazwisko'] . '  <br/>';
        }
        $stmt->closeCursor();
        $HTMLreturnSequence .= '</ul>';
        return $HTMLreturnSequence;
    } catch (PDOException $e) {
        echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
    }
}

function showReservations($pdo, $data)
{
    try {
        $stmt = $pdo->query("SET CHARSET utf8");
        $stmt = $pdo->query("SET NAMES `utf8` COLLATE `utf8_general_ci`");
        
        $stmt = $pdo->query('   SELECT rezerwacje.id_rezerwacji, pracownicy.username, rezerwacje.data, rezerwacje.czas_start, rezerwacje.czas_stop, rezerwacje.nazwa_sali, pracownicy.imie, pracownicy.nazwisko, pracownicy.username
                                FROM rezerwacje JOIN pracownicy WHERE rezerwacje.id_pracownika=pracownicy.id_pracownika and rezerwacje.data="' . $data . '" ORDER BY data,czas_start;');
        $stmt->execute();
        $HTMLreturnSequence = '<b>Lista rezerwacji:</b></br>';
        $HTMLreturnSequence .= '<div class="container">';
            $HTMLreturnSequence .= '<div class="row">';
                $HTMLreturnSequence .= '<div class="col-lg-1" id="70"></div>';
                $HTMLreturnSequence .= '<div class="col-lg-1" id="70biala"></div>';
            $HTMLreturnSequence .= '</div>';
        $HTMLreturnSequence .= '</div>';
        foreach ($stmt as $row) {
            echo '<script type="text/javascript">',
//            'show_reservations_by_color();',
              'document.getElementById("70").style.backgroundColor = "lightblue";',  
            '</script>';
            
//            $HTMLreturnSequence .= '  ' . $row['nazwa_sali'] . ': ' . $row['data'] . ' - ' . $row['czas_start'] . ' - ' . $row['czas_stop'] . ' - ' . $row['imie'] . ' ' . $row['nazwisko'] . '  <br/>';
        }
        $stmt->closeCursor();
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
$TRESC1="";

if(!isset($_SESSION['username'])){
    header("Location: login.php");
} else {
try{
    $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	//Konfiguracja zgłaszania błędów poprzez wyjątki
    
} catch (PDOException $e){
    echo "Nie można się połączyć do bazy".$e->getMessage();
    die();
}
if (isset($_POST['submit'])) {
        $TRESC1="";
        $TRESC1=showReservations($pdo, $_POST['data']);
}

$TRESC="";
$TRESC.=formGenerate(basename(__FILE__));

//Przetworzenie szablonów
require_once 'szablony/witryna.php';
}
?>
