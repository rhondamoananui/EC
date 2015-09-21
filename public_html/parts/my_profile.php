<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	include $root."/php/start.php";
	$complete = 0;
	$error = 0;
	$QL = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);
	if(isset($_POST['part']) && isset($_SESSION['loggedId']))
	{
		?>
			<div id="stuffInPopup">
				<?php
					$userRaw = $QL -> query('SELECT * FROM advertisers INNER JOIN membership ON membership.user=advertisers.id WHERE advertisers.id = '.$_SESSION['loggedId']);
					if($user = $userRaw -> fetch_array(MYSQLI_ASSOC))
					{
						?>
					<div id="profileParts">
						<span <?php if($_POST['part']!='photos' && $_POST['part']!='info' && $_POST['part']!='account' && $_POST['part']!='setupAccount') { ?>style="font-weight: bold;"<?php } ?> onclick="if(profileChanged==0||(profileChanged==1 && confirm('You have made unsaved change to your profile, are you sure you want to leave this page?'))) { showProfile('profile'); }">Profile</span> - <span <?php if($_POST['part']=='info') { ?>style="font-weight: bold;"<?php } ?> onclick="if(profileChanged==0||(profileChanged==1 && confirm('You have made unsaved change to your profile, are you sure you want to leave this page?'))) { showProfile('info'); }">Info</span> - <span <?php if($_POST['part']=='photos') { ?>style="font-weight: bold;"<?php } ?> onclick="if(profileChanged==0||(profileChanged==1 && confirm('You have made unsaved change to your profile, are you sure you want to leave this page?'))) { showProfile('photos'); }">Photos</span> - <span <?php if($_POST['part']=='account'||$_POST['part']=='setupAccount') { ?>style="font-weight: bold;"<?php } ?> onclick="if(profileChanged==0||(profileChanged==1 && confirm('You have made unsaved change to your profile, are you sure you want to leave this page?'))) { showProfile('account'); }">Account<?php if(strtolower($user['type'])=='agency') { echo 's'; } ?></span>
						<input onclick="<?php if($user['suspended']!=1) { if($_POST['part']!='photos' && $_POST['part']!='info' && $_POST['part']!='account') { ?>changeDescription(1);<?php } else if($_POST['part']=='info') { ?>updateInfo(1, '<?php echo $user['type'];?>');<?php } else if($_POST['part']=='account'||$_POST['part']=='setupAccount') { ?>checkAccountChanges(1);<?php } else if($_POST['part']=='photos') { ?>hidePopup();<?php } } else { ?>hidePopup();<?php } ?>" id="saveAndClose" type="button" style="position: absolute; right: <?php if($_POST['part']=='profile') { echo '-5'; } else { echo '6'; } ?>px;" value="Save and close" />
					</div>
					<div style="margin-top: 10px;">
						<?php
							if($user['suspended']==1)
							{
								?>
									Your account is suspended, you cannot add any change to it until it is unsuspended.
								<?php
							}
							else
							{
								if($_POST['part']=='photos')
								{
									if(strtolower($user['type'])=='agency')
									{
										?>
											<span style="font-weight: bold;">Banner</span><br /><br />
											<?php 
												$banners = $QL -> query('SELECT * FROM images WHERE user='.$_SESSION['loggedId'].' AND banner=1');
												if($banner = $banners -> fetch_array(MYSQLI_ASSOC))
												{
													?>
														<img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/images/<?php echo $banner['file'];?>" alt="Your banner did not load properly" style="width: 100%;" />
														<br /><br />
														<span id="deleteBanner"><a href="javascript:deleteBanner();">Delete banner</a></span>
														<br /><br />
														Change your banner: 
													<?php
												}
												else
												{
													echo "You did not upload a banner.<br /><br />Add a banner: ";
												}
											?>
											<div id="addBannerDiv"><input type="file" name="banner" id="banner" onchange="addBanner('<?php echo $_SESSION['loggedId'].'-'.date('YmdHis');?>');" /></div>
											<br /><br />
											<span style="font-weight: bold;">Photos</span><br /><br />
										<?php
									}
									$numberOfPhotos = 0;
									$mainPictureRaw = $QL -> query('SELECT * FROM images WHERE main=1 AND banner=0 AND user='.$_SESSION['loggedId']);
									if($mainPicture = $mainPictureRaw -> fetch_array(MYSQLI_ASSOC))
									{
										?>
											<div style="margin-bottom: 10px; float: left; width: 260px;">
												<img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/images/<?php echo $mainPicture['file'];?>" alt="Picture couldn't load" style="float: left; height: 60px; max-width: 60px;"/>
												<div style=" height: 60px; position: relative; left: 5px;">
													<br />
													<span id="editPic<?php echo $mainPicture['id'];?>">(main picture) <a href="javascript:deletePhoto(<?php echo $mainPicture['id'];?>);">Delete</a></span>
												</div>
											</div>
										<?php
										$numberOfPhotos++;
										$otherPictures = $QL -> query('SELECT * FROM images WHERE main=0 AND banner=0 AND user='.$_SESSION['loggedId']);
										while($picture = $otherPictures -> fetch_array(MYSQLI_ASSOC))
										{
											$numberOfPhotos++;
											?>
												<div style="margin-bottom: 10px; float: left; width: 260px;">
													<img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/images/<?php echo $picture['file'];?>" alt="Picture couldn't load" style="float: left; height: 60px; max-width: 60px;"/>
													<div style="height: 60px; position: relative; left: 5px;">
														<br />
														<span id="editPic<?php echo $picture['id'];?>"><a href="javascript:setMainPhoto(<?php echo $picture['id'];?>);">Set as main picture</a> <a href="javascript:deletePhoto(<?php echo $picture['id'];?>);">Delete</a></span>
													</div>
												</div>
											<?php
										}
									}
									else
									{
										echo "You have no photos yet.<br /><br />";
									}
									if($user['type']=='gold' && $numberOfPhotos>=8)
									{
										?>
											<div style="margin-bottom: 10px; width: 260px;">
												<br />You cannot add more than 8 photos with a Gold account.
											</div>
										<?php
									}
									else if($numberOfPhotos>=12)
									{
										?>
											<div style="margin-bottom: 10px; width: 260px;">
												<br />You cannot add more than 12 photos.
											</div>
										<?php
									}
									else
									{
										?>
											<div style="margin-bottom: 10px; width: 260px;">
												<br /><div id="addPhotoDiv">Add a photos: <input type="file" name="photo" id="photo" onchange="addPhoto('<?php echo $_SESSION['loggedId'].'-'.date('YmdHis');?>');" /></div>
											</div>
										<?php
									}
								}
								else if($_POST['part']=='info')
								{
									?>
									<div id="alphanum" style="color: red; display: none;">
										Please fill in all the required info (*). Use only alphanumeric characters, spaces and hyphens.
									</div>
									<div id="emailInc" style="color: red; display: none;">
										Please enter a valid e-mail address.
									</div>
									<div id="emailTaken" style="color: red; display: none;">
										This e-mail address is already assigned to another account.
									</div>
										<table id="myProfileInfoTable">
											<tr>
												<td class="labels" id="fnameLabel">Firstname*: </td>
												<td class="inputs"><input type="text" name="fname" id="fname" value="<?php echo $user['fname'];?>" /></td>

												<td class="labels" id="nationalityLabel" <?php if(preg_match("#agency#i", $user['type'])) { ?>style="color: #BBBBBB;"<?php } ?>>Nationality: </td>
												<td class="inputs"><input type="text" <?php if(preg_match("#agency#i", $user['type'])) { ?>disabled="disabled"<?php } ?> name="nationality" id="nationality" value="<?php echo $user['nationality'];?>" /></td>
											</tr>
											<tr>
												<td class="labels" id="lnameLabel">Lastname*: </td>
												<td class="inputs"><input type="text" name="lname" id="lname" value="<?php echo $user['lname'];?>" /></td>

												<td class="labels" id="ethnicityLabel" <?php if(preg_match("#agency#i", $user['type'])) { ?>style="color: #BBBBBB;"<?php } ?>>Ethnicity: </td>
												<td class="inputs"><input <?php if(preg_match("#agency#i", $user['type'])) { ?>disabled="disabled"<?php } ?> type="text" name="ethnicity" id="ethnicity" value="<?php echo $user['ethnicity'];?>" /></td>
											</tr>
											<tr>
												<td class="labels" <?php if(preg_match("#agency#i", $user['type'])) { ?>style="color: #BBBBBB;"<?php } ?>>Gender<?php if(!preg_match("#agency#i", $user['type'])) { ?>*<?php } ?>: </td>
												<td class="inputs"><select name="gender" id="gender" <?php if(preg_match("#agency#i", $user['type'])) { ?>disabled="disabled"<?php } ?> onchange="if(this.value!='male') { document.getElementById('bustSize').disabled = false; changeSizes('female', 1); } else { document.getElementById('bustSize').disabled = true; if(this.value!='male') { changeSizes('female', 1); } else { changeSizes('male', 1); } }"><?php if(preg_match("#agency#i", $user['type'])) { ?><option value=""> - Leave blank - </option><?php } else { ?><option value="male" <?php if($user['gender']=='male') { echo 'selected="selected"'; }?> >Male</option><option value="female" <?php if($user['gender']=='female') { echo 'selected="selected"'; }?> >Female</option><option value="transexual" <?php if($user['gender']=='transexual') { echo 'selected="selected"'; }?> >Transexual</option><?php } ?></select></td>

												<td class="labels">Birth date*: </td>
												<td class="inputs">
													<div style="display: none;" id="february">
														<?php
															for($i=1;$i<29;$i++)
															{
																?>
																	<option value="<?php echo sprintf('%02.0f', $i);?>" <?php if(date("d", strtotime($user['birthDate']))==$i) { ?>selected="selected"<?php }?>><?php echo $i;?></option>
																<?php
															}
														?>
													</div>
													<div style="display: none;" id="februaryB">
														<?php
															for($i=1;$i<30;$i++)
															{
																?>
																	<option value="<?php echo sprintf('%02.0f', $i);?>" <?php if(date("d", strtotime($user['birthDate']))==$i) { ?>selected="selected"<?php }?>><?php echo $i;?></option>
																<?php
															}
														?>
													</div>
													<div style="display: none;" id="31months">
														<?php
															for($i=1;$i<32;$i++)
															{
																?>
																	<option value="<?php echo sprintf('%02.0f', $i);?>" <?php if(date("d", strtotime($user['birthDate']))==$i) { ?>selected="selected"<?php }?>><?php echo $i;?></option>
																<?php
															}
														?>
													</div>
													<div style="display: none;" id="30months">
														<?php
															for($i=1;$i<31;$i++)
															{
																?>
																	<option value="<?php echo sprintf('%02.0f', $i);?>" <?php if(date("d", strtotime($user['birthDate']))==$i) { ?>selected="selected"<?php }?>><?php echo $i;?></option>
																<?php
															}
														?>
													</div>
													<select id="dayBirth" value="<?php echo date("d", strtotime($user['birthDate']));?>">
														<?php
															for($i=1;$i<32;$i++)
															{
																?>
																	<option value="<?php echo sprintf('%02.0f', $i);?>" <?php if(date("d", strtotime($user['birthDate']))==$i) { ?>selected="selected"<?php }?>><?php echo $i;?></option>
																<?php
															}
														?>
													</select>
													<select id="monthBirth" value="<?php echo date("m", strtotime($user['birthDate']));?>" onchange="var temp=document.getElementById('dayBirth').value; if(this.value==2 && document.getElementById('yearBirth').value%4!=0) { document.getElementById('dayBirth').innerHTML = document.getElementById('february').innerHTML; if(temp>28) { temp = 28; } } else if(this.value==2 && document.getElementById('yearBirth').value%4==0) { document.getElementById('dayBirth').innerHTML = document.getElementById('februaryB').innerHTML; if(temp > 29) { temp = 29; } } else if(this.value==4||this.value==6||this.value==9||this.value==11) { document.getElementById('dayBirth').innerHTML = document.getElementById('30months').innerHTML; if(temp > 30) { temp = 30; }} else { document.getElementById('dayBirth').innerHTML = document.getElementById('31months').innerHTML; } document.getElementById('dayBirth').value = temp;">
														<?php
															for($i=1;$i<13;$i++)
															{
																?>
																	<option value="<?php echo sprintf('%02.0f', $i);?>" <?php if(date("m", strtotime($user['birthDate']))==$i) { ?>selected="selected"<?php }?>><?php echo date("M", strtotime("2011-".$i."-07"));?></option>
																<?php
															}
														?>
													</select>
													<select id="yearBirth" onchange="$('#monthBirth').change();" value="<?php echo date("Y", strtotime($user['birthDate']));?>">
														<?php
															for($i=18;$i<100;$i++)
															{
																?>
																	<option value="<?php echo date("Y") - $i;?>" <?php if(date("Y", strtotime($user['birthDate']))==(date("Y") - $i)) { ?>selected="selected"<?php }?>><?php echo date("Y") - $i;?></option>
																<?php
															}
														?>
													</select>
												</td>
											</tr>
											<tr>
												<td class="labels" <?php if(preg_match("#agency#i", $user['type'])) { ?>style="color: #BBBBBB;"<?php } ?>>Eye colour: </td>
												<td class="inputs"><select id="eyeColour" name="eyeColour" <?php if(preg_match("#agency#i", $user['type'])) { ?>disabled="disabled"<?php } ?>>
													<option value=""> - Leave blank - </option>
													<option value="amber" <?php if($user['eyeColour']=='amber') { echo 'selected="selected"'; } ?>>Amber</option>
													<option value="blue" <?php if($user['eyeColour']=='blue') { echo 'selected="selected"'; } ?>>Blue</option>
													<option value="brown" <?php if($user['eyeColour']=='brown') { echo 'selected="selected"'; } ?>>Brown</option>
													<option value="gray" <?php if($user['eyeColour']=='gray') { echo 'selected="selected"'; } ?>>Gray</option>
													<option value="green" <?php if($user['eyeColour']=='green') { echo 'selected="selected"'; } ?>>Green</option>
													<option value="hazel" <?php if($user['eyeColour']=='hazel') { echo 'selected="selected"'; } ?>>Hazel</option>
													<option value="red/violet" <?php if($user['eyeColour']=='red/violet') { echo 'selected="selected"'; } ?>>Red/Violet</option>
												</select></td>

												<td class="labels" id="emailLabel" <?php if($user['parent']!=0) { ?> style="color: #BBBBBB;" <?php } ?> >E-mail<?php if($user['parent']==0) { echo "*"; } ?>: </td>
												<td class="inputs"><input type="text" id="email" name="email" <?php if($user['parent']!=0) { ?> style="color: #ffffff;" disabled="disabled" <?php } ?> value="<?php echo $user['email'];?>" /></td>
											</tr>
											<tr>
												<td class="labels" id="hairLabel" <?php if(preg_match("#agency#i", $user['type'])) { ?>style="color: #BBBBBB;"<?php } ?>>Hair description: </td>
												<td class="inputs"><input <?php if(preg_match("#agency#i", $user['type'])) { ?>disabled="disabled"<?php } ?> type="text" id="hair" name="hair" value="<?php echo $user['hair'];?>" /></td>

												<td class="labels" id="heightLabel" <?php if(preg_match("#agency#i", $user['type'])) { ?>style="color: #BBBBBB;"<?php } ?>>Height: </td>
												<td class="inputs"><input type="number" <?php if(preg_match("#agency#i", $user['type'])) { ?>disabled="disabled"<?php } ?> id="height" name="height" value="<?php if(!preg_match("#agency#i", $user['type']) && $user['height']!=0) { echo $user['height']; } ?>" /></td>
											</tr>
											<tr>
												<td class="labels" <?php if(preg_match("#agency#i", $user['type'])) { ?>style="color: #BBBBBB;"<?php } ?>>Body type: </td>
												<td class="inputs"><select id="bodyType" name="bodyType" <?php if(preg_match("#agency#i", $user['type'])) { ?>disabled="disabled"<?php } ?>>
												 	<option value=""> - Leave blank - </option>
													<option value="inverted triangle" <?php if(strtolower($user['bodyType'])=='inverted triangle') { echo 'selected="selected"'; } ?>>Inverted Triangle</option>
													<option value="lean column" <?php if(strtolower($user['bodyType'])=='lean column') { echo 'selected="selected"'; } ?>>Lean Column</option>
													<option value="rectangle" <?php if(strtolower($user['bodyType'])=='rectangle') { echo 'selected="selected"'; } ?>>Rectangle</option>
													<option value="apple" <?php if(strtolower($user['bodyType'])=='apple') { echo 'selected="selected"'; } ?>>Apple</option>
													<option value="pear" <?php if(strtolower($user['bodyType'])=='pear') { echo 'selected="selected"'; } ?>>Pear</option>
													<option value="hourglass" <?php if(strtolower($user['bodyType'])=='hourglass') { echo 'selected="selected"'; } ?>>Hourglass</option>
												</select></td>

												<td class="labels" id="dressSizeLabel" <?php if(preg_match("#agency#i", $user['type'])) { ?>style="color: #BBBBBB;"<?php } ?>><?php if($user['gender']=='male') { echo 'S'; } else { echo 'Dress s'; } ?>ize: </td>
												<td class="inputs">
													<select name="dressSize" id="dressSize" <?php if(preg_match("#agency#i", $user['type'])) { ?>disabled="disabled"<?php } ?>>
														<option value=""> - Leave blank - </option>
														<?php
															if($user['gender']=='male')
															{
																?>
																	<option value="XS" <?php if($user['dressSize']=='XS') { echo 'selected="selected"'; } ?>>XS</option>
																	<option value="S" <?php if($user['dressSize']=='S') { echo 'selected="selected"'; } ?>>S</option>
																	<option value="M" <?php if($user['dressSize']=='M') { echo 'selected="selected"'; } ?>>M</option>
																	<option value="L" <?php if($user['dressSize']=='L') { echo 'selected="selected"'; } ?>>L</option>
																	<option value="XL" <?php if($user['dressSize']=='XL') { echo 'selected="selected"'; } ?>>X</option>
																	<option value="XXL" <?php if($user['dressSize']=='XXL') { echo 'selected="selected"'; } ?>>XXL</option>
																<?php
															}
															else if($user['gender']=='female'||$user['gender']=='transexual')
															{
																?>
																	<option value="Zero" <?php if($user['dressSize']=="Zero") { echo 'selected="selected"'; } ?>>Zero</option>
																<?php
																for($i=2;$i<=20;$i=$i+2)
																{
																	?>
																		<option value="<?php echo $i;?>" <?php if(intval($user['dressSize'])==$i) { echo 'selected="selected"'; } ?>><?php echo $i;?></option>
																	<?php
																}
															}
														?>
													</select>
												</td>
											</tr>
											<tr>
												<td class="labels">Country*: </td>
												<td class="inputs">
													<select name="country" style="width: 130px;" id="country" onchange="if(this.value!=0) { document.getElementById('regions').innerHTML = document.getElementById('regionsFrom'+this.value).innerHTML; document.getElementById('regions').disabled = false; } else { document.getElementById('regions').disabled = true; }  $('#regions').change();">
														<?php 
															$countryRaw = $QL -> query('SELECT * FROM countries ORDER BY name');
				     										while($country = $countryRaw -> fetch_array(MYSQLI_ASSOC))
				     										{
				     											?>
																	<option value="<?php echo $country['id'];?>" <?php if($country['id']==$user['country']) { echo 'selected="selected"'; } ?> ><?php echo $country['name'];?></option>
				     											<?php
				     										}
														?>
													</select>
												</td>

												<td class="labels" style="width: 100px;<?php if(preg_match("#agency#i", $user['type'])) { ?> color: #BBBBBB;<?php } ?>">Bust size: </td>
												<td class="inputs">
													<select id="bustSize" name="bustSize" <?php if(preg_match("#agency#i", $user['type'])) { ?>disabled="disabled"<?php } ?>>
														<option value=""> - Leave blank - </option>
														<option value="AA Cup" <?php if($user['bustSize']=='AA Cup') { echo 'selected="selected"'; } ?>>AA Cup</option>
														<option value="A Cup" <?php if($user['bustSize']=='A Cup') { echo 'selected="selected"'; } ?>>A Cup</option>
														<option value="B Cup" <?php if($user['bustSize']=='B Cup') { echo 'selected="selected"'; } ?>>B Cup</option>
														<option value="C Cup" <?php if($user['bustSize']=='C Cup') { echo 'selected="selected"'; } ?>>C Cup</option>
														<option value="D Cup" <?php if($user['bustSize']=='D Cup') { echo 'selected="selected"'; } ?>>D Cup</option>
														<option value="DD Cup" <?php if($user['bustSize']=='DD Cup') { echo 'selected="selected"'; } ?>>DD Cup</option>
														<option value="E Cup" <?php if($user['bustSize']=='E Cup') { echo 'selected="selected"'; } ?>>E Cup</option>
														<option value="F Cup" <?php if($user['bustSize']=='F Cup') { echo 'selected="selected"'; } ?>>F Cup</option>
														<option value="FF Cup" <?php if($user['bustSize']=='FF Cup') { echo 'selected="selected"'; } ?>>FF Cup</option>
														<option value="G Cup" <?php if($user['bustSize']=='G Cup') { echo 'selected="selected"'; } ?>>G Cup</option>
														<option value="GG Cup" <?php if($user['bustSize']=='GG Cup') { echo 'selected="selected"'; } ?>>GG Cup</option>
														<option value="H Cup" <?php if($user['bustSize']=='H Cup') { echo 'selected="selected"'; } ?>>H Cup</option>
														<option value="HH Cup" <?php if($user['bustSize']=='HH Cup') { echo 'selected="selected"'; } ?>>HH Cup</option>
														<option value="J Cup" <?php if($user['bustSize']=='J Cup') { echo 'selected="selected"'; } ?>>J Cup</option>
														<option value="JJ Cup" <?php if($user['bustSize']=='JJ Cup') { echo 'selected="selected"'; } ?>>JJ Cup</option>
														<option value="KK Cup" <?php if($user['bustSize']=='KK Cup') { echo 'selected="selected"'; } ?>>KK Cup</option>
														<option value="L Cup" <?php if($user['bustSize']=='L Cup') { echo 'selected="selected"'; } ?>>L Cup</option>
														<option value="LL Cup" <?php if($user['bustSize']=='LL Cup') { echo 'selected="selected"'; } ?>>LL Cup</option>
														<option value="M Cup" <?php if($user['bustSize']=='M Cup') { echo 'selected="selected"'; } ?>>MM Cup</option>
														<option value="N Cup" <?php if($user['bustSize']=='N Cup') { echo 'selected="selected"'; } ?>>N Cup</option>
													</select>
												</td>
											</tr>
											<tr>
												<td class="labels" id="regionLabel">Region*: </td>
												<td class="inputs">
													<?php 
														$countriesRaw = $QL -> query('SELECT * FROM countries ORDER BY name');
														while($country = $countriesRaw -> fetch_array(MYSQLI_ASSOC))
														{
															?>
																<div style="display: none;" id="regionsFrom<?php echo $country['id'];?>"><option value="0"> - Please select - </option><?php
																$regionsRaw = $QL -> query('SELECT * FROM regions WHERE country ='.$country['id'].' ORDER BY name');
																while($region = $regionsRaw -> fetch_array(MYSQLI_ASSOC))
																{
																	?>
																		<option value="<?php echo $region['id'];?>" <?php if($user['region']==$region['id']) { ?>selected="selected"<?php } ?>><?php echo $region['name'];?></option>
																	<?php
																}
																?></div>
															<?php
														}
													?>
													<select id="regions" name="regions" style="width: 130px;" onchange="if(this.value!=0) { if(document.getElementById('citiesFrom'+document.getElementById('country').value+'_'+this.value).innerHTML!=defaultCityDiv) { document.getElementById('cities').disabled = false; document.getElementById('cities').innerHTML = document.getElementById('citiesFrom'+document.getElementById('country').value+'_'+this.value).innerHTML; } else { document.getElementById('cities').disabled=true; document.getElementById('cities').innerHTML = ''; } } else { document.getElementById('cities').innerHTML=''; document.getElementById('cities').disabled = true; }"></select>
												</td>

												<td class="labels" <?php if(preg_match("#agency#i", $user['type'])) { ?>style="color: #BBBBBB;"<?php } ?>>Smoking: </td>
												<td class="inputs"><input <?php if(preg_match("#agency#i", $user['type'])) { ?>disabled="disabled"<?php } ?> type="checkbox" id="smoking" name="smoking" <?php if($user['smoke']==1) { echo 'checked="checked"'; } ?> /></td>
											</tr>
											<tr>
												<td class="labels" id="cityLabel">Nearest city*: </td>
												<td class="inputs">
													<?php 
														$regionsRaw = $QL -> query('SELECT * FROM regions');
														while($region = $regionsRaw -> fetch_array(MYSQLI_ASSOC))
														{
															?>
																<div style="display: none;" id="citiesFrom<?php echo $region['country'].'_'.$region['id'];?>"><option value="0"> - Please select - </option><?php
																$citiesRaw = $QL -> query('SELECT * FROM cities WHERE region ='.$region['id'].' ORDER BY name');
																while($city = $citiesRaw -> fetch_array(MYSQLI_ASSOC))
																{
																	?>
																		<option value="<?php echo $city['id'];?>" <?php if($user['city']==$city['id']) { ?>selected="selected"<?php } ?>><?php echo $city['name'];?></option>
																	<?php
																}
																?></div>
															<?php
														}
													?>
													<select id="cities" name="cities" style="width: 130px;" ></select>
												</td>

												<td class="labels" <?php if(preg_match("#agency#i", $user['type'])) { ?>style="color: #BBBBBB;"<?php } ?>>Disable friendly: </td>
												<td class="inputs"><input <?php if(preg_match("#agency#i", $user['type'])) { ?>disabled="disabled"<?php } ?> type="checkbox" id="disableFriendly" name="disableFriendly" <?php if($user['disableFriendly']==1) { echo 'checked="checked"'; } ?> /></td>
											</tr>
											<tr>
												<td class="labels" id="postCodeLabel">Post code*:</td>
												<td class="inputs"><input type="text" id="postCode" name="postCode" value="<?php echo $user['postCode'];?>" /></td>

												<td class="labels" <?php if(preg_match("#agency#i", $user['type'])) { ?>style="color: #BBBBBB;"<?php } ?>>Shaved: </td>
												<td class="inputs"><input <?php if(preg_match("#agency#i", $user['type'])) { ?>disabled="disabled"<?php } ?> type="checkbox" id="shaved" name="shaved" <?php if($user['shaved']==1) { echo'checked="checked"'; } ?> /></td>
											</tr>
										</table>
										<div style="text-align: right;">
											<input type="button" value="Save changes" id="saveNotClose" onclick="updateInfo(0, '<?php echo $user['type'];?>');" />
										</div>
									<?php
								}
								else if($_POST['part']=='account')
								{
									if(strtolower($user['type'])=='agency')
									{
										?>
											<div style="font-weight: bold; margin-top: 10px; margin-bottom: 10px;">
												My account
											</div>
										<?php
									}
									?>
									<div id="alphanum" style="color: red; display: none;">
										Please choose a nickname with only alphanumeric characters, spaces and hyphens.
									</div>
									<div id="nicknameTaken" style="color: red; display: none;">
										This nickname is already taken. Please choose a different one.
									</div>
									<div id="enterPw">
										Please enter your current password for any action in this section.
										<br /><br />
									</div>
									<div id="incorrectPw" style="display: none; color: red;">
										Incorrect password.
										<br /><br />
									</div>
										<table>
											<tr>
												<td>
													Nickname: 
												</td>
												<td>
													<input type="text" name="nickname" id="nickname" value="<?php echo $user['nickname'];?>" />
												</td>
											</tr>
											<tr>
												<td>
													New password: 
												</td>
												<td>
													<input type="password" name="newPassword" id="newPassword" />
												</td>
											</tr>
											<tr>
												<td>
													<br />
													Current password: 
												</td>
												<td>
													<br />
													<input type="password" name="oldPassword" id="oldPassword" />
												</td>
											</tr>
										</table>
										<br />
										<input type="button" value="Save changes" id="saveNotClose" onclick="checkAccountChanges(0);" /> 
										<?php
											if($user['parent']==0)
											{
												?>
													<input type="button" value="Withdraw my current membership" id="withdrawMembership" onclick="withdraw(0);" /> <input type="button" value="Delete my account" id="deleteAccount" onclick="withdraw(1);" />
												<?php
											}
										?>
									<?php
									if(strtolower($user['type'])=='agency')
									{
										?>
											<div style="font-weight: bold; margin-top: 10px; margin-bottom: 10px;">
												Sub-accounts
											</div>
											<table id="accountsTable">
												<?php 
													$subAcRaw = $QL -> query('SELECT * FROM advertisers WHERE parent='.$_SESSION['loggedId']);
													$i = 0;
													while($subAc = $subAcRaw -> fetch_array(MYSQLI_ASSOC))
													{
														echo '<tr id="subAccount'.$subAc['id'].'"><td>';
														echo $subAc['nickname'];
														/*
														Uncomment if need to go back to old sub-account method
														if($subAc['nickname']!='')
														{
															echo ' (running) ';
														}
														else
														{
															echo ' (waiting for set up) ';
														}
														*/
														echo "</td><td>";
														echo '&nbsp;&nbsp;&nbsp;<span id="deleteSeparate'.$subAc['id'].'" style="font-size: 12px;">';
														/*
														Uncomment if need to go back to old sub-account method
														if($subAc['nickname']!='')
														{
															echo '<a href="javascript:separateAccount('.$subAc['id'].');">Separate account</a>';
														}
														else
														{
															echo '<span style="color: #bbb;">Separate account</span>';
														}
														*/
														echo '&nbsp;&nbsp;&nbsp;<a href="javascript:deleteAccount('.$subAc['id'].');">Delete</a></span>';
														echo "</td></tr>";
														$i++;
													}
												?>
											</table>
											<?php
												if($i<19)
												{
													?>
														<div id="addAccountBlurb">
															<!-- 
															Uncomment to get ol sub-account way back
															<div id="createAcInstructions">
																To create a sub-account, enter the person's e-mail address below.<br />An e-mail will be sent to them to set up their account.<br />
															</div>
															<div id="emailTaken" style="display: none; color: red;">
																This e-mail address is already assigned to an account.
															</div>
															<div id="invalidEmail" style="display: none; color: red;">
																Please enter a valid e-mail address.
															</div>
															<input type="text" id="email" name="email" /> <input type="button" value="Add account" id="addAccount" name="addAccount" onclick="addAccount();" />
															-->
															<input type="button" value="Click here to add a sub-account" id="addAccount" name="addAccount" onclick="showProfile('setupAccount');" />
														</div>
													<?php
												}
												else
												{
													?>
														<div id="addAccountBlurb">
															<br />
															You have reached the maximum number of accounts
														</div>
													<?php
												}
											?>
										<?php
									}
								}
								else if($_POST['part']=='setupAccount')
								{
									if(isset($_POST['fname']) && $_POST['fname']!='undefined')
									{
										if($_POST['fname']=="" || preg_match("#[^ a-zA-Z0-9-]#", $_POST['fname']))
										{
											$error=1;
											$errorFname=1;
										}
										if($_POST['lname']=="" || preg_match("#[^ a-zA-Z0-9-]#", $_POST['lname']))
										{
											$error=1;
											$errorLname=1;
										}
										if($_POST['gender']=="")
										{
											$error=1;
											$errorGender=1;
										}
										if($_POST['birthDay']=="0" || $_POST['birthMonth']=="0" || $_POST['birthYear']=="0" || ($_POST['birthDay']==31 && ($_POST['birthMonth']==4||$_POST['birthMonth']==6||$_POST['birthMonth']==9||$_POST['birthMonth']==11)) || ($_POST['birthDay']>29 && $_POST['birthMonth']==2 && is_int($_POST['birthYear']/4)) || ($_POST['birthDay']>28 && $_POST['birthMonth']==2 && !(is_int($_POST['birthYear']/4))))
										{
											$error=1;
											$errorBdate=1;
										}
										if($_POST['nickname']=="" || preg_match("#[^ a-zA-Z0-9-]#", $_POST['nickname']))
										{
											$error=1;
											$errorNickname=1;
										}
										$result = $QL -> query('SELECT COUNT(*) AS ofNicknames FROM advertisers WHERE UPPER(nickname)=UPPER("'.$_POST['nickname'].'")');
										$number = $result -> fetch_assoc();
										if($number['ofNicknames']!=0||$_POST['nickname']=='Nickname')
										{
											$error=1;
											$errorNicknameTaken=1;
										}
										if(strlen($_POST['password'])<4)
										{
											$error=1;
											$errorPassword=1;
										}
										if($_POST['region']==0)
										{
											$error=1;
											$errorRegion=1;
										}
										if($_POST['cityComp']==1 && $_POST['city']==0)
										{
											$error=1;
											$errorCity=1;
										}
										if($_POST['postCode']=="" || preg_match('#[^ a-zA-Z0-9-]#', $_POST['postCode']))
										{
											$error=1;
											$errorPostCode=1;
										}
										if(preg_match('#[^ a-zA-Z0-9-]#', $_POST['nationality']))
										{
											$error=1;
											$errorNationality=1;
										}
										if(preg_match('#[^ a-zA-Z0-9-]#', $_POST['ethnicity']))
										{
											$error=1;
											$errorEthnicity=1;
										}
										if(preg_match('#[^ a-zA-Z0-9-]#', $_POST['hairDescription']))
										{
											$error=1;
											$errorHairDescription=1;
										}
										if($_POST['height']!="" && ($_POST['height']<60 || $_POST['height']>230))
										{
											$error=1;
											$errorHeight=1;
										}
										if($error==0)
										{
											$complete = 1;
										}
									}
									if($error==0 && $complete==1)
									{
										if($_POST['shaved']=='true')
										{
											$shaved = 1;
										}
										else
										{
											$shaved = 0;
										}
										if($_POST['smoking']=='true')
										{
											$smoke = 1;
										}
										else
										{
											$smoke = 0;
										}
										if($_POST['disableFriendly']=='true')
										{
											$disableFriendly = 1;
										}
										else
										{
											$disableFriendly = 0;
										}
										if($QL -> query('INSERT INTO advertisers (email, fname, lname, nickname, password, region, country, city, featured, gender, dressSize, bustSize, bodyType, birthDate, nationality, ethnicity, eyeColour, height, shaved, disableFriendly, smoke, hair, postCode, parent) VALUES ("noemail'.$_SESSION['loggedId'].'@escortcentral.com.au", "'.$_POST['fname'].'", "'.$_POST['lname'].'", "'.$_POST['nickname'].'", "'.md5($_POST['password']).'", "'.$_POST['region'].'", "'.$currentCountry.'", "'.$_POST['city'].'", 0, "'.$_POST['gender'].'", "'.$_POST['dressSize'].'", "'.$_POST['bustSize'].'", "'.$_POST['bodyType'].'", "'.$_POST['birthYear'].'-'.$_POST['birthMonth'].'-'.$_POST['birthDay'].'", "'.$_POST['nationality'].'", "'.$_POST['ethnicity'].'", "'.$_POST['eyeColour'].'", "'.$_POST['height'].'", "'.$shaved.'", "'.$disableFriendly.'", "'.$smoke.'", "'.$_POST['hairDescription'].'", "'.$_POST['postCode'].'", '.$_SESSION['loggedId'].')'))
										{
											$idS = $QL -> insert_id;
											$parentRaw = $QL -> query('SELECT * FROM advertisers INNER JOIN membership ON membership.user=advertisers.id WHERE advertisers.id='.$_SESSION['loggedId']);
											$parent = $parentRaw -> fetch_array(MYSQLI_ASSOC);
						     				$QL -> query('INSERT INTO membership (type, user, paid, autoRenew, expiry) VALUES ("platinum", "'.$idS.'", 1, 1, "'.$parent['expiry'].'")');
						     				if($_POST['mainService']!='')
						     				{
						     					$QL -> query('INSERT INTO advertisers_services (advertiser, service) VALUES ("'.$idS.'", "'.$_POST['mainService'].'")');
						     				}
											?>
												<div style="padding: 5px; padding-top: 0;">
													This sub-account is set up! You can now login with it's nickname and password and enter optional profile information such as photos.
													<br /><br />
													<input type="button" value="Okay" onclick="showProfile('account');" />
												</div>
											<?php
										}
										else
										{
											?>
												<div style="padding: 5px; padding-top: 0;">
													<span style="font-weight: bold;">Sorry.</span> <br />
													There was an error during the sub-account's creation. Please try again later.
													<br /><br />
													<input type="button" value="Okay" onclick="showProfile('account');" />
												</div>
											<?php
										}
									}
									else
									{
										?>
											<div style="padding: 5px; padding-top: 0;">
												<div <?php if(isset($error) && $error==1) { ?>style="color: red;"<?php }?>>
													<?php 
														if((isset($errorFname) && $errorFname==1)||(isset($errorLname) && $errorLname==1)||(isset($errorNickname) && $errorNickname==1)||(isset($errorPostCode) && $errorPostCode==1)||(isset($errorNationality) && $errorNationality==1)||(isset($errorEthnicity) && $errorEthnicity==1)||(isset($errorHairDescription) && $errorHairDescription==1))
														{
															echo "Please fill in all the required info (*). Use only alphanumeric characters, spaces and hyphens";
														}
														else if(isset($errorNicknameTaken) && $errorNicknameTaken==1)
														{
															echo "This nickname is already assigned to an account, try logging in instead";
														}
														else if(isset($errorPassword) && $errorPassword==1)
														{
															echo "Your password needs to be at least 4 characters long";
														}
														else if(isset($errorHeight) && $errorHeight==1)
														{
															echo "Please enter a valid height";
														}
														else
														{
															echo "Please provide the following information about your escort:";
														}
													?>
												</div>
												<br />
												<table id="registerFormTable">
													<tr>
														<td <?php if(isset($errorFname) && $errorFname==1) { ?>style="color: red;"<?php } ?>>Firstname*:</td>
														<td class="info"><input type="text" id="fname" <?php if(isset($_POST['fname']) && $_POST['fname']!='undefined') { ?>value="<?php echo htmlspecialchars($_POST['fname']);?>"<?php }?> /></td>

														<td class="label" <?php if(isset($errorNationality) && $errorNationality==1) { ?>style="color: red;"<?php } ?>>Nationality:</td>
														<td class="info"><input type="text" id="nationality" <?php if(isset($_POST['nationality']) && $_POST['nationality']!='undefined') { ?>value="<?php echo htmlspecialchars($_POST['nationality']);?>"<?php }?> /></td>
													</tr>
													<tr>
														<td <?php if(isset($errorLname) && $errorLname==1) { ?>style="color: red;"<?php } ?>>Lastname*:</td>
														<td class="info"><input type="text" id="lname" <?php if(isset($_POST['lname']) && $_POST['lname']!='undefined') { ?>value="<?php echo htmlspecialchars($_POST['lname']);?>"<?php }?> /></td>

														<td class="label" <?php if(isset($errorEthnicity) && $errorEthnicity==1) { ?>style="color: red;"<?php } ?>>Ethnicity:</td>
														<td class="info"><input type="text" id="ethnicity" <?php if(isset($_POST['ethnicity']) && $_POST['ethnicity']!='undefined') { ?>value="<?php echo htmlspecialchars($_POST['ethnicity']);?>"<?php }?> /></td>
													</tr>
													<tr>
														<td <?php if(isset($errorGender) && $errorGender==1) { ?>style="color: red;"<?php } ?>>Gender*:</td>
														<td class="info"><select id="gender" onchange="if(this.value!='male') { document.getElementById('bustSize').disabled = false; changeSizes('female', 0); } else { document.getElementById('bustSize').disabled = true; if(this.value!='male') { changeSizes('female', 0); } else { changeSizes('male', 0); } }"><option value="<?php if(isset($_POST['gender']) && $_POST['gender']!='undefined') { echo $_POST['gender']; }?>"><?php if(isset($_POST['gender']) && $_POST['gender']!='' && $_POST['gender']!='undefined') { echo $_POST['gender']; } else { echo " - Please select - "; } ?></option><option value="male">Male</option><option value="female">Female</option><option value="transexual">Transexual</option></select></td>

														<td class="label" <?php if(isset($errorService) && $errorService=1) { ?>style="color: red;"<?php } ?>>Main service:</td>
														<td class="info">
															<select id="service" >
																<option value="0"> - Leave blank - </option>
																<?php
																	$services = $QL -> query('SELECT * FROM services');
																	while($service = $services -> fetch_array(MYSQLI_ASSOC))
																	{
																		?>
																			<option value="<?php echo $service['id'];?>" <?php if(isset($_POST['mainService']) && $_POST['mainService']==$service['id']) { ?>selected="selected"<?php } ?>><?php echo $service['name'];?></option>
																		<?php
																	}
																?>
															</select>
														</td>
													</tr>
													<tr>
														<td <?php if(isset($errorBdate) && $errorBdate==1) { ?>style="color: red;"<?php } ?>>Birth date*:</td>
														<td class="info">
														<div style="display: none;" id="february">
															<?php
																for($i=1;$i<29;$i++)
																{
																	?>
																		<option value="<?php echo $i;?>" <?php if(isset($_POST['birthDay']) && $_POST['birthDay']==$i) { ?>selected="selected"<?php }?>><?php echo $i;?></option>
																	<?php
																}
															?>
														</div>
														<div style="display: none;" id="februaryB">
															<?php
																for($i=1;$i<30;$i++)
																{
																	?>
																		<option value="<?php echo $i;?>" <?php if(isset($_POST['birthDay']) && $_POST['birthDay']==$i) { ?>selected="selected"<?php }?>><?php echo $i;?></option>
																	<?php
																}
															?>
														</div>
														<div style="display: none;" id="31months">
															<?php
																for($i=1;$i<32;$i++)
																{
																	?>
																		<option value="<?php echo $i;?>" <?php if(isset($_POST['birthDay']) && $_POST['birthDay']==$i) { ?>selected="selected"<?php }?>><?php echo $i;?></option>
																	<?php
																}
															?>
														</div>
														<div style="display: none;" id="30months">
															<?php
																for($i=1;$i<31;$i++)
																{
																	?>
																		<option value="<?php echo $i;?>" <?php if(isset($_POST['birthDay']) && $_POST['birthDay']==$i) { ?>selected="selected"<?php }?>><?php echo $i;?></option>
																	<?php
																}
															?>
														</div>
															<select id="dayBirth">
																<?php
																	for($i=1;$i<32;$i++)
																	{
																		?>
																			<option value="<?php echo $i;?>" <?php if(isset($_POST['birthDay']) && $_POST['birthDay']==$i) { ?>selected="selected"<?php }?>><?php echo $i;?></option>
																		<?php
																	}
																?>
															</select>
															<select id="monthBirth" onchange="var temp=document.getElementById('dayBirth').value; if(this.value==2 && document.getElementById('yearBirth').value%4!=0) { document.getElementById('dayBirth').innerHTML = document.getElementById('february').innerHTML; if(temp>28) { temp = 28; } } else if(this.value==2 && document.getElementById('yearBirth').value%4==0) { document.getElementById('dayBirth').innerHTML = document.getElementById('februaryB').innerHTML; if(temp > 29) { temp = 29; } } else if(this.value==4||this.value==6||this.value==9||this.value==11) { document.getElementById('dayBirth').innerHTML = document.getElementById('30months').innerHTML; if(temp > 30) { temp = 30; }} else { document.getElementById('dayBirth').innerHTML = document.getElementById('31months').innerHTML; } document.getElementById('dayBirth').value = temp;">
																<?php
																	for($i=1;$i<13;$i++)
																	{
																		?>
																			<option value="<?php echo $i;?>" <?php if(isset($_POST['birthMonth']) && $_POST['birthMonth']==$i) { ?>selected="selected"<?php }?>><?php echo date("M", strtotime("2011-".$i."-07"));?></option>
																		<?php
																	}
																?></select>
																<select id="yearBirth" <?php if(isset($_POST['birthYear'])) { ?>value="<?php echo $_POST['birthYear'];?>"<?php }?> onchange="$('#monthBirth').change();">
																	<?php
																		for($i=18;$i<100;$i++)
																		{
																			?>
																				<option value="<?php echo date("Y") - $i;?>" <?php if(isset($_POST['birthYear']) && $_POST['birthYear']==(date("Y") - $i)) { ?>selected="selected"<?php }?>><?php echo date("Y") - $i;?></option>
																			<?php
																		}
																	?>
																</select></td>

														<td class="label" <?php if(isset($errorEyeColour) && $errorEyeColour==1) { ?>style="color: red;"<?php } ?>>Eye colour:</td>
														<td class="info">
															<select id="eyeColour" <?php if(isset($_POST['eyeColour']) && $_POST['eyeColour']!='undefined') { ?>value="<?php echo $_POST['eyeColour'];?>"<?php }?>>
																<option value="<?php if(isset($_POST['eyeColour']) && $_POST['eyeColour']!='undefined') { echo $_POST['eyeColour']; } ?>"><?php if(isset($_POST['eyeColour']) && $_POST['eyeColour']!='' && $_POST['eyeColour']!='undefined') { echo $_POST['eyeColour']; } else { echo " - Leave blank - "; } ?></option>
																<option value="amber">Amber</option>
																<option value="blue">Blue</option>
																<option value="brown">Brown</option>
																<option value="gray">Gray</option>
																<option value="green">Green</option>
																<option value="hazel">Hazel</option>
																<option value="red/violet">Red/Violet</option>
																<option value=""> - Leave blank - </option>
															</select>
														</td>
													</tr>
													<tr>
														<td style="color: #BBBBBB;">Email:</td>
														<td class="info"><input type="text" disabled="disabled" id="email" /></td>

														<td class="label" <?php if(isset($errorHairDescription) && $errorHairDescription==1) { ?>style="color: red;"<?php } ?>>Hair description:</td>
														<td class="info"><input type="text" id="hair" <?php if(isset($_POST['hairDescription']) && $_POST['hairDescription']!='undefined') { ?>value="<?php echo htmlspecialchars($_POST['hairDescription']);?>"<?php }?> /></td>
													</tr>
													<tr>
														<td <?php if((isset($errorNickname) && $errorNickname==1)||(isset($errorNicknameTaken) && $errorNicknameTaken==1)) { ?>style="color: red;"<?php } ?>>Nickname*:</td>
														<td class="info"><input type="text" id="nickname" <?php if(isset($_POST['nickname']) && $_POST['nickname']!='undefined') { ?>value="<?php echo htmlspecialchars($_POST['nickname']);?>"<?php }?> /></td>

														<td class="label" <?php if(isset($errorHeight) && $errorHeight==1) { ?>style="color: red;"<?php } ?>>Height (cm):</td>
														<td class="info"><input type="number" id="height" <?php if(isset($_POST['height'])) { ?>value="<?php echo htmlspecialchars($_POST['height']);?>"<?php }?> /></td>
													</tr>
													<tr>
														<td <?php if(isset($errorPassword) && $errorPassword==1) { ?>style="color: red;"<?php } ?>>Password*:</td>
														<td class="info"><input type="password" id="password" <?php if(isset($_POST['password']) && $_POST['password']!='undefined') { ?>value="<?php echo htmlspecialchars($_POST['password']);?>"<?php }?> /></td>

														<td class="label" <?php if(isset($errorBodyType) && $errorBodyType==1) { ?>style="color: red;"<?php } ?>>Body type:</td>
														<td class="info"><select id="bodyType" <?php if(isset($_POST['bodyType']) && $_POST['bodyType']!='undefined') { ?>value="<?php echo $_POST['bodyType'];?>"<?php }?>>
															<option value="<?php if(isset($_POST['bodyType']) && $_POST['bodyType']!='undefined') { echo $_POST['bodyType']; } ?>"><?php if(isset($_POST['bodyType']) && $_POST['bodyType']!='' && $_POST['bodyType']!='undefined') { echo $_POST['bodyType']; } else { echo " - Leave blank - "; } ?></option>
															<option value="inverted triangle">Inverted Triangle</option>
															<option value="lean column">Lean Column</option>
															<option value="rectangle">Rectangle</option>
															<option value="apple">Apple</option>
															<option value="pear">Pear</option>
															<option value="hourglass">Hourglass</option>
															<option value="0"> - Leave blank - </option>
														</select></td>
													</tr>
													<tr>
														<td <?php if(isset($errorRegion) && $errorRegion==1) { ?>style="color: red;"<?php } ?>>Region*:</td>
														<td class="info">
															<select id="regionSelect" onchange="if(this.value==0) { document.getElementById('citiesSelect').innerHTML =''; document.getElementById('citiesSelect').disabled = true; document.getElementById('cityComp').value='0'; } else { if(document.getElementById('citiesFrom'+this.value).innerHTML!=defaultCityDiv) { document.getElementById('cityComp').value='1'; document.getElementById('citiesSelect').disabled = false; document.getElementById('citiesSelect').innerHTML = document.getElementById('citiesFrom'+this.value).innerHTML; } else { document.getElementById('cityComp').value='0'; document.getElementById('citiesSelect').disabled = true; } }">
																<option value="0"> - Please select - </option>
																<?php
																	$regionsRaw = $QL -> query('SELECT * FROM regions WHERE country ='.$currentCountry.' ORDER BY name');
																	while($region = $regionsRaw -> fetch_array(MYSQLI_ASSOC))
																	{
																		?>
																			<option value="<?php echo $region['id'];?>" <?php if(isset($_POST['region']) && $_POST['region']==$region['id']) { ?>selected="selected"<?php } ?>><?php echo $region['name'];?></option>
																		<?php
																	}
																?>
															</select>
														</td>

														<td class="label" <?php if(isset($errorDressSize) && $errorDressSize==1) { ?>style="color: red;"<?php } ?> id="dressSizeLabel">Dress size (US):</td>
														<td class="info"><select id="dressSize" <?php if(isset($_POST['dressSize']) && $_POST['dressSize']!='undefined') { ?>value="<?php echo $_POST['dressSize'];?>"<?php }?>>
															<option value="<?php if(isset($_POST['dressSize']) && $_POST['dressSize']!='undefined') { echo $_POST['dressSize']; } ?>"><?php if(isset($_POST['dressSize']) && $_POST['dressSize']!='' && $_POST['dressSize']!='undefined') { echo $_POST['dressSize']; } else { echo " - Leave blank - "; } ?></option>
															<?php 
																if(isset($_POST['gender']) && $_POST['gender']=='male')
																{
																	?>
																		<option value="XS">XS</option>
																		<option value="S">S</option>
																		<option value="M">M</option>
																		<option value="L">L</option>
																		<option value="XL">X</option>
																		<option value="XXL">XXL</option>
																	<?php
																}
																else
																{
																	?>
																		<option value="Zero">Zero</option>
																		<option value="2">2</option>
																		<option value="4">4</option>
																		<option value="6">6</option>
																		<option value="8">8</option>
																		<option value="10">10</option>
																		<option value="12">12</option>
																		<option value="14">14</option>
																		<option value="16">16</option>
																		<option value="18">18</option>
																		<option value="20">20</option>
																	<?php
																}
															?>
															<option value=""> - Leave blank - </option>
														</select></td>
													</tr>
													<tr>
														<td <?php if(isset($errorCity) && $errorCity==1) { ?>style="color: red;"<?php } ?>>Nearest city*:</td>
														<td class="info">
															<?php 
																$regionsRaw = $QL -> query('SELECT * FROM regions WHERE country ='.$currentCountry.' ORDER BY name');
																while($region = $regionsRaw -> fetch_array(MYSQLI_ASSOC))
																{
																	?>
																		<div style="display: none;" id="citiesFrom<?php echo $region['id'];?>"><option value="0"> - Please select - </option><?php
																		$citiesRaw = $QL -> query('SELECT * FROM cities WHERE region ='.$region['id'].' ORDER BY name');
																		while($city = $citiesRaw -> fetch_array(MYSQLI_ASSOC))
																		{
																			?>
																				<option value="<?php echo $city['id'];?>" <?php if(isset($_POST['city']) && $_POST['city']==$city['id']) { ?>selected="selected"<?php } ?>><?php echo $city['name'];?></option>
																			<?php
																		}
																		?></div>
																	<?php
																}
															?>
															<select id="citiesSelect" disabled="disabled" onload="$('#regionSelect').change();">
															</select>
															<input type="hidden" name="cityComp" id="cityComp" <?php if(isset($_POST['cityComp'])) { echo 'value="'.$_POST['cityComp'].'"'; } else { echo 'value="0"'; } ?> />
														</td>

														<td class="label" <?php if(isset($errorBustSize) && $errorBustSize==1) { ?>style="color: red;"<?php } ?>>Bust size:</td>
														<td class="info"><select id="bustSize" <?php if(isset($_POST['bustSize']) && $_POST['bustSize']!='undefined') { ?>value="<?php echo $_POST['bustSize'];?>"<?php }?>>
															<option value="<?php if(isset($_POST['bustSize']) && $_POST['bustSize']!='undefined') { echo $_POST['bustSize']; } ?>"><?php if(isset($_POST['bustSize']) && $_POST['bustSize']!='' && $_POST['bustSize']!='undefined') { echo $_POST['bustSize']; } else { echo " - Leave blank - "; } ?></option>
															<option value="AA Cup">AA Cup</option>
															<option value="A Cup">A Cup</option>
															<option value="B Cup">B Cup</option>
															<option value="C Cup">C Cup</option>
															<option value="D Cup">D Cup</option>
															<option value="DD Cup">DD Cup</option>
															<option value="E Cup">E Cup</option>
															<option value="F Cup">F Cup</option>
															<option value="FF Cup">FF Cup</option>
															<option value="G Cup">G Cup</option>
															<option value="GG Cup">GG Cup</option>
															<option value="H Cup">H Cup</option>
															<option value="HH Cup">HH Cup</option>
															<option value="J Cup">J Cup</option>
															<option value="JJ Cup">JJ Cup</option>
															<option value="KK Cup">KK Cup</option>
															<option value="L Cup">L Cup</option>
															<option value="LL Cup">LL Cup</option>
															<option value="M Cup">MM Cup</option>
															<option value="N Cup">N Cup</option>
															<option value=""> - Leave blank - </option>
														</select></td>
													</tr>
													<tr>
														<td <?php if(isset($errorPostCode) && $errorPostCode==1) { ?>style="color: red;"<?php } ?>>Post code*:</td>
														<td class="info"><input type="text" id="postCode" <?php if(isset($_POST['postCode']) && $_POST['postCode']!='undefined') { ?>value="<?php echo htmlspecialchars($_POST['postCode']);?>"<?php }?> /></td>

														<td class="label">Smoking:</td>
														<td class="info"><input type="checkbox" id="smoking" <?php if(isset($_POST['smoking']) && $_POST['smoking']=='true') { ?>checked="checked"<?php }?> /></td>
													</tr>
													<tr>
														<td>Shaved:</td>
														<td class="info"><input type="checkbox" id="shaved" <?php if(isset($_POST['shaved']) && $_POST['shaved']=='true') { ?>checked="checked"<?php }?> /></td>

														<td class="label">Disable friendly:</td> 
														<td class="info"><input type="checkbox" id="disableFriendly" <?php if(isset($_POST['disableFriendly']) && $_POST['disableFriendly']=='true') { ?>checked="checked"<?php }?> /></td>
													</tr>
												</table>
												<br />
												<div style="text-align: center;"><input type="button" value="Next" onclick="setupSubAccount(document.getElementById('fname').value, document.getElementById('lname').value, document.getElementById('gender').value, document.getElementById('dayBirth').value, document.getElementById('monthBirth').value, document.getElementById('yearBirth').value, document.getElementById('email').value, document.getElementById('nickname').value, document.getElementById('password').value, document.getElementById('regionSelect').value, document.getElementById('citiesSelect').value, document.getElementById('postCode').value, document.getElementById('shaved').checked, document.getElementById('nationality').value, document.getElementById('ethnicity').value, document.getElementById('service').value, document.getElementById('eyeColour').value, document.getElementById('hair').value, document.getElementById('height').value, document.getElementById('bodyType').value, document.getElementById('dressSize').value, document.getElementById('bustSize').value, document.getElementById('smoking').checked, document.getElementById('disableFriendly').checked, document.getElementById('cityComp').value);" /></div>
											</div>
										<?php
									}
								}
								else
								{
									?>
									<div id="welcomeDiv" style="display: none;">
										Welcome to Escort Central! Your membership is now activated and your profile is visible to everyone. <br />
										Below you can add more information to your profile<br /><br />
									</div>
										<table class="profileTable">
											<tr>
												<td>Contact: </td>
												<td class="profileRight">
													<div style="float: right; position: relative; z-index: 600; max-height: 40px;">
														<img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/design/facebook.png" alt="Facebook:" style="margin: 0; padding: 0; height: 19px; position: relative; top: -3px;"/> <input type="text" id="facebook" <?php if($user['parent']!=0) { ?>disabled="disabled"<?php } ?> style="width: 250px; position: relative; top: -8px;" onchange="profileChanged=1;" value="<?php echo htmlspecialchars($user['facebook']);?>"/><br />
														<img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/design/twitter.png" alt="Twitter:" style="margin: 0; padding: 0; width: 19px; position: relative; top: -9px;"/> <input type="text" id="twitter" <?php if($user['parent']!=0) { ?>disabled="disabled"<?php } ?> onchange="profileChanged=1;" style="width: 250px; position: relative; top: -12px;" value="<?php echo htmlspecialchars($user['twitter']);?>"/><br />
													</div>
													<div style="width: 700px; position: relative; right: 15px; z-index: 500;<?php if($user['parent']!=0) { ?>color: #BBBBBB;<?php } ?>">
														Phone: <input type="text" <?php if($user['parent']!=0) { ?>disabled="disabled"<?php } ?> onchange="profileChanged=1;" id="phoneNum" style="width: 250px;" value="<?php echo htmlspecialchars($user['phone']);?>"/><br />
														Website: <input type="text" <?php if($user['parent']!=0) { ?>disabled="disabled"<?php } ?> onchange="profileChanged=1;" id="website" style="width: 250px;" value="<?php echo htmlspecialchars($user['website']);?>"/><br />
													</div>
													<input type="button" <?php if($user['parent']!=0) { ?>disabled="disabled"<?php } ?> id="saveContact" value="Save" onclick="saveContact();" />
												</td>
											</tr>
											<tr>
												<td>About<?php if(!preg_match('#agency#i', $user['type'])) { ?> Me<?php } ?>: </td>
												<td class="profileRight">
													<textarea style="resize: none; width: 560px; height: 125px;" onchange="profileChanged=1;" id="myDescription"><?php echo htmlspecialchars($user['description']);?></textarea><br />
													<input type="button" value="Save" id="saveDescription" onclick="changeDescription();" />
												</td>
											</tr>
											<?php
											/*
											Favourites, if ever needed again
											?>
											<tr>
												<td>Favourites: </td>
												<td class="profileRight">
													<span id="exisitingFavs">
														<?php
															$favourites_raw = $QL -> query('SELECT * FROM advertisers_favourites INNER JOIN favourites ON favourites.id=advertisers_favourites.favourite WHERE advertiser='.$_SESSION['loggedId']);
															while($favourite = $favourites_raw -> fetch_array(MYSQLI_ASSOC))
															{
																?>
																	<span id="favourite<?php echo $favourite['idF'];?>"><?php echo $favourite['name'];?> <span id="deleteFavourite<?php echo $favourite['idF'];?>"><a href="javascript:deleteFavourite(<?php echo $favourite['idF'];?>);">delete</a></span><br /></span>
																<?php
															}
														?>
													</span>
													<br />
													<select id="favouritesSelect">
														<option value="0"> - Select - </option>
														<?php
															$favourites_raw = $QL -> query('SELECT * FROM favourites');
															while($favourite = $favourites_raw -> fetch_array(MYSQLI_ASSOC))
															{
																$result = $QL -> query('SELECT COUNT(*) AS ofFavourites FROM advertisers_favourites WHERE favourite='.$favourite['id'].' AND advertiser='.$_SESSION['loggedId']);
																$number = $result -> fetch_assoc();
																if($number['ofFavourites']==0)
																{
																	?>
																		<option value="<?php echo $favourite['id'];?>"><?php echo $favourite['name'];?></option>
																	<?php
																}
															}
														?>
													</select>
													<input type="button" value="Add" id="addFavourite" onclick="addFavourite();" />
												</td>
											</tr>
											<?php
												*/
											?>
											<tr>
												<td>Services: </td>
												<td class="profileRight">
													<table id="exisitingServices" style="border: none; text-align: right; width: 100%; position: relative; top: -8px;">
														<tr style="border: none;">
															<?php
																$services_raw = $QL -> query('SELECT * FROM services');
																$step = 1;
																while($service = $services_raw -> fetch_array(MYSQLI_ASSOC))
																{
																	$result = $QL -> query('SELECT COUNT(*) AS ofServices FROM advertisers_services WHERE service = '.$service['id'].' AND advertiser='.$_SESSION['loggedId']);
																	$number = $result -> fetch_assoc();
																	?>
																		<td style="border: none; width: 25%;"><?php echo $service['name'];?><input class="serviceCheckboxes" id="service<?php echo $service['id'];?>" onchange="this.disabled = true; if(this.checked) { addService(<?php echo $service['id'];?>); } else { deleteService(<?php echo $service['id'];?>); }" type="checkbox" <?php if($number['ofServices']==1) { echo 'checked="checked"'; } ?> /></td>
																	<?php
																	if($step==4)
																	{
																		?>
																			</tr>
																			<tr style="border: none;">
																		<?php
																		$step = 1;
																	}
																	else
																	{
																		$step++;
																	}
																}
																while($step<4)
																{
																	echo '<td style="border: none;"></td>';
																	$step++;
																}
															?>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td>Rates: </td>
												<td class="profileRight">
												<span id="rates">
													<?php 
														$ratesRaw = $QL -> query('SELECT * FROM rates WHERE user = '.$_SESSION['loggedId']);
														while($rate = $ratesRaw -> fetch_array(MYSQLI_ASSOC))
														{
															?>
																<span id="rate<?php echo $rate['id'];?>">$<?php echo sprintf('%0.2f', $rate['price']);?> for <?php echo sprintf('%02.0f', $rate['hours']);?>h<?php echo sprintf('%02.0f', $rate['minutes']);?> <span id="deleteRate<?php echo $rate['id'];?>"><a href="javascript:deleteRate(<?php echo $rate['id'];?>);">delete</a></span><br /></span>
															<?php
														}
													?>
												</span>
												<br />
												<span id="dollarSign">$</span> <input type="text" id="priceRate" style="width: 50px;" /> for 
													<input type="number" style="width: 50px;" id="hoursRate" />
													hour(s) and
													<input type="number" style="width: 50px;" id="minutesRate">
													minutes
													<br />
													<input type="button" id="addRate" value="Add" onclick="addRate();" />
												</td>
											</tr>
											<tr>
												<td>Availability: </td>
												<td class="profileRight">
													<span id="availabilities">
														<?php 
															$availableRaw = $QL -> query('SELECT * FROM advertisers_availability WHERE advertiser='.$_SESSION['loggedId'].' ORDER BY day');
															$days=['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
															while($available = $availableRaw -> fetch_array(MYSQLI_ASSOC))
															{
																?>
																	<span id="available<?php echo $available['id'];?>"><?php echo $days[$available['day']];?> <?php echo $available['fromTime'];?> - <?php echo $available['toTime'];?> <span id="deleteAvailability<?php echo $available['id'];?>"><a href="javascript:deleteAvailability(<?php echo $available['id'];?>);">delete</a></span><br /></span>
																<?php
															}
														?>
													</span>
													<br />
													<select id="availabilityDay">
														<option value="0">Mon</option>
														<option value="1">Tue</option>
														<option value="2">Wed</option>
														<option value="3">Thu</option>
														<option value="4">Fri</option>
														<option value="5">Sat</option>
														<option value="6">Sun</option>
													</select>
													From: 
													<select id="availabilityFromHour">
														<?php 
															for($i=0;$i<=12;$i++)
															{
																?>
																	<option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT);?>"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT);?></option>
																<?php
															}
														?>
													</select>:
													<select id="availabilityFromMin">
														<?php 
															for($i=0;$i<=60;$i++)
															{
																?>
																	<option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT);?>"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT);?></option>
																<?php
															}
														?>
													</select>
													<select id="availabilityFromAmPm">
														<option value="am">am</option>
														<option value="pm">pm</option>
													</select><br />
													To: 
													<select id="availabilityToHour">
														<?php 
															for($i=0;$i<=12;$i++)
															{
																?>
																	<option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT);?>"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT);?></option>
																<?php
															}
														?>
													</select>:
													<select id="availabilityToMin">
														<?php 
															for($i=0;$i<=60;$i++)
															{
																?>
																	<option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT);?>"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT);?></option>
																<?php
															}
														?>
													</select>
													<select id="availabilityToAmPm">
														<option value="am">am</option>
														<option value="pm">pm</option>
													</select><br />
													<input type="button" value="Add" onclick="addAvailability();" id="addAvailabilityButton" />
												</td>
											</tr>
										</table>
									<?php
								}
							}
						}
						else
						{
							?>
								Sorry, an error occured. Please try again later.
							<?php
						}
					?>
				</div>
			</div>
		<?php
	}
	else
	{
		//error 404
		include $root."/php/error404.php";
	}
?>