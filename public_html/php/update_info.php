<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	include $root."/php/start.php";
	$data = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);
	if(isset($_SESSION['loggedId']) && isset($_POST['email']))
	{
		if($_POST['shaved']=='true')
		{
			$shaved = 1;
		}
		else
		{
			$shaved = 0;
		}
		if($_POST['smoking']=='true')
		{
			$smoking = 1;
		}
		else
		{
			$smoking = 0;
		}
		if($_POST['disableFriendly']=='true')
		{
			$disableFriendly = 1;
		}
		else
		{
			$disableFriendly = 0;
		}
		if($data -> query('UPDATE advertisers SET fname="'.$_POST['fname'].'", nationality="'.$_POST['nationality'].'", lname="'.$_POST['lname'].'", ethnicity="'.$_POST['ethnicity'].'", gender="'.$_POST['gender'].'", birthDate="'.$_POST['birthDate'].'", eyeColour="'.$_POST['eyeColour'].'", email="'.$_POST['email'].'", hair="'.$_POST['hair'].'", height="'.$_POST['height'].'", bodyType="'.$_POST['body'].'", dressSize="'.$_POST['dressSize'].'", country='.$_POST['country'].', bustSize="'.$_POST['bustSize'].'", region='.$_POST['region'].', smoke='.$smoking.', city='.$_POST['city'].', disableFriendly='. $disableFriendly.', postCode="'.$_POST['postCode'].'", shaved='.$shaved.' WHERE id='.$_SESSION['loggedId']))
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