<?php 
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include "php/start.php";
include "php/components_display_functions.php";
$data = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);
if(isset($_POST['usernameField']) && isset($_POST['passwordField']) && $_POST['passwordField']!='' && $_POST['usernameField']!='' && $_POST['usernameField']!='Nickname or E-mail' && $_POST['passwordField']!="Password") {
	$result = $data -> query('SELECT COUNT(*) AS ofUsers FROM advertisers WHERE (UPPER(nickname) = UPPER("'.$_POST['usernameField'].'") OR UPPER(email) = UPPER("'.$_POST['usernameField'].'")) AND password = "'.md5($_POST['passwordField']).'"');
	$number = $result -> fetch_assoc();
	
	if($number['ofUsers']==1) {
		$advertiserRaw = $data -> query('SELECT * FROM advertisers INNER JOIN membership ON membership.user=advertisers.id WHERE (UPPER(nickname) = UPPER("'.$_POST['usernameField'].'") OR UPPER(email) = UPPER("'.$_POST['usernameField'].'")) AND password = "'.md5($_POST['passwordField']).'"');
		
		if($advertiser = $advertiserRaw -> fetch_array(MYSQLI_ASSOC)){

			if($advertiser['paid']==1 && strtotime($advertiser['expiry'])>strtotime(date("Y-m-d"))) {
				$_SESSION['loggedId'] = $advertiser['user'];
				$loggedIn = 1;
				
				if($advertiser['suspended']==1) {
					$suspendedNotice=1;
				}
			}
			else {
				$checkMembership = 1;
			}
		}else {
			$checkMembership = 1;
		}
	}
}
?>


<!DOCTYPE html>
<!-- START OF HTML -->

<html xmlns="http://www.w3.org/1999/xhtml" >
 <!-- xmlns:fb="http://ogp.me/ns/fb#" -->

