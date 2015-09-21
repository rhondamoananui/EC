var exScrollTop = 0;
var mouseOverPopup = 0;
var popupXhr;
var welcome = 0;
var profileChanged = 0;
var defaultCityDiv = '<option value="0"> - Please select - </option>';

function previousFeatured()
{
	if(featuredLimit > 5 && changingFeaturedScroll==0) 
	{ 
		changingFeaturedScroll=1; 
		setTimeout(function() { changingFeaturedScroll = 0; }, animationTime); 
		if(currentFeaturedPosition <= 1) 
		{ 
			$('#featuredScroller').animate({marginLeft: -(featuredLimit-5)*displacement+'px'}, animationTime); 
			currentFeaturedPosition = featuredLimit-4; 
		} 
		else 
		{ 
			$('#featuredScroller').animate({marginLeft: '+='+displacement}, animationTime); 
			currentFeaturedPosition--; 
		} 
	}
}

function selectNewMembership(id, deletem, pass)
{
	showPopup("Select new membership");
	document.getElementById("popupWindowContent").innerHTML = '<div style="text-align: center; line-height: 400px;">Loading...</div>';
	popupXhr = $.ajax({
		url : "parts/new_membership.php",
		type : "POST",
		data : "id="+id+"&renew=0&delete="+deletem+"&pass="+pass,
 		success : function(result){
 			document.getElementById("popupWindowContent").innerHTML = result;
    	}
	});
}

function showTAC()
{
	document.getElementById("popupWindowContent").innerHTML = '<div style="text-align: center; line-height: 400px;">Loading...</div>';
	popupXhr = $.ajax({
		url : "parts/tac.php",
		type : "POST",
		data : "site=1",
 		success : function(result){
 			document.getElementById("popupWindowContent").innerHTML = result;
    	}
	});
}

function showContact(email, message, name)
{
	document.getElementById("popupWindowContent").innerHTML = '<div style="text-align: center; line-height: 400px;">Loading...</div>';
	popupXhr = $.ajax({
		url : "parts/contact.php",
		type : "POST",
		data : "site=1&email="+email+"&name="+name+"&message="+message,
 		success : function(result){
 			document.getElementById("popupWindowContent").innerHTML = result;
    	}
	});
}

function nextFeatured()
{
	if(featuredLimit > 5 && changingFeaturedScroll==0) 
	{ 
		changingFeaturedScroll=1; 
		setTimeout(function() { changingFeaturedScroll = 0; }, animationTime); 
		if(currentFeaturedPosition > featuredLimit-5) 
		{ 
			$('#featuredScroller').animate({marginLeft: '0'}, animationTime); 
			currentFeaturedPosition = 1; 
		} 
		else 
		{ 
			$('#featuredScroller').animate({marginLeft: '-='+displacement}, animationTime); 
			currentFeaturedPosition++; 
		} 
	}
}

function hidePopup()
{
	if(typeof(popupXhr)!='undefined')
	{
		popupXhr.abort();
	}
	document.body.style.overflow='scroll';
	$("#popupDiv").fadeOut(500);
	$(window).scrollTop(exScrollTop);
}

function showPopup(title)
{
	document.body.style.overflow='hidden';
	exScrollTop = $(window).scrollTop();
	document.getElementById("popupWindowTitle").innerHTML = title;
	document.getElementById("popupWindowContent").innerHTML = '<div style="text-align: center; line-height: 400px;">Loading...</div>';
	$("#popupDiv").fadeIn(500);
}

function advertiseInfo()
{
	var diese = window.location.hash;
	if(diese=='#freemembership' && numberOfUsers<100)
	{
		window.location = "http://"+window.location.hostname+"?advertise=1&freeM=1";
	}
	else
	{
		window.location = "http://"+window.location.hostname+"?advertise=1";
	}
}

function goToUser(id, name)
{
	window.location="http://"+window.location.hostname+"/?id="+id;
	/*showPopup(name);
	popupXhr = $.ajax({
		url : "parts/profile.php",
		type : "POST",
		data : "id="+id,
 		success : function(result){
 			document.getElementById("popupWindowContent").innerHTML = result;
    	}
	});*/
}

