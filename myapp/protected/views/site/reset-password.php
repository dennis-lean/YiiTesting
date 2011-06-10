<div id="main_form">
	<div style="margin-bottom: 30px;">Reset password.</div>
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
	<div id="errMsg" style="height: 20px; line-height: 20px; margin: 15px auto;"></div>
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
	if (!validateLogin()) return;
	$.ajax({
		url: baseUrl + '/?r=site/changePassword&act=reset',
		type: "POST",
		dataType: "json",
		data: {
			pwd: $('#pwd').val(),
			pwd_verify: $('#pwd-verify').val()
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
	var pwd = $('#pwd').val();
	var pwdVerify = $('#pwd-verify').val();
	var focus = '';
	
	var errMsg = "";
	var result = true;
	
	if (pwd == "") {
		errMsg += " Password is required.";
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