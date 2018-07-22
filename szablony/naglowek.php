Witamy na stronie wypożyczalni "Nasza wypożyczalnia". Jesteś w <?php echo $LOKALIZACJA;?>.
<?php 
if (isset($_SESSION['username'])){
    echo "Jesteś zalogowany jako <b>".$_SESSION['username']."</b>";
    echo "<a href='logout.php'> Wyloguj się</a>";
} else {
    echo "Nie jesteś zalogowany.  ";
    echo "<a href='login.php'> Zaloguj się</a>";
}
?>