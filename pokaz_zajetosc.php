<?php 
function formGenerate($action){
    $form="<form  class='form-group' name'formularz' action='$action' method='post'>";
    $form.="<fieldset><legend>Pokaż zajętość sali</legend>";
    $form.="<p id='warning' type='hidden'></p>";
    $form.="<label class='col-form-label' for='inputDefault'>Data:</label>";
    $form.="<input type='date' name='data' id='data' min='2010-01-01' max='2020-12-31' value='".date('Y-m-d')."' required class='form-control' placeholder='Default input' onclick=saleList() onchange= 'show_reservations_single()'>";
    $form.="<label class='col-form-label' for='inputDefault'>Wybierz sale:</label>";
    $form.="<select name='sale' id='sale' class='form-control' onclick= 'show_reservations_single()' onchange= 'show_reservations_single()' onfocus='saleList()' required></select><br />";
    $form.="</fieldset></form>";
    return $form;
}

require_once 'include/obslugaSesji.php';
require_once 'include/settings.php';
require_once 'include/settings_db.php';
require_once 'include/functions.php';

$LOKALIZACJA="";
$TRESC1="";
        $TRESC1 .= '<div class="container" id="container">';
        $TRESC1 .= '<div class="row">';
        $TRESC1 .= '<div class="col-lg-3 col-sm-4">od - do</div>';
        $TRESC1 .= '<div class="col-lg-9 col-sm-8" id="nazwaSali"></div>';
        $TRESC1 .= '</div>';
        for($i=7;$i<16;$i++){
            $TRESC1 .= '<div class="row">';
            $TRESC1 .= '<div class="mdiv col-lg-3 col-sm-4" id="0'.$i.'00">'.$i.':00-'.$i.':30</div>';
            $TRESC1 .= '<div class="mdiv cal col-lg-9 col-sm-8" id="'.$i.'001"></div>';
            $TRESC1 .= '</div>';
            $TRESC1 .= '<div class="row">';
            $plus=$i+1;
            $TRESC1 .= '<div class="mdiv col-lg-3 col-sm-4" id="0'.$i.'50">'.$i.':30-'.$plus.':00</div>';
            $TRESC1 .= '<div class="mdiv cal col-lg-9 col-sm-8" id="'.$i.'501"></div>';
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
//$TRESC1 .= '<script type="text/javascript"> show_reservations_by_color(); </script>';


//Przetworzenie szablonów
require_once 'szablony/witryna.php';
}
?>