<!-- ============================================================ HEAD ======================================================== -->

	<head>
								<?php
		$country_raw = $data -> query('SELECT * FROM countries WHERE id = '.$currentCountry);
		$country = $country_raw -> fetch_array(MYSQLI_ASSOC);
								?>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
						<?php
		
		if(isset($_GET['location']) && $_GET['location']!=0) {

			if(isset($_GET['city']) && $_GET['city']!=0) {
				$number = $data -> query('SELECT COUNT(*) AS ofInfo FROM locations_info WHERE location_id=0 AND city_id='.$_GET['city']);
				$inf = $data -> query('SELECT * FROM locations_info WHERE location_id=0 AND city_id='.$_GET['city']);
			
			}else {
				$number = $data -> query('SELECT COUNT(*) AS ofInfo FROM locations_info WHERE location_id='.$_GET['location'].' AND city_id=0');
				$inf = $data -> query('SELECT * FROM locations_info WHERE location_id='.$_GET['location'].' AND city_id=0');
			} 
		
		}else {
			$number = $data -> query('SELECT COUNT(*) AS ofInfo FROM locations_info WHERE location_id=0 AND city_id=0 AND country='.$country['id']);
			$inf = $data -> query('SELECT * FROM locations_info WHERE location_id=0 AND city_id=0 AND country='.$country['id']);
		}

		$info['description']='';
		$info['keywords']='';
		$info['title']='Escort Central - Escort Services in '.$country['name'].', Private Escorts & Escort Agencies';
		$info['h1']='';
		$info['text']='';
		$ng = $number -> fetch_assoc();

		if($ng['ofInfo']!=0) {
			$info = $inf -> fetch_array(MYSQLI_ASSOC);
		}

											?>
		<title><?php echo $info['title'];?></title>
		
		<meta name="description" content="<?php echo $info['description'];?>" />
		<meta name="keywords" content="<?php echo $info['keywords'];?>" />

		<script type="text/javascript">
				
			var server = "<?php echo $_SERVER['HTTP_HOST'];?>";
			var numberOfUsers = <?php 
			$numberR = $data -> query('SELECT COUNT(*) AS ofProfiles FROM advertisers');
			$number = $numberR -> fetch_assoc();
			$numberOfUsers = $number['ofProfiles'];
			echo $numberOfUsers;
						?>;
				
		</script>
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/start_scripts.js"></script>
		
		<link rel="stylesheet" media="screen" type="text/css" title="ECStyle" href="design/style.css" />
		<link rel="stylesheet" media="screen" type="text/css" title="ECStyle" href="design/stylesheet.css" />

		<!-- Google Font -->
		<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:400,300,700%7cOpen+Sans' rel='stylesheet' type='text/css'>

		<!-- Google Analytics --> 
		<script>
				(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
					(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
					m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
				})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

				ga('create', 'UA-51240646-1', 'escortcentral.com.au');
				ga('send', 'pageview');

		</script>
		<script>(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
			fjs.parentNode.insertBefore(js, fjs);}
			(document, 'script', 'facebook-jssdk'));
		</script>
		<script type="text/javascript">
			(function() {
				var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
				po.src = 'https://apis.google.com/js/plusone.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			})();
		</script>

	
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
		<meta name="format-detection" content="telephone=no">
		<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE">
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<link rel="icon" href="favicon.ico" type="image/x-icon">
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/responsive.css">
		<link rel="stylesheet" type="text/css" href="css/jquery.bxslider.css">

		<link href="solid/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css">
		<link href="solid/css/pricing-tables.css" rel="stylesheet" type="text/css">
		<link href="solid/tooltip/tooltip.css" rel="stylesheet" type="text/css">
		<script src="solid/tooltip/tooltip.js" type="text/javascript"></script>
		<script type="text/javascript">
		jQuery(function()
		{
		  jQuery('.pt-block').tooltip(
		  {
			selector: '.show-hint'
		  });
		});
		</script>
	</head>

<!-- ============================================================ BODY ======================================================== -->
	<body>
		<div id="fb-root"></div>
		
		
			
			<?php
			

		if(isset($suspendedNotice) && $suspendedNotice==1) {
			?>
			<script type="text/javascript">
			<!--
			alert('Your account is suspended, it is not visible to anyone using the site.');
					//-->
					</script>
					<?php
		}
			
		if(isset($reload) && $reload==1) {	
					?>
					<script type="text/javascript">
					<!--
					window.location = "http://<?php echo $_SERVER['HTTP_HOST'];?>";
					//-->
					</script>
					<?php
		}
					?>
		<div onclick="if(mouseOverPopup==0) { <?php if(!preg_match('#trident#', $_SERVER['HTTP_USER_AGENT'])) { echo 'hidePopup();'; } ?> }" id="popupDiv">
			<div onmouseout="mouseOverPopup=0;" onmouseover="mouseOverPopup=1;" id="popupWindow">
				<div id="popupWindowTopBar"><span id="popupWindowTitle"></span><img src="design/cross.png" id="closePopupWindow" alt="Close" onclick="hidePopup();" /></div>
				<div id="popupWindowContent"></div>
			</div>
		</div>


<!-- ============================================================ HEADER - hidden ======================================================== -->

			<!-- This section is hidden -->
			<header id="topBannerContent">

				<?php
				if(isset($_SESSION['loggedId'])) {
					?>
					<input type="button" value="Edit Profile" class="loginButtons" style="top: 15px;" onclick="showPopup('My Profile'); showProfile('profile');" />
					<input id="whiteLoginButton" type="button" value="Logout" class="loginButtons" style="top: 55px;" onclick="window.location='http://<?php echo $_SERVER['HTTP_HOST'];?>/?logout=1';" />
					<?php
				
				}else {
					?>
					<input type="button" value="Escort login" class="loginButtons" style="top: 15px;" onclick="loginPrompt('', '');" />
					<input id="whiteLoginButton" type="button" value="Advertise" class="loginButtons" style="top: 55px;" onclick="advertiseInfo();" />
					<?php
				}
					?>
			</header>

<!-- ============================================================ NAVIGATION ======================================================== -->

			<!-- LARGE SCREEN Navigation Section -->
			<nav id="topMenu">
				<div class="nav-logo">
					<img src="design/logo.png" alt="escortcentral logo">
				</div>
				<div id="topMenuContent">

					<!-- Check php/components_display_functions.php for the menu function -->
					<?php displayMenuItems($currentCountry, new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB)); ?>
				</div>
			</nav>


			<!-- MOBILE NAVIGATION -->
			<div class="resp-navigation">
				<a href="http://escortcentral.com.au/" id="home"></a>
				<a href="#" id="pull"></a>
				<div id="main-nav">		
					<ul>
						<li class="dropdown">
							<a href="#" data-toggle="dropdown">NSW ESCORTS<i class="icon-arrow"></i></a>
							<ul class="dropdown-menu">
								<li><a href="http://escortcentral.com.au/?location=1">ALL NSW ESCORTS</a></li>
								<li><a href="http://escortcentral.com.au/?location=1&amp;city=1">SYDNEY</a></li>
								<li><a href="http://escortcentral.com.au/?location=1&amp;city=2">KINGS CROSS</a></li>
								<li><a href="http://escortcentral.com.au/?location=1&amp;city=3">NORTH SYDNEY</a></li>
								<li><a href="http://escortcentral.com.au/?location=1&amp;city=4">PARRAMATTA</a></li>
								<li><a href="http://escortcentral.com.au/?location=1&amp;city=5">NEWCASTLE</a></li>
								<li><a href="http://escortcentral.com.au/?location=1&amp;city=6">CENTRAL COAST</a></li>
								<li><a href="http://escortcentral.com.au/?location=1&amp;city=7">FORSTER</a></li>
								<li><a href="http://escortcentral.com.au/?location=1&amp;city=8">WOLLOGONG</a></li>
								<li><a href="http://escortcentral.com.au/?location=1&amp;city=9">BYRON BAY</a></li>

							</ul>
						</li>


						<li class="dropdown">
							<a href="#" data-toggle="dropdown">QLD ESCORTS<i class="icon-arrow"></i></a>
							<ul class="dropdown-menu">
								<li><a href="http://escortcentral.com.au/?location=2">ALL NSW ESCORTS</a></li>
								<li><a href="http://escortcentral.com.au/?location=2&amp;city=10">BRISBANE</a></li>
								<li><a href="http://escortcentral.com.au/?location=2&amp;city=11">GOLD COAST</a></li>
								<li><a href="http://escortcentral.com.au/?location=2&amp;city=12">SUNSHNE COAST</a></li>
								<li><a href="http://escortcentral.com.au/?location=2&amp;city=13">IPSWITCH</a></li>
								<li><a href="http://escortcentral.com.au/?location=2&amp;city=14">TOOWOOMBA</a></li>
								<li><a href="http://escortcentral.com.au/?location=2&amp;city=15">GLADSTONE</a></li>
								<li><a href="http://escortcentral.com.au/?location=2&amp;city=16">ROCKHAMPTON</a></li>
								<li><a href="http://escortcentral.com.au/?location=2&amp;city=17">MACKAY</a></li>
								<li><a href="http://escortcentral.com.au/?location=2&amp;city=18">TOWNSLVILLE</a></li>
								<li><a href="http://escortcentral.com.au/?location=2&amp;city=19">CAIRNS</a></li> 
								<li><a href="http://escortcentral.com.au/?location=2&amp;city=20">MOUNT ISSA</a></li>                  
							</ul>
						</li>



						<li class="dropdown">
							<a href="#" data-toggle="dropdown">VIC ESCORTS<i class="icon-arrow"></i></a>
							<ul class="dropdown-menu">
								<li><a href="http://escortcentral.com.au/?location=3">ALL VIC ESCORTS</a></li>
								<li><a href="http://escortcentral.com.au/?location=3&amp;city=21">MELBOURNE</a></li>
								<li><a href="http://escortcentral.com.au/?location=3&amp;city=22">ST KILDA</a></li>
								<li><a href="http://escortcentral.com.au/?location=3&amp;city=23">GEELONG</a></li>
								<li><a href="http://escortcentral.com.au/?location=3&amp;city=24">BALLARAT</a></li>
							</ul>
						</li>


						<li class="dropdown">
							<a href="#" data-toggle="dropdown">SA ESCORTS<i class="icon-arrow"></i></a>
							<ul class="dropdown-menu">
								<li><a href="http://escortcentral.com.au/?location=4">ALL SA ESCORTS</a></li>
								<li><a href="http://escortcentral.com.au/?location=4&amp;city=25">ADELAIDE</a></li>
								<li><a href="http://escortcentral.com.au/?location=4&amp;city=26">GLENELG</a></li>
								<li><a href="http://escortcentral.com.au/?location=4&amp;city=27">FLAGSTAFF</a></li>
							</ul>
						</li>



						<li class="dropdown">
							<a href="#" data-toggle="dropdown">WA ESCORTS<i class="icon-arrow"></i></a>
							<ul class="dropdown-menu">
								<li><a href="http://escortcentral.com.au/?location=5">ALL WA ESCORTS</a></li>
								<li><a href="http://escortcentral.com.au/?location=5&amp;city=29">MANDURAH</a></li>
								<li><a href="http://escortcentral.com.au/?location=5&amp;city=30">ROCKINGHAM</a></li>
								<li><a href="http://escortcentral.com.au/?location=5&amp;city=31">BUNBURY</a></li>
								<li><a href="http://escortcentral.com.au/?location=5&amp;city=32">JOONDALUP</a></li>
								<li><a href="http://escortcentral.com.au/?location=5&amp;city=33">FREMANTLE</a></li>
								<li><a href="http://escortcentral.com.au/?location=5&amp;city=34">KALGOORLIE</a></li>
								<li><a href="http://escortcentral.com.au/?location=5&amp;city=35">BROOME</a></li>
								<li><a href="http://escortcentral.com.au/?location=5&amp;city=36">PORT HEDLAND</a></li>
							</ul>
						</li>

						<li class="dropdown">
							<a href="#" data-toggle="dropdown">TAS ESCORTS<i class="icon-arrow"></i></a>
							<ul class="dropdown-menu">
								<li><a href="http://escortcentral.com.au/?location=6">ALL TAS ESCORTS</a></li>
								<li><a href="http://escortcentral.com.au/?location=6&amp;city=37">HOBART</a></li>
								<li><a href="http://escortcentral.com.au/?location=6&amp;city=38">LAUNCESTON</a></li>
							</ul>
						</li>


						<li class="dropdown">
							<a href="#" data-toggle="dropdown">ACT ESCORTS<i class="icon-arrow"></i></a>
							<ul class="dropdown-menu">
								<li><a href="http://escortcentral.com.au/?location=7">ALL ACT ESCORTS</a></li>
								<li><a href="http://escortcentral.com.au/?location=7&amp;city=39">CANBERRA</a></li>
							</ul>
						</li>


						<li class="dropdown">
							<a href="#" data-toggle="dropdown">NT ESCORTS<i class="icon-arrow"></i></a>
							<ul class="dropdown-menu">
								<li><a href="http://escortcentral.com.au/?location=8">ALL NT ESCORTS</a></li>
								<li><a href="http://escortcentral.com.au/?location=8&amp;city=40">DARWIN</a></li>
								<li><a href="http://escortcentral.com.au/?location=8&amp;city=41">KATHERINE</a></li>
								<li><a href="http://escortcentral.com.au/?location=8&amp;city=42">ALICE SPRINGS</a></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>


<!-- ============================================================ BANNER IMAGE ======================================================== -->

			<!-- BANNER IMAGE -->
			<section class="searchSec" id="wws" <?php if(isset($_GET['search'])||(isset($_GET['location']) && $_GET['location']!='')||(isset($_GET['city']) && $_GET['city']!='')||(isset($_GET['id']) && !preg_match("#[^0-9]#", $_GET['id']))||(isset($_GET['advertise']) && $_GET['advertise']==1)) { ?>style="display: none;"<?php } ?> >
				
				<h1 style="display:none;">Search</h1>

			</section>

<!-- ========================================== SEARCH BAR & FEATURED IMAGE TICKER ======================================================== -->


			<!-- BODY CONTENT BELOW THE BANNER IMAGE -->
			<section class="imageTickerSec" id="bodyContent">

				<h1 style="display:none;">Featured Images</h1>
				
				<!-- SEARCH BAR -->
				<!-- 
				display the search bar at the top of the home page, & hide it on all other pages
				this function can be found in "components_display_functions.php "
				-->

				<?php 

				$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];


				if (strpos($url,'?') !== false) {
					// dont show the search bar
				   
				} else {
					// show the search bar 
					showSearchDiv(new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB), $currentCountry); 

				    
				}



				// if($_SERVER['SERVER_NAME']){
				
				// }elseif($_SERVER['SERVER_NAME'].'?location'){
				// 	echo '';
				// }
				?>
				<ul class="bxslider">
				<!-- 
				IMAGE TICKER FOR FEATURED IMAGES 

				display the image ticker on the home page 
				this function for retrieving the images can be found in "components_display_functions.php "
				Source: http://bxslider.com/examples/ticker
				-->
					<?php	

					imageTicker($currentCountry, new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB)); 
					?>
				</ul>


			</section>

