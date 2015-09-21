<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	include $root."/php/start.php";
	$data = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);
	if(isset($_POST['site']) && $_POST['site']==1)
	{
		?>
		<div id="stuffInPopup">
			<?php
				if($_POST['email']=='' || $_POST['message']=='' || $_POST['name']=='' || !(preg_match("#^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$#", $_POST['email'])))
				{
					?>
						Please provide the following information:
						<br /><br />
						<div class="contact-form">
<div class="full-form">
	<div class="your-name" style="<?php if(($_POST['email']!='' || $_POST['message']!='') && $_POST['name']=='') { echo 'color: red'; } ?>">
	<!-- Your name:<br /> -->
		
		<input type="text" id="name" style="width: 300px; padding: .5em;" placeholder="Your Name:" value="<?php echo htmlspecialchars($_POST['name']);?>" />
	</div>

	<div class="email-address"  style="<?php if(($_POST['name']!='' || $_POST['message']!='') && !(preg_match("#^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$#", $_POST['email']))) { echo 'color: red'; } ?>">
	<!-- Your e-mail address: -->
	<br />

		<input type="text" id="email" placeholder="Your Email:" style="width: 300px; padding: .5em;" value="<?php echo htmlspecialchars($_POST['email']);?>" />
	</div>

<div class="message" style="<?php if(($_POST['email']!='' || $_POST['name']!='') && $_POST['message']=='') { echo 'color: red'; } ?>">
<!-- Message:-->
<br /> 
<textarea placeholder="Message:" style="resize: none; width: 450px; height: 62px; padding: .5em;" id="message"><?php echo $_POST['message'];?></textarea>

<input type="button" value="Submit" onclick="showContact(document.getElementById('email').value, document.getElementById('message').value, document.getElementById('name').value);">
</div>
</div>
</div>

					<?php
				}
				else
				{
						$to      = $helpEmail;
		     			$subject = 'Escort Central Contact';
		     			$message = 'Someone has contacted Escort Central:<br /><table><tr><td>Name: </td><td>'.$_POST['name'].'</td></tr><tr><td>email: </td><td>'.$_POST['email'].'</td></tr><tr><td>Message: </td><td>'.$_POST['message'].'</td></tr></table><br /><br />You can directly reply to this email to answer this person.';
		     			$countryRaw = $data -> query('SELECT * FROM countries WHERE id='.$currentCountry);
		     			$country = $countryRaw -> fetch_array(MYSQLI_ASSOC);

		     			$headers = 'From: ' . $_POST['email'] . "\r\n" .
		    			'Reply-To: ' . $_POST['email'] . "\r\n" .
		    			'Content-Type: text/html; charset="UTF-8";' . "\r\n" .
		     			'X-Mailer: PHP/' . phpversion();

		     			if(mail($to, $subject, $message, $headers))
		     			{
		     				?>
		     					We got your message! We will get back to you as soon as possible.<br /><br />
		     					<input type="button" value="Okay" onclick="hidePopup();" />
		     				<?php
		     			}
		     			else
		     			{
		     				?>
		     					Sorry, ann error occured while sending your message. Please try again later.<br /><br />
		     					<input type="button" value="Okay" onclick="hidePopup();" />
		     				<?php
		     			}
				}
			?>
		</div>
		<?php
	}
	else 
	{
		//Error 404
		include $root."/php/error404.php";
	}
?>