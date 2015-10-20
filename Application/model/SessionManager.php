<?php

class SessionManager {
    
    private static $LoggedInUserID = "SessionHandler::LoggedInUser";
    private static $IsLoggedInID = "SessionHandler::IsLoggedIn";
    private static $newUserID = "SessionHandler::newUserName";
    

    public function __construct(){
        //if a session is not defined it gets a defualt value to avoid crashes.
        if(!isset($_SESSION[self::$LoggedInUserID])){
            self::setUserNameSession("");
        }
        if(!isset($_SESSION[self::$IsLoggedInID])){
            self::setLoggedInSession(false);
        }
        //$_SESSION[$newUserID] dont need to be set to default because if it gets set, then it's used, when it later on used it's re-unset.
    }
    
    //return value of $UserName session
    public function getUserNameSession(){
        return $_SESSION[self::$LoggedInUserID];
    }
    
    //sets value of $UserName Session
    public function setUserNameSession($value){
        if(!is_string($value)){
            throw new SessionException("Value is not valid.");
        }
        $_SESSION[self::$LoggedInUserID] = $value;
    }
    
    //returns value of $IsLoggedIn session
    public function getLoggedInSession(){
        return $_SESSION[self::$IsLoggedInID];
    }
    
    //sets the value of $IsLoggedIn session 
    public function setLoggedInSession($value){
        if(!is_bool($value)){
            throw new SessionException("Value is not valid.");
        }
        $_SESSION[self::$IsLoggedInID] = $value;
    }
    
    //returns the value of $newUsedID session and unsets the value.
    public function getNewUserSession(){
        //when this is used it's unset directly after to make sure that a Username isent stored somewhere.
        $Uname = $_SESSION[self::$newUserID];
        unset($_SESSION[self::$newUserID]);
        return $Uname;
    }
    
    //sets a value to session $newUserID
    public function setNewUserSession($value){
        if(!is_string($value)){
            throw new SessionException("Value is not valid.");
        }
        $_SESSION[self::$newUserID] = $value;
    }
    
    //returns boolean, true if newUserSession has a value else false
    public function isNewUserSessionSet(){
        if(isset($_SESSION[self::$newUserID])){
            return true;
        }
        return false;
    }
}

class SessionException extends Exception{}