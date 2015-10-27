<?php


class VerifyView{
    
    private $sessionManager;
    
    public function __construct($sessionManager){
        $this -> sessionManager = $sessionManager;
    }
    
    public function response(){
        switch($this -> sessionManager -> getVerifySession())
        {
            case EnumStatus::$failVerification: 
                return "The account for this url does not exist. Please make sure you clicked the link you got in your email. <a href=?>Return to homepage.</a>";
                
            case EnumStatus::$successfulVerification:
                return "You have successfully activated your account! You can now log in to our site <a href=?>here!</a>";
                
            case EnumStatus::$alreadyActiveVerification:
                return "Your account has allready been activated previously. <a href=?>Return to homepage.</a>";
                
            default:
                return "An error occured when trying to show your verification result. Please try and reload the URL given to you in your mail.";
        }
    }
    
    
    //if the url indicates a verification attempt this method returns true
    public function isVerificationAttempt(){
      if(isset($_GET[EnumStatus::$EmailURLText]) && isset($_GET[EnumStatus::$HashURLText])){
        return true;
      }
      return false;
    }
    
    //gets data from the url and returns it
    public function getUrlRequestEmail(){
        return $_GET[EnumStatus::$EmailURLText];
    }
    
    public function getUrlRequestHash(){
        return $_GET[EnumStatus::$HashURLText];
    }
}


//https://php-a-shitty-monday-lw222gz-1-1.c9.io/Application/email=lw222gz@student.lnu.se&hash=bbf94b34eb32268ada57a3be5062fe7d