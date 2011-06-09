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
			$result->message = 'Incorrect username or password.';
			echo json_encode($result);
			Yii::app()->end();
			
		}
	}
	
	public function actionChangePassword()
	{
		
		
	}
	
	public function actionResetPassword()
	{
		
		
	}
	
	public function actionCreateUser()
	{
		
		
	}
	
	private function authenticate()
	{
		$this->_identity=new UserIdentity($this->email, $this->pwd);
		return $this->_identity->authenticate();
			
	}
}