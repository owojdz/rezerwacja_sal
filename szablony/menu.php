<?php 
require_once 'skrypty/menu.php';
require_once 'include/settings.php';
require_once 'include/settings_db.php';
echo menu($MENU,$DBEngine,$DBServer,$DBUser,$DBPass,$DBName);
?>