function updateInfo(close, membership)
{
	document.getElementById('emailTaken').style.display = 'none';
	document.getElementById('emailInc').style.display = 'none';
	document.getElementById('alphanum').style.display = 'none';
	document.getElementById('saveAndClose').disabled = true;
	document.getElementById('saveAndClose').value = 'Saving...';
	document.getElementById('saveNotClose').disabled = true;
	document.getElementById('saveNotClose').value = 'Saving...';
	var errFname = 0;
	var errLname = 0;
	var errHair = 0;
	var errRegion = 0;
	var errCity = 0;
	var errPostCode = 0;
	var errNationality = 0;
	var errEthnicity = 0;
	var errEmail = 0;
	var errHeight = 0;
	var fnamef = document.getElementById('fname').value;
	var nationalityf = document.getElementById('nationality').value;
	var lnamef = document.getElementById('lname').value;
	var ethnicityf = document.getElementById('ethnicity').value;
	var genderf = document.getElementById('gender').value;
	var bdf = document.getElementById('dayBirth').value;
	var bmf = document.getElementById('monthBirth').value;
	var byf = document.getElementById('yearBirth').value;
	var eyeColourf = document.getElementById('eyeColour').value;
	var emailf = document.getElementById('email').value;
	var hairf = document.getElementById('hair').value;
	var heightf = document.getElementById('height').value;
	var bodyTypef = document.getElementById('bodyType').value;
	var dressSizef = document.getElementById('dressSize').value;
	var countryf = document.getElementById('country').value;
	var bustSizef = document.getElementById('bustSize').value;
	var regionf = document.getElementById('regions').value;
	if(document.getElementById('citiesFrom'+countryf+'_'+regionf)!=null && typeof(document.getElementById('citiesFrom'+countryf+'_'+regionf))!='undefined')
	{
		if(document.getElementById('citiesFrom'+countryf+'_'+regionf).innerHTML==defaultCityDiv)
		{
			var cityf = 0;
		}
		else 
		{
			var cityf = document.getElementById('cities').value;
		}
	}
	else
	{
		var cityf = 0;
	}
	var smokingf = document.getElementById('smoking').checked;
	var disableFriendlyf = document.getElementById('disableFriendly').checked;
	var postCodef = document.getElementById('postCode').value;
	var shavedf = document.getElementById('shaved').checked;

	if(fnamef.match(/[^0-9A-Za-z -]/)||fnamef=='')
	{
		errFname = 1;
		document.getElementById('fnameLabel').style.color = "red";
	}
	else
	{
		errFname = 0;
		document.getElementById('fnameLabel').style.color = "#000";
	}
	if(nationalityf.match(/[^0-9A-Za-z -]/))
	{
		errNationality = 1;
		document.getElementById('nationalityLabel').style.color = "red";
	}
	else
	{
		errNationality = 0;
		if(!membership.match(/agency/i))
		{
			document.getElementById('nationalityLabel').style.color = "#000";
		}
		else
		{
			document.getElementById('nationalityLabel').style.color = "#BBB";
		}
	}
	if(lnamef.match(/[^0-9A-Za-z -]/)||lnamef=='')
	{
		errLname = 1;
		document.getElementById('lnameLabel').style.color = "red";
	}
	else
	{
		errLname = 0;
		document.getElementById('lnameLabel').style.color = "#000";
	}
	if(ethnicityf.match(/[^0-9A-Za-z -]/))
	{
		errEthnicity = 1;
		document.getElementById('ethnicityLabel').style.color = "red";
	}
	else
	{
		errEthnicity = 0;
		if(!membership.match(/agency/i))
		{
			document.getElementById('ethnicityLabel').style.color = "#000";
		}
		else
		{
			document.getElementById('ethnicityLabel').style.color = "#BBB";
		}
	}
	if(hairf.match(/[^0-9A-Za-z -]/))
	{
		errHair = 1;
		document.getElementById('hairLabel').style.color = "red";
	}
	else
	{
		errHair = 0;
		if(!membership.match(/agency/i))
		{
			document.getElementById('hairLabel').style.color = "#000";
		}
		else
		{
			document.getElementById('hairLabel').style.color = "#BBB";
		}
	}
	if(heightf!="" && (heightf < 60 || heightf > 230))
	{ 
		errHeight = 1;
		document.getElementById('heightLabel').style.color = "red";
	}
	else
	{
		errHeight = 0;
		if(!membership.match(/agency/i))
		{
			document.getElementById('heightLabel').style.color = "#000";
		}
		else
		{
			document.getElementById('heightLabel').style.color = "#BBB";
		}
	}
	if(postCodef.match(/[^0-9A-Za-z -]/)||postCodef=='')
	{
		errPostCode = 1;
		document.getElementById('postCodeLabel').style.color = "red";
	}
	else
	{
		errPostCode = 0;
		document.getElementById('postCodeLabel').style.color = "#000";
	}
	if(regionf==0||regionf=='')
	{
		errRegion = 1;
		document.getElementById('regionLabel').style.color = "red";
	}
	else
	{
		errRegion = 0;
		document.getElementById('regionLabel').style.color = "#000";
		if((cityf==0||cityf=='') && document.getElementById('citiesFrom'+countryf+'_'+regionf).innerHTML!=defaultCityDiv)
		{
			errCity = 1;
			document.getElementById('cityLabel').style.color = "red";
		}
		else
		{
			errCity = 0;
			document.getElementById('cityLabel').style.color = "#000";
		}
	}
	if(!(emailf.match(/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$/)))
	{
		errEmail = 1;
		document.getElementById('emailLabel').style.color = "red";
	}
	else
	{
		errEmail = 0;
		document.getElementById('emailLabel').style.color = "#000";
	}

	if(errFname+errLname+errPostCode+errNationality+errEthnicity+errHair!=0)
	{
		document.getElementById('alphanum').style.display = "";
		document.getElementById('saveAndClose').disabled = false;
		document.getElementById('saveAndClose').value = 'Save and close';
		document.getElementById('saveNotClose').disabled = false;
		document.getElementById('saveNotClose').value = 'Save changes';
	}
	else
	{
		document.getElementById('alphanum').style.display = "none";
		if(errEmail!=0)
		{
			document.getElementById('emailInc').style.display = "";
			document.getElementById('saveAndClose').disabled = false;
			document.getElementById('saveAndClose').value = 'Save and close';
			document.getElementById('saveNotClose').disabled = false;
			document.getElementById('saveNotClose').value = 'Save changes';
		}
		else
		{
			document.getElementById('emailInc').style.display = "none";
			if(errCity+errRegion+errHeight==0)
			{
				popupXhr = $.ajax({
					url : "php/check_email.php",
					type : "POST",
					data : "email="+emailf,
			 		success : function(result){
			 			if(result=='ok')
			 			{
			 				document.getElementById('emailTaken').style.display = 'none';
			 				popupXhr = $.ajax({
								url : "php/update_info.php",
								type : "POST",
								data : "fname="+fnamef+"&height="+heightf+"&lname="+lnamef+"&nationality="+nationalityf+"&ethnicity="+ethnicityf+"&body="+bodyTypef+"&gender="+genderf+"&birthDate="+byf+"-"+bmf+"-"+bdf+"&eyeColour="+eyeColourf+"&email="+emailf+"&hair="+hairf+"&dressSize="+dressSizef+"&country="+countryf+"&bustSize="+bustSizef+"&region="+regionf+"&smoking="+smokingf+"&city="+cityf+"&disableFriendly="+disableFriendlyf+"&postCode="+postCodef+"&shaved="+shavedf,
						 		success : function(result){
						 			if(result=='ok')
						 			{
						 				if(close==1)
						 				{
						 					hidePopup();
						 				}
						 				else
						 				{
						 					alert('Your info was successfuly updated.');
						 				}
						 			}
						 			else
						 			{
						 				alert('Sorry an error occured. Please try again later.');
						 			}
									document.getElementById('saveAndClose').disabled = false;
									document.getElementById('saveAndClose').value = 'Save and close';
									document.getElementById('saveNotClose').disabled = false;
									document.getElementById('saveNotClose').value = 'Save changes';
						    	}
							});
			 			}
			 			else
			 			{
			 				document.getElementById('emailTaken').style.display = '';
							document.getElementById('saveAndClose').disabled = false;
							document.getElementById('saveAndClose').value = 'Save and close';
							document.getElementById('saveNotClose').disabled = false;
							document.getElementById('saveNotClose').value = 'Save changes';
			 			}
			    	}
				});
			}
			else
			{
				document.getElementById('saveAndClose').disabled = false;
				document.getElementById('saveAndClose').value = 'Save and close';
				document.getElementById('saveNotClose').disabled = false;
				document.getElementById('saveNotClose').value = 'Save changes';
			}
		}
	}
}

function showProfile(part)
{
	profileChanged = 0;
	document.getElementById("popupWindowContent").innerHTML = '<div style="text-align: center; line-height: 400px;">Loading...</div>';
	popupXhr = $.ajax({
		url : "parts/my_profile.php",
		type : "POST",
		data : "part="+part,
 		success : function(result){
 			document.getElementById("popupWindowContent").innerHTML = result;
 			$('#monthBirth').change();
 			$('#country').change();
 			$('#gender').change();
 			if(welcome==1)
 			{
 				document.getElementById('welcomeDiv').style.display = '';
 				welcome = 0;
 			}
 			else
 			{
 				document.getElementById('welcomeDiv').style.display = 'none';
 			}
    	}
	});
}

