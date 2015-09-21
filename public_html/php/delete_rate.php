<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	include $root."/php/start.php";
	$data = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);
	if(isset($_POST['id']) && isset($_SESSION['loggedId']))
	{
		$result = $data -> query('SELECT COUNT(*) AS ofRates FROM rates WHERE id='.$_POST['id'].' AND user='.$_SESSION['loggedId']);
		$number = $result -> fetch_assoc();
		if($number['ofRates']==1 && $data -> query('DELETE FROM rates WHERE id='.$_POST['id'].' AND user='.$_SESSION['loggedId']))
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