<?php

class User{
    
    //email, displayName, date registerd
    private $Name;
    private $DisplayName;
    private $Password;
    
    
    public function __construct($userName, $DisplayName, $password){
        $this -> Name = $userName;
        $this -> DisplayName = $DisplayName;
        $this -> Password = $password;
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
}