function setupSubAccount(fname, lname, gender, birthDay, birthMonth, birthYear, email, nickname, password, region, city, postCode, shaved, nationality, ethnicity, mainService, eyeColour, hairDescription, height, bodyType, dressSize, bustSize, smoking, disableFriendly, cityComp)
{
	profileChanged = 0;
	document.getElementById("popupWindowContent").innerHTML = '<div style="text-align: center; line-height: 400px;">Loading...</div>';
	popupXhr = $.ajax({
		url : "parts/my_profile.php",
		type : "POST",
		data : "part=setupAccount&fname="+fname+"&lname="+lname+"&gender="+gender+"&birthDay="+birthDay+"&birthMonth="+birthMonth+"&birthYear="+birthYear+"&nickname="+nickname+"&password="+encodeURIComponent(password)+"&region="+region+"&city="+city+"&postCode="+postCode+"&shaved="+shaved+"&nationality="+nationality+"&ethnicity="+ethnicity+"&mainService="+mainService+"&eyeColour="+eyeColour+"&hairDescription="+hairDescription+"&height="+height+"&bodyType="+bodyType+"&dressSize="+dressSize+"&bustSize="+bustSize+"&smoking="+smoking+"&disableFriendly="+disableFriendly+"&cityComp="+cityComp,
 		success : function(result){
 			document.getElementById("popupWindowContent").innerHTML = result;
 			$('#monthBirth').change();
 			$('#regionSelect').change();
    	}
	});
}

function loginPrompt(username, password)
{
	window.location.hash = '';
	showPopup('Please provide your login details');
	popupXhr = $.ajax({
		url : "parts/login.php",
		type : "POST",
		data : "site=1&usernameField="+username+"&passwordField="+encodeURIComponent(password),
 		success : function(result){
 			document.getElementById("popupWindowContent").innerHTML = result;
    	}
	});
}

sfHover = function() {
        var sfEls = document.getElementById("menu").getElementsByTagName("LI");
        for (var i=0; i<sfEls.length; i++) {
                sfEls[i].onmouseover=function() {
                        this.className+=" sfhover";
                }
                sfEls[i].onmouseout=function() {
                        this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
                }
        }
}

function register(membership)
{
	showPopup('Register');
	document.getElementById("popupWindowContent").innerHTML = '<div style="text-align: center; line-height: 400px;">Loading...</div>';
	if(typeof(freeGrant)!="undefined" && freeGrant)
	{
		var freeM = 1;
	}
	else
	{
		var freeM = 0;
	}
	popupXhr = $.ajax({
		url : "parts/register.php",
		type : "POST",
		data : "membership="+membership+"&freeM="+freeM,
 		success : function(result){
 			document.getElementById("popupWindowContent").innerHTML = result;
    	}
	});
}

function createMembership(membership, id, renew, pass)
{
	document.getElementById("popupWindowContent").innerHTML = '<div style="text-align: center; line-height: 400px;">Loading...</div>';
	popupXhr = $.ajax({
		url : "parts/new_membership.php",
		type : "POST",
		data : "membership="+membership+"&id="+id+"&renew="+renew+"&pass="+pass,
 		success : function(result){
 			document.getElementById("popupWindowContent").innerHTML = result;
    	}
	});
}

function autoRenew(id, renew, pass)
{
	popupXhr = $.ajax({
		url : "php/auto_renew.php",
		type : "POST",
		data : "id="+id+"&renew="+renew,
 		success : function(result){
 			document.getElementById('stuffInPopup').style.display = 'none';
 			document.getElementById('waitingForPayment').style.display = '';
 			document._xclick.submit();
 			waitForPayment(id, pass);
    	}
	});
}

function waitForPayment(id, pass)
{
	popupXhr = $.ajax({
		url : "php/check_payment.php",
		type : "POST",
		data : "id="+id+"&pass="+pass,
 		success : function(result){
 			if(result.match(/1/)||result==1)
 			{
 				window.location = "http://"+server+"/?paid=1";
 			}
 			else
 			{
 				waitForPayment(id, pass);
 			}
    	}
	});
}

function testRegister(membership, fname, lname, gender, birthDay, birthMonth, birthYear, email, nickname, password, region, city, postCode, shaved, nationality, ethnicity, mainService, eyeColour, hairDescription, height, bodyType, dressSize, bustSize, smoking, disableFriendly, cityComp)
{
	document.getElementById("popupWindowTitle").innerHTML = "Register";
	document.getElementById("popupWindowContent").innerHTML = '<div style="text-align: center; line-height: 400px;">Loading...</div>';
	var diese = window.location.hash;
	if(typeof(freeGrant)!="undefined" && freeGrant)
	{
		var freeM = 1;
	}
	else
	{
		var freeM = 0;
	}
	popupXhr = $.ajax({
		url : "parts/register.php",
		type : "POST",
		data : "membership="+membership+"&fname="+fname+"&lname="+lname+"&gender="+gender+"&birthDay="+birthDay+"&birthMonth="+birthMonth+"&birthYear="+birthYear+"&email="+email+"&nickname="+nickname+"&password="+encodeURIComponent(password)+"&region="+region+"&city="+city+"&postCode="+postCode+"&shaved="+shaved+"&nationality="+nationality+"&ethnicity="+ethnicity+"&mainService="+mainService+"&eyeColour="+eyeColour+"&hairDescription="+hairDescription+"&height="+height+"&bodyType="+bodyType+"&dressSize="+dressSize+"&bustSize="+bustSize+"&smoking="+smoking+"&disableFriendly="+disableFriendly+"&cityComp="+cityComp+"&freeM="+freeM,
 		success : function(result){
 			document.getElementById("popupWindowContent").innerHTML = result;
 			$('#monthBirth').change();
 			$('#regionSelect').change();
 			if(document.getElementById('gender').value!='male') { document.getElementById('bustSize').disabled = false; } else { document.getElementById('bustSize').disabled = true; }
    	}
	});
}

function forgotPW()
{
	popupXhr = $.ajax({
		url : "parts/forgot_pw.php",
		type : "POST",
		data : "site=1",
		success : function(result){
 			document.getElementById("popupWindowContent").innerHTML = result;
 		}
	});
}

function testForgotPW()
{
	document.getElementById('sendEmail').disabled = true;
	var email = document.getElementById('emailForgot').value;
	popupXhr = $.ajax({
		url : "php/check_email_forgot.php",
		type : "POST",
		data : "email="+email,
		success : function(result){
			if(result=='ok')
			{
				alert('An e-mail was successfuly sent to the address you provided.');
				document.getElementById('sendEmail').disabled = false;
				hidePopup();
			}
			else if(result=='no')
			{
				alert('The e-mail address you provided is not assigned to any account.');
				document.getElementById('sendEmail').disabled = false;
			}
			else
			{
				alert('Sorry an error occured. Please try again later.');
				document.getElementById('sendEmail').disabled = false;
			}
 		}
	});
}

function resetPW(id, oldPassword)
{
	var password = prompt('Please enter your new password:');
	while(password.length<4)
	{
		password = prompt('Your password needs to be at least 4 characters long.');
	}
	popupXhr = $.ajax({
		url : "php/change_pw.php",
		type : "POST",
		data : "id="+id+"&oldPassword="+encodeURIComponent(oldPassword)+"&newPW="+encodeURIComponent(password),
		success : function(result){
			if(result=='ok')
			{
				alert('Your password was successfuly changed!');
			}
			else
			{
				alert('Sorry an error occured. Please try again later.');
			}
 		}
	});
}

