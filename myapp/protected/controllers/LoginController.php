<?php

class LoginController extends Controller
{
	private $email;
	private $pwd;
	private $_identity;
	
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array( );
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$this->email = $_POST['email'];
		$this->pwd = $_POST['pwd'];
		$this->authenticate();
	}
	
	private function authenticate()
	{
		$this->_identity=new UserIdentity($this->email, $this->pwd);
		
		if(!$this->_identity->authenticate()) {
			$result = new stdClass();
			$result->status = false;
			$result->message = 'Incorrect username or password.';
			echo json_encode($result);
			Yii::app()->end();
			
		} else {
			$duration = 0;
			Yii::app()->user->login($this->_identity,$duration);
			
			$result = new stdClass();
			$result->status = true;
			$result->message = 'Login success.';
			echo json_encode($result);
			Yii::app()->end();
		}
	}
}