<div id="stuffInPopup">
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
				Featured Listing
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
				Logo on each profile
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
				<input type="button" class="signUpButtons" value="<?php if(isset($_POST['freeM']) && $_POST['freeM']==1) { echo 'Signup for free!'; } else { echo 'Sign up for $49.99/month'; } ?>" onclick="register('Gold');" />
			</td>
			<td>
				<input type="button" class="signUpButtons" value="<?php if(isset($_POST['freeM']) && $_POST['freeM']==1) { echo 'Signup for free!'; } else { echo 'Sign up for $59.99/month'; } ?>" onclick="register('Platinum');" />
			</td>
			<td>
				<input type="button" class="signUpButtons" value="<?php if(isset($_POST['freeM']) && $_POST['freeM']==1) { echo 'Signup for free!'; } else { echo 'Sign up for $199.99/month'; } ?>" onclick="register('Agency');" />
			</td>
		</tr>
	</table>
</div>