<div id="main_form">
	<h4 style="margin-top: 0px;">Reset Password</h4>
	<div>Email Address</div>
	<div>
		<input type="text" class="big-textbox" value="<?=$email ?>" disabled />
	</div>
	<div>New Password</div>
	<div>
		<input type="password" id="pwd" class="big-textbox" />
	</div>
	<div>New Password - Verify</div>
	<div>
		<input type="password" id="pwd-verify" class="big-textbox" />
	</div>
	<br />
	<div style="text-align: center;">
		<input type="button" id="submit" value="Submit" />
	</div>
	<div id="errMsg" style="height: 20px; line-height: 20px; margin: 5px auto;"></div>
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
	$('#submit').bind('click', doAction);
}

function doAction() {
	if (!validateForm()) return;
	$('#errMsg').html( 'Please wait...' );
	$.ajax({
		url: baseUrl + '/?r=site/performResetPassword&token=' + (/\?.*\&token=(.*)/.exec(location.search)[1]),
		type: "POST",
		dataType: "json",
		data: {
			pwd: $('#pwd').val(),
			pwd_verify: $('#pwd-verify').val()
		},
		success: function(data) {
			runAjaxSuccess(data);
		},
		fail: function() {
			runAjaxFail();
		}
	});
}

function runAjaxSuccess(data) {
	if ( data.status ) {
		$('#errMsg').html( data.message );
		location.replace( data.returnUrl );
	} else {
		$('#errMsg').html( data.message );
	}
}

function runAjaxFail() {
	$('#errMsg').html( 'Error connecting to server. Please try again.' );
}

function validateForm() {
	var pwd = $('#pwd').val();
	var pwdVerify = $('#pwd-verify').val();
	var focus = '';
	
	var errMsg = "";
	var result = true;
	
	if (pwd == "") {
		errMsg = "Password is required.";
		focus = 'pwd';
		result = false;
		
	} else if (pwdVerify == "") {
		errMsg = "Password - Verify is required.";
		focus = 'pwd-verify';
		result = false;
		
	} else if (pwd !== pwdVerify) {
		errMsg = "Password - Verify is not match.";
		focus = 'pwd-verify';
		result = false;
	}
	
	$('#errMsg').html(errMsg);
	
	if (!result) $('#' + focus).focus();
	return result;
}
</script>