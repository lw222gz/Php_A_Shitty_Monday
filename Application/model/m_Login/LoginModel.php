<?php


class LoginModel {
    
    private $UserDAL;
    private $sessionManager; //session handler
    
    
    
    public function __construct($DAL, $sessionManager){
        $this -> UserDAL = $DAL;
        $this -> sessionManager = $sessionManager;
    }
    
    //Takes the given data and throws a proper Exception if anything is wrong, 
    //otherwise returns @boolean
    public function CheckLoginInfo($UserN, $Pass) {
        
        $RegisterdUsers = $this -> UserDAL -> getUserData();
        //if $RegisterdUsers is false then the .bin is empty
        if(!$RegisterdUsers){
            
            throw new LoginModelException("Error occured when trying to loggin.");
        }

        //When an Exception is thrown, the controller will pick that exception up, 
        //pass it on to the view that uses the message in the exception to present it to the user.
        if(empty($UserN)){
            throw new LoginModelException('Username is missing');
            
        }
        
        else if(empty($Pass)){
            throw new LoginModelException('Password is missing');
        }
        if($UserN != strip_tags($UserN) || $Pass != strip_tags($Pass)){
            throw new LoginModelException(EnumStatus::$InvalidCharactersError);
        }
        
        //Otherwise it's the origninal login and the user credentials will be checked.
        foreach ($RegisterdUsers as $Ruser){
            if($UserN == $Ruser -> getUserName()){
                
                if(sha1(Settings::$Salt.$UserN.$Pass) == $Ruser -> getHasedPassword()){
                    
                    if($Ruser -> getAccountStatus() == "false"){
                        throw new LoginModelException("You account has not yet been activated, please activate it through a link sent to your email.");
                    }
                    //sets the user display name is set for a welcome message aswell as usage when making a post
                    $this -> sessionManager -> setUserNameSession($Ruser -> getDisplayName());

                    $this -> sessionManager -> setLoggedInSessionTrue();
                    break;
                }
            }
        }
        if(!$this -> sessionManager -> getLoggedInSession()){
            throw new LoginModelException('Wrong name or password');
        }
    }

    
    //Logs the user out of the system
    public function LogOut(){
        //If this session allready exsists and it's value is false, then it's a repost, 
        //if it's a repost I throw an empty error to remove the StatusMessage
        if (!$this -> sessionManager -> getLoggedInSession()){
            throw new LoginModelException();
        }
        
        //Otherwise the person just logged out and the bye bye message will be shown.
        $this -> sessionManager -> setLoggedInSessionFalse();
    }
}



/**
 * Custom error for code optimization in the controller
 */
class LoginModelException extends Exception
{}
