<?php

class RegisterView{
    
    private static $RegisterID = "RegisterView::Register";
    private static $UserNameID = "RegisterView::UserName";
    private static $PasswordID = "RegisterView::Password";
    private static $PasswordCheckID = "RegisterView::PasswordRepeat";
    private static $messageID = "RegisterView::Message";
    private static $DisplayNameID = "RegisterView::DisplayName";
    private static $EmailID = "RegisterView:EmailID";
    
    
    private $message;
    private $saveUserName;
    private $saveEmail;
    private $saveDisplayName;
    
    
    //returns HTML for registering
    public function RegisterLayout(){
        return '
			<form method="post"> 
				<fieldset>
					<legend>Register - enter the fields below</legend>
					<p id="' . self::$messageID . '">' . $this -> message . '</p>
					
					<label for="' . self::$UserNameID . '">Username(max 25 characters, white spaces not allowed):</label>
					<input type="text" id="' . self::$UserNameID . '" name="' . self::$UserNameID . '" value="' . $this -> saveUserName . '" maxlength="25"/><br/>
					
					<label for="' . self::$DisplayNameID . '">Display name, this is the name shown to other users(max 25 characters, white spaces allowed):</label>
					<input type="text" id="' . self::$DisplayNameID . '" name="' . self::$DisplayNameID . '" value="' . $this -> saveDisplayName . '" maxlength="25"/><br/>
					
					<label for="' . self::$EmailID . '">Email: </label>
					<input type="text" id="' . self::$EmailID . '" name="' . self::$EmailID . '" value="' . $this -> saveEmail . '" maxlength="254"/><br/>
					
					<label for="' . self::$PasswordID . '">Password(white spaces not allowed) :</label>
					<input type="password" id="' . self::$PasswordID . '" name="' . self::$PasswordID . '" /><br/>
					
					<label for="' . self::$PasswordCheckID . '">Re-type Password :</label>
					<input type="password" id="' . self::$PasswordCheckID . '" name="' . self::$PasswordCheckID . '" /><br/>
					
					<input type="submit" name="' . self::$RegisterID . '" value="Register" />
				</fieldset>
			</form>
		';
    }
    
    
    //returns input usernamer
    public function getRequestUserName(){
        if (isset($_POST[self::$UserNameID])){
            //saves a correct version of the name(no white spaces in the string) to display ventual errors by user
            $this -> saveUserName = str_replace(' ', '', $_POST[self::$UserNameID]); 
            //returns a trimed version to be validated in the model so it can check if the user has any whitespaces and if so throw an error.
            return trim($_POST[self::$UserNameID]);
        }
        return null;
    }
    
    //returns input DisplayName
    public function getRequestDisplayName(){
        if(isset($_POST[self::$DisplayNameID])){
            $this -> saveDisplayName = trim($_POST[self::$DisplayNameID]);
            return $this -> saveDisplayName;
        }
        return null;
    }
    
    //returns input email
    public function getRequestEmail(){
        if(isset($_POST[self::$EmailID])){
            return trim($_POST[self::$EmailID]);
        }
        return null;
    }
    
    //returns input password
    public function getRequestPassword(){
        if (isset($_POST[self::$PasswordID])){
            return trim($_POST[self::$PasswordID]);
        }
        return null;
    }
    
    //returns input password double check
    public function getRequestPasswordCheck(){
        if(isset($_POST[self::$PasswordCheckID])){
            return trim($_POST[self::$PasswordCheckID]);
        }
        return null;
    }
    
    //returns true if user has pressed register
    public function hasPressedRegister(){
        if(isset($_POST[self::$RegisterID])){
            return true;
        }
        return null;
    }
    
    
    //sets an error message when an exception was thrown
    public function setErrorMessage($e){
        //checks if error message is cause due to invalid characters
        if($e -> getMessage() == EnumStatus::$InvalidCharactersError){
            //if so those are removed.
            $this -> saveDisplayName = strip_tags($this -> saveDisplayName);
            $this -> saveUserName = strip_tags($this -> saveUserName);
        }
        $this -> message = $e -> getMessage();
    }
    
    //sets message if an unhandelsException was thrown
    public function UnhandeldException(){
		$this -> message = "An unhandeld exception was thrown. Please infrom...";
	}
}