<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	include $root."/php/start.php";
	$QL = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);
	if(isset($_SESSION['loggedId']))
	{
		if($QL -> query('DELETE FROM images WHERE banner=1 AND user='.$_SESSION['loggedId']))
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