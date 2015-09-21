<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	include $root."/php/start.php";
	$data = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);
	if(isset($_SESSION['loggedId']) && isset($_POST['id']))
	{
		$result = $data -> query('SELECT COUNT(*) AS ofSubAc FROM advertisers WHERE id='.$_POST['id'].' AND parent='.$_SESSION['loggedId']);
		$number = $result -> fetch_assoc();
		if($number['ofSubAc']!=0)
		{
			$infoRaw = $data -> query('SELECT * FROM advertisers WHERE id ='.$_POST['id']);
			$info = $infoRaw -> fetch_array(MYSQLI_ASSOC);
			$to      = $info['email'];
			$subject = 'Your Escort Central account';
			$message = 'Hi '.$info['fname'].',<br />Your administrator has deleted your account from their agency. <br />This means you need to create a new account and set up a new membership if you want to keep using Escort Central.';
			$countryRaw = $data -> query('SELECT * FROM countries WHERE id='.$currentCountry);
			$country = $countryRaw -> fetch_array(MYSQLI_ASSOC);

			$headers = 'From: noreply@escortcentral' . $country['extension'] . "\r\n" .
			'Reply-To: noreply@escortcentral' . $country['extension'] . "\r\n" .
			'Content-Type: text/html; charset="UTF-8";' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();



			$imagesRaw = $data -> query('SELECT * FROM images WHERE user = '.$_POST['id']);
			while($image = $imagesRaw -> fetch_array(MYSQLI_ASSOC))
			{
				/*
				CREATES A BLOODY 500 ERROR
				if(file_exists($root."/images/".$image['file']))
				{
					unlink($root."/images/".$image['file']);
				}*/
				$data -> query('DELETE FROM images WHERE id='.$image['id']);
			}
			if($data -> query('DELETE FROM membership WHERE user='.$_POST['id']) && $data -> query('DELETE FROM advertisers_availability WHERE advertiser='.$_POST['id']) && $data -> query('DELETE FROM advertisers_favourites WHERE advertiser='.$_POST['id']) && $data -> query('DELETE FROM advertisers_services WHERE advertiser='.$_POST['id']) && $data -> query('DELETE FROM rates WHERE user='.$_POST['id']) && $data -> query('DELETE FROM advertisers WHERE id='.$_POST['id']) && mail($to, $subject, $message, $headers) && $data -> query('UPDATE advertisers SET parent=0 WHERE id='.$_POST['id'].' AND nickname!=""') && $data -> query('DELETE FROM membership WHERE user='.$_POST['id']))
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