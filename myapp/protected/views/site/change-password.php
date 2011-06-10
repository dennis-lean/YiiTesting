<div id="main_form">
	<div style="margin-bottom: 30px;">Reset password.</div>
	<div>Old Password</div>
	<div>
		<input type="password" id="old-pwd" class="big-textbox" />
	</div>
	<div>New Password</div>
	<div>
		<input type="password" id="new-pwd" class="big-textbox" />
	</div>
	<div>New Password - Verify</div>
	<div>
		<input type="password" id="new-pwd-verify" class="big-textbox" />
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
	$('#submit').bind('click', doLogin);
}

function doLogin() {
	if (!validateForm()) return;
	$.ajax({
		url: baseUrl + '/?r=site/performChangePassword',
		type: "POST",
		dataType: "json",
		data: {
			old_pwd: $('#old-pwd').val(),
			new_pwd: $('#new-pwd').val(),
			new_pwd_verify: $('#new-pwd-verify').val()
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
	var old_pwd = $('#old-pwd').val();
	var pwd = $('#new-pwd').val();
	var pwdVerify = $('#new-pwd-verify').val();
	var focus = '';
	
	var errMsg = "";
	var result = true;
	
	if (old_pwd == "") {
		errMsg = "Old Password is required.";
		focus = 'old-pwd';
		result = false;
		
	} else if (pwd == "") {
		errMsg = "New Password is required.";
		focus = 'new-pwd';
		result = false;
		
	} else if (pwdVerify == "") {
		errMsg = "Password - Verify is required.";
		focus = 'new-pwd-verify';
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