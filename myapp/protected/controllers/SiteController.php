<?php

class SiteController extends Controller
{
	private $email;
	private $pwd;
	private $_identity;
	
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		if ( Yii::app()->user->isGuest )
			$this->render('index');
		else
			$this->render('dashboard');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect( Yii::app()->homeUrl );
	}
	
	public function actionLogin()
	{
		$this->email = $_POST['email'];
		$this->pwd = $_POST['pwd'];
		if ($this->authenticate()) {
			$duration = 0;
			Yii::app()->user->login($this->_identity,$duration);
			
			$result = new stdClass();
			$result->status = true;
			$result->message = 'Login success.';
			echo json_encode($result);
			Yii::app()->end();
			
		} else {
			$result = new stdClass();
			$result->status = false;
			$result->message = 'Incorrect Email Address or Password.';
			echo json_encode($result);
			Yii::app()->end();
			
		}
	}
	
	public function actionChangePassword()
	{
		if ( Yii::app()->user->isGuest ) {
			$this->render('index');
			
		} else {
			$this->render('change-password');
		}
	}
	
	public function actionPerformChangePassword()
	{
		if (empty( $_POST )) {
			header("HTTP/1.0 400 Bad Request");
			die;
		}
		
		$data = $_POST;
		if ( empty($data['old_pwd']) ||
			 empty($data['new_pwd']) ||
			 empty($data['new_pwd_verify']) ||
			 $data['new_pwd'] !== $data['new_pwd_verify'] )
		{
			$result = new stdClass();
			$result->status = false;
			$result->message = 'Invalid password.';
			echo json_encode($result);
			Yii::app()->end();
		}
		else
		{
			$user = User::model()->find( 'id = ?',array( Yii::app()->user->getId() ) );

			if ( $user === null ) {
				$result = new stdClass();
				$result->status = false;
				$result->message = 'Invalid request.';
				echo json_encode($result);
				Yii::app()->end();

			} else if ( !$user->validatePassword( $data['old_pwd'] ) ) {
				$result = new stdClass();
				$result->status = false;
				$result->message = 'Invalid old password.';
				echo json_encode($result);
				Yii::app()->end();
				
			} else {
				$user->salt = $this->generateSalt();
				$user->password = $user->hashPassword($data['new_pwd'], $user->salt);
				$user->update();

				$result = new stdClass();
				$result->status = true;
				$result->message = 'Password changed successful.';
				$result->returnUrl = Yii::app()->request->baseUrl;
				echo json_encode($result);
				Yii::app()->end();
			}
		}
	}
	
	public function actionPerformResetPassword()
	{
		if (empty( $_POST )) {
			header("HTTP/1.0 400 Bad Request");
			die;
		}
		
		$data = $_POST;
		if ( empty($data['pwd']) ||
			 empty($data['pwd_verify']) ||
			 $data['pwd'] !== $data['pwd_verify'] )
		{
			$result = new stdClass();
			$result->status = false;
			$result->message = 'Invalid password.';
			echo json_encode($result);
			Yii::app()->end();
		}
		else if (isset($_GET['token'])) {
			$user_token = User::model()->find( 'LOWER(reset_token) = ?',array( strtolower( $_GET['token'] ) ) );

			//Token validation
			if ( $user_token === null ) {
				$data['message'] = "Invalid request.";
				$this->render('error-message', $data);
				return;

			} else if ( $user_token->reset_expired < time() ) {
				$data['message'] = "Token has expired. Please request again.";
				$this->render('error-message', $data);
				return;

			} else {
				$user_token->salt = $this->generateSalt();
				$user_token->password = $user_token->hashPassword($data['pwd'], $user_token->salt);
				$user_token->reset_token = null;
				$user_token->reset_expired = null;
				$user_token->update();

				$result = new stdClass();
				$result->status = true;
				$result->message = 'Password changed successful.';
				$result->returnUrl = Yii::app()->request->baseUrl;
				echo json_encode($result);
				Yii::app()->end();
			}

		} else {
			//Invalid Request!
			$data['message'] = "Invalid Request";
			$this->render('error-message', $data);

		}
	}
	
	public function actionResetPassword()
	{
		$data = array();
		if (isset($_GET['token'])) {
			$user_token = User::model()->find( 'LOWER(reset_token) = ?',array( strtolower( $_GET['token'] ) ) );
			
			//Token validation
			if ( $user_token === null ) {
				$data['message'] = "Invalid request.";
				$this->render('error-message', $data);
				return;
				
			} else if ( $user_token->reset_expired < time() ) {
				$data['message'] = "Token has expired. Please request again.";
				$this->render('error-message', $data);
				return;
				
			}
			
			$data['email'] = $user_token->username;
			$this->render('reset-password', $data);
			
		} else {
			//Invalid Request!
			$data['message'] = "Invalid Request";
			$this->render('error-message', $data);
			
		}
	}
	
	public function actionSendResetPassword()
	{
		$this->email = $_POST['email'];
		$this->authenticate();
		if ( $this->_identity->errorCode === UserIdentity::ERROR_USERNAME_INVALID ) {
			//User not exist
			$result = new stdClass();
			$result->status = false;
			$result->message = 'Invalid email address.';
			echo json_encode($result);
			Yii::app()->end();
			
		} else {
			$to = $this->email;
			$to = 'dennis@40square.com';
			$subject = 'Reset password for My App';
			$link = Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . '/?r=site/resetPassword&token=' . $this->createResetPasswordToken( );
			$message = <<<HTML
<html>
	<head>
		<title>Reset Password</title>
	</head>
	<body>
		Hi there.<br />
		We received your request regarding the matter above.<br />
		Well, don't worry by clicking the link below and you will be able to create your new password.<br />
		<a href="$link">$link</a><br />
		<br />
		The link token will expire after 7 days.<br />
		Ignore this email if you don't want to reset your password.<br />
		<br />
		<br />
		Regards,<br />
		Webmaster<br />
		My App<br />
	</body>
</html>
HTML;
			$this->emailNotice($to, $subject, $message);

			//User is exist
			$result = new stdClass();
			$result->status = true;
			$result->message = 'An email has sent to you.<br />Please check your mailbox.';
			echo json_encode($result);
			Yii::app()->end();
			
		}
		
//		if ($this->authenticate()) {
//			
//			$duration = 0;
//			Yii::app()->user->login($this->_identity,$duration);
//			
//			$result = new stdClass();
//			$result->status = true;
//			$result->message = 'Login success.';
//			echo json_encode($result);
//			Yii::app()->end();
//			
//		} else {
//			$result = new stdClass();
//			$result->status = false;
//			$result->message = 'Incorrect username or password.';
//			echo json_encode($result);
//			Yii::app()->end();
//			
//		}
	}
	
	public function actionCreateUser()
	{
		if (empty( $_POST )) {
			header("HTTP/1.0 400 Bad Request");
			die;
		}

		$data = $_POST;
		if ( empty($data['email']) ||
			 empty($data['pwd']) ||
			 empty($data['pwd_verify']) ||
			 $data['pwd'] !== $data['pwd_verify'] )
		{
			$result = new stdClass();
			$result->status = false;
			$result->message = 'Invalid password.';
			echo json_encode($result);
			Yii::app()->end();
		}
		else
		{
			$user = User::model()->find( 'username = ?',array( $data['email'] ) );

			if ( $user !== null ) {
				$result = new stdClass();
				$result->status = false;
				$result->message = 'Email already exist.';
				echo json_encode($result);
				Yii::app()->end();

			} else {
				$user = new User();
				$user->username = $data['email'];
				$user->salt = $this->generateSalt();
				$user->password = $user->hashPassword($data['pwd'], $user->salt);
				$user->save();

				$result = new stdClass();
				$result->status = true;
				$result->message = 'Account created.';
				$result->returnUrl = Yii::app()->request->baseUrl;
				echo json_encode($result);
				Yii::app()->end();
			}
		}
	}
	
	private function authenticate()
	{
		$this->_identity=new UserIdentity($this->email, $this->pwd);
		return $this->_identity->authenticate();
			
	}
	
	private function createResetPasswordToken() {
		$token = $this->generateSalt( $this->email );
		$user = User::model()->find( 'LOWER(username) = ?',array( strtolower($this->email) ) );
		$user->reset_token = $token;
		$user->reset_expired = time() + (7 * 24 * 3600);
		$user->update();
		
		return $token;
	}
	
	private function generateSalt($extra = '') {
		return sha1( uniqid('',true) . $extra );
	}

	private function emailNotice($to, $subject, $message)
	{
$to = 'dennis@40square.com';
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		// Additional headers
//			$headers .= 'To: <' . $this->email . '>' . "\r\n";
		$headers .= 'To: <' . 'dennis@40square.com' . '>' . "\r\n";
		$headers .= 'From: ' . Yii::app()->params['noReplyName'] . ' <' . Yii::app()->params['noReplyEmail'] . ">\r\n";

		// Mail it
		mail($to, $subject, $message, $headers);
	}
}