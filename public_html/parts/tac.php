<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	include $root."/php/start.php";
	$data = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);
	if(isset($_POST['site']) && $_POST['site']==1)
	{
		?>
			<div id="stuffInPopup">
				Escort Central is an Advertising directory for Escorts and Escort Agencies.   We do NOT provide booking services or supply escorts. The advertisers on this site are not employed by Escort Central.
				<br /><br />
				<b>MEMBERSHIP</b>
				<br />
				It is free to browse our website without membership, however you must agree that you are 18 years or older.  Membership is only required by advertisers.
				To create a profile with Escort Central you must:
				<br />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a) be at least 18 years old.  <br />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b) Complete registration and payment (via paypal, a well known secure online 	payment gateway) <br />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c) confirm your email address with us.<br />
				We reserve the right to terminate your membership without notice at any time if you breach these terms and conditions.
				When you have complete registration, you can use your email and password to login and edit your profile.  Membership for advertisers will start from $49.99 per month or $59.99 per month for a featured Profile.  You can choose to pay monthly or annually.
				The advertising period will commence at the time and date in which payment is received.  You will be notified prior to expiration of your advertisement so that you can elect whether to make payment for a further advertising period.  Payment must be received before the advertisement can be displayed.  If payment is not received by the date of expiration of the advertising period, we reserve the right to remove your profile from the website until you pay the fee for a further advertising period.
				If we decide, in our sole discretion, to offer you a free period of advertising as part of any promotional campaign, you will be notified of when the advertising period will commence and finish.
				<br /><br />
				<b>PROFILES</b>
				<br />
				If you are registered as a member for creating and posting your profile on this website, you are able to provide us with content pursuant to these terms and conditions.
				You are responsible for the content of your own Profile, and you are required to abide be your state laws http://www.scarletalliance.org.au/laws/ 
				We reserve the right to remove your content and/or terminate your membership without notice at any time if you breach these terms and conditions. 
				Please be aware that the content that you provide to the website is governed by the rules and regulations of each State.  The State in which you reside are the statutory rules and regulations in which you must abide when placing your advertisement on the website.  If you are unsure of what you can and cannot include in your profile advertisement please check http://www.scarletalliance.org.au/laws/.
				Your Profile will go live as soon as you have completed it, and we will be regularly checking all profiles to make sure obligations and state laws are being met.   However Escort Central does not accept responsibility if individual profiles are in breach of state laws.
				<br /><br />
				<b>OBLIGATIONS</b>
				<br />
				By registering as a member of our website, you agree to abide by our Terms and Conditions:
				<ul class="tcList">
					<li>you acknowledge that any information or material submitted by you to the website is and will be treated by us as non-confidential and non-proprietary and we may use such material without restriction;</li>
					<li>when you submit material to the website, you assign all copyright which subsists in such material to us;</li>
					<li>you agree that you are over the age of 18 years if you are advertising on the website;</li>
					<li>you agree not to post or transmit any material in which the copyright is owned by another person or entity and you warrant that all material posted is your original work and not sourced from any third party;</li>
					<li>upon our request, you must procure on behalf of yourself and on behalf of us all proper licences, clearances, permissions and releases in writing in respect of any copyright material included in your content so that your content can be distributed through our web site;</li>
					<li>you are responsible for protecting the confidentiality of your password;</li>
					<li>you will not post or transmit any material or information which is offensive, defamatory, obscene, unlawful, vulgar, harmful, threatening, abusive, harassing or ethnically objectionable;</li>
					<li>you warrant that your content is not fraudulent, defamatory and does not infringe the intellectual property rights, confidentiality rights or privacy rights of any person;</li>
					<li>you agree not to impersonate any other person;</li>
					<li>you agree to ensure that your content is up to date, accurate and not misleading;</li>
					<li>you agree not to post or transmit any unsolicited advertising or promotional materials;</li>
					<li>you will not post any material which contains viruses or other computer codes, files or programs which are designed to limit or destroy the functionality of other computer software or hardware;</li>
					<li>you accept that any material or information provided by you may be posted in the web site for any other members or guests to read;</li>
					<li>all information provided by us in our web site are provided in good faith.  You accept that any information provided by us is general information and is not in the nature of advice.  We derive our information from sources which we believe to be accurate and up to date as at the date of publication and we reserve the right to update this information at any time;</li>
					<li>we do not make any representations or warranties that the information we provide in the website is reliable, accurate or complete or that your access to the web site will be uninterrupted, timely or secure.  You accept that we are not liable for any loss resulting from any action taken or reliance made by you on any information or material posted by us.  You agree to make your own inquiries and seek independent advice from relevant industry professionals before acting or relying on any information or material which appears in the website;</li>
					<li>you accept that we do not accept any liability for the accuracy or content of any material posted by other members of the web site.  We are not liable for any loss resulting from any action taken of reliance made by you on any information or material posted by another member;</li>
					<li>you accept that we do not accept any responsibility or liability for any information or material which you submit to the web site, nor do we accept any responsibility for any use or misuse which you or any other members or guests make of information or material which you submit to the website;</li>
					<li>you accept that we do not warrant that we will respond to questions or comments submitted by you to our website;</li>
				</ul>
			</div>
		<?php
	}
	else 
	{
		//Error 404
		include $root."/php/error404.php";
	}
?>