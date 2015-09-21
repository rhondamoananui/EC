<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	include $root."/php/start.php";
	$data = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);
	if(isset($_POST['hoursRate']) && isset($_POST['minutesRate']) && isset($_POST['price']) && isset($_SESSION['loggedId']))
	{
		if($data -> query('INSERT INTO rates (user, price, hours, minutes) VALUES ("'.$_SESSION['loggedId'].'", "'.$_POST['price'].'", "'.$_POST['hoursRate'].'", "'.$_POST['minutesRate'].'")'))
		{
			echo "ok".$data -> insert_id;
		}
		else
		{
			echo "error";
		}
	}
	else
	{
		//error 404
		include $root."/php/error404.php";
	}
?>