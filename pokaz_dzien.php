<?php 
/**Generowanie formularza wyboru dnia
 * @param $action - akcja do wykonania po submit
 * @return string - zwraca kod HTML formularza
 */
 function formGenerate($action){
    $form="<form  class='form-group' name='formularz' action='$action' method='post'>";
    $form.="<fieldset><legend>Podgląd rezerwacji</legend>";
    $form.="<label class='col-form-label' for='data'>Data:</label>";
    $form.="<input type='date' name='data' id='data' min='2010-01-01' max='2020-12-31' value='".date('Y-m-d')."' required class='form-control' onchange='show_reservations_by_color()' onclick='show_reservations_by_color()'>";
    $form.="</fieldset></form>";
    return $form;
}

require_once 'include/obslugaSesji.php';
require_once 'include/settings.php';
require_once 'include/settings_db.php';

$LOKALIZACJA="";
//generowanie treści strony przy użyciu bootstrap - tabela rezerwacji dla wszystkich sal
$TRESC1="";
        //nagłówek tabeli
        $TRESC1 .= '<div class="container-fluid" id="container">';
        $TRESC1 .= '<div class="row">';
        $TRESC1 .= '<div class="col-lg-2 col-sm-2">od - do</div>';
        $TRESC1 .= '<div class="col-lg-2 col-sm-2">Czerwona</div>';
        $TRESC1 .= '<div class="col-lg-2 col-sm-2">Szara</div>';
        $TRESC1 .= '<div class="col-lg-2 col-sm-2">Biała</div>';
        $TRESC1 .= '<div class="col-lg-2 col-sm-2">Zielona</div>';
        $TRESC1 .= '<div class="col-lg-2 col-sm-2">Niebieska</div>';
        $TRESC1 .= '</div>';
        //godziny
        for($i=7;$i<16;$i++){
            //pełne
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
            //połówki
            $TRESC1 .= '<div class="mdiv col-lg-2 col-sm-2" id="0'.$i.'50">'.$i.':30-'.$plus.':00</div>';
            $TRESC1 .= '<div class="mdiv cal col-lg-2 col-sm-2" id="'.$i.'501"></div>';
            $TRESC1 .= '<div class="mdiv cal col-lg-2 col-sm-2" id="'.$i.'502"></div>';
            $TRESC1 .= '<div class="mdiv cal col-lg-2 col-sm-2" id="'.$i.'503"></div>';
            $TRESC1 .= '<div class="mdiv cal col-lg-2 col-sm-2" id="'.$i.'504"></div>';
            $TRESC1 .= '<div class="mdiv cal col-lg-2 col-sm-2" id="'.$i.'505"></div>';
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