function deletePhoto(id)
{
	var oldContent = document.getElementById('editPic'+id).innerHTML;
	document.getElementById('editPic'+id).innerHTML = "deleting...";
	popupXhr = $.ajax({
		url : "php/delete_picture.php",
		type : "POST",
		data : "id="+id,
		success : function(result){
			if(result=='ok')
			{
				showProfile('photos');
			}
			else
			{
				document.getElementById('editPic'+id).innerHTML = oldContent;
				alert('Sorry an error occured. Please try again later.');
			}
 		}
	});
}

function deleteBanner()
{
	var oldContent = document.getElementById('deleteBanner').innerHTML;
	document.getElementById('deleteBanner').innerHTML = "deleting...";
	popupXhr = $.ajax({
		url : "php/delete_banner.php",
		type : "POST",
		success : function(result){
			if(result=='ok')
			{
				showProfile('photos');
			}
			else
			{
				document.getElementById('deleteBanner').innerHTML = oldContent;
				alert('Sorry an error occured. Please try again later.');
			}
 		}
	});
}

function addPhoto(id)
{
	var oldContent = document.getElementById('addPhotoDiv').innerHTML;
    var formData = new FormData();
    formData.append('picture', document.getElementById('photo').files[0]);
    formData.append('banner', '0');
    formData.append('id', id);
	document.getElementById('addPhotoDiv').innerHTML = "Uploading...";
    $.ajax({
        url: 'php/upload_image.php',
        type: 'POST',
        success: function(result) {
        	if(result=='ok')
        	{}
        	else if(result=='sizerr')
        	{
        		alert('The file you uploaded is too large. Please upload a file that is less than 3MB.');
        	}
        	else if(result=='typerr')
        	{
        		alert('You have uploaded an invalid format. Please upload .png, .jpg or .gif images');
        	}
        	else
        	{
				alert('Sorry an error occured. Please try again later.');
			}
        	showProfile('photos');
        },
        error: function() { 
			document.getElementById('addPhotoDiv').innerHTML = oldContent;
			alert('Sorry an error occured. Please try again later.');
        },
        data: formData,
        cache: false,
        contentType: false,
        processData: false
    });
}

function addBanner(id)
{
	var oldContent = document.getElementById('addBannerDiv').innerHTML;
    var formData = new FormData();
    formData.append('picture', document.getElementById('banner').files[0]);
    formData.append('banner', '1');
    formData.append('id', id);
	document.getElementById('addBannerDiv').innerHTML = "Uploading...";
    $.ajax({
        url: 'php/upload_image.php',
        type: 'POST',
        success: function(result) {
        	if(result=='ok')
        	{}
        	else if(result=='sizerr')
        	{
        		alert('The file you uploaded is too large. Please upload a file that is less than 3MB.');
        	}
        	else if(result=='typerr')
        	{
        		alert('You have uploaded an invalid format. Please upload .png, .jpg or .gif images');
        	}
        	else
        	{
				alert('Sorry an error occured. Please try again later.');
			}
        	showProfile('photos');
        },
        error: function() { 
			document.getElementById('addBannerDiv').innerHTML = oldContent;
			alert('Sorry an error occured. Please try again later.');
        },
        data: formData,
        cache: false,
        contentType: false,
        processData: false
    });
}

function setMainPhoto(id)
{
	var oldContent = document.getElementById('editPic'+id).innerHTML;
	document.getElementById('editPic'+id).innerHTML = "setting...";
	popupXhr = $.ajax({
		url : "php/set_main_picture.php",
		type : "POST",
		data : "id="+id,
		success : function(result){
			if(result=='ok')
			{
				showProfile('photos');
			}
			else
			{
				document.getElementById('editPic'+id).innerHTML = oldContent;
				alert('Sorry an error occured. Please try again later.');
			}
 		}
	});
}

function deleteAvailability(id)
{
	document.getElementById('deleteAvailability'+id).innerHTML = 'Deleting...';
	$.ajax({
		url : "php/delete_availability.php",
		type : "POST",
		data : "id="+id,
 		success : function(result){
 			if(result=='ok')
 			{
				document.getElementById('available'+id).style.display = 'none';
			}
			else
			{
				document.getElementById('deleteAvailability'+id).innerHTML = '<a href="javascript:deleteAvailability('+id+');">delete</a>';
				alert('Sorry an error occured. Please try again later.');
			}
    	}
	});
}

function addAvailability()
{
	document.getElementById('addAvailabilityButton').disabled = true;
	document.getElementById('availabilityDay').disabled = true;
	document.getElementById('availabilityFromHour').disabled = true;
	document.getElementById('availabilityFromMin').disabled = true;
	document.getElementById('availabilityFromAmPm').disabled = true;
	document.getElementById('availabilityToHour').disabled = true;
	document.getElementById('availabilityToMin').disabled = true;
	document.getElementById('availabilityToAmPm').disabled = true;
	var days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
	$.ajax({
		url : "php/add_availability.php",
		type : "POST",
		data : "day="+document.getElementById('availabilityDay').value+"&fromHour="+document.getElementById('availabilityFromHour').value+'&fromMin='+document.getElementById('availabilityFromMin').value+'&fromAmPm='+document.getElementById('availabilityFromAmPm').value+'&toHour='+document.getElementById('availabilityToHour').value+'&toMin='+document.getElementById('availabilityToMin').value+'&toAmPm='+document.getElementById('availabilityToAmPm').value,
 		success : function(result){
 			if(result.match('ok'))
 			{
 				var id=result.replace('ok', '');
				document.getElementById('addAvailabilityButton').disabled = false;
				document.getElementById('availabilityDay').disabled = false;
				document.getElementById('availabilityFromHour').disabled = false;
				document.getElementById('availabilityFromMin').disabled = false;
				document.getElementById('availabilityFromAmPm').disabled = false;
				document.getElementById('availabilityToHour').disabled = false;
				document.getElementById('availabilityToMin').disabled = false;
				document.getElementById('availabilityToAmPm').disabled = false;
				document.getElementById('availabilities').innerHTML += '<span id="available'+id+'">'+days[document.getElementById('availabilityDay').value]+' '+document.getElementById('availabilityFromHour').value+':'+document.getElementById('availabilityFromMin').value+' '+document.getElementById('availabilityFromAmPm').value+' - '+document.getElementById('availabilityToHour').value+':'+document.getElementById('availabilityToMin').value+' '+document.getElementById('availabilityToAmPm').value+' <span id="deleteAvailability'+id+'"><a href="javascript:deleteAvailability('+id+');">delete</a></span><br /></span>';
			}
			else
			{
				document.getElementById('addAvailabilityButton').disabled = false;
				document.getElementById('availabilityDay').disabled = false;
				document.getElementById('availabilityFromHour').disabled = false;
				document.getElementById('availabilityFromMin').disabled = false;
				document.getElementById('availabilityFromAmPm').disabled = false;
				document.getElementById('availabilityToHour').disabled = false;
				document.getElementById('availabilityToMin').disabled = false;
				document.getElementById('availabilityToAmPm').disabled = false;
				alert('Sorry an error occured. Please try again later.');
			}
    	}
	});
}

