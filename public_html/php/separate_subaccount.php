<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	include $root."/php/start.php";
	$data = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);
	if(isset($_SESSION['loggedId']) && isset($_POST['id']))
	{
		$result = $data -> query('SELECT COUNT(*) AS ofSubAc FROM advertisers WHERE id='.$_POST['id'].' AND nickname!="" AND parent='.$_SESSION['loggedId']);
		$number = $result -> fetch_assoc();
		if($number['ofSubAc']!=0)
		{
			$infoRaw = $data -> query('SELECT * FROM advertisers WHERE id ='.$_POST['id']);
			$info = $infoRaw -> fetch_array(MYSQLI_ASSOC);
			$to      = $info['email'];
			$subject = 'Your Escort Central account';
			$message = 'Hi '.$info['fname'].',<br />Your administrator has separated your account from their agency. <br />This means your account is still existent but you need to login and setup a new membership to keep using Escort Central.';
			$countryRaw = $data -> query('SELECT * FROM countries WHERE id='.$currentCountry);
			$country = $countryRaw -> fetch_array(MYSQLI_ASSOC);

			$headers = 'From: noreply@escortcentral' . $country['extension'] . "\r\n" .
			'Reply-To: noreply@escortcentral' . $country['extension'] . "\r\n" .
			'Content-Type: text/html; charset="UTF-8";' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
			if(mail($to, $subject, $message, $headers) && $data -> query('UPDATE advertisers SET parent=0 WHERE id='.$_POST['id'].' AND nickname!=""') && $data -> query('DELETE FROM membership WHERE user='.$_POST['id']))
			{
				echo "ok";
			}
		}
	}
	else
	{
		//error 404
		include $root."/php/error404.php";
	}
?>