<?php

class User{
    
    //email, displayName, date registerd
    private $Name;
    private $DisplayName;
    private $Password;
    private $hashedEmail;
    private $registerHash;
    private $accountStatus;
    
    
    public function __construct($userName, $DisplayName, $password, $hashedEmail, $accountStatus, $registerHash){
        $this -> Name = $userName;
        $this -> DisplayName = $DisplayName;
        $this -> Password = $password;
        $this -> hashedEmail = $hashedEmail;
        $this -> registerHash = $registerHash;
        $this -> accountStatus = $accountStatus;
    }
    
    public function getUserName(){
        return $this -> Name;
    }
    
    public function getHasedPassword(){
        return $this -> Password;
    }
    
    public function getDisplayName(){
        return $this -> DisplayName;
    }
    
    public function getHashedEmail(){
        return $this -> hashedEmail;
    }
    
    public function getRegisterHash(){
        return $this -> registerHash;
    }
    
    public function getAccountStatus(){
        return $this -> accountStatus;
    }
}