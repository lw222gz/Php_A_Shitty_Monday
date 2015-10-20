<?php


class LoginController {
    
    //LoginView object
    private $v;
    //LoginModel object
    private $lm;
    //RegisterView object.
    private $rv;
    //RegisterModel object
    private $rm;
    //session manipulator
    private $sm;

    
    //sets the object references.
    public function __construct($v, $lm, $rv, $rm,$sm){
        $this -> v = $v;
        $this -> lm = $lm;
        $this -> rv = $rv;
        $this -> rm = $rm;
        $this -> sm = $sm;
    }
    
    
   public function RegisterNewUser(){
        //validates input data and registers a user.
        $RegisterUserName = $this -> rv -> getRequestUserName();
        $this -> rm -> Register($RegisterUserName, $this -> rv -> getRequestDisplayName(), $this -> rv -> getRequestPassword(), $this -> rv -> getRequestPasswordCheck());
        
        $this -> sm -> setNewUserSession($RegisterUserName);
        
        //redirects user to index page
        header("Location: ?");
    }
    
    //tries to log in the user
    public function LogIn(){
        $this -> lm -> CheckLoginInfo($this -> v -> getRequestUserName(), $this -> v -> getRequestUserPassword());

        //On the original log in, it shows the Welcome message
        $this -> v -> JustLoggedIn();
    }
    
    //Logs out the user
    public function LogOut(){
        $this -> lm -> LogOut();
        
        //on the original logout it shows the bye bye message
        $this -> v -> JustLoggedOut();
    }
    
}