<!-- ========================================== ESCORT PROFILE ======================================================== -->

			<section class="escortProfileSec">

				<!-- 
				ORIGINAL FEATURED LISTINGS 
				
				to display the featured listings, un-comment the function below
				the function is in "components_display_functions.php"
				-->
				<?php //displayFeatured($country, $QL); ?>

				



				<!-- 
				ESCORT PROFILE PAGE 

				1. if there is $_GET['id'] - query the database, gather all the info to build the profile page
				2. Display all the escorts images (div.left-images)
				3. Display all escort info (div.right-text)
					- If the advertiser belongs to an agency, display the agency contact details
					- If the advertiser is independent of an agency, display their personal contact details
				4. Advertisers Name & Details (Availability, Rates etc.)
				-->

				<?php 

				// ============================== 1. ==================================
				if(isset($_GET['id']) && !preg_match("#[^0-9]#", $_GET['id'])) {
					?>
					<div>
						<?php
						$result = $data -> query('SELECT COUNT(*) AS ofAdvertisers FROM advertisers WHERE id='.$_GET['id']);
						$number = $result -> fetch_assoc();
						
						if($number['ofAdvertisers']==1) {
							$advertiser_raw = $data -> query('SELECT * FROM advertisers WHERE id='.$_GET['id']);
							$advertiser = $advertiser_raw -> fetch_array(MYSQLI_ASSOC);
							$imageRaw = $data -> query('SELECT * FROM images WHERE user = '.$advertiser['id'].' AND main = 1');
							$regions_raw = $data -> query('SELECT * FROM regions WHERE id= '.$advertiser['region']);
							$region = $regions_raw -> fetch_array(MYSQLI_ASSOC);
							$service_raw = $data -> query('SELECT * FROM advertisers_services INNER JOIN services ON services.id=advertisers_services.service WHERE advertiser = '.$advertiser['id']);
							
							if($advertiser['city']!='' && $advertiser['city']!=0) {
								$cities_raw = $data -> query('SELECT * FROM cities WHERE id= '.$advertiser['city']);
								$city = $cities_raw -> fetch_array(MYSQLI_ASSOC);
							}
				

							// ================================ 2. ================================
							?>


							
							<div id="escortProfilePage">

								<div class="left-images">

									<div class="noImageOnProfile">
										<div id="displayedImage" style="width: 100%; height: 100%; <?php if($image = $imageRaw -> fetch_array(MYSQLI_ASSOC)) { ?>background-position: center; background-image: url('images/<?php echo $image['file'];?>'); background-repeat: no-repeat; background-size: <?php if($image['width']>$image['height']) { ?>auto 100%<?php } else { ?>100% auto<?php } ?>;<?php } ?>" >
										</div>
									</div>
									<?php 
									$imagesRaw = $data -> query('SELECT * FROM images WHERE user = '.$advertiser['id'].' AND main = 0 AND banner = 0');
									$i = 0;
									
									while($currentImage=$imagesRaw -> fetch_array(MYSQLI_ASSOC)) {
										?>
										<div id="image<?php echo $i;?>" style="cursor: pointer; float: left; width: 90px; margin-right: 15px; margin-top: 15px; height: 90px; background-color: black; background-position: center; background-image: url('images/<?php echo $currentImage['file'];?>'); background-repeat: no-repeat; background-size: <?php if($currentImage['width']>$currentImage['height']) { ?>auto 100%<?php } else { ?>100% auto<?php } ?>;" onclick="var tempBgSize = document.getElementById('displayedImage').style.backgroundSize; var tempBgImage = document.getElementById('displayedImage').style.backgroundImage; document.getElementById('displayedImage').style.backgroundSize = this.style.backgroundSize; document.getElementById('displayedImage').style.backgroundImage = this.style.backgroundImage; this.style.backgroundSize = tempBgSize; this.style.backgroundImage = tempBgImage;"></div>
										<?php
										$i++;
									}
										?>
								</div>


								<!--   ================================ 3. ================================-->
								<div class="right-text">
									<?php
									$agency = 0;
									$child = 0;

									// if the advertiser is from an agency
									if($advertiser['parent']!=0) {
											$child = 1;
											$agency = 1;

											// query the database for the Agency's info
											$agencyInfRaw = $data -> query('SELECT * FROM advertisers WHERE id = '.$advertiser['parent']);
											$parentInf = $agencyInfRaw -> fetch_array(MYSQLI_ASSOC);
											$advertiser['email'] = $parentInf['email'];
											$advertiser['website'] = $parentInf['website'];
											$advertiser['facebook'] = $parentInf['facebook'];
											$advertiser['twitter'] = $parentInf['twitter'];
											$advertiser['phone'] = $parentInf['phone'];
											$imagesRaw = $data -> query('SELECT * FROM images WHERE user = '.$advertiser['parent'].' AND banner=1');
											
											// if there is an agency banner image, display it here
										if($image = $imagesRaw -> fetch_array(MYSQLI_ASSOC)) {
												?>
												<img style="width: 100%;" src="http://<?php echo $_SERVER['HTTP_HOST'];?>/images/<?php echo $image['file'];?>" alt="Agency banner" />

												<?php
										}
									}else {
										// if the advertiser is a private escort
										// query the database for the the details
									
										$result = $data -> query('SELECT COUNT(*) AS ofAgencies FROM membership WHERE user = '.$advertiser['id'].' AND UPPER(type)="AGENCY"');
										$number = $result -> fetch_assoc();
											
										if($number['ofAgencies']==1) {
											$agency = 1;
											$imagesRaw = $data -> query('SELECT * FROM images WHERE user = '.$advertiser['id'].' AND banner=1');
												
											if($image = $imagesRaw -> fetch_array(MYSQLI_ASSOC)) {
													?>
												<img style="width: 100%;" src="http://<?php echo $_SERVER['HTTP_HOST'];?>/images/<?php echo $image['file'];?>" alt="Agency banner" />
													<?php
											}
										}
									}
									?>
								

									<?php

				


								function showContInf($fb, $tt, $ws, $ph, $em, $ag, $ch)
								{										?>
									<div style="margin-top: 10px;" class="profile-contact">
										<h3>Contact 
										<?php if($ag==0) { ?>Me<?php } else if($ch==1) { ?>My Agency<?php } ?>
										</h3>
										<div>  <!-- style="float: left; width: 220px; height: 100px; font-size: 12px; line-height: 20px; margin-right: 10px;" -->
											E-mail: <?php echo $em;?><br />
											<?php if($ph!='') { ?>Phone: <?php echo $ph;?><br /><?php } ?>
											<?php if($ws!='') { ?>Website: <a href="<?php if(!preg_match("#^https?://.+#", $ws)) { echo "http://"; } echo $ws;?>" target="_blank"><?php echo $ws;?></a><?php } ?>
										</div>
										<div style="height: 100px; font-size: 12px; line-height: 20px;">
											<?php if($fb!='') { ?><a href="<?php if(!preg_match("#^https?://.+#", $fb)) { echo "http://"; } echo $fb;?>" target="_blank"><img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/design/facebook.png" style="height: 20px;" alt="Facebook" /></a><br /><?php } ?>
											<?php if($tt!='') { ?><a href="<?php if(!preg_match("#^https?://.+#", $tt)) { echo "http://"; } echo $tt;?>" target="_blank"><img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/design/twitter.png" style="height: 20px;" alt="Twitter" /></a><?php } ?>
										</div>
									</div>
																			<?php
								}?>
						

								

						 		<!--  ================================ 4. ================================ 

								display the advertisers nickname
								independent escorts: show all personal specifications - age, height, bust-size, dress-size etc.

								 -->

								<!-- display the advertisers nickname -->
								<span style="font-weight: bold; font-size: 20px;">
										<?php
									echo $advertiser['nickname'];
										?>
								</span>
								<br /><br />
									
									<?php 

								// if it is not and agency
								if(!($agency==1 && $child==0)) {
										?>
									<div id="profileInfoTable">
										<div class="profile-heading">	
											<p>Location:</p>
											<p class="right">
													<?php 
												if($advertiser['city']!='' && $advertiser['city']!=0) { 
													echo $city['name'].', '; 
												} 

												echo $region['name'];?>
											</p>
										</div>

										

										<div class="profile-heading"<?php if($advertiser['nationality']=='') { ?> style="display: none;"<?php } ?>>
											<p>Nationality:</p>
											<p class="right">
													<?php
												echo $advertiser['nationality'];
													?>
											</p>
										</div>

										<div class="profile-heading"<?php if($advertiser['ethnicity']=='') { ?> style="display: none;"<?php } ?>>
											<p>Ethnicity:</p>
											<p class="right">
													<?php
												echo $advertiser['ethnicity'];
													?>
											</p>
										</div>
											
										<div class="profile-heading">
											<p> Gender: </p>
											<p class="right">
													<?php
												echo $advertiser['gender'];
													?>
											</p>
										</div>
											<?php
											
											$nos = $data -> query('SELECT COUNT(*) AS ofServices FROM advertisers_services INNER JOIN services ON services.id=advertisers_services.service WHERE advertiser = '.$advertiser['id']);
											$numberOS = $nos -> fetch_assoc();
											?>

										<div class="profile-heading" <?php if($numberOS['ofServices']==0) { ?> style="display: none;"<?php } ?>>
											<p> Services: </p>
											<p class="right">
													<?php
												while($service = $service_raw -> fetch_array(MYSQLI_ASSOC)) {
													echo $service['name'].'<br />';
												}
													?>
											</p>
										</div>
										
										<div class="profile-heading">
											<p>Age: </p>
											<p class="right">
													<?php
												$birthDate = new DateTime($advertiser['birthDate']." 00:00:00");
													
												if(date("m")>($birthDate->format("m")) || (date("m")==($birthDate->format("m")) && date("d")>($birthDate->format("d")))) {
													$age = date("Y") - ($birthDate->format("Y"));
												}else {
													$age = date("Y") - ($birthDate->format("Y")) - 1;
												}
													
												echo $age;
													?>
											</p>
										</div>
											
										<div class="profile-heading"<?php if($advertiser['eyeColour']=='') { ?> style="display: none;"<?php } ?>>
											<p>Eye colour: </p>
											<p class="right">
													<?php
												echo $advertiser['eyeColour'];
													?>
											</p>
										</div>

										<div class="profile-heading"<?php if($advertiser['hair']=='') { ?> style="display: none;"<?php } ?>>
											<p>Hair: </p>
											<p class="right">
													<?php
												echo $advertiser['hair'];
													?>
											</p>
										</div>
										
										<div class="profile-heading"<?php if($advertiser['height']==''||$advertiser['height']==0) { ?> style="display: none;"<?php } ?>>
											<p>Height: </p>
											<p class="right">
													<?php
												echo $advertiser['height'].' cm';
													?>
											</p>
										</div>
											
										<div class="profile-heading"<?php if($advertiser['bodyType']=='') { ?> style="display: none;"<?php } ?>>
											<p>Body type: </p>
											<p class="right">
													<?php
												echo $advertiser['bodyType'];
													?>
											</p>
										</div>

										<div class="profile-heading"<?php if($advertiser['dressSize']==''||($advertiser['dressSize']==0&&$advertiser['dressSize']!='Zero')) { ?> style="display: none;"<?php } ?>>
											<p><?php if($advertiser['gender']=='male') { echo "S"; } else { echo "Dress s"; } ?>ize (US): </p>
											<p class="right">
													<?php
												echo $advertiser['dressSize'];
													?>
											</p>
										</div>
										
										<div class="profile-heading"<?php if($advertiser['bustSize']=='') { ?> style="display: none;"<?php } ?>>
											<p>Bust size: </p>
											<p class="right">
													<?php
												if($advertiser['gender']!="male") {
													echo $advertiser['bustSize'];
												}else {
													echo "NA";
												}
													?>
											</p>
										</div>

										<div class="profile-heading">
											<p>Shaved: </p>
											<p class="right">
													<?php
												if($advertiser['shaved']==1) {
													echo "yes";
												}else {
													echo "no";
												}
													?>
											</p>
										</div>
											
										<div class="profile-heading">
											<p>Smoke: </p>
											<p class="right">
													<?php
												if($advertiser['smoke']==1) {
													
													echo "yes";
												}else {
														
													echo "no";
												}
													?>
											</p>
										</div>

										<div class="profile-heading">
											<p>Disable friendly: </p>
											<p class="right">
													<?php
												if($advertiser['disableFriendly']==1) {
													echo "yes";
												}else {
													echo "no";
												}
													?>
											</p>
										</div>
									</div>

										<?php
								}
										?>

								

								<div id="workTable">
										<?php
									$result = $data -> query('SELECT COUNT(*) AS available FROM advertisers_availability WHERE advertiser = '.$advertiser['id']);
									$number = $result -> fetch_assoc();
										?>
									<div class="left-work">
										
											
													<?php
												if($number['available']!=0) {
													echo "<h3>Availability</h3>";
												}
											
													?>
										
										<p>
												<?php
												
											if($number['available']!=0) {
													
												$availability_raw = $data -> query('SELECT * FROM advertisers_availability WHERE advertiser='.$advertiser['id'].' ORDER BY day');
												
												while($available = $availability_raw -> fetch_array(MYSQLI_ASSOC)) {
														$days=['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
														echo '<span>'.$days[$available['day']].'</span> '.$available['fromTime'].' - '.$available['toTime'].'<br/>';
												}
											}			?>
										</p>
									</div>

									<div class="right-work">

										
													<?php
											$result = $data -> query('SELECT COUNT(*) AS rates FROM rates WHERE user = '.$advertiser['id']);
											$numbers = $result -> fetch_assoc();
										
											if($numbers['rates']!=0) {
												echo "<h3>Rates</h3>";
											}
													?>
										
										<p>
											


												<?php
											$rates_raw = $data -> query('SELECT * FROM rates WHERE user='.$advertiser['id'].' ORDER BY price');
											
											while($rate = $rates_raw -> fetch_array(MYSQLI_ASSOC)) {
												
												if($rate['hours']==0) {
													echo '$'.sprintf('%0.2f', $rate['price']).' for '.$rate['minutes'].' min<br />';
												
												}else if($rate['minutes']==0) {
													echo '$'.sprintf('%0.2f', $rate['price']).' for '.$rate['hours'].' hour(s)<br />';
												
												}else if($rate['minutes']!=0) {
													echo '$'.sprintf('%0.2f', $rate['price']).' for '.$rate['hours'].' hour(s) '.$rate['minutes'].' min<br />';
												}
											}
													?>
										</p>
									</div>



										<?php 
									if(preg_match("#[^ \n]#", $advertiser['description'])) {
											?>
										<div class="about-me">
										
										
											
											<h3>About <?php if($agency==0||$child==1) { ?>Me<?php } ?></h3>
											<p style="font-size: 12px;"><?php echo preg_replace("#\n#", "", htmlspecialchars($advertiser['description']));?></p>
										</div>
											<?php
									}
										?>
										<?php
									if($agency==0) {
										showContInf($advertiser['facebook'], $advertiser['twitter'], $advertiser['website'], $advertiser['phone'], $advertiser['email'], $agency, $child);
									}
										?>
										<?php
								// If its an agency, get the agencies social details
								if($agency==1) {
									showContInf($advertiser['facebook'], $advertiser['twitter'], $advertiser['website'], $advertiser['phone'], $advertiser['email'], $agency, $child);
								}
									?>
								</div> <!-- end of work-table -->

							</div> <!-- end of right-text -->
							</div> <!-- END OF escortProfilePage -->
								<?php
						
						}else {
								?>

							<div id="escortProfilePage">
								Sorry, the profile you are trying to reach was not found.<br />
								It has either never existed or been deleted.
								<br /><br />
								<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/">Go to Home Page</a>
							</div>
								<?php
						}
							?>
						<!-- End escort profile page -->



						<!-- ================================ Membership ===================================== -->

						<?php
						if(isset($number['ofAdvertisers']) && $number['ofAdvertisers']!=1) {
								?>
							<div style="vertical-align: top;">
								<?php
								showSearchDiv(new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB), $currentCountry);
								?>
							</div>
							<?php
						}
									?>
					</div> <!-- END OF number of advertisers -->
								<?php
				
				}else if(isset($_GET['advertise']) && $_GET['advertise']==1) {
					
					if(isset($_GET['freeM']) && $_GET['freeM']==1 && $numberOfUsers<100) {
								?>
						<script type="text/javascript">
						
							var freeGrant = 1;
						
						</script>
							<?php
					}
							?>


					


<?php
// ========================================================================================================================================
// This is the new 'Pricing Plan Template'

?>
<!-- <h4 style="color: #333333; text-align: center;">I like this one</h4> -->
	<!-- Start: solid skin - default color -->
	<div class="solid-pt">
	  	<div class="pt-cols pt-cols-3">
			<div class="pt-col">
		  		<div class="pt-block">
					<div class="pt-back">
					</div>
					<div class="pt-head">
					  <div class="pt-title">Basic</div>
					  <div class="pt-sub-title">classic plan</div>
					</div>
					<div class="pt-price-block">
					  <span class="pt-currency"></span>
					  <span class="pt-price-main">Free</span>
					  <span class="pt-price-rest"></span>
					</div>
					<div class="pt-sub-text">listing</div>
					<ul class="pt-list">
					  <li><i class="fa fa-check"></i> 1 Profile</li>
					  <li><i class="fa fa-check"></i> 8 Photo's</li>
					  <!-- <li><i class="fa fa-database"></i></li>
					  <li><i class="fa fa-globe"></i></li> -->
					</ul>
					<div class="pt-footer">
					  <input class="pt-btn" style="text-align: center;" value="<?php if(isset($_GET['freeM']) && $_GET['freeM']==1 && $numberOfUsers<100) { echo 'Signup for free!'; } else { echo 'Choose Plan'; } ?>" onclick="register('Gold');">
					</div>
		  		</div>
			</div>
			<div class="pt-col">
		  		<div class="pt-block pt-selected">
					<div class="pt-back">
					  	<div class="pt-badge pt-popular">
							<span>most popular</span>
					  	</div>
					</div>
					<div class="pt-head">
					  	<div class="pt-title">Featured</div>
					  	<div class="pt-sub-title">for private escorts</div>
					</div>
					<div class="pt-price-block">
					  <span class="pt-currency">$</span>
					  <span class="pt-price-main">9</span>
					  <span class="pt-price-rest">99</span>
					</div>
					<div class="pt-sub-text">per month</div>
					<ul class="pt-list">
					  <li><i class="fa fa-check"></i> 1 Profile<i class="fa fa-info-circle show-hint" title="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam convallis porttitor mattis. Fusce fermentum lorem eget nulla consectetur iaculis. Nam luctus sapien tempus eleifend congue. Aliquam eu viverra nunc."></i></li>
					  <li><i class="fa fa-check"></i> 12 Photo's</li>
					  
					  <li><i class="fa fa-check"></i> A Link to your Website</li>
					  <li><i class=""></i></li>
					</ul>
					<div class="pt-footer">
					  <input class="pt-btn" style="text-align: center;" value="<?php if(isset($_GET['freeM']) && $_GET['freeM']==1 && $numberOfUsers<100) { echo 'Signup for free!'; } else { echo 'Choose Plan'; } ?>" onclick="register('Platinum');">
					</div>
		  		</div>
			</div>
			<div class="pt-col">
		  		<div class="pt-block">
					<div class="pt-back">
					  <div class="pt-badge pt-discount">
						<span>20 listings</span>
					  </div>
					</div>
					<div class="pt-head">
					  <div class="pt-title">Agency</div>
					  <div class="pt-sub-title">the best choice</div>
					</div>
					<div class="pt-price-block">
					  <span class="pt-currency">$</span>
					  <span class="pt-price-main">59</span>
					  <span class="pt-price-rest">99</span>
					</div>
					<div class="pt-sub-text">per month</div>
					<ul class="pt-list">
					  <li><i class="fa fa-check"></i> 20 Profile's<i class="fa fa-info-circle show-hint" title="Add up to 20 Escort Profiles.  Each Profile will have the Escort's description & Photo's.  All Profile's will have agency contact details"></i></li>
					  <li><i class="fa fa-check"></i> 12 Photo's per Profile</li>
					  <li><i class="fa fa-check"></i> A Link to Agency's Website<i class="fa fa-info-circle show-hint" title="A link to your website will help your website's search engine ranking"></i></li>
					  
					  <li><i class="fa fa-check"></i> Banner Image On each Profile</li>
					  <li><i class="fa fa-check"></i> Feature Listing's<i class="fa fa-info-circle show-hint" title="All profile's will be Featured at the top of every page"></i></li>
					  <li><i class="fa fa-check"></i> Agency Logo</li>
					</ul>
					<div class="pt-footer">
					  <input class="pt-btn" style="text-align: center;" value="<?php if(isset($_GET['freeM']) && $_GET['freeM']==1 && $numberOfUsers<100) { echo 'Signup for free!'; } else { echo 'Choose Plan'; } ?>" onclick="register('Agency');">
					</div>
		  		</div>
			</div>
	  	</div>
	</div>
	<!-- End: solid skin - default color -->
<?php
// ========================================================================================================================================
				
				}else if(isset($_GET['search'])) {
					// if there is 'get data' for the search form
					
							?>
					<div class="clear"></div>

					<!-- MAIN CONTENT ON HOME PAGE -->

					<div class="main-container">

						<!-- LEFT SECTION OF THE MAIN-CONTAINER -->
						<div class="left-container">
														<?php
							if($info['h1']!='') {
														?>
								<h1 id="mainH1"><?php echo $info['h1'];?></h1>

													<?php
							}
								
							if($info['text']!='') {
														?>
								<p id="mainDesc"><?php echo $info['text'];?></p>


									<!-- SEARCH BAR -->
									<?php
							}




								// ==========================================================================
								// SEARCH BAR
								// if any of the options on the search bar have been chosen
								// Show the results



							$city = 0;
							$location = 0;
							$services = 0;
							$gender = '';
							$postCode = '';


							if(isset($_GET['services'])) {
								$services = $_GET['services'];
							}
							if(isset($_GET['gender'])) {
								$gender = $_GET['gender'];
							}
							if(isset($_GET['postCode'])) {
								$postCode = $_GET['postCode'];
							}
							if(isset($_GET['location'])) {
								$location = $_GET['location'];
									
								if(isset($_GET['city'])) {
									$city = $_GET['city'];
								}
							}
							if(isset($_GET['page']) && !(preg_match("#[^0-9]#", $_GET['page'])) && $_GET['page']!=0 && $_GET['page']!='') {
								$page = $_GET['page'];
								
							}else {
								$page = 1;
							}

							showResults($location, $city, $_GET['search'], $services, $gender, $postCode, $currentCountry, $page, new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB));
								?>

						</div> <!-- END OF left-container -->


						<div class="right-container">
							<?php showSearchDiv(new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB), $currentCountry); ?>

						</div>
					</div>

						<?php
				}else if(isset($_GET['location'])) {
						?>
					<div class="main-container">
						<div class="left-container">
								<?php
							if($info['h1']!='') {
									?>
								<h1 id="mainH1"><?php echo $info['h1'];?></h1>
									<?php
							}
							
							if($info['text']!='') {
									?>
							 	<p id="mainDesc"><?php echo $info['text'];?></p>
									<?php
							}
								?>
								<?php
							$city = 0;
							if(isset($_GET['city'])) {
								$city = $_GET['city'];
							}
							
							if(isset($_GET['page']) && !(preg_match("#[^0-9]#", $_GET['page'])) && $_GET['page']!=0 && $_GET['page']!='') {
								$page = $_GET['page'];
							
							}else {
								$page = 1;
							}
							
							showResults($_GET['location'], $city, '', 0, '', '', $currentCountry, $page, new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB));
								?>
						</div>


							

						<div class="right-container">
								<?php
							showSearchDiv(new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB), $currentCountry);
								?>
						</div>
					</div>

						<?php
				}else {
						?>
					<div class="main-container">
						<div class="left-container">

								<?php
							if($info['h1']!='') {
									?>
								<h1 id="mainH1"><?php echo $info['h1'];?></h1>
									<?php
							}
							
							if($info['text']!='') {
									?>
								<p id="mainDesc"><?php echo $info['text'];?></p>
									<?php
							}
								
								// SHOW THE FEATURED ESCORTS ON THE HOME PAGE
								displayFeatured($currentCountry, new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB));
								?>
						</div>

						<div class="right-container">
							<a href="http://escortcentral.com.au/live-chat.php" class="liveChat">Live Chat</a>


							<br>

							<!-- ================================== ADVERTISEMENTS - SIDEBAR ================================== -->



							<!-- <iframe class="frame" src="http://promo.awempire.com/iframes/?t_id=template1006&psid=EscortCentral&psprogram=pps&pstool=203_7&site=jasmin&cobrand_site_id=&template=iframe_big&skin=wg&flags=4&column=2&row=2&campaign_id=&category=couple&subaffid={SUBAFFID}" scrolling="no" align="MIDDLE" width="300" height="250" frameborder="No" allowtransparency="true" background-color="transparent" marginHeight="0" marginWidth="0"></iframe> -->
							<iframe class="npt-hypnotic-frame" src="http://promo.awempire.com/hypnotic/?site=lsa&amp;superCategory=girls&amp;cobrandId=&amp;psId=EscortCentral&amp;psTool=210_12&amp;psProgram=pps&amp;campaignId=&amp;category=mature&amp;performerName=&amp;pageName=random&amp;type=12&amp;banner=9&amp;animate=true&amp;animateDirection=0&amp;animateDuration=2&amp;depthScale=1&amp;depthBlurSize=20&amp;depthFocus=0.5&amp;subAffId={SUBAFFID}" width="300" height="480" style="background-color:transparent;"></iframe>
							<br>
							<iframe class="npt-hypnotic-frame" src="http://promo.awempire.com/hypnotic/?site=lsa&amp;superCategory=girls&amp;cobrandId=&amp;psId=EscortCentral&amp;psTool=210_4&amp;psProgram=pps&amp;campaignId=&amp;category=girl&amp;performerName=&amp;pageName=random&amp;type=4&amp;banner=1&amp;animate=true&amp;animateDirection=horizontal&amp;animateDuration=2.5&amp;depthScale=1.6&amp;depthBlurSize=20&amp;depthFocus=0.8&amp;subAffId={SUBAFFID}" width="300" height="540" style="background-color:transparent;"></iframe>












							<!-- ====================================================================================================== -->

						</div> <!-- end right-container -->







							<!--
							<div class="social-icons">
							<div style="position: absolute; right: 0; top: 5px;">
									<div class="fb-like" data-href="https://www.facebook.com/pages/Escort-Central/715298868509962" data-width="60" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false" style="position: relative; top: -4px;"></div>
									<g:plusone size="medium" href="https://plus.google.com/u/0/b/113482270280079006603/113482270280079006603/p"></g:plusone>
									<a href="https://twitter.com/escortcentral" class="twitter-follow-button" data-show-count="true" data-show-screen-name="false" data-lang="en">Follow @escortcentral</a>
									<script type="text/javascript">!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
								</div>
							
							-->

					</div> <!-- closing main-container -->

				 	<!--</div> closing body-content -->
				
					
					<!-- Advertisement -->

					<!-- <iframe class="frame" src="http://promo.awempire.com/iframes/?t_id=template1000&psid=EscortCentral&psprogram=pps&pstool=203_1&site=jasmin&cobrand_site_id=&template=iframe_big&skin=wg&flags=1&column=2&row=2&campaign_id=&category=girl&subaffid={SUBAFFID}" scrolling="no" align="MIDDLE" width="300" height="250" frameborder="No" allowtransparency="true" background-color="transparent" marginHeight="0" marginWidth="0"></iframe>
					<iframe class="frame" src="http://promo.awempire.com/iframes/?t_id=template1006&psid=EscortCentral&psprogram=pps&pstool=203_7&site=jasmin&cobrand_site_id=&template=iframe_big&skin=wg&flags=4&column=2&row=2&campaign_id=&category=couple&subaffid={SUBAFFID}" scrolling="no" align="MIDDLE" width="300" height="250" frameborder="No" allowtransparency="true" background-color="transparent" marginHeight="0" marginWidth="0"></iframe>
					-->



											<?php
				}



				if(isset($_GET['confirm']) && isset($_GET['id']) && $_GET['id']!='') {
					$result = $data -> query('SELECT COUNT(*) AS ofUsers FROM advertisers  WHERE id='.$_GET['confirm'].' AND password="'.$_GET['id'].'" AND parent=0');
					$number = $result -> fetch_assoc();
					$user_raw = $data -> query('SELECT * FROM advertisers INNER JOIN membership ON membership.user=advertisers.id WHERE advertisers.id='.$_GET['confirm'].' AND password="'.$_GET['id'].'" AND parent=0 AND expiry+0 >CURDATE()+0');
					
					if($user=$user_raw -> fetch_array(MYSQLI_ASSOC)) {
						
						if($user['paid']==1) {
							?>
							<script type="text/javascript">
							<!--
							alert('This account is already active');
														//-->
							</script>
														<?php
						}else{
														?>
							<script type="text/javascript">
														<!--
							showPopup("Payment");
							createMembership("<?php echo $user['type'];?>", <?php echo $user['user'];?>, <?php echo $user['autoRenew'];?>, "<?php echo $user['password'];?>");
														//-->
							</script>
														<?php
						}
					}else if($number['ofUsers']==1) {
													$user_raw = $data -> query('SELECT * FROM advertisers WHERE id='.$_GET['confirm'].' AND password="'.$_GET['id'].'" AND parent=0');
													$user=$user_raw -> fetch_array(MYSQLI_ASSOC);
													$data -> query('DELETE FROM membership WHERE user='.$user['id']);
													?>
													<script type="text/javascript">
													<!--
													selectNewMembership(<?php echo $user['id'];?>, 0);
													//-->
													</script>
													<?php
											//If agency, sub accounts have platinum but can't change it
					}

				}else if(isset($_GET['setup']) && isset($_GET['code'])) {
												
					if(isset($_SESSION['loggedId'])) {
						unset($_SESSION['loggedId']);
					}
					
					$setupRaw = $data -> query('SELECT * FROM advertisers WHERE id='.$_GET['setup'].' AND nickname=""');
					
					if($setup = $setupRaw -> fetch_array(MYSQLI_ASSOC)) {
						
						if(md5($setup['email'])==$_GET['code']) {
														?>
							<script type="text/javascript">
														<!--
							showPopup('Setup your account');
							setupAccount(<?php echo $setup['id'];?>, '<?php echo $_GET['code'];?>');
														//-->
							</script>
														<?php
						}
					}
				}else if(isset($_GET['reset']) && isset($_GET['id']) && $_GET['id']!='') {
												
					$setupRaw = $data -> query('SELECT * FROM advertisers WHERE id='.$_GET['reset'].' AND password="'.$_GET['id'].'"');
												
					if($setup = $setupRaw -> fetch_array(MYSQLI_ASSOC)) {
													?>
						<script type="text/javascript">
													<!--
						resetPW(<?php echo $setup['id'];?>, '<?php echo $_GET['id'];?>');
													//-->
						</script>
													<?php
					}
				}
											?>

				

			</section>
			<!-- END OF bodyContent -->


