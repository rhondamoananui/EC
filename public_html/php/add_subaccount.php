<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	include $root."/php/start.php";
	$data = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);
	if(isset($_SESSION['loggedId']) && isset($_POST['email']))
	{
		$result = $data -> query('SELECT COUNT(*) AS ofEmails FROM advertisers WHERE email="'.$_POST['email'].'"');
		$number = $result -> fetch_assoc();
		if($number['ofEmails']!=0)
		{
			echo "taken";
		}
		else
		{
			$result = $data -> query('SELECT COUNT(*) AS ofSubAccounts FROM advertisers WHERE parent='.$_SESSION['loggedId']);
			$number = $result -> fetch_assoc();
			if($number['ofSubAccounts']<19)
			{
				if($data -> query('INSERT INTO advertisers (email, parent) VALUES ("'.$_POST['email'].'", '.$_SESSION['loggedId'].')'))
				{
					$id = $data -> insert_id;

					$to      = $_POST['email'];
		 			$subject = 'Set up your Escort Central account';
		 			$message = 'Hi there,<br />An agency account owner on Escort Central has created a sub-account with your e-mail address. please click the following link to set up your account:<br /><br /><a href="http://'.$_SERVER['HTTP_HOST'].'/?logout=1&setup='.$id.'&code='.md5($_POST['email']).'">http://'.$_SERVER['HTTP_HOST'].'/?logout=1&setup='.$id.'&code='.md5($_POST['email']).'</a>';
		 			$countryRaw = $data -> query('SELECT * FROM countries WHERE id='.$currentCountry);
		 			$country = $countryRaw -> fetch_array(MYSQLI_ASSOC);

		 			$headers = 'From: noreply@escortcentral' . $country['extension'] . "\r\n" .
					'Reply-To: noreply@escortcentral' . $country['extension'] . "\r\n" .
					'Content-Type: text/html; charset="UTF-8";' . "\r\n" .
		 			'X-Mailer: PHP/' . phpversion();

		 			if(mail($to, $subject, $message, $headers))
		 			{
		 				$newNumber = $number['ofSubAccounts']+1;
		 				echo $id.'ok'.$newNumber;
		 			}
	 			}
	 		}
		}
	}
	else
	{
		//error 404
		include $root."/php/error404.php";
	}
?>