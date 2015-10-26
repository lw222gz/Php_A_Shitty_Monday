<?php

class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';
	
	//Varibel to set the username input value, if $_POST[self::$name] is used an Error notice is given at the first run of the page.
	private $SaveUserName = '';
	private $StatusMessage = '';
	
	
	//refrence object to the appView
	private $AppV;
	//session manipulator
	private $sessionManager;

	public function __construct($AppV, $sessionManager){
		$this -> AppV = $AppV;
		$this -> sessionManager = $sessionManager;
	}

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response() {
		if($this -> sessionManager -> getNewlyRegisterdUserStatus()){
			$this -> sessionManager -> setNewlyRegisterdUserFalse();
			return "Thanks for registering on our site! A mail has been sent to the give mail address that you will receive in a couple of minutes.<br/> 
	                Please press the link in that mail to activate your account.<br/>
	                <a href=?>Return to homepage.</a>";
		}
		//Sets the current status message
		$message = $this -> StatusMessage;

		//If logged in only the logout HTML is shown
		if($this -> sessionManager -> getLoggedInSession())
		{
			$response = $this->generateLogoutButtonHTML($message);
		}
		//else show login HTML
		else {
			$response = $this->generateLoginFormHTML($message);
		}
		return $response;
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLogoutButtonHTML($message) {
		$ret =  '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
		$ret .= $this -> AppV -> GetAppView();
		
		return $ret;
	}
	
	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLoginFormHTML($message) {
		return '
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="'. $this -> SaveUserName .'" maxlength="25"/>

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />
					
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
		/*<label for="' . self::$keep . '">Keep me logged in  :</label>
		<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />*/
					
	}

	
	//returns user login name
	public function getRequestUserName() {
		if(isset($_POST[self::$name])) {
			//saves the value so if wrong password, the enterd username is still set
			$this -> SaveUserName = trim($_POST[self::$name]);
			return $this -> SaveUserName;
		}
	}

	//returns user password
	public function getRequestUserPassword(){
		if(isset($_POST[self::$password])){
			return trim($_POST[self::$password]);
		}
	}
	
	//checks if the login button has been pressed.
	public function hasPressedLogin(){
		if(isset($_POST[self::$login])){
			return true;
		}
		return false;
	}
	
	//checks if the logout button has been pressed
	public function hasPressedLogOut(){
		if(isset($_POST[self::$logout])){
			return true;
		}
		return false;
	}
	
	//gets the message the error threw and sets it to the status message
	public function setStatusMessage($e) {
		if(strpos($e -> getMessage(), "not contain HTML tags.")){
			$this -> SaveUserName = strip_tags($this -> SaveUserName);
		}
		$this -> StatusMessage = $e -> getMessage();
	}
	
	//Sets the Welcome message when the user just logged in
	public function JustLoggedIn(){
		$this -> StatusMessage = 'Welcome';
	}
	
	//Sets the Bye bye! message when the user just logged out
	public function JustLoggedOut(){
		$this -> StatusMessage = 'Bye bye!';
	}
	
	//sets a message if an unhandeld exception was thrown.
	public function UnHandeldException(){
		$this -> StatusMessage = "An unhandeld exception was thrown. Please infrom...";
	}
}