function changeDescription(close)
{
	if(typeof(close)!='undefined' && close==1)
	{
		document.getElementById('saveAndClose').disabled = true;
		document.getElementById('saveAndClose').value = 'Saving...';
	}
	document.getElementById('saveDescription').disabled = true;
	$.ajax({
		url : "php/change_description.php",
		type : "POST",
		data : "description="+encodeURIComponent(document.getElementById('myDescription').value),
 		success : function(result){
 			if(result=='ok')
 			{
				if(typeof(close)!='undefined' && close==1)
				{
					saveContact(1);
				}
				else
				{
					document.getElementById('saveDescription').disabled = false;
					alert('Your "About Me" section has been updated');
				}
				profileChanged = 0;
			}
			else
			{
				if(typeof(close)!='undefined' && close==1)
				{
					document.getElementById('saveAndClose').disabled = false;
					document.getElementById('saveAndClose').value = 'Save and close';
				}
				document.getElementById('saveDescription').disabled = false;
				alert('Sorry an error occured. Please try again later.');
				profileChanged = 1;
			}
    	}
	});
}

function saveContact(close)
{
	document.getElementById('saveContact').disabled = true;
	var errorCheck = 0;
	var phone =document.getElementById('phoneNum').value;
	var website = document.getElementById('website').value;
	var facebook = document.getElementById('facebook').value;
	var twitter = document.getElementById('twitter').value;
	if(phone!='' && phone.match(/[^0-9 .-]/))
	{
		errorCheck = 1;
		document.getElementById('phoneNum').style.color = 'red';
	}
	else
	{
		document.getElementById('phoneNum').style.color = 'black';
	}
	if(website!='' && !website.match(/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/))
	{
		errorCheck = 1;
		document.getElementById('website').style.color = 'red';
	}
	else
	{
		document.getElementById('website').style.color = 'black';
	}
	if(facebook!='' && !facebook.match(/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/))
	{
		errorCheck = 1;
		document.getElementById('facebook').style.color = 'red';
	}
	else
	{
		document.getElementById('facebook').style.color = 'black';
	}
	if(twitter!='' && !twitter.match(/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/))
	{
		errorCheck = 1;
		document.getElementById('twitter').style.color = 'red';
	}
	else
	{
		document.getElementById('twitter').style.color = 'black';
	}
	if(errorCheck==0)
	{
		$.ajax({
			url : "php/save_contact.php",
			type : "POST",
			data : "facebook="+document.getElementById('facebook').value+"&twitter="+document.getElementById('twitter').value+"&phone="+document.getElementById('phoneNum').value+"&website="+document.getElementById('website').value,
	 		success : function(result){
	 			if(result=='ok')
	 			{
					document.getElementById('saveContact').disabled = false;
					if(typeof(close)!='undefined' && close==1)
					{
						document.getElementById('saveDescription').disabled = false;
						document.getElementById('saveAndClose').value = 'Save and close';
						hidePopup();
					}
					else
					{
						alert('Your "Contact" section has been updated');
					}
					profileChanged = 0;
				}
				else
				{
					if(typeof(close)!='undefined' && close==1)
					{
						document.getElementById('saveAndClose').disabled = false;
						document.getElementById('saveAndClose').value = 'Save and close';
						document.getElementById('saveDescription').disabled = false;
					}
					document.getElementById('saveContact').disabled = false;
					alert('Sorry an error occured. Please try again later.');
					profileChanged = 1;
				}
	    	}
		});
	}
	else
	{
		if(typeof(close)!='undefined' && close==1)
		{
			document.getElementById('saveAndClose').disabled = false;
			document.getElementById('saveAndClose').value = 'Save and close';
			document.getElementById('saveDescription').disabled = false;
		}
		document.getElementById('saveContact').disabled = false;
		profileChanged = 1;
	}
}

function addFavourite()
{
	if(document.getElementById('favouritesSelect').value!=0)
	{
		document.getElementById('addFavourite').disabled = true;
		$.ajax({
			url : "php/add_favourite.php",
			type : "POST",
			data : "id="+document.getElementById('favouritesSelect').value,
	 		success : function(result){
	 			if(result.match('ok'))
	 			{
	 				var id = result.replace(/(.+)ok( )+/, '');
	 				var name = result.replace(/( )+ok(.+)/, '');
	 				$("#favouritesSelect option[value='"+document.getElementById('favouritesSelect').value+"']").remove();
	 				document.getElementById('exisitingFavs').innerHTML += '<span id="favourite'+id+'">'+name+' <span id="deleteFavourite'+id+'"><a href="javascript:deleteFavourite('+id+');">delete</a></span><br /></span>';
					document.getElementById('addFavourite').disabled = false;
				}
				else
				{
					document.getElementById('addFavourite').disabled = false;
					alert('Sorry an error occured. Please try again later.');
				}
	    	}
		});
	}
}

function deleteFavourite(id)
{
	document.getElementById('deleteFavourite'+id).innerHTML = 'Deleting...';
	$.ajax({
		url : "php/delete_favourite.php",
		type : "POST",
		data : "id="+id,
 		success : function(result){
 			if(result.match('ok'))
 			{
 				var idD = result.replace(/(.+)ok( )+/, '');
 				var name = result.replace(/( )+ok(.+)/, '');
				document.getElementById('favourite'+id).style.display = 'none';
				document.getElementById('favouritesSelect').innerHTML += '<option value="'+idD+'">'+name+'</option>';
			}
			else
			{
				document.getElementById('deleteFavourite'+id).innerHTML = '<a href="javascript:deleteFavourite('+id+');">delete</a>';
				alert('Sorry an error occured. Please try again later.');
			}
    	}
	});
}

function deleteService(id)
{
	var i = 0;
	while(typeof(document.getElementsByClassName('serviceCheckboxes')[i])!='undefined')
	{
		document.getElementsByClassName('serviceCheckboxes')[i].disabled = true;
		i++;
	}
	$.ajax({
		url : "php/delete_service.php",
		type : "POST",
		data : "id="+id,
 		success : function(result){
 			if(result!='ok')
 			{
				document.getElementById('service'+id).checked = false;
				alert('Sorry an error occured. Please try again later.');
			}
			i = 0;
			while(typeof(document.getElementsByClassName('serviceCheckboxes')[i])!='undefined')
			{
				document.getElementsByClassName('serviceCheckboxes')[i].disabled = false;
				i++;
			}
    	}
	});
}

