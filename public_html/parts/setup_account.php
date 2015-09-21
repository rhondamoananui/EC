<?php 
	if(isset($_POST['id']) && isset($_POST['code']))
	{
		$root = realpath($_SERVER["DOCUMENT_ROOT"]);
		include $root."/php/start.php";
		$QL = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);
		$complete = 0;
		$error = 0;
		$rawUsers = $QL -> query('SELECT * FROM advertisers WHERE id='.$_POST['id']);
		if($setup = $rawUsers -> fetch_array(MYSQLI_ASSOC))
		{
			$emailCode = md5($setup['email']);
		}
		else
		{
			$emailCode = 'F5%$dsfsdfdsf';
		}
		if($emailCode==$_POST['code'])
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
				if(!(preg_match("#^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$#", $_POST['email'])))
				{
					$error=1;
					$errorEmail=1;
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
				if($QL -> query('UPDATE advertisers SET fname="'.$_POST['fname'].'", lname="'.$_POST['lname'].'", nickname="'.$_POST['nickname'].'", password="'.md5($_POST['password']).'", region="'.$_POST['region'].'", country="'.$currentCountry.'", city="'.$_POST['city'].'", featured=0, gender="'.$_POST['gender'].'", dressSize="'.$_POST['dressSize'].'", bustSize="'.$_POST['bustSize'].'", bodyType="'.$_POST['bodyType'].'", birthDate="'.$_POST['birthYear'].'-'.$_POST['birthMonth'].'-'.$_POST['birthDay'].'", nationality="'.$_POST['nationality'].'", ethnicity="'.$_POST['ethnicity'].'", eyeColour="'.$_POST['eyeColour'].'", height="'.$_POST['height'].'", shaved="'.$shaved.'", disableFriendly="'.$disableFriendly.'", smoke="'.$smoke.'", hair="'.$_POST['hairDescription'].'", postCode="'.$_POST['postCode'].'" WHERE id='.$_POST['id']))
				{
					$parentRaw = $QL -> query('SELECT * FROM advertisers INNER JOIN membership ON membership.user=advertisers.id WHERE advertisers.id='.$setup['parent']);
					$parent = $parentRaw -> fetch_array(MYSQLI_ASSOC);
     				$QL -> query('INSERT INTO membership (type, user, paid, autoRenew, expiry) VALUES ("platinum", "'.$_POST['id'].'", 1, 1, "'.$parent['expiry'].'")');
     				$QL -> query('INSERT INTO advertisers_services (advertiser, service) VALUES ("'.$_POST['id'].'", "'.$_POST['mainService'].'")');
					?>
						<div style="padding: 5px; padding-top: 0;">
							<span style="font-weight: bold;">Congratulations!</span> <br />
							Your profile is set up! You can now login and enter optional profile information.
							<br /><br />
							<input type="button" value="Okay" onclick="hidePopup();" />
						</div>
					<?php
				}
				else
				{
					?>
						<div style="padding: 5px; padding-top: 0;">
							<span style="font-weight: bold;">Sorry.</span> <br />
							There was an error during your profile's creation. Please try again later.
							<br /><br />
							<input type="button" value="Okay" onclick="hidePopup();" />
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
								else if(isset($errorEmail) && $errorEmail==1)
								{
									echo "Please enter a valid e-mail address";
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
									echo "Welcome to Escort Central! Please provide the following information to create your account:";
								}
							?>
						</div>
						<br />
						<div id="registerFormTable">
							<div>
								<div <?php if(isset($errorFname) && $errorFname==1) { ?>style="color: red;"<?php } ?>>Firstname*:</div>
								<div class="info"><input type="text" id="fname" <?php if(isset($_POST['fname']) && $_POST['fname']!='undefined') { ?>value="<?php echo htmlspecialchars($_POST['fname']);?>"<?php }?> /></div>

								<div class="label" <?php if(isset($errorNationality) && $errorNationality==1) { ?>style="color: red;"<?php } ?>>Enter your nationality:</div>
								<div class="info"><input type="text" id="nationality" <?php if(isset($_POST['nationality']) && $_POST['nationality']!='undefined') { ?>value="<?php echo htmlspecialchars($_POST['nationality']);?>"<?php }?> /></div>
							</div>
							<div>
								<div <?php if(isset($errorLname) && $errorLname==1) { ?>style="color: red;"<?php } ?>>Lastname*:</div>
								<div class="info"><input type="text" id="lname" <?php if(isset($_POST['lname']) && $_POST['lname']!='undefined') { ?>value="<?php echo htmlspecialchars($_POST['lname']);?>"<?php }?> /></div>

								<div class="label" <?php if(isset($errorEthnicity) && $errorEthnicity==1) { ?>style="color: red;"<?php } ?>>Ethnicity:</div>
								<div class="info"><input type="text" id="ethnicity" <?php if(isset($_POST['ethnicity']) && $_POST['ethnicity']!='undefined') { ?>value="<?php echo htmlspecialchars($_POST['ethnicity']);?>"<?php }?> /></div>
							</div>
							<div>
								<div <?php if(isset($errorGender) && $errorGender==1) { ?>style="color: red;"<?php } ?>>Gender*:</div>
								<div class="info"><select id="gender" onchange="if(this.value!='male') { document.getElementById('bustSize').disabled = false; changeSizes('female', 0); } else { document.getElementById('bustSize').disabled = true; if(this.value!='male') { changeSizes('female', 0); } else { changeSizes('male', 0); } }"><option value="<?php if(isset($_POST['gender']) && $_POST['gender']!='undefined') { echo $_POST['gender']; }?>"><?php if(isset($_POST['gender']) && $_POST['gender']!='' && $_POST['gender']!='undefined') { echo $_POST['gender']; } else { echo " - Please select - "; } ?></option><option value="male">Male</option><option value="female">Female</option><option value="transexual">Transexual</option></select></div>

								<div class="label" <?php if(isset($errorService) && $errorService=1) { ?>style="color: red;"<?php } ?>>Main service:</div>
								<div class="info">
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
								</div>
							</div>
							<div>
								<div <?php if(isset($errorBdate) && $errorBdate==1) { ?>style="color: red;"<?php } ?>>Birth date*:</div>
								<div class="info">
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
										</select></div>

								<div class="label" <?php if(isset($errorEyeColour) && $errorEyeColour==1) { ?>style="color: red;"<?php } ?>>Eye colour:</div>
								<div class="info">
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
								</div>
							</div>
							<div>
								<div <?php if(isset($errorEmail) && $errorEmail==1) { ?>style="color: red;"<?php } ?>>Email*:</div>
								<div class="info"><input type="text" disabled="disabled" id="email" <?php if(isset($_POST['email'])) { ?>value="<?php echo $setup['email'];?>"<?php }?> /></div>

								<div class="label" <?php if(isset($errorHairDescription) && $errorHairDescription==1) { ?>style="color: red;"<?php } ?>>Hair description:</div>
								<div class="info"><input type="text" id="hair" <?php if(isset($_POST['hairDescription']) && $_POST['hairDescription']!='undefined') { ?>value="<?php echo htmlspecialchars($_POST['hairDescription']);?>"<?php }?> /></div>
							</div>
							<div>
								<div <?php if((isset($errorNickname) && $errorNickname==1)||(isset($errorNicknameTaken) && $errorNicknameTaken==1)) { ?>style="color: red;"<?php } ?>>Choose a nickname*:</div>
								<div class="info"><input type="text" id="nickname" <?php if(isset($_POST['nickname']) && $_POST['nickname']!='undefined') { ?>value="<?php echo htmlspecialchars($_POST['nickname']);?>"<?php }?> /></div>

								<div class="label" <?php if(isset($errorHeight) && $errorHeight==1) { ?>style="color: red;"<?php } ?>>Height (cm):</div>
								<div class="info"><input type="number" id="height" <?php if(isset($_POST['height'])) { ?>value="<?php echo htmlspecialchars($_POST['height']);?>"<?php }?> /></div>
							</div>
							<div>
								<div <?php if(isset($errorPassword) && $errorPassword==1) { ?>style="color: red;"<?php } ?>>And a password*:</div>
								<div class="info"><input type="password" id="password" <?php if(isset($_POST['password']) && $_POST['password']!='undefined') { ?>value="<?php echo htmlspecialchars($_POST['password']);?>"<?php }?> /></div>

								<div class="label" <?php if(isset($errorBodyType) && $errorBodyType==1) { ?>style="color: red;"<?php } ?>>Body type:</div>
								<div class="info"><select id="bodyType" <?php if(isset($_POST['bodyType']) && $_POST['bodyType']!='undefined') { ?>value="<?php echo $_POST['bodyType'];?>"<?php }?>>
									<option value="<?php if(isset($_POST['bodyType']) && $_POST['bodyType']!='undefined') { echo $_POST['bodyType']; } ?>"><?php if(isset($_POST['bodyType']) && $_POST['bodyType']!='' && $_POST['bodyType']!='undefined') { echo $_POST['bodyType']; } else { echo " - Leave blank - "; } ?></option>
									<option value="inverted triangle">Inverted Triangle</option>
									<option value="lean column">Lean Column</option>
									<option value="rectangle">Rectangle</option>
									<option value="apple">Apple</option>
									<option value="pear">Pear</option>
									<option value="hourglass">Hourglass</option>
									<option value="0"> - Leave blank - </option>
								</select></div>
							</div>
							<div>
								<div <?php if(isset($errorRegion) && $errorRegion==1) { ?>style="color: red;"<?php } ?>>Select your region*:</div>
								<div class="info">
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
								</div>

								<div class="label" <?php if(isset($errorDressSize) && $errorDressSize==1) { ?>style="color: red;"<?php } ?> id="dressSizeLabel">Dress size (US):</div>
								<div class="info"><select id="dressSize" <?php if(isset($_POST['dressSize']) && $_POST['dressSize']!='undefined') { ?>value="<?php echo $_POST['dressSize'];?>"<?php }?>>
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
								</select></div>
							</div>
							<div>
								<div <?php if(isset($errorCity) && $errorCity==1) { ?>style="color: red;"<?php } ?>>Nearest city*:</div>
								<div class="info">
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
								</div>

								<div class="label" <?php if(isset($errorBustSize) && $errorBustSize==1) { ?>style="color: red;"<?php } ?>>Bust size:</div>
								<div class="info"><select id="bustSize" <?php if(isset($_POST['bustSize']) && $_POST['bustSize']!='undefined') { ?>value="<?php echo $_POST['bustSize'];?>"<?php }?>>
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
								</select></div>
							</div>
							<div>
								<div <?php if(isset($errorPostCode) && $errorPostCode==1) { ?>style="color: red;"<?php } ?>>Post code*:</div>
								<div class="info"><input type="text" id="postCode" <?php if(isset($_POST['postCode']) && $_POST['postCode']!='undefined') { ?>value="<?php echo htmlspecialchars($_POST['postCode']);?>"<?php }?> /></div>

								<div class="label">Smoking:</div>
								<div class="info"><input type="checkbox" id="smoking" <?php if(isset($_POST['smoking']) && $_POST['smoking']=='true') { ?>checked="checked"<?php }?> /></div>
							</div>
							<div>
								<div>Shaved:</div>
								<div class="info"><input type="checkbox" id="shaved" <?php if(isset($_POST['shaved']) && $_POST['shaved']=='true') { ?>checked="checked"<?php }?> /></div>

								<div class="label">Disable friendly:</div> 
								<div class="info"><input type="checkbox" id="disableFriendly" <?php if(isset($_POST['disableFriendly']) && $_POST['disableFriendly']=='true') { ?>checked="checked"<?php }?> /></div>
							</div>
						</div>
						<br />
						<div style="text-align: center;"><input type="button" value="Next" onclick="setupAccount(<?php echo $_POST['id'];?>, '<?php echo $_POST['code'];?>', document.getElementById('fname').value, document.getElementById('lname').value, document.getElementById('gender').value, document.getElementById('dayBirth').value, document.getElementById('monthBirth').value, document.getElementById('yearBirth').value, document.getElementById('email').value, document.getElementById('nickname').value, document.getElementById('password').value, document.getElementById('regionSelect').value, document.getElementById('citiesSelect').value, document.getElementById('postCode').value, document.getElementById('shaved').checked, document.getElementById('nationality').value, document.getElementById('ethnicity').value, document.getElementById('service').value, document.getElementById('eyeColour').value, document.getElementById('hair').value, document.getElementById('height').value, document.getElementById('bodyType').value, document.getElementById('dressSize').value, document.getElementById('bustSize').value, document.getElementById('smoking').checked, document.getElementById('disableFriendly').checked, document.getElementById('cityComp').value);" /></div>
					</div>
				<?php
			}
		}
	}
	else
	{
		//Error 404
		include $root."/php/error404.php";
	}
?>