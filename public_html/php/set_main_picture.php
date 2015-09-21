<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	include $root."/php/start.php";
	$QL = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);
	if(isset($_POST['id']) && isset($_SESSION['loggedId']))
	{
		if($QL -> query('UPDATE images SET main=1 WHERE id='.$_POST['id'].' AND user='.$_SESSION['loggedId']) && $QL -> query('UPDATE images SET main=0 WHERE main=1 AND id!='.$_POST['id'].' AND user='.$_SESSION['loggedId']))
		{
			echo "ok";
		}
		else
		{
			echo 'error';
		}
	}
	else
	{
		//error 404
		include $root."/php/error404.php";
	}
?>