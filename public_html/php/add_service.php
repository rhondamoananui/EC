<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	include $root."/php/start.php";
	$data = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);
	if(isset($_POST['id']) && isset($_SESSION['loggedId']))
	{
		if($data -> query('INSERT INTO advertisers_services (advertiser, service) VALUES ('.$_SESSION['loggedId'].', '.$_POST['id'].')'))
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