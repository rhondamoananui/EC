<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	include $root."/php/start.php";
	$data = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);
	if(isset($_POST['id']) && isset($_SESSION['loggedId']))
	{
		$result = $data -> query('SELECT COUNT(*) AS ofAvailabilities FROM advertisers_availability WHERE id='.$_POST['id'].' AND advertiser='.$_SESSION['loggedId']);
		$number = $result -> fetch_assoc();
		if($number['ofAvailabilities']==1 && $data -> query('DELETE FROM advertisers_availability WHERE id='.$_POST['id'].' AND advertiser='.$_SESSION['loggedId']))
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