function addService(id)
{
	var i = 0;
	while(typeof(document.getElementsByClassName('serviceCheckboxes')[i])!='undefined')
	{
		document.getElementsByClassName('serviceCheckboxes')[i].disabled = true;
		i++;
	}
	$.ajax({
		url : "php/add_service.php",
		type : "POST",
		data : "id="+id,
 		success : function(result){
 			if(result!='ok')
 			{
				document.getElementById('service'+id).checked = false;
				alert('Sorry an error occured. Please try again later.');
			}
			i = 0;
			while(typeof(document.getElementsByClassName('serviceCheckboxes')[i])!='undefined')
			{
				document.getElementsByClassName('serviceCheckboxes')[i].disabled = false;
				i++;
			}
    	}
	});
}

function deleteRate(id)
{
	document.getElementById('deleteRate'+id).innerHTML = 'Deleting...';
	$.ajax({
		url : "php/delete_rate.php",
		type : "POST",
		data : "id="+id,
 		success : function(result){
 			if(result=='ok')
 			{
				document.getElementById('rate'+id).style.display = 'none';
			}
			else
			{
				document.getElementById('deleteRate'+id).innerHTML = '<a href="javascript:deleteRate('+id+');">delete</a>';
				alert('Sorry an error occured. Please try again later.');
			}
    	}
	});
}

function addRate()
{
	var price = document.getElementById('priceRate').value;
	if(price.match(/[^0-9.]/))
	{
		document.getElementById('priceRate').style.color = 'red';
		document.getElementById('dollarSign').style.color = 'red';
	}
	else if(price!='' && (document.getElementById('hoursRate').value>0 || document.getElementById('minutesRate').value>0))
	{
		document.getElementById('priceRate').style.color = 'black';
		document.getElementById('dollarSign').style.color = 'black';
		document.getElementById('priceRate').disabled = true;
		document.getElementById('hoursRate').disabled = true;
		document.getElementById('minutesRate').disabled = true;
		$.ajax({
			url : "php/add_rate.php",
			type : "POST",
			data : "price="+document.getElementById('priceRate').value+"&hoursRate="+document.getElementById('hoursRate').value+'&minutesRate='+document.getElementById('minutesRate').value,
	 		success : function(result){
	 			if(result.match('ok'))
	 			{
	 				var id=result.replace('ok', '');
					document.getElementById('priceRate').disabled = false;
					document.getElementById('hoursRate').disabled = false;
					document.getElementById('minutesRate').disabled = false;
					if(document.getElementById('hoursRate').value<100 && document.getElementById('hoursRate').value!=0)
					{
						var hours = ("0" + document.getElementById('hoursRate').value).slice(-2);
					}
					else if(document.getElementById('hoursRate').value==0)
					{
						var hours = "00";
					}
					else
					{
						var hours = document.getElementById('hoursRate').value;
					}
					if(document.getElementById('minutesRate').value<100 && document.getElementById('minutesRate').value!=0)
					{
						var minutes = ("0" + document.getElementById('minutesRate').value).slice(-2);
					}
					else if(document.getElementById('minutesRate').value==0)
					{
						var minutes = "00";
					}
					else
					{
						var minutes = document.getElementById('minutesRate').value;
					}
					document.getElementById('rates').innerHTML += '<span id="rate'+id+'">$'+document.getElementById('priceRate').value+' for '+hours+'h'+minutes+' <span id="deleteRate'+id+'"><a href="javascript:deleteRate('+id+');">delete</a></span><br /></span>';
				}
				else
				{
					document.getElementById('priceRate').disabled = false;
					document.getElementById('hoursRate').disabled = false;
					document.getElementById('minutesRate').disabled = false;
					alert('Sorry an error occured. Please try again later.');
				}
	    	}
		});
	}
}

function withdraw(account)
{
	document.getElementById('saveNotClose').disabled = true;
	document.getElementById('saveAndClose').disabled = true;
	document.getElementById('withdrawMembership').disabled = true;
	document.getElementById('deleteAccount').disabled = true;
	if(document.getElementById('oldPassword').value=='')
	{
		document.getElementById('enterPw').style.color = 'red';
		document.getElementById('saveNotClose').disabled = false;
		document.getElementById('saveAndClose').disabled = false;
		document.getElementById('withdrawMembership').disabled = false;
		document.getElementById('deleteAccount').disabled = false;
	}
	else
	{
		document.getElementById('enterPw').style.color = 'black';
		if(account==1)
		{
			var donnees = "deleteAccount=1&opw="+encodeURIComponent(document.getElementById('oldPassword').value);
		}
		else
		{
			var donnees = "deleteMembership=1&opw="+encodeURIComponent(document.getElementById('oldPassword').value);
		}
		if(account==1)
		{
			var message = 'Are you sure you want to delete your account?';
		}
		else
		{
			var message = 'Are you sure you want to withdraw your membership?';
		}
		if(confirm(message))
		{
			$.ajax({
				url : "php/check_old_pw.php",
				type : "POST",
				data : donnees,
		 		success : function(result){
		 			if(result=='ok')
		 			{
		 				if(account==1)
		 				{
		 					alert('Your account was successfuly closed.');
		 				}
		 				else
		 				{
		 					alert('Your membership was successfuly withdrawn and you were logged out. Loggin again to set up your new membership.');
		 				}
		 				window.location='http://'+server+'/';
		 			}
		 			else if(result=='ip')
		 			{
						document.getElementById('enterPw').style.display = 'none';
						document.getElementById('nicknameTaken').style.display = 'none';
						document.getElementById('incorrectPw').style.display = '';
						document.getElementById('saveNotClose').disabled = false;
						document.getElementById('saveAndClose').disabled = false;
						document.getElementById('withdrawMembership').disabled = false;
						document.getElementById('deleteAccount').disabled = false;
		 			}
		 			else
		 			{
						document.getElementById('enterPw').style.display = '';
						document.getElementById('incorrectPw').style.display = 'none';
						document.getElementById('nicknameTaken').style.display = 'none';
						document.getElementById('saveNotClose').disabled = false;
						document.getElementById('saveAndClose').disabled = false;
						document.getElementById('withdrawMembership').disabled = false;
						document.getElementById('deleteAccount').disabled = false;
						alert('Sorry an error occured. Please try again later.');
		 			}
		    	}
			});
		}
		else
		{
			document.getElementById('saveNotClose').disabled = false;
			document.getElementById('saveAndClose').disabled = false;
			document.getElementById('withdrawMembership').disabled = false;
			document.getElementById('deleteAccount').disabled = false;
		}
	}
}

