<div class="second-stage" style="display: none;">sdfsdf</div>
<div>Member please login.</div>
<br />
<br />
<div>
	<div style="margin-bottom: 10px;">
		Username<br />
		<input type="text" id="uname" class="big-textbox" />
	</div>
	<div>
		Password<br />
		<input type="password" id="pwd" class="big-textbox" />
	</div>
	<br />
	<div style="text-align: center;">
		<input type="button" id="login" value="Login" />
	</div>
</div>
<br />
<div id="errMsg">&nbsp;</div>
<br />
<hr />
<div style="text-align: center;">
	<span style="margin-bottom: 10px;">Forgot password? <input type="button" value="Reset Password" /></span> |
	<span>Don&#039;t have account? <input type="button" value="Sign Up" /></span>
</div>





<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/restaddon.js"></script>
<script type="text/javascript">
var baseUrl = '<?php echo Yii::app()->request->baseUrl; ?>';

$(document).ready(function() {
	setListener();
});

function setListener() {
	$('#login').bind('click', function() {
		doLogin();
	});
}

function doLogin() {
	if (!validateLogin()) return;
	$.ajax({
		url: baseUrl + '/login',
		type: "POST",
		dataType: "json",
		data: {
			uname: $('#uname').val(),
			pwd: $('#pwd').val()
		},
		success: function(data) {
			loginAjaxSuccess();
		},
		fail: function() {
			loginAjaxFail();
		}
	});
}

function loginAjaxSuccess() {
	
}

function loginAjaxFail() {
	
}

function validateLogin() {
	$('#errMsg').html('&nbsp;');
	var uname = $('#uname').val();
	var pwd = $('#pwd').val();
	
	var errMsg = "";
	var result = true;
	
	if (uname == "") {
		errMsg = "Username is required.";
		result = false;
	}
	
	if (pwd == "") {
		errMsg += " Password is required.";
		result = false;
	}
	
	$('#errMsg').html(errMsg);
	
	return result;
}
</script>