<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	include $root."/php/start.php";
	$data = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);
	if(isset($_POST['id']) && isset($_SESSION['loggedId']))
	{
		$services_raw = $data -> query('SELECT * FROM advertisers_services WHERE service='.$_POST['id'].' AND advertiser='.$_SESSION['loggedId']);
		$service = $services_raw -> fetch_array(MYSQLI_ASSOC);
		if($data -> query('DELETE FROM advertisers_services WHERE service='.$_POST['id'].' AND advertiser='.$_SESSION['loggedId']))
		{
			echo 'ok';
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