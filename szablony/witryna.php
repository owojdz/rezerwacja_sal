<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php $NAZWA_STRONY ?></title>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link rel="Stylesheet" type="text/css" href="css/style.css">	
</head>
<body>
	<div class="container">
	<?php require_once 'szablony/menu.php';?>
 	<div class="row">
			<div class="col-lg-3 col-sm-6" id="TRESC"><?php echo $TRESC;?></div>
			<div class="col-lg-9 col-sm-12" id="TRESC1"><?php echo $TRESC1;?></div>
		</div>
	</div>
	<script type="text/javascript" src="js/ajax.js"></script>
	<script type="text/javascript" src="js/javascript.js"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js"></script>
</body>
</html>