function checkAccountChanges(close)
{
	document.getElementById('saveNotClose').disabled = true;
	document.getElementById('saveAndClose').disabled = true;
	if(typeof(document.getElementById('withdrawMembership'))!='undefined' && document.getElementById('withdrawMembership')!=null && typeof(document.getElementById('deleteAccount'))!='undefined' && document.getElementById('deleteAccount')!=null)
	{
		document.getElementById('withdrawMembership').disabled = true;
		document.getElementById('deleteAccount').disabled = true;
	}
	document.getElementById('saveNotClose').value = 'Saving...';
	document.getElementById('saveAndClose').value = 'Saving...';
	var nickname = document.getElementById('nickname').value;
	if(nickname=='' || nickname.match(/[^0-9A-Za-z -]/))
	{
		document.getElementById('alphanum').style.display = '';
		document.getElementById('saveNotClose').disabled = false;
		document.getElementById('saveAndClose').disabled = false;
		if(typeof(document.getElementById('withdrawMembership'))!='undefined' && document.getElementById('withdrawMembership')!=null && typeof(document.getElementById('deleteAccount'))!='undefined' && document.getElementById('deleteAccount')!=null)
		{
			document.getElementById('withdrawMembership').disabled = false;
			document.getElementById('deleteAccount').disabled = false;
		}
		document.getElementById('saveNotClose').value = 'Save changes';
		document.getElementById('saveAndClose').value = 'Save and close';
	}
	else
	{
		document.getElementById('alphanum').style.display = 'none';
		if(document.getElementById('oldPassword').value=='')
		{
			document.getElementById('enterPw').style.color = 'red';
			document.getElementById('saveNotClose').disabled = false;
			document.getElementById('saveAndClose').disabled = false;
			if(typeof(document.getElementById('withdrawMembership'))!='undefined' && document.getElementById('withdrawMembership')!=null && typeof(document.getElementById('deleteAccount'))!='undefined' && document.getElementById('deleteAccount')!=null)
			{
				document.getElementById('withdrawMembership').disabled = false;
				document.getElementById('deleteAccount').disabled = false;
			}
			document.getElementById('saveNotClose').value = 'Save changes';
			document.getElementById('saveAndClose').value = 'Save and close';
		}
		else
		{
			document.getElementById('enterPw').style.color = 'black';
			$.ajax({
				url : "php/check_old_pw.php",
				type : "POST",
				data : "nickname="+nickname+"&npw="+encodeURIComponent(document.getElementById('newPassword').value)+"&opw="+encodeURIComponent(document.getElementById('oldPassword').value),
		 		success : function(result){
		 			if(result=='ok')
		 			{
						document.getElementById('enterPw').style.display = '';
						document.getElementById('incorrectPw').style.display = 'none';
						document.getElementById('nicknameTaken').style.display = 'none';
						document.getElementById('saveNotClose').disabled = false;
						document.getElementById('saveAndClose').disabled = false;
						if(typeof(document.getElementById('withdrawMembership'))!='undefined' && document.getElementById('withdrawMembership')!=null && typeof(document.getElementById('deleteAccount'))!='undefined' && document.getElementById('deleteAccount')!=null)
						{
							document.getElementById('withdrawMembership').disabled = false;
							document.getElementById('deleteAccount').disabled = false;
						}
						document.getElementById('saveNotClose').value = 'Save changes';
						document.getElementById('saveAndClose').value = 'Save and close';
						if(close==1)
						{
							hidePopup();
						}
						else
						{
							alert('Your changes were successfuly saved.');
						}
		 			}
		 			else if(result=='ip')
		 			{
						document.getElementById('enterPw').style.display = 'none';
						document.getElementById('nicknameTaken').style.display = 'none';
						document.getElementById('incorrectPw').style.display = '';
						document.getElementById('saveNotClose').disabled = false;
						document.getElementById('saveAndClose').disabled = false;
						if(typeof(document.getElementById('withdrawMembership'))!='undefined' && document.getElementById('withdrawMembership')!=null && typeof(document.getElementById('deleteAccount'))!='undefined' && document.getElementById('deleteAccount')!=null)
						{
							document.getElementById('withdrawMembership').disabled = false;
							document.getElementById('deleteAccount').disabled = false;
						}
						document.getElementById('saveNotClose').value = 'Save changes';
						document.getElementById('saveAndClose').value = 'Save and close';
		 			}
		 			else if(result=='nt')
		 			{
						document.getElementById('enterPw').style.display = 'none';
						document.getElementById('incorrectPw').style.display = 'none';
						document.getElementById('nicknameTaken').style.display = '';
						document.getElementById('saveNotClose').disabled = false;
						document.getElementById('saveAndClose').disabled = false;
						if(typeof(document.getElementById('withdrawMembership'))!='undefined' && document.getElementById('withdrawMembership')!=null && typeof(document.getElementById('deleteAccount'))!='undefined' && document.getElementById('deleteAccount')!=null)
						{
							document.getElementById('withdrawMembership').disabled = false;
							document.getElementById('deleteAccount').disabled = false;
						}
						document.getElementById('saveNotClose').value = 'Save changes';
						document.getElementById('saveAndClose').value = 'Save and close';
		 			}
		 			else
		 			{
						document.getElementById('enterPw').style.display = '';
						document.getElementById('incorrectPw').style.display = 'none';
						document.getElementById('nicknameTaken').style.display = 'none';
						document.getElementById('saveNotClose').disabled = false;
						document.getElementById('saveAndClose').disabled = false;
						if(typeof(document.getElementById('withdrawMembership'))!='undefined' && document.getElementById('withdrawMembership')!=null && typeof(document.getElementById('deleteAccount'))!='undefined' && document.getElementById('deleteAccount')!=null)
						{
							document.getElementById('withdrawMembership').disabled = false;
							document.getElementById('deleteAccount').disabled = false;
						}
						document.getElementById('saveNotClose').value = 'Save changes';
						document.getElementById('saveAndClose').value = 'Save and close';
						alert('Sorry an error occured. Please try again later.');
		 			}
		    	}
			});
		}
	}
}

function deleteAccount(id)
{
	if(confirm('Are you sure you want to delete this account?'))
	{
		var exContent = document.getElementById('deleteSeparate'+id).innerHTML;
		document.getElementById('deleteSeparate'+id).innerHTML = 'Deleting...';
		$.ajax({
			url : "php/delete_subaccount.php",
			type : "POST",
			data : "id="+id,
				success : function(result){
					if(result=='ok')
					{
						document.getElementById('subAccount'+id).style.display = 'none';
						document.getElementById('addAccountBlurb').innerHTML = '<input type="button" value="Click here to add a sub-account" id="addAccount" name="addAccount" onclick="showProfile(\'setupAccount\');" />';
						//document.getElementById('addAccountBlurb').innerHTML = '<br /><div id="createAcInstructions">To create a sub-account, enter the person\'s e-mail address below.<br />An e-mail will be sent to them to set up their account.<br /></div><div id="emailTaken" style="display: none; color: red;">This e-mail address is already assigned to an account.</div><div id="invalidEmail" style="display: none; color: red;">Please enter a valid e-mail address.</div><input type="text" id="email" name="email" /> <input type="button" value="Add account" id="addAccount" name="addAccount" onclick="addAccount();" />';
		 			}
					else
					{
						document.getElementById('deleteSeparate'+id).innerHTML = exContent;
						alert('Sorry, an error occured. Please try again later.');
					}
			}
		});
	}
}

