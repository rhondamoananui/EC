<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	include $root."/php/start.php";
	$data = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);
	if(isset($_POST['deletem']) && $_POST['deletem']==1 && isset($_POST['id']) && isset($_POST['renew']))
	{
		$data -> query('DELETE FROM membership WHERE user='.$_POST['id']);
	}
	if(isset($_POST['membership']) && isset($_POST['id']) && isset($_POST['pass']) && isset($_POST['renew']))
	{
		if(strtolower($_POST['membership'])!='agency')
		{
			$usersRaw = $data -> query('SELECT * FROM advertisers WHERE parent='.$_POST['id']);
			while($user = $usersRaw -> fetch_array(MYSQLI_ASSOC))
			{
				$data -> query('DELETE FROM membership WHERE user='.$user['id']);
				$data -> query('UPDATE advertisers SET parent=0, featured=2147483648 WHERE id='.$user['id']);
			}
		}
		else
		{
			$usersRaw = $data -> query('SELECT * FROM advertisers WHERE parent='.$_POST['id']);
			while($user = $usersRaw -> fetch_array(MYSQLI_ASSOC))
			{
				$data -> query('UPDATE membership SET expiry="'.(date("Y")+1).'-'.date("m-d").'", paid=0 WHERE user='.$user['id']);
				$data -> query('UPDATE advertisers SET featured=0 WHERE id='.$user['id']);
			}
		}
		$data -> query('DELETE FROM membership WHERE user='.$_POST['id']);
		$data -> query('INSERT INTO membership (type, user, paid, autoRenew, expiry) VALUES ("'.$_POST['membership'].'", "'.$_POST['id'].'", "0", "'.$_POST['renew'].'", "'.(date("Y")+1).'-'.date("m-d").'")');
		if(strtolower($_POST['membership'])!='gold')
		{
			$data -> query('UPDATE advertisers SET featured=0 WHERE id='.$_POST['id']);
		}
		else
		{
			$data -> query('UPDATE advertisers SET featured=2147483648 WHERE id='.$_POST['id']);
		}
		$membershipRaw = $data -> query('SELECT * FROM membership WHERE user='.$_POST['id']);
		$membership = $membershipRaw -> fetch_array(MYSQLI_ASSOC);
		?>
			<div id="stuffInPopup">
				You have selected <b><?php echo $_POST['membership'];?> membership.</b><br />
				Please follow the Paypal instructions below for payment. Your account will be active once you have paid.<br />
				Click <a href="javascript:selectNewMembership(<?php echo $_POST['id'];?>, 1, '<?php echo $_POST['pass'];?>');"><b>here</b></a> if you would like to change your membership.<br /><br />

				<input type="checkbox" id="autoRenew" <?php if($membership['autoRenew']==1) { echo 'checked="checked"'; } ?> /> Tick this box if you would like your membership to automatically renew<br /><br />

				<form name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
					<input type="hidden" name="cmd" value="_xclick">
					<input type="hidden" name="business" value="payment@escortcentral.com.au">
					<input type="hidden" name="invoice" value="<?php echo $membership['id'];?>">
					<input type="hidden" name="currency_code" value="NZD">
					<input type="hidden" name="item_name" value="<?php echo $_POST['membership'];?> membership">
					<input type="hidden" name="amount" value="<?php if($_POST['membership']=='Agency'||$_POST['membership']=='agency') { echo "59.99"; } else if($_POST['membership']=='Platinum'||$_POST['membership']=='platinum') { echo "9.99"; } else { echo "0.00"; };?>">
					<input name="notify_url" value="http://<?php echo $_SERVER['HTTP_HOST'];?>/php/payment_done.php" type="hidden"> 
				</form>
				<input type="image" src="http://<?php echo $_SERVER['HTTP_HOST'];?>/design/paypal.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!" onclick="if(document.getElementById('autoRenew').checked) { autoRenew(<?php echo $membership['id'];?>, 1, '<?php echo $_POST['pass'];?>'); } else {  autoRenew(<?php echo $membership['id'];?>, 0, '<?php echo $_POST['pass'];?>'); }">
			</div>
			<div id="waitingForPayment" style="display: none;">
				<img style="float: left; height: 50px; margin-right: 10px; margin-left: 10px;" src="http://<?php echo $_SERVER['HTTP_HOST'];?>/design/loading.gif" alt="Loading..." />
				Waiting for Paypal payment to be complete...<br />
				Please follow the instructions on the Paypal window.<br />
				Or <a href="#" onclick="document.getElementById('waitingForPayment').style.display='none';document.getElementById('stuffInPopup').style.display='';">go back</a>
			</div>
		<?php
	}
	else if(isset($_POST['id']) && isset($_POST['renew']) && isset($_POST['pass']))
	{
		?>
			<div id="stuffInPopup">
				Your previous membership has expired. Please select your new membership to carry on using Escort Central.<br /><br />
				<table id="pricesTable">
					<tr id="topRow">
						<td class="leftColumn" style="font-weight: bold;">
							Plans
						</td>
						<td id="gold">
							Gold
						</td>
						<td id="platinum">
							Platinum
						</td>
						<td id="agency">
							Agency
						</td>
					</tr>
					<tr>
						<td class="leftColumn">
							Profiles
						</td>
						<td class="gold">
							1
						</td>
						<td class="platinum">
							1
						</td>
						<td class="agency">
							20
						</td>
					</tr>
					<tr>
						<td class="leftColumn">
							Photos
						</td>
						<td class="gold">
							8
						</td>
						<td class="platinum">
							12
						</td>
						<td class="agency">
							12 each
						</td>
					</tr>
					<tr>
						<td class="leftColumn">
							Social Media Promotion
						</td>
						<td class="gold">
							<img src="../design/yes.png" alt="yes" class="yes" />
						</td>
						<td class="platinum">
							<img src="../design/yes.png" alt="yes" class="yes" />
						</td>
						<td class="agency">
							<img src="../design/yes.png" alt="yes" class="yes" />
						</td>
					</tr>
					<tr>
						<td class="leftColumn">
							Link to your personal website
						</td>
						<td class="gold">
							<img src="../design/yes.png" alt="yes" class="yes" />
						</td>
						<td class="platinum">
							<img src="../design/yes.png" alt="yes" class="yes" />
						</td>
						<td class="agency">
							<img src="../design/yes.png" alt="yes" class="yes" />
						</td>
					</tr>
					<tr>
						<td class="leftColumn">
							Featured listing on Home page
						</td>
						<td class="gold">
							<img src="../design/no.png" alt="no" class="no" />
						</td>
						<td class="platinum">
							<img src="../design/yes.png" alt="yes" class="yes" />
						</td>
						<td class="agency">
							<img src="../design/yes.png" alt="yes" class="yes" />
						</td>
					</tr>
					<tr>
						<td class="leftColumn">
							Logo and banner on each profile
						</td>
						<td class="gold" style="border-radius: 0 0 0 5px;">
							<img src="../design/no.png" alt="no" class="no" />
						</td>
						<td class="platinum">
							<img src="../design/no.png" alt="no" class="no" />
						</td>
						<td class="agency" style="border-radius: 0 0 5px 0;">
							<img src="../design/yes.png" alt="yes" class="yes" />
						</td>
					</tr>
					<tr>
						<td class="leftColumn">
						</td>
						<td>
							<input type="button" class="signUpButtons" value="Sign up for $49.99/month" onclick="createMembership('Gold', <?php echo $_POST['id'];?>, <?php echo $_POST['renew'];?>, '<?php echo $_POST['pass'];?>');" />
						</td>
						<td>
							<input type="button" class="signUpButtons" value="Sign up for $59.99/month" onclick="createMembership('Platinum', <?php echo $_POST['id'];?>, <?php echo $_POST['renew'];?>, '<?php echo $_POST['pass'];?>');" />
						</td>
						<td>
							<input type="button" class="signUpButtons" value="Sign up for $199.99/month" onclick="createMembership('Agency', <?php echo $_POST['id'];?>, <?php echo $_POST['renew'];?>, '<?php echo $_POST['pass'];?>');" />
						</td>
					</tr>
				</table>
			</div>
		<?php
	}
	else
	{
		//err 404;
		include $root."/php/error404.php";
	}
?>