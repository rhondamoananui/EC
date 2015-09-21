<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	include $root."/php/start.php";
	$data = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);
	if(isset($_POST['facebook']) && isset($_POST['twitter']) && isset($_POST['website']) && isset($_POST['phone']) && isset($_SESSION['loggedId']))
	{
		if($data -> query('UPDATE advertisers SET facebook="'.$data -> real_escape_string($_POST['facebook']).'", twitter="'.$data -> real_escape_string($_POST['twitter']).'", phone="'.$data -> real_escape_string($_POST['phone']).'", website="'.$data -> real_escape_string($_POST['website']).'" WHERE id = '.$_SESSION['loggedId']))
		{
			echo "ok";
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