function separateAccount(id)
{
	if(confirm('Are you sure you want to separate this account from your agency?'))
	{
		var exContent = document.getElementById('deleteSeparate'+id).innerHTML;
		document.getElementById('deleteSeparate'+id).innerHTML = 'Separating...';
		$.ajax({
			url : "php/separate_subaccount.php",
			type : "POST",
			data : "id="+id,
				success : function(result){
					if(result=='ok')
					{
						document.getElementById('subAccount'+id).style.display = 'none';
						document.getElementById('addAccountBlurb').innerHTML = '<br /><div id="createAcInstructions">To create a sub-account, enter the person\'s e-mail address below.<br />An e-mail will be sent to them to set up their account.<br /></div><div id="emailTaken" style="display: none; color: red;">This e-mail address is already assigned to an account.</div><div id="invalidEmail" style="display: none; color: red;">Please enter a valid e-mail address.</div><input type="text" id="email" name="email" /> <input type="button" value="Add account" id="addAccount" name="addAccount" onclick="addAccount();" />';
		 			}
					else
					{
						document.getElementById('deleteSeparate'+id).innerHTML = exContent;
						alert('Sorry, an error occured. Please try again later.');
					}
			}
		});
	}			
}

function addAccount()
{
	var email = document.getElementById('email').value;
	document.getElementById('email').disabled = true;
	document.getElementById('addAccount').disabled = true;
	if(!(email.match(/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$/)))
	{
		document.getElementById('invalidEmail').style.display = '';
		document.getElementById('createAcInstructions').style.display = 'none';
		document.getElementById('email').disabled = false;
		document.getElementById('addAccount').disabled = false;
	}
	else
	{
		document.getElementById('invalidEmail').style.display = 'none';
		document.getElementById('createAcInstructions').style.display = '';
		$.ajax({
			url : "php/add_subaccount.php",
			type : "POST",
			data : "email="+email,
	 		success : function(result){
	 			if(result.match('ok'))
	 			{
	 				var id = result.replace(/ok.+/, '');
	 				var number = result.replace(/.+ok/, '');
	 				document.getElementById('accountsTable').innerHTML += '<tr id="subAccount'+id+'"><td>'+document.getElementById('email').value+' (waiting for set up) </td><td>&nbsp;&nbsp;&nbsp;<span style="font-size: 12px;" id="deleteSeparate'+id+'"><span style="color: #bbb;">Separate account</span>&nbsp;&nbsp;&nbsp;<a href="javascript:deleteAccount('+id+');">Delete</a></span></td></tr>';
	 				if(number>=19)
	 				{
	 					document.getElementById('addAccountBlurb').innerHTML = '<br />You have reached the maximum number of accounts';
	 				}
	 				else
	 				{
	 					document.getElementById('addAccountBlurb').innerHTML = '<br /><div id="createAcInstructions">To create a sub-account, enter the person\'s e-mail address below.<br />An e-mail will be sent to them to set up their account.<br /></div><div id="emailTaken" style="display: none; color: red;">This e-mail address is already assigned to an account.</div><div id="invalidEmail" style="display: none; color: red;">Please enter a valid e-mail address.</div><input type="text" id="email" name="email" /> <input type="button" value="Add account" id="addAccount" name="addAccount" onclick="addAccount();" />';
	 				}
					document.getElementById('invalidEmail').style.display = 'none';
					document.getElementById('createAcInstructions').style.display = '';
					document.getElementById('emailTaken').style.display = 'none';
					document.getElementById('email').disabled = false;
					document.getElementById('addAccount').disabled = false;
	 			}
	 			else if(result=='taken')
	 			{
					document.getElementById('invalidEmail').style.display = 'none';
					document.getElementById('createAcInstructions').style.display = 'none';
					document.getElementById('emailTaken').style.display = '';
					document.getElementById('email').disabled = false;
					document.getElementById('addAccount').disabled = false;
	 			}
	 			else
	 			{
					document.getElementById('invalidEmail').style.display = 'none';
					document.getElementById('createAcInstructions').style.display = '';
					document.getElementById('emailTaken').style.display = 'none';
	 				alert('Sorry, an error occured. Please try again later.');
					document.getElementById('email').disabled = false;
					document.getElementById('addAccount').disabled = false;
	 			}
	    	}
		});
	}
}

function setupAccount(id, code, fname, lname, gender, birthDay, birthMonth, birthYear, email, nickname, password, region, city, postCode, shaved, nationality, ethnicity, mainService, eyeColour, hairDescription, height, bodyType, dressSize, bustSize, smoking, disableFriendly, cityComp)
{
	if(typeof(fname!='undefined') && fname!='undefined')
	{
		var data = "id="+id+"&code="+code+"&fname="+fname+"&lname="+lname+"&gender="+gender+"&birthDay="+birthDay+"&birthMonth="+birthMonth+"&birthYear="+birthYear+"&email="+email+"&nickname="+nickname+"&password="+encodeURIComponent(password)+"&region="+region+"&city="+city+"&postCode="+postCode+"&shaved="+shaved+"&nationality="+nationality+"&ethnicity="+ethnicity+"&mainService="+mainService+"&eyeColour="+eyeColour+"&hairDescription="+hairDescription+"&height="+height+"&bodyType="+bodyType+"&dressSize="+dressSize+"&bustSize="+bustSize+"&smoking="+smoking+"&disableFriendly="+disableFriendly+"&cityComp="+cityComp;
	}
	else
	{
		var data = "id="+id+"&code="+code;
	}
	$.ajax({
		url : "parts/setup_account.php",
		type : "POST",
		data : data,
 		success : function(result){
 			document.getElementById('popupWindowContent').innerHTML = result;
 			$('#monthBirth').change();
 			$('#regionSelect').change();
 			if(document.getElementById('gender').value!='male') { document.getElementById('bustSize').disabled = false; } else { document.getElementById('bustSize').disabled = true; }
    	}
	});
}

function changeSizes(gender, noSelect)
{
	if(gender!='male')
	{
		document.getElementById('dressSizeLabel').innerHTML = 'Dress size (US): ';
		document.getElementById('dressSize').innerHTML = '';
		if(!(typeof(noSelect)!='undefined' && noSelect == 1))
		{
			document.getElementById('dressSize').innerHTML += '<option value=""> - Leave blank - </option>';
		}
		document.getElementById('dressSize').innerHTML += '<option value="0">0</option><option value="2">2</option><option value="4">4</option><option value="6">6</option><option value="8">8</option><option value="10">10</option><option value="12">12</option><option value="14">14</option><option value="16">16</option><option value="18">18</option><option value="20">20</option>';
	}
	else
	{
		document.getElementById('dressSizeLabel').innerHTML = 'Size (US): ';
		document.getElementById('dressSize').innerHTML = '';
		if(!(typeof(noSelect)!='undefined' && noSelect == 1))
		{
			document.getElementById('dressSize').innerHTML += '<option value=""> - Leave blank - </option>';
		}
		document.getElementById('dressSize').innerHTML += '<option value="XS">XS</option><option value="S">S</option><option value="M">M</option><option value="L">L</option><option value="XL">X</option><option value="XXL">XXL</option>';
	}
}

if (window.attachEvent) window.attachEvent("onload", sfHover);