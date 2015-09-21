<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	include $root."/php/start.php";
	$data = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);
	if(isset($_POST['id']) && isset($_SESSION['loggedId']))
	{
		if($data -> query('INSERT INTO advertisers_favourites (advertiser, favourite) VALUES ('.$_SESSION['loggedId'].', '.$_POST['id'].')'))
		{
			$id = $data -> insert_id;
			$favourites_raw = $data -> query('SELECT * FROM favourites WHERE id='.$_POST['id']);
			$favourite = $favourites_raw -> fetch_array(MYSQLI_ASSOC);
			echo $favourite['name']." ok ".$id;
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