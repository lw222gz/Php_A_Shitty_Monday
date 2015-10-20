<?php

class RegisterModel {
    
    private static $UserDAL;
    
    public function __construct($DAL){
        self::$UserDAL = $DAL;
    }
    
    //Registers a new user, 
    public function Register($Username, $DisplayName, $Password, $PasswordCheck){
        
        if (self::ValidateData($Username, $DisplayName, $Password, $PasswordCheck)){
            //hashing the password before going to the DAL just to make sure no bugg in the DAL will cause an unhased password to be stored.
            //using the default salt+username to make sure that 2 people with diffrent usernames but same passwords dont get the same hash.
            if(!self::$UserDAL -> AddUser($Username, $DisplayName, sha1(Settings::$Salt.$Username.$Password))){
                throw new RegisterModelException("An error occured when trying to add you to the database, please inform...");
            }
        }
    }
    
    //validates the data a new user wants to register with, if some data is not accepted an error is thrown and true will not be returned
    private function ValidateData($Username, $DisplayName, $Password, $PasswordCheck){
        //get all the users to check if username allready exists, if $RegisterdUsers == false then the .bin is empty
        $RegisterdUsers = self::$UserDAL -> getUserData();
        if($RegisterdUsers != false){
            foreach($RegisterdUsers as $Ruser){
                if($Username == $Ruser -> getUserName()){
                    throw new RegisterModelException("User exists, pick another username.");
                }
            }
        }
        
        //character validation
        if($DisplayName != strip_tags($DisplayName) || $Username != strip_tags($Username) || $Password != strip_tags($Password)){
            throw new RegisterModelException("Name or passwords are not allowed to contain HTML-tags");
        }
        if($Password != $PasswordCheck){
            throw new RegisterModelException("Passwords do not match.");
        }
        if(strlen($Username) < 3){
            if(strlen($Password) < 6){
                if(strlen($DisplayName) < 3){
                    throw new RegisterModelException("Username has too few characters, at least 3 characters. <br/>
                    Password has too few characters, at least 6 characters.<br/>
                    Display name has too few characters, at least 3 characters.");
                }
                throw new RegisterModelException("Username has too few characters, at least 3 characters. <br/>Password has too few characters, at least 6 characters.");
            }
            throw new RegisterModelException("Username has too few characters, at least 3 characters.");
        }
        if(strlen($DisplayName) < 3){
            throw new RegisterModelException("DisplayName has to few characters, at least 3 characters.");
        }
        if(strlen($Password) < 6){
            throw new RegisterModelException("Password has too few characters, at least 6 characters.");
        }

        return true;
    }
}


/**
 * Custom exceptions for code optimization in the controller.
 */
class RegisterModelException extends Exception
{}