<!-- ============================================================ FOOTER ======================================================== -->
			
			<footer id="bottomBar">
					<a href="javascript:showPopup('Terms and Conditions');showTAC();">Terms &amp; conditions</a>

					<a href="javascript:showPopup('Contact Us');showContact('', '', '');">Contact us</a>

					<?php
					if(isset($_SESSION['loggedId'])) {
							?>
						<input type="button" value="Edit Profile" class="loginButtons" style="top: 15px;" onclick="showPopup('My Profile'); showProfile('profile');" />
						<input type="button" value="Logout" class="loginButtons" style="top: 55px;" onclick="window.location='http://<?php echo $_SERVER['HTTP_HOST'];?>/?logout=1';" />
							<?php
					
					}else {
							?>
						<input type="button" value="Escort login" class="loginButtons" style="top: 15px;" onclick="loginPrompt('', '');" />
						<input type="button" value="Escort Sign Up" class="loginButtons" style="top: 55px;" onclick="advertiseInfo();" />
							<?php
					}
							?>

							<?php
					$lawRaw = $data ->query('SELECT * FROM countries WHERE id='.$currentCountry);
					$law = $lawRaw -> fetch_array(MYSQLI_ASSOC);
							?>
					<a href="<?php echo $law['lawUrl'];?>" target="_blank"><?php echo $law['lawName'];?></a>
					<p>	© Copyright 2010 - 2013 Escort Central</p>
			</footer>			


						<?php 
		if(isset($checkMembership) && $checkMembership==1) {
			$user_raw = $data -> query('SELECT * FROM advertisers INNER JOIN membership ON membership.user=advertisers.id WHERE UPPER(nickname)=UPPER("'.$_POST['usernameField'].'") AND password = "'.md5($_POST['passwordField']).'" AND expiry+0 >CURDATE()+0');
			
			if($user=$user_raw -> fetch_array(MYSQLI_ASSOC)) {
				
				if($user['parent']==0) {
					?>
					<script type="text/javascript">
						<!--
						showPopup("Payment");
						createMembership("<?php echo $user['type'];?>", <?php echo $user['user'];?>, <?php echo $user['autoRenew'];?>, '<?php echo $user['password'];?>');
										//-->
					</script>
										<?php
				}else {
										?>
					<script type="text/javascript">
										<!--
						alert('Your membership has expired, please ask your administrator to renew it in order to keep using you account');
										//-->
					</script>
										<?php
				}
			}else {
				$user_raw = $data -> query('SELECT * FROM advertisers WHERE UPPER(nickname)=UPPER("'.$_POST['usernameField'].'") AND password = "'.md5($_POST['passwordField']).'"');
				$user=$user_raw -> fetch_array(MYSQLI_ASSOC);
									
				if($user['parent']==0) {
					$data -> query('DELETE FROM membership WHERE user='.$user['id']);
										?>
					<script type="text/javascript">
										<!--
						selectNewMembership(<?php echo $user['id'];?>, 0);
										//-->
					</script>
										<?php
				}else {
										?>
					<script type="text/javascript">
										<!--
						alert('Your membership has expired, please ask your administrator to renew it in order to keep using you account');
										//-->
					</script>
										<?php
				}
			}

		}else if(isset($_POST['usernameField']) && !isset($_SESSION['loggedId'])) { 
								?>
			<script type="text/javascript">
								<!--
				loginPrompt("<?php echo htmlspecialchars($_POST['usernameField']); ?>", "<?php echo htmlspecialchars($_POST['passwordField']); ?>");
								//-->
			</script>
								<?php
		} 

		if(isset($_GET['paid']) && $_GET['paid']==1 && isset($_SESSION['loggedId']) && $_SESSION['loggedId']!=0) {
								?>
			<script type="text/javascript">
								<!--
				welcome = 1;
				showPopup('My Profile'); 
				showProfile('profile');
								//-->
			</script>
								<?php
		}else if(isset($loggedIn) && $loggedIn==1 && (!isset($suspendedNotice)||$suspendedNotice==0) && isset($_SESSION['loggedId']) && $_SESSION['loggedId']!=0) {
								?>
			<script type="text/javascript">
								<!--
				showPopup('My Profile'); 
				showProfile('profile');
								//-->
			</script>
								<?php
		}else if(isset($_GET['elogin']) && $_GET['elogin']==1) {
								?>
			<script type="text/javascript">
								<!--
				loginPrompt('', '');
								//-->
			</script>
								<?php
		}
							?>
		<script type="text/javascript">
							<!--
							<?php 
			if(isset($_GET['error']) && $_GET['error']==404) {
									?>
				var address = window.location.href;
				
				if ( history.pushState ) history.pushState( {}, document.title, address.replace(/\?error=404/, "") );
					showPopup('Error 404');
					document.getElementById("popupWindowContent").innerHTML = '<div style="padding: 20px;">The page you are trying to reach was not found. You were redirected to our home page.</div><div style="text-align: right; padding-bottom: 20px; padding-right: 20px;"><input type="button" value="Okay" onclick="hidePopup();" /></div>';
									<?php
				
			}else {
									?>
				var diesette = window.location.hash;
				
				if(diesette=='#freemembership' && numberOfUsers<100) {
					advertiseInfo();
				
				}else if(diesette=='#freemembership') {
					alert('Sorry, this offer has now expired. Thank you for your interest in Escort Central');
				}
									<?php
			}
							?>
					//-->
		</script>
		<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.2.min.js"><\/script>')</script> 
		<script src="js/main.js"></script>
		<script type="text/javascript" src="js/jquery.bxslider/jquery.bxslider.js"></script>
		<script type="text/javascript">
			
			$('document').ready(function(){

				// Featured image ticker on home page

				// if the screens is mobile size, show 1 
				$('.bxslider').bxSlider({
					minSlides: 1,
					maxSlides: 6,
					slideWidth: 220,
					slideMargin: 0,
					ticker: true,
					speed: 800000
				});

			});
		</script>

	</body>
</html>