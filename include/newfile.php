<?php
session_start();

if(!isset($_SESSION['inicjuj'])){
    session_regenerate_id();
    $_SESSION['inicjuj'] = TRUE;
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
}

if($_SESSION['ip'] != $_SERVER['REMOTE_ADDR']){
    die('Proba przejecia sesji udaremniona!');
}
?>