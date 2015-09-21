<?php
	session_start();
	if(isset($_POST['site']) && $_POST['site']==1 && !isset($_SESSION['loggedId']))
	{
		?>
			<div id="stuffInPopup">
				<form action="" method="POST">
					<?php 
						if(isset($_POST['usernameField']) && $_POST['usernameField']!='' && $_POST['usernameField']!='Nickname or E-mail' && isset($_POST['passwordField']) && $_POST['passwordField']!='' && $_POST['passwordField']!='Password')
						{
							?>
								<span style="color: red;">Incorrect nickname/e-mail/password</span>
							<?php
						}
					?>
					<table id="loginTable">
						<tr>
							<td>
								<input <?php if(isset($_POST['usernameField']) && $_POST['usernameField']!='' && $_POST['usernameField']!='Nickname or E-mail') { ?>style="color: #000;"<?php }?> type="text" name="usernameField" id="usernameField" class="textFields" value="<?php if(isset($_POST['usernameField']) && $_POST['usernameField']!='') { echo htmlspecialchars($_POST['usernameField']); } else { echo 'Nickname or E-mail'; } ?>" onfocus="if(this.value=='Nickname or E-mail') { this.style.color = 'black'; this.value = ''; }" onblur="if(this.value=='Nickname or E-mail'||this.value=='') { this.style.color = 'grey'; this.value = 'Nickname or E-mail'; }" />
							</td>
							<td style="padding-left: 10px;">
								<input type="submit" style="width: 154px;" value="Sign In" />
							</td>
						</tr>
						<tr>
							<td>
								<input <?php if(isset($_POST['passwordField']) && $_POST['passwordField']!='' && $_POST['passwordField']!='Password') { ?>style="color: #000;"<?php }?> type="password" name="passwordField" id="passwordField" class="textFields" value="<?php if(isset($_POST['passwordField']) && $_POST['passwordField']!='') { echo htmlspecialchars($_POST['passwordField']); } else { echo 'Password'; } ?>" onfocus="if(this.value=='Password') { this.style.color = 'black'; this.value = ''; }" onblur="if(this.value=='Password'||this.value=='') { this.style.color = 'grey'; this.value = 'Password'; }" />
							</td>
							<td style="/*padding-left: 10px;*/ font-size: 10px; text-align: center;">
								<img src="design/loginfb-button.png" style="display: none;" alt="Login With Facebook" />
								<a href="javascript:forgotPW();">Forgot nickname/password?</a>
							</td>
						</tr>
					</table>
				</form>
			</div>
		<?php
	}
	else
	{
		//error 404
		include $root."/php/error404.php";
	}
?>