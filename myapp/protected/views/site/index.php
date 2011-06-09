<!-- theater mask -->
<div id="theater_mask" class="theater-mask">sdfsdf</div>
<div class="theater-container">
	<!-- Sign Up Form -->
	<div id="signup_form" style="display: none;">
		<div class="close_button" onclick="hideSignup()">X</div>
		<div style="margin-bottom: 30px;">Sign Up</div>
		<div>Email Address</div>
		<div><input type="text" id="resetpwd_email" class="big-textbox" value="" /></div>
		<div>Password</div>
		<div><input type="text" id="resetpwd_email" class="big-textbox" value="" /></div>
		<div>Password - Verify</div>
		<div><input type="text" id="resetpwd_email" class="big-textbox" value="" /></div>
		<div style="text-align: center; margin-top: 15px;">
			<input type="button" value="Create Account" />
		</div>
		<div style="color: #00A; font-size: 10pt; margin-top: 15px; height: 20px;"></div>
	</div>
	
	<!-- Reset Password Form -->
	<div id="resetpwd_form" style="display: none;">
		<div class="close_button" onclick="hideResetPwd()">X</div>
		<div style="margin-bottom: 30px;">Reset Password</div>
		<div>Email Address</div>
		<div><input type="text" id="resetpwd_email" class="big-textbox" value="" /></div>
		<div style="text-align: center; margin-top: 15px;">
			<input type="button" value="reset password" />
		</div>
		<div style="color: #00A; font-size: 10pt; margin-top: 15px; height: 20px;"></div>
	</div>
</div>



<div id="main_form">
	<div style="margin-bottom: 30px;">Member please login.</div>
	<!-- Login Form -->
	<div>Email Address</div>
	<div>
		<input type="text" id="email" class="big-textbox" autofocus />
	</div>
	<div>Password</div>
	<div>
		<input type="password" id="pwd" class="big-textbox" />
	</div>
	<br />
	<div style="text-align: center;">
		<input type="button" id="login" value="Login" />
	</div>
	<div id="errMsg" style="height: 20px; line-height: 20px; margin: 15px auto;"></div>
	<hr />
	<div style="text-align: center;">
		<span style="margin-bottom: 10px;">Forgot password? <input type="button" id="resetpwd" value="Reset Password" /></span> |
		<span>Don&#039;t have account? <input type="button" id="signup" value="Sign Up" /></span>
	</div>
</div>



<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/restaddon.js"></script>
<script type="text/javascript">

//This is bad but I'm not going to setup any SEO site for this, so just keep it this way.
var baseUrl = '<?php echo Yii::app()->request->baseUrl; ?>';

$(document).ready(function() {
	setListener();
});

function setListener() {
	$('#login').bind('click', doLogin);
	$('#resetpwd').bind('click', doResetPwd);
	$('#signup').bind('click', doSignup);
}

function doLogin() {
	if (!validateLogin()) return;
	$.ajax({
		url: baseUrl + '/?r=site/login',
		type: "POST",
		dataType: "json",
		data: {
			email: $('#email').val(),
			pwd: $('#pwd').val()
		},
		success: function(data) {
			loginAjaxSuccess(data);
		},
		fail: function() {
			loginAjaxFail();
		}
	});
}

function loginAjaxSuccess(data) {
	if ( data.status ) {
		$('#errMsg').html( data.message );
		location.reload();
	} else {
		$('#errMsg').html( data.message );
	}
}

function loginAjaxFail() {
	$('#errMsg').html( 'Error connecting to server. Please try again.' );
}

function validateLogin() {
	var email = $('#email').val();
	var pwd = $('#pwd').val();
	var focus = '';
	
	var errMsg = "";
	var result = true;
	
	if (email == "") {
		errMsg = "Email Address is required.";
		focus = 'email';
		result = false;
	}
	
	if (pwd == "") {
		errMsg += " Password is required.";
		if (focus == '') focus = 'pwd';
		result = false;
	}
	
	$('#errMsg').html(errMsg);
	
	if (!result) $('#' + focus).focus();
	return result;
}

function doResetPwd() {
	showTheater();
	$('#resetpwd_form').show();
}

function hideResetPwd() {
	hideTheater();
	$('#resetpwd_form').hide();
}

function doSignup() {
	showTheater();
	$('#signup_form').show();
}
//An email has sent to you.<br />Please check your mailbox.

function hideSignup() {
	hideTheater();
	$('#signup_form').hide();
}

function showTheater() {
	$('body').addClass('theater-mode');
	$('#main_form input').attr('disabled', 'disabled');
}

function hideTheater() {
	$('body').removeClass('theater-mode');
	$('#main_form input').removeAttr('disabled');
}

function validateEmail( email ) {
	var emailRegex = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
	return emailRegex.test( trim( email ) );
}


//PHPJS function
function trim(str,charlist){
var whitespace,l=0,i=0;str+='';if(!charlist){whitespace=" \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";}else{charlist+='';whitespace=charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g,'$1');}
l=str.length;for(i=0;i<l;i++){if(whitespace.indexOf(str.charAt(i))===-1){str=str.substring(i);break;}}
l=str.length;for(i=l-1;i>=0;i--){if(whitespace.indexOf(str.charAt(i))===-1){str=str.substring(0,i+1);break;}}
return whitespace.indexOf(str.charAt(0))===-1?str:'';
}
</script>