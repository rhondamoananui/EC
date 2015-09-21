<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	include $root."/php/start.php";
	$data = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);
	if(isset($_POST['id']))
	{
		$result = $data -> query('SELECT COUNT(*) AS ofPayments FROM membership INNER JOIN advertisers ON advertisers.id=membership.user WHERE membership.id='.$_POST['id'].' AND paid=1');
		$number = $result -> fetch_assoc();
		if($number['ofPayments']==1)
		{
			$raw = $data -> query('SELECT * FROM membership WHERE id='.$_POST['id']);
			$use = $raw -> fetch_array(MYSQLI_ASSOC);
			$_SESSION['loggedId'] = $use['user'];
			echo "1";
		}
		else
		{
			echo "0";
		}
	}
	else
	{
		//error 404
		include $root."/php/error404.php";
	}
?>