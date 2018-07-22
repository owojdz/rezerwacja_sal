<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php $NAZWA_STRONY ?></title>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<!--  charset=windows-1250"> -->
<link rel="Stylesheet" type="text/css" href="styles/style.css" />	
<link rel="Stylesheet" type="text/css" href="styles/menu.css" />	
</head>
<body>
<div id="top">
<div id="NAGLOWEK"><?php require_once 'szablony/naglowek.php';?></div>
<div id="MENU"><?php require_once 'szablony/menu.php';?></div>
<div id="TRESC"><?php echo $TRESC;?></div>
<div id="STOPKA"><?php require_once 'szablony/stopka.php';?></div>
</div>
</body>
</html>
