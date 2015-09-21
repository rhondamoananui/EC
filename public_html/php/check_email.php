<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	include $root."/php/start.php";
	$data = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);
	if(isset($_POST['email']) && isset($_SESSION['loggedId']))
	{
		$result = $data -> query('SELECT COUNT(*) AS ofEmails FROM advertisers WHERE UPPER(email)=UPPER("'.$_POST['email'].'") AND id!='.$_SESSION['loggedId']);
		$number = $result -> fetch_assoc();
		$info = $data -> query('SELECT * FROM advertisers WHERE id='.$_SESSION['loggedId']);
		$inf = $info -> fetch_array(MYSQLI_ASSOC);
		if($number['ofEmails']==0||$inf['parent']!=0)
		{
			echo 'ok';
		}
		else
		{
			echo "NO";
		}
	}
	else
	{
		//error 404
		include $root."/php/error404.php";
	}
?>