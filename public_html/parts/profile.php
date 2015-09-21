<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	include $root."/php/start.php";
	$data = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);
	if(isset($_POST['id']))
	{
		$result = $data -> query('SELECT COUNT(*) AS ofAdvertisers FROM advertisers WHERE id='.$_POST['id']);
		$number = $result -> fetch_assoc();
		if($number['ofAdvertisers']==1)
		{
			$advertiser_raw = $data -> query('SELECT * FROM advertisers WHERE id='.$_POST['id']);
			$advertiser = $advertiser_raw -> fetch_array(MYSQLI_ASSOC);
			$imageRaw = $data -> query('SELECT * FROM images WHERE user = '.$advertiser['id'].' AND main = 1');
			$regions_raw = $data -> query('SELECT * FROM regions WHERE id= '.$advertiser['region']);
			$region = $regions_raw -> fetch_array(MYSQLI_ASSOC);
			$service_raw = $data -> query('SELECT * FROM advertisers_services INNER JOIN services ON services.id=advertisers_services.service WHERE advertiser = '.$advertiser['id']);
			if($advertiser['city']!='' && $advertiser['city']!=0)
			{
				$cities_raw = $data -> query('SELECT * FROM cities WHERE id= '.$advertiser['city']);
				$city = $cities_raw -> fetch_array(MYSQLI_ASSOC);
			}
			?>
				<div id="escortProfile">
					<div style="float: left; height: 100%; max-width: 315px;">
						<div class="noImageOnProfile">
							<div id="displayedImage" style="width: 100%; height: 100%; <?php if($image = $imageRaw -> fetch_array(MYSQLI_ASSOC)) { ?>background-position: center; background-image: url('images/<?php echo $image['file'];?>'); background-repeat: no-repeat; background-size: <?php if($image['width']>$image['height']) { ?>auto 100%<?php } else { ?>100% auto<?php } ?>;<?php } ?>" >
							</div>
						</div>
						<?php 
							$imagesRaw = $data -> query('SELECT * FROM images WHERE user = '.$advertiser['id'].' AND main = 0 AND banner = 0');
							$i = 0;
							while($currentImage=$imagesRaw -> fetch_array(MYSQLI_ASSOC))
							{
								?>
									<div id="image<?php echo $i;?>" style="cursor: pointer; float: left; width: 90px; margin-right: 15px; margin-top: 15px; height: 90px; background-color: black; background-position: center; background-image: url('images/<?php echo $currentImage['file'];?>'); background-repeat: no-repeat; background-size: <?php if($currentImage['width']>$currentImage['height']) { ?>auto 100%<?php } else { ?>100% auto<?php } ?>;" onclick="var tempBgSize = document.getElementById('displayedImage').style.backgroundSize; var tempBgImage = document.getElementById('displayedImage').style.backgroundImage; document.getElementById('displayedImage').style.backgroundSize = this.style.backgroundSize; document.getElementById('displayedImage').style.backgroundImage = this.style.backgroundImage; this.style.backgroundSize = tempBgSize; this.style.backgroundImage = tempBgImage;"></div>
								<?php
								$i++;
							}
						?>
					</div>
					<div style="min-height: <?php if($i==0) { echo '300'; } else if($i<4) { echo '385'; } else if($i<7) { echo '490'; } else if($i<10) { echo '595'; } else { echo '700'; } ?>px; margin-left: 315px;">
						<?php
							$agency = 0;
							$child = 0;
							if($advertiser['parent']!=0)
							{
								$child = 1;
								$agency = 1;
								$agencyInfRaw = $data -> query('SELECT * FROM advertisers WHERE id = '.$advertiser['parent']);
								$parentInf = $agencyInfRaw -> fetch_array(MYSQLI_ASSOC);
								$advertiser['email'] = $parentInf['email'];
								$advertiser['website'] = $parentInf['website'];
								$advertiser['facebook'] = $parentInf['facebook'];
								$advertiser['twitter'] = $parentInf['twitter'];
								$advertiser['phone'] = $parentInf['phone'];
								$imagesRaw = $data -> query('SELECT * FROM images WHERE user = '.$advertiser['parent'].' AND banner=1');
								if($image = $imagesRaw -> fetch_array(MYSQLI_ASSOC))
								{
									?>
										<img style="width: 100%;" src="http://<?php echo $_SERVER['HTTP_HOST'];?>/images/<?php echo $image['file'];?>" alt="Agency banner" />

									<?php
								}
							}
							else
							{
								$result = $data -> query('SELECT COUNT(*) AS ofAgencies FROM membership WHERE user = '.$advertiser['id'].' AND UPPER(type)="AGENCY"');
								$number = $result -> fetch_assoc();
								if($number['ofAgencies']==1)
								{
									$agency = 1;
									$imagesRaw = $data -> query('SELECT * FROM images WHERE user = '.$advertiser['id'].' AND banner=1');
									if($image = $imagesRaw -> fetch_array(MYSQLI_ASSOC))
									{
										?>
											<img style="width: 100%;" src="http://<?php echo $_SERVER['HTTP_HOST'];?>/images/<?php echo $image['file'];?>" alt="Agency banner" />
										<?php
									}
								}
							}
							function showContInf($fb, $tt, $ws, $ph, $em, $ag, $ch)
							{
								?>
									<div style="margin-top: 10px; width: 360px;">
										Contact <?php if($ag==0) { ?>Me<?php } else if($ch==1) { ?>My Agency<?php } ?><br />
										<div style="float: left; width: 220px; height: 100px; font-size: 12px; line-height: 20px; margin-right: 10px;">
											E-mail: <?php echo $em;?><br />
											<?php if($ph!='') { ?>Phone: <?php echo $ph;?><br /><?php } ?>
											<?php if($ws!='') { ?>Website: <a href="<?php if(!preg_match("#^https?://.+#", $ws)) { echo "http://"; } echo $ws;?>" target="_blank"><?php echo $ws;?></a><?php } ?>
										</div>
										<div style="height: 100px; font-size: 12px; line-height: 20px;">
											<?php if($fb!='') { ?><a href="<?php if(!preg_match("#^https?://.+#", $fb)) { echo "http://"; } echo $fb;?>" target="_blank"><img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/design/fb.png" style="height: 20px;" alt="Facebook" /></a><br /><?php } ?>
											<?php if($tt!='') { ?><a href="<?php if(!preg_match("#^https?://.+#", $tt)) { echo "http://"; } echo $tt;?>" target="_blank"><img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/design/tt.png" style="height: 20px;" alt="Twitter" /></a><?php } ?>
										</div>
									</div>
								<?php
							}
							if($agency==1)
							{
								showContInf($advertiser['facebook'], $advertiser['twitter'], $advertiser['website'], $advertiser['phone'], $advertiser['email'], $agency, $child);
							}
						?>
						<span style="font-weight: bold; font-size: 20px;">
							<?php
								echo $advertiser['nickname'];
							?>
						</span>
						<br /><br />
						<?php 
							if(!($agency==1 && $child==0))
							{
								?>
									<table id="profileInfoTable">
										<tr>
											<td>
												Location: 
											</td>
											<td class="right">
												<?php if($advertiser['city']!='' && $advertiser['city']!=0) { echo $city['name'].', '; } echo $region['name'];?>
											</td>
										</tr>
										<tr <?php if($advertiser['nationality']=='') { ?>style="display: none;"<?php } ?>>
											<td>
												Nationality: 
											</td>
											<td class="right">
											<?php 
												echo $advertiser['nationality'];
											?>
											</td>
										</tr>
										<tr <?php if($advertiser['ethnicity']=='') { ?>style="display: none;"<?php } ?>>
											<td>
												Ethnicity: 
											</td>
											<td class="right">
											<?php 
												echo $advertiser['ethnicity'];
											?>
											</td>
										</tr>
										<tr>
											<td>
												Gender: 
											</td>
											<td class="right">
											<?php 
												echo $advertiser['gender'];
											?>
											</td>
										</tr>
										<?php 
											$nos = $data -> query('SELECT COUNT(*) AS ofServices FROM advertisers_services INNER JOIN services ON services.id=advertisers_services.service WHERE advertiser = '.$advertiser['id']);
											$numberOS = $nos -> fetch_assoc();
										?>
										<tr <?php if($numberOS['ofServices']==0) { ?>style="display: none;"<?php } ?>>
											<td>
												Services: 
											</td>
											<td class="right">
											<?php 
												while($service = $service_raw -> fetch_array(MYSQLI_ASSOC))
												{
													echo $service['name'].'<br />';
												}
											?>
											</td>
										</tr>
										<tr>
											<td>
												Age: 
											</td>
											<td class="right">
												<?php 
													$birthDate = new DateTime($advertiser['birthDate']." 00:00:00");
													if(date("m")>($birthDate->format("m")) || (date("m")==($birthDate->format("m")) && date("d")>($birthDate->format("d"))))
													{
														$age = date("Y") - ($birthDate->format("Y"));
													}
													else
													{
														$age = date("Y") - ($birthDate->format("Y")) - 1;
													}
													echo $age;
												?>
											</td>
										</tr>
										<tr <?php if($advertiser['eyeColour']=='') { ?>style="display: none;"<?php } ?>>
											<td>
												Eye colour: 
											</td>
											<td class="right">
											<?php 
												echo $advertiser['eyeColour'];
											?>
											</td>
										</tr>
										<tr <?php if($advertiser['hair']=='') { ?>style="display: none;"<?php } ?>>
											<td>
												Hair: 
											</td>
											<td class="right">
											<?php 
												echo $advertiser['hair'];
											?>
											</td>
										</tr>
										<tr <?php if($advertiser['height']==''||$advertiser['height']==0) { ?>style="display: none;"<?php } ?>>
											<td>
												Height: 
											</td>
											<td class="right">
											<?php 
												echo $advertiser['height'].' cm';
											?>
											</td>
										</tr>
										<tr <?php if($advertiser['bodyType']=='') { ?>style="display: none;"<?php } ?>>
											<td>
												Body type: 
											</td>
											<td class="right">
											<?php 
												echo $advertiser['bodyType'];
											?>
											</td>
										</tr>
										<tr <?php if($advertiser['dressSize']==''||($advertiser['dressSize']==0&&$advertiser['dressSize']!='Zero')) { ?>style="display: none;"<?php } ?>>
											<td>
												<?php if($advertiser['gender']=='male') { echo "S"; } else { echo "Dress s"; } ?>ize (US): 
											</td>
											<td class="right">
											<?php 
												echo $advertiser['dressSize'];
											?>
											</td>
										</tr>
										<tr <?php if($advertiser['bustSize']=='') { ?>style="display: none;"<?php } ?>>
											<td>
												Bust size: 
											</td>
											<td class="right">
											<?php 
												if($advertiser['gender']!="male")
												{
													echo $advertiser['bustSize'];
												}
												else
												{
													echo "NA";
												}
											?>
											</td>
										</tr>
										<tr>
											<td>
												Shaved: 
											</td>
											<td class="right">
											<?php 
												if($advertiser['shaved']==1)
												{
													echo "yes";
												}
												else
												{
													echo "no";
												}
											?>
											</td>
										</tr>
										<tr>
											<td>
												Smoke: 
											</td>
											<td class="right">
											<?php 
												if($advertiser['smoke']==1)
												{
													echo "yes";
												}
												else
												{
													echo "no";
												}
											?>
											</td>
										</tr>
										<tr>
											<td>
												Disable friendly: 
											</td>
											<td class="right">
											<?php 
												if($advertiser['disableFriendly']==1)
												{
													echo "yes";
												}
												else
												{
													echo "no";
												}
											?>
											</td>
										</tr>
									</table>
								<?php
							}
						?>
						<table id="workTable">
						<?php
							$result = $data -> query('SELECT COUNT(*) AS available FROM advertisers_availability WHERE advertiser = '.$advertiser['id']);
							$number = $result -> fetch_assoc();
						?>
							<tr>
								<td>
								<?php 
									if($number['available']!=0) 
									{ 
										?>
												<?php
													if($number['available']!=0)
													{
														echo "Availability";
													}
												?>
											</td>
											<td class="right">
										<?php
									}
								?>
									<?php
										$result = $data -> query('SELECT COUNT(*) AS rates FROM rates WHERE user = '.$advertiser['id']);
										$numbers = $result -> fetch_assoc();
										if($numbers['rates']!=0)
										{
											echo "Rates";
										}
									?>
								</td>
							</tr>
							<tr style="font-size: 12px;">
								<td>
								<?php 
									if($number['available']!=0) 
									{ 
										?>
												<table style="border-collapse: collapse; margin: 0; padding: 0;">
													<?php 
														$availability_raw = $data -> query('SELECT * FROM advertisers_availability WHERE advertiser='.$advertiser['id'].' ORDER BY day');
														while($available = $availability_raw -> fetch_array(MYSQLI_ASSOC))
														{
															$days=['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
															echo '<tr><td><span style="color: grey;">'.$days[$available['day']].'</span> </td><td>'.$available['fromTime'].' - '.$available['toTime'].'</td></tr>';
														}
													?>
												</table>
											</td>
											<td class="right">
										<?php
									}
								?>
									<?php 
										$rates_raw = $data -> query('SELECT * FROM rates WHERE user='.$advertiser['id'].' ORDER BY price');
										while($rate = $rates_raw -> fetch_array(MYSQLI_ASSOC))
										{
											if($rate['hours']==0)
											{
												echo '$'.sprintf('%0.2f', $rate['price']).' for '.$rate['minutes'].' min<br />';
											}
											else if($rate['minutes']==0)
											{
												echo '$'.sprintf('%0.2f', $rate['price']).' for '.$rate['hours'].' hour(s)<br />';
											}
											else if($rate['minutes']!=0)
											{
												echo '$'.sprintf('%0.2f', $rate['price']).' for '.$rate['hours'].' hour(s) '.$rate['minutes'].' min<br />';
											}
										}
									?>
								</td>
							</tr>
						</table>
						<?php 
						if(preg_match("#[^ \n]#", $advertiser['description']))
						{
							?>
								<div>
								<br />
									About <?php if($agency==0||$child==1) { ?>Me<?php } ?><br />
									<span style="font-size: 12px;"><?php echo preg_replace("#\n#", "<br />", htmlspecialchars($advertiser['description']));?></span>
								</div>
							<?php
						}
						/*
						Favourites, If ever needed again.
						$favourites_raw = $data -> query('SELECT * FROM advertisers_favourites INNER JOIN favourites ON advertisers_favourites.favourite=favourites.id WHERE advertiser='.$_POST['id']);
						$i = 0;
						?>
						<div style="margin-top: 5px;">
						<?php
						while($favourite = $favourites_raw -> fetch_array(MYSQLI_ASSOC))
						{
							?>
								<?php 
									if($i==0)
									{
										?>My Favourites<br /><?php
									}
								?>
								<span style="font-size: 12px;">
									<?php
										if($i!=0)
										{
											echo ", ";
										}
										echo $favourite['name'];
									?>
								</span>
							<?php
							$i++;
						}
						?>
						</div>
						<?php
							*/
						?>
						<?php
							if($agency==0)
							{
								showContInf($advertiser['facebook'], $advertiser['twitter'], $advertiser['website'], $advertiser['phone'], $advertiser['email'], $agency, $child);
							}
						?>
					</div>


				</div>
					
			<?php
		}
		else
		{
			?>
				<div id="escortProfile">
					Sorry, the profile you are trying to reach was not found.<br />
					It has either never existed or been deleted.
					<br /><br />
					<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/">Go to Home Page</a>
				</div>
			<?php
		}
	}
	else 
	{
		//Error 404
		include $root."/php/error404.php";
	}
?>