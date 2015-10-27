<?php

class SessionManager {
    
    private static $LoggedInUserID = "SessionHandler::LoggedInUser";
    private static $IsLoggedInID = "SessionHandler::IsLoggedIn";
    private static $NewlyRegisterdUser = "SessionHandler::NewlyRegisterUser";
    private static $VerifySessionID = "SessionHandler::verificationStatus";
    

    public function __construct(){
        //if a session is not defined it gets a defualt value to avoid errors
        if(!isset($_SESSION[self::$LoggedInUserID])){
            self::setUserNameSession("");
        }
        if(!isset($_SESSION[self::$IsLoggedInID])){
            self::setLoggedInSessionFalse();
        }
        if(!isset($_SESSION[self::$VerifySessionID])){
            self::setVerifySessionFail();
        }
        if(!isset($_SESSION[self::$NewlyRegisterdUser])){
            self::setNewlyRegisterdUserFalse();
        }
    }
    
    //return value of $UserName session
    public function getUserNameSession(){
        return $_SESSION[self::$LoggedInUserID];
    }
    
    //sets value of $UserName Session
    public function setUserNameSession($value){
        if(!is_string($value)){
            throw new Exception("Value is not valid.");
        }
        $_SESSION[self::$LoggedInUserID] = $value;
    }
    
    //returns value of $IsLoggedIn session
    public function getLoggedInSession(){
        return $_SESSION[self::$IsLoggedInID];
    }
    
    //sets the value of $IsLoggedIn session 
    public function setLoggedInSessionFalse(){
        $_SESSION[self::$IsLoggedInID] = false;
    }
    public function setLoggedInSessionTrue(){
        $_SESSION[self::$IsLoggedInID] = true;
    }
    
    //boolean indicating if a user was just registerd.
    public function getNewlyRegisterdUserStatus(){
        return $_SESSION[self::$NewlyRegisterdUser];
    }
    
    //sets a value to session $NewlyRegisterdUser
    public function setNewlyRegisterdUserTrue(){
        $_SESSION[self::$NewlyRegisterdUser] = true;
    }
    
    //returns boolean, true if newUserSession has a value else false
    public function setNewlyRegisterdUserFalse(){
        $_SESSION[self::$NewlyRegisterdUser] = false;
    }
    
    //sets diffrent values for the verifysession
    public function setVerifySessionFail(){
        $_SESSION[self::$VerifySessionID] = EnumStatus::$failVerification;
    }
    public function setVerifySessionsuccessful(){
        $_SESSION[self::$VerifySessionID] = EnumStatus::$successfulVerification;
    }
    public function setVerifySessionAlreadyActive(){
        $_SESSION[self::$VerifySessionID] = EnumStatus::$alreadyActiveVerification;
    }
    //returns verifysession
    public function getVerifySession(){
        return $_SESSION[self::$VerifySessionID];
    }
}