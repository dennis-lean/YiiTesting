<!-- theater mask -->
<div id="theater_mask" class="theater-mask">sdfsdf</div>
<div class="theater-container">
	<!-- Sign Up Form -->
	<div id="signup_form" style="display: none;">
		<div class="close_button" onclick="hideSignup()">X</div>
		<div style="margin-bottom: 30px;">Sign Up</div>
		<div>Email Address</div>
		<div><input type="text" id="signup_email" class="big-textbox" value="" /></div>
		<div>Password</div>
		<div><input type="password" id="signup_pwd" class="big-textbox" value="" /></div>
		<div>Password - Verify</div>
		<div><input type="password" id="signup_pwd_verify" class="big-textbox" value="" /></div>
		<div style="text-align: center; margin-top: 15px;">
			<input type="button" value="Create Account" onclick="doSignup();" />
		</div>
		<div id="signup_msg" style="color: #F00; font-size: 10pt; margin-top: 15px; height: 20px; text-align: center;"></div>
	</div>
	
	<!-- Reset Password Form -->
	<div id="resetpwd_form" style="display: none;">
		<div class="close_button" onclick="hideResetPwd()">X</div>
		<div style="margin-bottom: 30px;">Reset Password</div>
		<div>Email Address</div>
		<div><input type="text" id="resetpwd_email" class="big-textbox" value="" /></div>
		<div style="text-align: center; margin-top: 15px;">
			<input type="button" value="reset password" onclick="doResetPwd();" />
		</div>
		<div id="resetpwd_msg" style="color: #F00; font-size: 10pt; margin-top: 15px; height: 20px; text-align: center;"></div>
	</div>
</div>



<div id="main_form">
	<h4 style="margin-top: 0px;">Member Login</h4>
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
	<div id="errMsg" style="height: 20px; line-height: 20px; margin: 5px auto;"></div>
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
	$('#resetpwd').bind('click', showResetPwd);
	$('#signup').bind('click', showSignup);
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
		
	} else if ( !validateEmail( email ) ) {
		errMsg = "Invalid Email Address.";
		focus = 'email';
		result = false;

	} else if (pwd == "") {
		errMsg = "Password is required.";
		focus = 'pwd';
		result = false;
	}
	
	$('#errMsg').html(errMsg);
	
	if (!result) $('#' + focus).focus();
	return result;
}

function showResetPwd() {
	showTheater();
	$('#resetpwd_form').show();
	$('#resetpwd_email').focus();
}

function doResetPwd() {
	var email = $('#resetpwd_email').val();
	if ( email == '' ) {
		$('#resetpwd_msg').html( "Email address is required." );
		$('#resetpwd_email').focus();
		return;
		
	} else if ( !validateEmail( email ) ) {
		$('#resetpwd_msg').html( "Invalid email address." );
		$('#resetpwd_email').focus();
		return;
		
	} else {
		$.ajax({
			url: baseUrl + '/?r=site/sendResetPassword',
			type: "POST",
			dataType: "json",
			data: {
				email: email
			},
			success: function(data) {
				$('#resetpwd_msg').html( data.message );
			},
			fail: function() {
				$('#resetpwd_msg').html( 'Error connecting to server. Please try again.' );
			}
		});
	}
}

function hideResetPwd() {
	hideTheater();
	$('#resetpwd_form').hide();
	$('#email').focus();
}

function showSignup() {
	showTheater();
	$('#signup_form').show();
	$('#signup_email').focus();
}

function doSignup() {
	var email = $('#signup_email').val();
	var pwd = $('#signup_pwd').val();
	var pwd_verify = $('#signup_pwd_verify').val();
	
	if ( email == '' ) {
		$('#signup_msg').html( "Email address is required." );
		$('#signup_email').focus();
		return;

	} else if ( !validateEmail( email ) ) {
		$('#signup_msg').html( "Invalid email address." );
		$('#signup_email').focus();
		return;

	} else if ( pwd == '' ) {
		$('#signup_msg').html( "Password is required." );
		$('#signup_pwd').focus();
		return;

	} else if ( pwd_verify == '' ) {
		$('#signup_msg').html( "Password - Verify is required." );
		$('#signup_pwd_verify').focus();
		return;

	} else if ( pwd != pwd_verify ) {
		$('#signup_msg').html( "Password - Verify is not match." );
		$('#signup_pwd_verify').focus();
		return;

	} else {
		$.ajax({
			url: baseUrl + '/?r=site/createUser',
			type: "POST",
			dataType: "json",
			data: {
				email: email,
				pwd: pwd,
				pwd_verify: pwd_verify
			},
			success: function(data) {
				$('#signup_msg').html( data.message );
			},
			fail: function() {
				$('#signup_msg').html( 'Error connecting to server. Please try again.' );
			}
		});
	}
}

function hideSignup() {
	hideTheater();
	$('#signup_form').hide();
	$('#email').focus();
}

function showTheater() {
	$('body').addClass('theater-mode');
	$('#main_form input').attr('disabled', 'disabled');
}

function hideTheater() {
	$('body').removeClass('theater-mode');
	$('#main_form input').removeAttr('disabled');
}
</script>