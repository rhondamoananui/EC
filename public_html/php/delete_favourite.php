<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	include $root."/php/start.php";
	$data = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);
	if(isset($_POST['id']) && isset($_SESSION['loggedId']))
	{
		$favourites_raw = $data -> query('SELECT * FROM advertisers_favourites INNER JOIN favourites ON favourites.id=advertisers_favourites.favourite WHERE idF='.$_POST['id']);
		$favourite = $favourites_raw -> fetch_array(MYSQLI_ASSOC);
		if($data -> query('DELETE FROM advertisers_favourites WHERE idF='.$_POST['id'].' AND advertiser='.$_SESSION['loggedId']))
		{
			echo $favourite['name']." ok ".$favourite['id'];
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