<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	include $root."/php/start.php";
	$data = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);
	if(isset($_POST['email']))
	{
		$rawUserInfo = $data -> query('SELECT * FROM advertisers WHERE email="'.$_POST['email'].'"');
		if($newU = $rawUserInfo -> fetch_array(MYSQLI_ASSOC))
		{
			$to      = $newU['email'];
 			$subject = 'Escort Central Nickname and Password';
 			$message = 'Hi '.$newU['fname'].',<br />Your nickname is '.$newU['nickname'].'. If you would like to reset your password, please click the following link:<br /><br /><a href="http://'.$_SERVER['HTTP_HOST'].'/?reset='.$newU['id'].'&id='.$newU['password'].'">http://'.$_SERVER['HTTP_HOST'].'/?reset='.$newU['id'].'&id='.$newU['password'].'</a>';
 			$countryRaw = $data -> query('SELECT * FROM countries WHERE id='.$currentCountry);
 			$country = $countryRaw -> fetch_array(MYSQLI_ASSOC);

 			$headers = 'From: noreply@escortcentral' . $country['extension'] . "\r\n" .
			'Reply-To: noreply@escortcentral' . $country['extension'] . "\r\n" .
			'Content-Type: text/html; charset="UTF-8";' . "\r\n" .
 			'X-Mailer: PHP/' . phpversion();

 			if(mail($to, $subject, $message, $headers))
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
			echo "no";
		}
	}
	else 
	{
		//Error 404
		include $root."/php/error404.php";
	}
?>