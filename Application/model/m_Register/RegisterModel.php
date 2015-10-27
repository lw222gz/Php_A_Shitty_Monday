<?php

//model for registering an account aswell as Verifying a verification link.
class RegisterModel {
    
    private $userDAL;
    
    public function __construct($userDAL){
        $this -> userDAL = $userDAL;
    }
    
    //Registers a new user, 
    public function Register($Username, $DisplayName, $Password, $PasswordCheck, $Email){
        
        if (self::ValidateData($Username, $DisplayName, $Password, $PasswordCheck, $Email)){
            //generates a random 32 lenght hash used to verifying an email
            $RegisterHash = md5(rand(0,1000));
            
            //hashing the password before going to the DAL just to make sure no bugg in the DAL will cause an unhased password to be stored.
            //using the default salt+username to make sure that 2 people with diffrent usernames but same passwords dont get the same hash.
            //Also hasing the email since I dont use it for sending any emails more than the verification one.
            if(!$this -> userDAL -> AddUser($Username, $DisplayName, sha1(Settings::$Salt.$Username.$Password), sha1(Settings::$Salt.strtolower($Email)), $RegisterHash)){
                throw new RegisterModelException("An error occured when trying to add you to the database, please inform...");
            }
            else{
                //Create a mail and sends it
                $subject = "Signup verification";
                $headers = "From: noreply@AShittyMonday.com"."\r\n";
                $msg = "
                Thanks for signing up an account on A Shitty Monday!
                
                Your account credentials:
                ----------------------------
                Username: ".$Username."
                Password: ".$Password."
                ----------------------------
                
                Please verify your email by clicking the link below:
                ".Settings::$url."?".EnumStatus::$EmailURLText."=".$Email."&".EnumStatus::$HashURLText."=".$RegisterHash."
                ";
                
                
                //sends the mail, if result gets value false then the sent failed.
                $result = mail($Email, $subject, $msg, $headers);
                if(!$result){
                    throw new RegisterModelException("An error occured when trying to send you the email. Please try again.");
                }
            }
        }
    }
    
    
    
    //validates the data a new user wants to register with, if some data is not accepted an error is thrown and true will not be returned
    private function ValidateData($Username, $DisplayName, $Password, $PasswordCheck, $Email){
        //get all the users to check if username allready exists, if $RegisterdUsers == false then the .bin is empty
        $RegisterdUsers = $this -> userDAL -> getUserData();
        if($RegisterdUsers != false){
            foreach($RegisterdUsers as $Ruser){
                if($Username == $Ruser -> getUserName()){
                    throw new RegisterModelException("User exists, pick another username.");
                }
                //im storing the hash of an emil in lower case so an email cant be registerd twice.
                if(sha1(Settings::$Salt.strtolower($Email)) == $Ruser -> getHashedEmail()){
                    throw new RegisterModelException("That email is allready registerd.");
                }
            }
        }
        
        //validates the email
        if(!filter_var($Email, FILTER_VALIDATE_EMAIL)){
            throw new RegisterModelException("The email was not considerd valid.");
        }
        if(preg_match('/\s/',$Username) || preg_match('/\s/',$Password)){
            throw new RegisterModelException("Login name or passwords may NOT contain whitespaces");
        }
        //character validation
        if($DisplayName != strip_tags($DisplayName) || $Username != strip_tags($Username) || $Password != strip_tags($Password)){
            throw new RegisterModelException(EnumStatus::$InvalidCharactersError);
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
    
    
    //verifies an account by checking the given mail and hash, if they match in the DB then the account is activated.
    public function VerifyAccount($email, $hash){
        if(!$this -> userDAL -> activateAccount(sha1(Settings::$Salt.strtolower($email)), $hash)){
            throw new RegisterModelException("And error occured when trying to activate your account, please inform...");
        }
    }
}


/**
 * Custom exceptions for code optimization in the controller.
 */
class RegisterModelException extends Exception
{}