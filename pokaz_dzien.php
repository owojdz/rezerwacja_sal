<?php 
function formGenerate($action){
    $form="<form  class='form-group' name'formularz' action='$action' method='post' onsubmit=\"return checkform(this);\">";
    $form.="<fieldset><legend>Pokaż rezerwacje</legend>";
    $form.="<p id='warning' type='hidden'></p>";
    $form.="<label class='col-form-label' for='inputDefault'>Data:</label>";
    $form.="<input type='date' name='data' id='data' min='2010-01-01' max='2020-12-31' value='".date('Y-m-d')."' required class='form-control' placeholder='Default input' onchange= 'show_reservations_by_color()'>";
//    $form.="wynik: <input type='text' name='wynik' id='wynik'/> <br />";
//    $form.="<div><input type='button' value='Pokaż' name='submit' class='btn btn-primary btn-lg btn-block' onclick= 'show_reservations_by_color()'/></div><br/>";
//    $form.="<div><input type='submit' value='Pokaż' name='submit' class='btn btn-primary btn-lg btn-block' onclick= 'myFunction()'/></div><br/>";
    $form.="</fieldset></form>";
    return $form;
}
?>
</head>

<body>
<?php
require_once 'include/obslugaSesji.php';
require_once 'include/settings.php';
require_once 'include/settings_db.php';
require_once 'include/functions.php';

$LOKALIZACJA="";
$TRESC1="";
   //     $TRESC1 = '<b>Lista rezerwacji:</b></br>';
        $TRESC1 .= '<div class="container" id="container">';
        $TRESC1 .= '<div class="row">';
        $TRESC1 .= '<div class="col-lg-2 col-sm-2">od - do</div>';
        $TRESC1 .= '<div class="col-lg-2 col-sm-2">Czerwona</div>';
        $TRESC1 .= '<div class="col-lg-2 col-sm-2">Szara</div>';
        $TRESC1 .= '<div class="col-lg-2 col-sm-2">Biała</div>';
        $TRESC1 .= '<div class="col-lg-2 col-sm-2">Zielona</div>';
        $TRESC1 .= '<div class="col-lg-2 col-sm-2">Niebieska</div>';
        $TRESC1 .= '</div>';
        for($i=7;$i<16;$i++){
            $TRESC1 .= '<div class="row">';
            $TRESC1 .= '<div class="mdiv col-lg-2 col-sm-2" id="0'.$i.'00">'.$i.':00-'.$i.':30</div>';
            $TRESC1 .= '<div class="mdiv cal col-lg-2 col-sm-2" id="'.$i.'001"></div>';
            $TRESC1 .= '<div class="mdiv cal col-lg-2 col-sm-2" id="'.$i.'002"></div>';
            $TRESC1 .= '<div class="mdiv cal col-lg-2 col-sm-2" id="'.$i.'003"></div>';
            $TRESC1 .= '<div class="mdiv cal col-lg-2 col-sm-2" id="'.$i.'004"></div>';
            $TRESC1 .= '<div class="mdiv cal col-lg-2 col-sm-2" id="'.$i.'005"></div>';
            $TRESC1 .= '</div>';
            $TRESC1 .= '<div class="row">';
            $plus=$i+1;
            $TRESC1 .= '<div class="mdiv col-lg-2 col-sm-2" id="0'.$i.'50">'.$i.':30-'.$plus.':00</div>';
            $TRESC1 .= '<div class="mdiv cal col-lg-2 col-sm-2" id="'.$i.'501"></div>';
            $TRESC1 .= '<div class="mdiv cal col-lg-2 col-sm-2" id="'.$i.'502"></div>';
            $TRESC1 .= '<div class="mdiv cal col-lg-2 col-sm-2" id="'.$i.'503"></div>';
            $TRESC1 .= '<div class="mdiv cal col-lg-2 col-sm-2" id="'.$i.'504"></div>';
            $TRESC1 .= '<div class="mdiv cal col-lg-2 col-sm-2" id="'.$i.'505"></div>';
            $TRESC1 .= '</div>';
        }
        $TRESC1 .= '</div>';


if(!isset($_SESSION['username'])){
    header("Location: login.php");
} else {
$TRESC="";
$TRESC.=formGenerate(basename(__FILE__));
$TRESC1 .= '<script type="text/javascript" src="js/ajax.js"></script>';
$TRESC1 .= '<script type="text/javascript" src="js/javascript.js"></script>';
$TRESC1 .= '<script type="text/javascript"> show_reservations_by_color(); </script>';

//Przetworzenie szablonów
require_once 'szablony/witryna.php';
}
?>
