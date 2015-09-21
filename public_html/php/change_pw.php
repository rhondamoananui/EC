<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	include $root."/php/start.php";
	$data = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);
	if(isset($_POST['id']) && isset($_POST['oldPassword']) && isset($_POST['newPW']))
	{
		$result = $data -> query('SELECT COUNT(*) AS ofAccounts FROM advertisers WHERE id='.$_POST['id'].' AND password="'.$_POST['oldPassword'].'"');
		$number = $result -> fetch_assoc();
		if($number['ofAccounts']==1)
		{
			if($data -> query('UPDATE advertisers SET password="'.md5($_POST['newPW']).'" WHERE id='.$_POST['id'].' AND password="'.$_POST['oldPassword'].'"'))
			{
				echo 'ok';
			}
			else
			{
				echo 'error';
			}
		}
		else
		{
			echo 'error';
		}
	}
	else 
	{
		//Error 404
		include $root."/php/error404.php";
	}
?>