<?php 
/**Generowanie formularza wyboru dnia i sali
 * @param $action - akcja do wykonania po submit
 * @return string - zwraca kod HTML formularza
 */
function formGenerate($action){
    $form="<form  class='form-group' name='formularz' action='$action' method='post'>";
    $form.="<fieldset><legend>Zajętość poszczególnych sali</legend>";
    $form.="<p id='warning' style='color:red;'></p>";
    $form.="<label class='col-form-label' for='data'>Data:</label>";
    $form.="<input type='date' name='data' id='data' min='2010-01-01' max='2020-12-31' value='".date('Y-m-d')."' required class='form-control' onclick='show_reservations_single()' onchange='show_reservations_single()'><br/>";
    $form.="<div><input type='button' value='Pokaż sale' name='dostepne' onClick='saleList()' class='btn btn-secondary btn-lg btn-block'/></div>";
    $form.="<label class='col-form-label' for='sale'>Wybierz sale:</label>";
    $form.="<select name='sale' id='sale' size='5' class='form-control' onclick='show_reservations_single()' onchange='show_reservations_single()' required></select><br />";
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
            $TRESC1 .= '<div class="mdiv col-lg-3 col-sm-4" id="0'.$i.'00">'.$i.':00-'.$i.':30</div>';
            $TRESC1 .= '<div class="mdiv cal col-lg-9 col-sm-8" id="'.$i.'001"></div>';
            $TRESC1 .= '</div>';
            $TRESC1 .= '<div class="row">';
            $plus=$i+1;
            //połówki
            $TRESC1 .= '<div class="mdiv col-lg-3 col-sm-4" id="0'.$i.'50">'.$i.':30-'.$plus.':00</div>';
            $TRESC1 .= '<div class="mdiv cal col-lg-9 col-sm-8" id="'.$i.'501"></div>';
            $TRESC1 .= '</div>';
        }
        $TRESC1 .= '</div>';


//weryfikacja zalogowania, jeśli nie to odesłanie do strony logowania
if(!isset($_SESSION['username'])){
    header("Location: login.php");
} else {
    //generowanie treści strony
    $TRESC="";
    $TRESC.=formGenerate(basename(__FILE__));
    $TRESC1 .= '<script type="text/javascript" src="js/ajax.js"></script>';
    $TRESC1 .= '<script type="text/javascript" src="js/javascript.js"></script>';
    
    //Przetworzenie szablonów
    require_once 'szablony/witryna.php';
}
?>
