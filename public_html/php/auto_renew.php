<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	include $root."/php/start.php";
	$data = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);
	if(isset($_POST['id']))
	{
		$data -> query('UPDATE membership SET autoRenew = '.$_POST['renew'].' WHERE id='.$_POST['id']);
	}
	else
	{
		//error 404
		include $root."/php/error404.php";
	}
?>