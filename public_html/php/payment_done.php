<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	include $root."/php/start.php";
	$data = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);
	if(isset($_POST['payment_status']) && isset($_POST['invoice']))
	{
		if($_POST['payment_status']=="Completed")
		{
			$invoiceRaw = $data -> query('SELECT * FROM membership INNER JOIN advertisers ON advertisers.id=membership.user WHERE membership.id='.$_POST['invoice']);
			if($invoice = $invoiceRaw -> fetch_array(MYSQLI_ASSOC))
			{
				$to      = $invoice['email'];
     			$subject = 'Escort Central Payment';
     			$countryRaw = $data -> query('SELECT * FROM countries WHERE id='.$currentCountry);
     			$country = $countryRaw -> fetch_array(MYSQLI_ASSOC);

     			$headers = 'From: noreply@escortcentral' . $country['extension'] . "\r\n" .
    			'Reply-To: noreply@escortcentral' . $country['extension'] . "\r\n" .
    			'Content-Type: text/html; charset="UTF-8";' . "\r\n" .
     			'X-Mailer: PHP/' . phpversion();
     			
				if($data -> query('UPDATE membership SET paid=1 WHERE id = '.$_POST['invoice']))
				{
					$infoRaw = $data -> query('SELECT * FROM membership WHERE id = '.$_POST['invoice']);
					$info = $infoRaw -> fetch_array(MYSQLI_ASSOC);
					if(strtolower($info['type'])=='agency')
					{
						$subURaw = $data -> query('SELECT * FROM advertisers WHERE parent = '.$info['user']);
						while($subU = $subURaw -> fetch_array(MYSQLI_ASSOC))
						{
							$data -> query('UPDATE membership SET paid=1 WHERE user = '.$subU['id']);
						}
					}
	     			$message = 'Hi '.$invoice['fname'].',<br />Your pament was succesfully received! Your account is now active and visible to every one and you can <a href="'.$_SERVER['HTTP_HOST'].'?elogin=1">login</a> using your Nickname/e-mail and password to complete your profile. <br />Your invoice number is: '.$_POST['invoice'];	     			
				}
				else
				{
					$message = 'Hi '.$invoice['fname'].',<br />Your payment was succesfully received, but there was an error activating your account. Please, contact us on the following email: '.$helpEmail.', make sure you include your invoice number: '.$_POST['invoice'];	     			
				}
				if($invoice['type']!="Agency" && $invoice['type']!="agency")
				{
					$subusers = $data -> query('SELECT * FROM advertisers WHERE parent = '.$invoice['user']);
					while($subuser = $subusers -> fetch_array(MYSQLI_ASSOC))
					{
						$data -> query('UPDATE membership SET expiry="0000-00-00" WHERE user = '.$subuser['id']);
					}
					$data -> query('UPDATE advertisers SET parent=0 WHERE parent = '.$invoice['user']);
				}
	     		mail($to, $subject, $message, $headers);
			}
		}
	}
	else
	{
		//404 error
		include $root."/php/error404.php";
	}
?>