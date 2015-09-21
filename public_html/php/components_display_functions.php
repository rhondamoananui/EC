<?php
	if(isset($root))
	{
		// Display the menu Items on all pages
		function displayMenuItems($country, $QL)
		{
			?>
			<ul id="menu">
       			<li onclick="window.location='http://<?php echo $_SERVER['HTTP_HOST'];?>/';" style="margin-left: 5px; width: 54px; <?php if(!(isset($_GET['location'])) && !(isset($_GET['search'])) && !(isset($_GET['id']))) { ?>font-weight:bold; color: #ffffff; background-color:#b30000;<?php }?>">
            	  	Home
       			</li>
			<?php
			$allRegions = $QL -> query('SELECT * FROM regions WHERE country = "'.$country.'"');
			while($region = $allRegions -> fetch_array(MYSQLI_ASSOC))
			{
				?>
			        <li <?php if($country==1) { ?>style="width: 145px;"<?php }?> <?php if(isset($_GET['location']) && $_GET['location']==$region['id']) { ?>style='display: flex; flex-direction: column; justify-content: center;align-items: center;font-weight: bold; color:#fff; background-color:#b30000;height: 100%;'<?php } ?>>
			               	<span <?php if(isset($_GET['location']) && $_GET['location']==$region['id']) { ?>style="font-weight: bold; color:#fff; background-color:#b30000;"<?php } ?> onclick="window.location='http://<?php echo $_SERVER['HTTP_HOST'];?>/?location=<?php echo $region['id'];?>';"><?php echo $region['abreviation']; if($country==2) { echo " Escorts"; } ?></span>
			                <ul>
			                	<?php
			                		$allCities = $QL -> query('SELECT * FROM cities WHERE region = "'.$region['id'].'"');
			                		while($city = $allCities -> fetch_array(MYSQLI_ASSOC))
			                		{
			                			?>
			                       			<li <?php if(isset($_GET['location']) && $_GET['location']==$region['id'] && isset($_GET['city']) && $_GET['city']==$city['id']) { ?>style="font-weight:bold; color: #ffffff; background-color:#b30000;"<?php } ?> onclick="window.location='http://<?php echo $_SERVER['HTTP_HOST'];?>/?location=<?php echo $region['id'];?>&amp;city=<?php echo $city['id'];?>';"><?php echo $city['name'];?></li>
			                			<?php
			                		}
			                	?>
			                </ul>
			        </li>
			        
				<?php
			}
			?>
			<!-- <li onclick="window.location='http://<?php echo $_SERVER['HTTP_HOST'];?>/';" style="margin-left: 5px; width: 54px; <?php if(!(isset($_GET['location'])) && !(isset($_GET['search'])) && !(isset($_GET['id']))) { ?>font-weight:bold; color: #ffffff; background-color:#b30000;<?php }?>">
            	  	Live Chat
       				</li> -->
		</ul>

		<?php
			$allRegions -> close();
			$QL -> close();
		}

		function showResults($region, $city, $nickname, $services, $gender, $postCode, $country, $page, $QL)
		{
			$perPage = 10;
			$query = 'FROM advertisers INNER JOIN membership ON membership.user=advertisers.id';
			if($nickname!='') {
				$query .= ' WHERE UPPER(nickname) REGEXP UPPER("'.$nickname.'") AND suspended=0';
			
			}else if($services!=0 || $gender!='' || $postCode!='') {
				
				if($services!=0) {
					$query .= ' INNER JOIN advertisers_services ON advertisers.id=advertisers_services.advertiser WHERE suspended = 0 AND service = '.$services;
				
				}else {
					$query .= ' WHERE suspended = 0';
				}

				if($gender!='') {
					$query .= ' AND gender = "'.$gender.'"';
				}
				
				if($postCode!='') {
					$query .= ' AND postCode = "'.$postCode.'"';
				}
				
				if($region!=0) {
					$query .= ' AND region = '.$region;
					
					if($city!=0) {
						$query .= ' AND city = '.$city;
					}

				}
				$query .= ' AND country = '.$country;
			
			}else if($region!=0) {
				$query .= ' WHERE suspended = 0 AND region = '.$region;
				
				if($city!=0) {
					$query .= ' AND city = '.$city;
				}
				
				$query .= ' AND country = '.$country;
			
			}else {
				$query .= ' WHERE advertisers.id!=0';
			}

			$query .= ' AND paid = 1 AND expiry+0 > CURDATE()+0';
			$results = $QL -> query('SELECT * '.$query.' LIMIT '.($page-1)*$perPage.', '.$perPage);
			
			while($advertiser = $results -> fetch_array(MYSQLI_ASSOC)) {
				$result = $QL -> query('SELECT COUNT(*) AS ofPrices FROM rates WHERE user = '.$advertiser['user']);
				$number = $result -> fetch_assoc();
				$imageRaw = $QL -> query('SELECT * FROM images WHERE user = '.$advertiser['user'].' AND main = 1');
				
				if($advertiser['parent']!=0) {
					$parent_raw = $QL -> query('SELECT * FROM advertisers WHERE id='.$advertiser['parent']);
					$parent_info = $parent_raw -> fetch_array(MYSQLI_ASSOC);
				}
							?>
				<div class='searchResult' style="cursor: pointer;" onclick="goToUser(<?php echo $advertiser['user'];?>, '<?php echo $advertiser['nickname'];?>');">
					<div class="noImageOnSearch">
						<div style="border: 1px solid #737373; border-radius: 5px; width: 100%; height: 100%; background-position: center; <?php if($image = $imageRaw -> fetch_array(MYSQLI_ASSOC)) { ?>background-image: url('images/<?php echo $image['file'];?>'); background-repeat: no-repeat; background-size: <?php if($image['width']>$image['height']) { ?>auto 100%<?php } else { ?>100% auto<?php } ?>;<?php } ?>"></div>
					</div>
					<div style="min-height: 150px;">
						<span style="font-weight: bold; padding-left:1em;">
							<?php echo $advertiser['nickname'];?>
						</span> - 
									<?php
						if($number['ofPrices']!=0) {
							$allPrices = $QL -> query('SELECT * FROM rates WHERE user = '.$advertiser['user'].' ORDER BY price LIMIT 1');
							$cheapestPrice = $allPrices -> fetch_array(MYSQLI_ASSOC);
							echo 'From $'.$cheapestPrice['price'];
						
						}else {
							echo "No Price Specified";
						}
									?>
						<br>
						<br>
							
						<div class="searchResultTable">
								<div>
									<div style="">
										PHONE: 
									</div>
									<div class="info">
										<?php 
											if($advertiser['parent']!=0)
											{
												$phoneR = $parent_info['phone'];
											}
											else
											{
												$phoneR = $advertiser['phone'];
											}
											if($phoneR!='') 
											{
												echo htmlspecialchars($phoneR);
											}
											else
											{
												echo 'No phone specified';
											}
											?>
									</div>
								</div>
								<div>
									<div style="font-weight: bold; padding-right: 30px;">
										EMAIL: 
									</div>
									<div class="info">
										<?php 
											if($advertiser['parent']!=0)
											{
												$emailR = $parent_info['email'];
											}
											else
											{
												$emailR = $advertiser['email'];
											}
											echo htmlspecialchars($emailR);
										?>
									</div>
								</div>
								<div>
									<div style="font-weight: bold; padding-right: 30px;">
										WEBSITE: 
									</div>
									<div class="info">
										<?php 
											if($advertiser['parent']!=0)
											{
												$websiteR = $parent_info['website'];
											}
											else
											{
												$websiteR = $advertiser['website'];
											}
											if($websiteR!='' && $websiteR!='http://' && $websiteR!='https://') 
											{
												echo htmlspecialchars($websiteR);
											}
											else
											{
												echo 'No website specified';
											}
											?>
									</div>
								</div>
						</div>
					</div>
				</div>
				<?php
			}
			$count = $QL -> query('SELECT COUNT(*) AS ofResults '.$query);
			$number = $count -> fetch_assoc();
			$pageNumber = (ceil($number['ofResults']/$perPage));
			?>
				<div class="searchPages" style="text-align: center;">
					<?php
						if($pageNumber!=0)
						{
							if($pageNumber<25)
							{
								if($page!=1)
								{
									?>
										<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/?page=1<?php if($region!=0) { echo '&amp;location='.$region; } if($city!=0) { echo '&amp;city='.$city; } if(isset($_GET['search'])) { echo '&search='.$nickname; } if($services!=0) { echo '&amp;services='.$services; } if($gender!='') { echo '&amp;gender='.$gender; } if($postCode!='') { echo '&amp;postCode='.$postCode; } ?>">&lt;&lt;</a>
										<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/?page=<?php echo $page - 1;?><?php if($region!=0) { echo '&amp;location='.$region; } if($city!=0) { echo '&amp;city='.$city; } if(isset($_GET['search'])) { echo '&search='.$nickname; } if($services!=0) { echo '&amp;services='.$services; } if($gender!='') { echo '&amp;gender='.$gender; } if($postCode!='') { echo '&amp;postCode='.$postCode; } ?>">&lt;</a>
									<?php
								}
								else
								{
									echo "<span class=\"noLink\">&lt;&lt; &lt;</span>";
								}
								for($i=1;$i<=$pageNumber;$i++)
								{
									if($i==$page)
									{
										?>
											<span style="font-weight: bold;"><?php echo $i;?></span> 
										<?php
									}
									else
									{
										?>
										<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/?page=<?php echo $i;?><?php if($region!=0) { echo '&amp;location='.$region; } if($city!=0) { echo '&amp;city='.$city; } if(isset($_GET['search'])) { echo '&search='.$nickname; } if($services!=0) { echo '&amp;services='.$services; } if($gender!='') { echo '&amp;gender='.$gender; } if($postCode!='') { echo '&amp;postCode='.$postCode; } ?>"><?php echo $i;?></a>
										<?php
									}
								}
								if($page!=$pageNumber)
								{
									?>
										<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/?page=<?php echo $page + 1;?><?php if($region!=0) { echo '&amp;location='.$region; } if($city!=0) { echo '&amp;city='.$city; } if(isset($_GET['search'])) { echo '&search='.$nickname; } if($services!=0) { echo '&amp;services='.$services; } if($gender!='') { echo '&amp;gender='.$gender; } if($postCode!='') { echo '&amp;postCode='.$postCode; } ?>">></a>
										<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/?page=<?php echo $pageNumber;?><?php if($region!=0) { echo '&amp;location='.$region; } if($city!=0) { echo '&amp;city='.$city; } if(isset($_GET['search'])) { echo '&search='.$nickname; } if($services!=0) { echo '&amp;services='.$services; } if($gender!='') { echo '&amp;gender='.$gender; } if($postCode!='') { echo '&amp;postCode='.$postCode; } ?>">>></a>
									<?php
								}
								else
								{
									echo "<span class=\"noLink\">> >></span>";
								}
							}
							else
							{
								?>
									Page: 
									<select id="pageSelect" onchange="window.location = 'http://<?php echo $_SERVER['HTTP_HOST'];?>/?page='+this.value+'<?php if($region!=0) { echo '&amp;location='.$region; } if($city!=0) { echo '&amp;city='.$city; } if(isset($_GET['search'])) { echo '&search='.$nickname; } if($services!=0) { echo '&amp;services='.$services; } if($gender!='') { echo '&amp;gender='.$gender; } if($postCode!='') { echo '&amp;postCode='.$postCode; } ?>';">
										<?php
											for($i=1;$i<=$pageNumber;$i++)
											{
												if($i!=$page)
												{
													?>
														<option value="<?php echo $i;?>"><?php echo $i;?></option>
													<?php
												}
												else
												{
													?>
														<option value="<?php echo $i;?>" selected="selected"><?php echo $i;?></option>
													<?php
												}
											}
										?>
									</select>
								<?php
							}
						}
						else
						{
							echo "Sorry, there were no results for the criteria you selected.";
						}
					?>
				</div>
			<?php
		}

		function showSearchDiv($QL, $userCountry)
		{
			$services_raw = $QL -> query('SELECT * FROM services');
			$regionFound = 0;
			$cityFound = 0;
			$country_raw = $QL -> query('SELECT * FROM countries WHERE id = '.$userCountry);
			$country = $country_raw -> fetch_array(MYSQLI_ASSOC);
			if(isset($_GET['location']))
			{
				$regions_raw = $QL -> query('SELECT * FROM regions WHERE country = '.$userCountry.' AND id = '.$_GET['location']);
				if($region = $regions_raw -> fetch_array(MYSQLI_ASSOC))
				{
					$regionFound = 1;
					if(isset($_GET['city']))
					{
						$city_raw = $QL -> query('SELECT * FROM cities WHERE region = '.$_GET['location'].' AND id = '.$_GET['city']);
						if($city = $city_raw -> fetch_array(MYSQLI_ASSOC))
						{
							$cityFound = 1;
						}
					}
				}
			}
			if($cityFound == 1)
			{
				$selectedLocation = $city['name'].' ('.$region['name'].')';
			}
			else if($regionFound == 1)
			{
				$selectedLocation = $region['name'];
			}
			else
			{
				$selectedLocation = $country['name'];
			}
			?>

<!-- SEARCH BAR -->
				<div id="searchDiv">
					<!-- <div style="margin-top: 10px; padding-left: 5px; font-size: 16px; border-bottom: 1px solid #737373;">Find Escorts</div> -->
					<div class="search-area" style="font-size: 12px;">

						<div class="contain-searchDiv">

							<div>
								<h3>Search</h3>
								<select id="genderSearch" class="search-inputs"><option value="all">All</option><option value="female" <?php if(isset($_GET['search']) && isset($_GET['gender']) && strtolower($_GET['gender'])=='female') { ?>selected="selected"<?php }?>>Women</option><option value="male" <?php if(isset($_GET['search']) && isset($_GET['gender']) && strtolower($_GET['gender'])=='male') { ?>selected="selected"<?php }?>>Men</option><option value="transexual" <?php if(isset($_GET['search']) && isset($_GET['gender']) && strtolower($_GET['gender'])=='transexual') { ?>selected="selected"<?php }?>>Trans</option></select>
							</div>
							
							<div>
								<h3>Service</h3>
								<select id="serviceSearch" class="search-inputs">
										<option value="0">All</option>
										<?php
											while($service = $services_raw -> fetch_array(MYSQLI_ASSOC))
											{
												?>
													<option value="<?php echo $service['id'];?>" <?php if(isset($_GET['search']) && isset($_GET['services']) && $_GET['services']==$service['id']) { ?>selected="selected"<?php }?>><?php echo $service['name'];?></option>
												<?php
											}
										?>
								</select>
							</div>
							
							<div>
								<h3>Post Code</h3>
								<input type="text" id="postCodeSearch" class="search-inputs" value="<?php if(isset($_GET['search']) && isset($_GET['postCode'])) { echo $_GET['postCode']; } ?>" onkeydown="if((event.keyCode ? event.keyCode : event.which)==13) { $('#searchButton').click(); }" />	
							</div>
							<div>
								<h3>Nickname</h3>
								<input type="text" id="nicknameSearch" class="search-inputs" value="<?php if(isset($_GET['search'])) { echo $_GET['search']; } ?>" onkeydown="if((event.keyCode ? event.keyCode : event.which)==13) { $('#searchButton').click(); }" />
							</div>
							
							<div class="search-btn">

								<input type="button" value="SEARCH" id="searchButton" class="search-inputs" onclick="var newLocation = 'http://<?php echo $_SERVER['HTTP_HOST'];?>/?search='+document.getElementById('nicknameSearch').value<?php if(isset($_GET['location'])) { ?>+'&amp;location=<?php echo $_GET['location'];?>'<?php if(isset($_GET['city'])) { ?>+'&amp;city=<?php echo $_GET['city'];?>'<?php } } ?>; if(document.getElementById('nicknameSearch').value=='') { if(document.getElementById('genderSearch').value!='all') { newLocation += '&amp;gender='+document.getElementById('genderSearch').value; } if(document.getElementById('serviceSearch').value!='0') { newLocation += '&amp;services='+document.getElementById('serviceSearch').value; } if(document.getElementById('postCodeSearch').value!='') { newLocation += '&amp;postCode='+document.getElementById('postCodeSearch').value; } } window.location = newLocation;" />
							</div>
						</div>
						
					</div>
					
				</div>
				<p style="text-align:center">* You must be over the age of 18yrs to browse this website</p>

<!-- ESCORT SIGN UP CALL TO ACTION -->

				<!-- <div id="ctaDiv">
					<div style="margin-top: 10px; padding-left: 5px; font-size: 16px; border-bottom: 1px solid #737373;"><?php if(isset($_SESSION['loggedId']) && $_SESSION['loggedId']!=0) { ?>Edit Your Profile<?php } else { ?>Add Your Profile<?php } ?></div>
					
					<?php 
						if(isset($_SESSION['loggedId']) && $_SESSION['loggedId']!=0) 
						{ 
							?>
								<div style="margin-top: 10px; color:  #0000ff;">Update your Profile, Edit your information or add new photographs.  Keep your profile looking fresh</div>
								
								<div style="text-align: center;"><input type="button" id="accountButton" value="EDIT MY PROFILE" onclick="showPopup('My Profile'); showProfile('profile');" /></div>
							<?php
						}
						else
						{
							?>
								<div style="margin-top: 10px; color: #0000ff;">Be seen amongst the best Escorts in Australia.</div>

								<ul>
									<li>Get listed as one of our stunning Featured Escorts</li>
									<li>Create a professional profile</li>
									<li>Your listing will be easily viewed on a mobile</li>
								</ul>
								
								<div style="text-align: center;"><input type="button" id="accountButton" value="CREATE AN ACCOUNT" onclick="advertiseInfo();" /></div>
							<?php
						}
					?>
				</div> -->



			<?php
		}

// GET FEATURED IMAGES TO SHOW IN IMAGE TICKER
// 1. query db for all featured images
// 2. loop through all featured images 
// 3. display featured images in a list

		function imageTicker($country, $QL)
		{
			// if the current page is home page, select all featured escorts in random order
			// $currentPage = 'http://escortcentral.com.au';
			// if(isset($_GET['location'])){
			// 	echo 'Hello';


			// 	$allFeatured = $QL -> query('SELECT * FROM advertisers INNER JOIN membership ON membership.user=advertisers.id WHERE paid = 1 AND suspended=0 AND country='.$country.' AND expiry+0 > CURDATE()+0 AND (UPPER(type)="PLATINUM" OR UPPER(type)="AGENCY" OR parent!=0) ORDER BY RAND(featured) LIMIT 100');
			// 	// print_r($allFeatured);

			// }else{
			// else select all featured escorts from location x in random order
				$allFeatured = $QL -> query('SELECT * FROM advertisers INNER JOIN membership ON membership.user=advertisers.id WHERE paid = 1 AND suspended=0 AND country='.$country.' AND expiry+0 > CURDATE()+0 AND (UPPER(type)="PLATINUM" OR UPPER(type)="AGENCY" OR parent!=0) ORDER BY RAND(featured) LIMIT 100');
				
			// }

			$numberOfUsers = 0;

			while($advertiser = $allFeatured -> fetch_array(MYSQLI_ASSOC)) {
				// print_r($advertiser);

				// if($numberOfUsers!=0 && $numberOfUsers%20==0) {
				// 						?>
			
					<!-- <div style="display: none;" class="pagesF" id="page<?php //echo $numberOfUsers/20 +1;?>"></div> -->
				 						<?php
				// }
				
				$result = $QL -> query('SELECT COUNT(*) AS ofPrices FROM rates WHERE user = '.$advertiser['user']);
				$number = $result -> fetch_assoc();
				$townRaw = $QL -> query('SELECT * FROM cities WHERE id = '.$advertiser['city']);
				
				$town = $townRaw -> fetch_array(MYSQLI_ASSOC);
				$imageRaw = $QL -> query('SELECT * FROM images WHERE user = '.$advertiser['user'].' AND main = 1');
				


				if(intval($advertiser['featured'])<2147483630) {
					$views = intval($advertiser['featured']) + 1;
					$QL -> query('UPDATE advertisers SET featured = "'.$views.'" WHERE id='.$advertiser['user']);
				}
									?>

				<li class="featuredEscort-1" style="color: #333;" >
					<div class="featuredEscortImage-1"

						<?php if($image = $imageRaw -> fetch_array(MYSQLI_ASSOC)) { ?> style="background-position: center; background-image: url('images/<?php echo $image['file'];?>'); background-repeat: no-repeat; background-size: <?php if($image['width']>$image['height']) { ?>auto 100%<?php } else { ?>100% auto<?php } ?>;" <?php } ?>>
						
						<div onclick="goToUser(<?php echo $advertiser['user'];?>, '<?php echo $advertiser['nickname'];?>');">
							<h3 style=""> <?php echo $advertiser['nickname'];?></h3>
							<h3><?php echo $town['name'];?></h3>
							
						</div>	

					</div>
					
					
				</li>
									<?php
				$numberOfUsers++;
			}

		}


		function displayFeatured($country, $QL)
		{
			?>
				<div id="featuredEscorts" style="position: relative;">
					
					<div style="width: 97%;">
						<div style="width: 100%;">
							<div style="font-weight: bold; width: 150px; padding: 0;">Featured Escorts</div>
							<div style="padding: 0;"><div style="border-bottom: 1px solid #fff; width: 100%; height: 10px;"></div></div>
						</div>
					</div>

					<br>

					<div id="featuredImages" class="clearfix">
						<div id="page1" class="pagesF">
							<?php
								$allFeatured = $QL -> query('SELECT * FROM advertisers INNER JOIN membership ON membership.user=advertisers.id WHERE paid = 1 AND suspended=0 AND country='.$country.' AND expiry+0 > CURDATE()+0 AND (UPPER(type)="PLATINUM" OR UPPER(type)="AGENCY" OR parent!=0) ORDER BY featured LIMIT 100');
								$numberOfUsers = 0;
								while($advertiser = $allFeatured -> fetch_array(MYSQLI_ASSOC))
								{
									if($numberOfUsers!=0 && $numberOfUsers%100==0)
									{
										?>
											<!--  -->
										<!-- <div style="display: flex;" class="pagesF" id="page<?php echo $numberOfUsers/20 +1;?>"> -->
												<?php
												}
												$result = $QL -> query('SELECT COUNT(*) AS ofPrices FROM rates WHERE user = '.$advertiser['user']);
												$number = $result -> fetch_assoc();
												$townRaw = $QL -> query('SELECT * FROM cities WHERE id = '.$advertiser['city']);
												$town = $townRaw -> fetch_array(MYSQLI_ASSOC);
												$imageRaw = $QL -> query('SELECT * FROM images WHERE user = '.$advertiser['user'].' AND main = 1');
												if(intval($advertiser['featured'])<2147483630)
												{
													$views = intval($advertiser['featured']) + 1;
													$QL -> query('UPDATE advertisers SET featured = "'.$views.'" WHERE id='.$advertiser['user']);
												}
												?>

											<div class="featuredEscort" onclick="goToUser(<?php echo $advertiser['user'];?>, '<?php echo $advertiser['nickname'];?>');">
												<div class="featuredEscortImage" 

													<?php if($image = $imageRaw -> fetch_array(MYSQLI_ASSOC)) { ?> style="display: flex; justify-content: flex-end; flex-direction: column;background-position: center; background-image: url('images/<?php echo $image['file'];?>'); background-repeat: no-repeat; background-size: <?php if($image['width']>$image['height']) { ?>auto 100%<?php } else { ?>100% auto<?php } ?>;" <?php } ?>>
													<b style="width: 100%; display: flex; justify-content: center;  flex-direction: column; align-items: center; background-color: rgba(0,0,0,.5); border-radius: 0 0 5px 5px; padding-bottom: 1em;">
														<br><span style="color: #b30000; font-size: x-large; text-align: center;"><?php echo $advertiser['nickname'];?></span>
														<br><?php echo $town['name'];?>
													</b>	
												</div>
												
											</div>
										<!-- </div> -->
									<?php
									$numberOfUsers++;
								}
								?>
						</div>
						<div style="margin: auto; text-align: center; width: 100%;"></div>
										<script type="text/javascript">
											// var featuredLimit = <?php echo $numberOfUsers;?>;
											// var currentFeaturedPosition = 1;
											// var changingFeaturedScroll = 0;
											// var animationTime = 350;
											// var displacement = 178;
										</script>
						<div class="clear"></div>

					</div> <!-- end id="featuredImages" -->
				</div> <!-- end id="featuredEscorts" -->
		 <?php
		}



	}else {
		//Error 404
		$root = realpath($_SERVER["DOCUMENT_ROOT"]);
		include $root."/php/error404.php";
	}

	



?>