<?php
	session_start();
	if(isset($_POST['site']) && $_POST['site']==1 && !isset($_SESSION['loggedId']))
	{
		?>
			<div id="stuffInPopup">
				Please provide the e-mail address you used to create your Escort Central account.<br />
				An e-mail with your nickname and a link to reset your password will be sent to you.<br /><br />
				<input type="text" id="emailForgot" /> <input type="button" value="Send" id="sendEmail" onclick="testForgotPW();" />
			</div>
		<?php
	}
	else
	{
		//error 404
		include $root."/php/error404.php";
	}
?>