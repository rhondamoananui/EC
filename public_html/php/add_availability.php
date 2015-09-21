<?php

	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	include $root."/php/start.php";
	$data = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);
	if(isset($_POST['fromHour']) && isset($_SESSION['loggedId']))
	{
		if($data -> query('INSERT INTO advertisers_availability (fromTime, day, toTime, advertiser) VALUES ("'.$_POST['fromHour'].':'.$_POST['fromMin'].' '.$_POST['fromAmPm'].'", '.$_POST['day'].', "'.$_POST['toHour'].':'.$_POST['toMin'].' '.$_POST['toAmPm'].'", '.$_SESSION['loggedId'].')'))
		{
			echo "ok".$data -> insert_id;
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