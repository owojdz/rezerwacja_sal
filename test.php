<?php
require_once 'include/obslugaSesji.php';
require_once 'include/settings.php';
require_once 'include/functions.php';

$LOKALIZACJA="aktorzy";
$TRESC1="";
//     $TRESC1 = '<b>Lista rezerwacji:</b></br>';
$TRESC1 .= '<div class="container" id="con">';
$TRESC1 .= '<div class="row">';
$TRESC1 .= '<div class="col-lg-1 col-sm-1" id="0700">7:00-7:30</div>';
$TRESC1 .= '<div class="col-lg-1 col-sm-1" id="700"> dwa</div>';
$TRESC1 .= '</div>';
$TRESC1 .= '<div class="row">';
$TRESC1 .= '<div class="col-lg-1 col-sm-1" id="0750">7:30-8:00</div>';
$TRESC1 .= '<div class="col-lg-1 col-sm-1" id="750"> dwa</div>';
$TRESC1 .= '</div>';
$TRESC1 .= '<div class="row">';
$TRESC1 .= '<div class="col-lg-1 col-sm-1" id="0800">8:00-8:30</div>';
$TRESC1 .= '<div class="col-lg-1 col-sm-1" id="800"> dwa</div>';
$TRESC1 .= '</div>';
$TRESC1 .= '<div class="row">';
$TRESC1 .= '<div class="col-lg-1 col-sm-1" id="0850">Dupa</div>';
$TRESC1 .= '<div class="col-lg-1 col-sm-1" id="850"> dwa</div>';
$TRESC1 .= '</div>';
$TRESC1 .= '<div class="row">';
$TRESC1 .= '<div class="col-lg-1 col-sm-1" id="0900">Dupa</div>';
$TRESC1 .= '<div class="col-lg-1 col-sm-1" id="900"> dwa</div>';
$TRESC1 .= '</div>';
$TRESC1 .= '<div class="row">';
$TRESC1 .= '<div class="col-lg-1" id="0950">Dupa</div>';
$TRESC1 .= '<div class="col-lg-1" id="950"> dwa</div>';
$TRESC1 .= '</div>';
$TRESC1 .= '<div class="row">';
$TRESC1 .= '<div class="col-lg-1" id="01000">Dupa</div>';
$TRESC1 .= '<div class="col-lg-1" id="1000"> dwa</div>';
$TRESC1 .= '</div>';
$TRESC1 .= '<div class="row">';
$TRESC1 .= '<div class="col-lg-1" id="01050">Dupa</div>';
$TRESC1 .= '<div class="col-lg-1" id="1050"> dwa</div>';
$TRESC1 .= '</div>';
$TRESC1 .= '<div class="row">';
$TRESC1 .= '<div class="col-lg-1" id="01100">Dupa</div>';
$TRESC1 .= '<div class="col-lg-1" id="1100"> dwa</div>';
$TRESC1 .= '</div>';
$TRESC1 .= '<div class="row">';
$TRESC1 .= '<div class="col-lg-1" id="01100">Dupa</div>';
$TRESC1 .= '<div class="col-lg-1" id="1100"> dwa</div>';
$TRESC1 .= '</div>';
$TRESC1 .= '<div class="row">';
$TRESC1 .= '<div class="col-lg-1" id="01150">Dupa</div>';
$TRESC1 .= '<div class="col-lg-1" id="1150"> dwa</div>';
$TRESC1 .= '</div>';
$TRESC1 .= '<div class="row">';
$TRESC1 .= '<div class="col-lg-1 col-sm-1" id="01200">Dupa</div>';
$TRESC1 .= '<div class="col-lg-1 col-sm-1" id="1200"> dwa</div>';
$TRESC1 .= '</div>';
$TRESC1 .= '<div class="row">';
$TRESC1 .= '<div class="col-lg-1 col-sm-1" id="01250">Dupa</div>';
$TRESC1 .= '<div class="col-lg-1 col-sm-1" id="1250"> dwa</div>';
$TRESC1 .= '</div>';
$TRESC1 .= '<div class="row">';
$TRESC1 .= '<div class="col-lg-1 col-sm-1" id="01300">Dupa</div>';
$TRESC1 .= '<div class="col-lg-1 col-sm-1" id="1300"> dwa</div>';
$TRESC1 .= '</div>';
$TRESC1 .= '<div class="row">';
$TRESC1 .= '<div class="col-lg-1" id="01350">Dupa</div>';
$TRESC1 .= '<div class="col-lg-1" id="1350"> dwa</div>';
$TRESC1 .= '</div>';
$TRESC1 .= '<div class="row">';
$TRESC1 .= '<div class="col-lg-1" id="01400">Dupa</div>';
$TRESC1 .= '<div class="col-lg-1" id="1400"> dwa</div>';
$TRESC1 .= '</div>';
$TRESC1 .= '<div class="row">';
$TRESC1 .= '<div class="col-lg-1" id="01450">Dupa</div>';
$TRESC1 .= '<div class="col-lg-1" id="1450"> dwa</div>';
$TRESC1 .= '</div>';
$TRESC1 .= '<div class="row">';
$TRESC1 .= '<div class="col-lg-1" id="01500">Dupa</div>';
$TRESC1 .= '<div class="col-lg-1" id="1500"> dwa</div>';
$TRESC1 .= '</div>';
$TRESC1 .= '<div class="row">';
$TRESC1 .= '<div class="col-lg-1" id="01550">15:30-16:00</div>';
$TRESC1 .= '<div class="col-lg-1" id="1550"> dwa</div>';
$TRESC1 .= '</div>';
$TRESC1 .= '</div>';
$data="2018-07-27";
$sala="Czarna";
$TRESC="";


    try{
        $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	//Konfiguracja zgłaszania błędów poprzez wyjątki
        
    } catch (PDOException $e){
        echo "Nie można się połączyć do bazy".$e->getMessage();
        die();
    }
    try{
        $stmt = $pdo->prepare(' SELECT rezerwacje.data, rezerwacje.czas_start, rezerwacje.czas_stop, pracownicy.imie, pracownicy.nazwisko 
                                FROM rezerwacje JOIN pracownicy 
                                WHERE rezerwacje.id_pracownika=pracownicy.id_pracownika and rezerwacje.nazwa_sali=:sala and rezerwacje.data=:data;');
    
    $stmt->bindValue(':data', $data, PDO::PARAM_STR);
    $stmt->bindValue(':sala', $sala, PDO::PARAM_STR);
    $stmt->execute();
    $tresc="";
    $ar=array();
//    if($inner){
//        $tresc.='<select id="sale_ajax" name="sale_ajax">'.PHP_EOL;
//    }
//    $tresc.="<option value='".$sala."' >".$sala."</option>".PHP_EOL;
//    $i=0;
    foreach ($stmt as $row){
//        array_push($a,"blue","yellow");
        //            $tresc.="<option value='".$row['nazwa_sali']."' >".$row['czas_start']." ".$row['czas_stop']."</option>".PHP_EOL;
//        if($row['nazwa_sali']!=$sala){
//            $TRESC.='<div class="row">';
//            $TRESC .= '<div class="col-lg-1" id="01550">'.$row['czas_start'].'</div>';
 //           $TRESC .= '<div class="col-lg-1" id="1550">'.$row['czas_stop'].'</div></div>';
 //       }
 
            $str1 =$row['czas_start'];
            $str2 =$row['czas_stop'];
   //         echo $str1."<br/>";
            $str1 =str_ireplace(":30",":50",$str1);
            $str1 =str_ireplace(":","",$str1);
            //         echo $str1."<br/>";
            $str1 =substr($str1,0,4);
  //          echo $str1."<br/>";
  //          echo $str2."<br/>";
            $str2 =str_ireplace(":30",":50",$str2);
            $str2 =str_ireplace(":","",$str2);
            //        echo $str2."<br/>";
            $str2 =substr($str2,0,4);
      //      echo $str2."<br/>";
            $st1=(int)$str1;
            $st2=(int)$str2;
            
            while ($st1 <= $st2) {
                array_push($ar, $st1);
                $st1+=50;
            }
            for ($x = 0; $x < count($ar); $x++) {
                echo "The number is: $ar[$x] <br>";
            } 
            echo "end";
//                    
//                    text += " " + val1.toString();
//                    val1 += 50;
//                }
                //   document.getElementById("demo1").innerHTML = val1;
//                document.getElementById("demo2").innerHTML = text;
            
    }
//    if($inner){
//        $tresc.='</select><br/>'.PHP_EOL;
//    }
    $stmt->closeCursor();
    
    } catch (PDOException $e){
        echo 'Błąd odczytu z bazy: ' . $e->getMessage();
    }
    
    
    
    
    
    
    
    
    
    //Przetworzenie szablonów
    require_once 'szablony/witryna.php';
