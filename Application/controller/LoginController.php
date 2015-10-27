<?php


class LoginController {
    
    //LoginView object
    private $loginView;
    //LoginModel object
    private $loginModel;

    
    //sets the object references.
    public function __construct($loginView, $loginModel){
        $this -> loginView = $loginView;
        $this -> loginModel = $loginModel;
    }
    
    //tries to log in the user
    public function LogIn(){
        $this -> loginModel -> CheckLoginInfo($this -> loginView -> getRequestUserName(), $this -> loginView -> getRequestUserPassword());
    }
    
    //Logs out the user
    public function LogOut(){
        $this -> loginModel -> LogOut();
        
        //on the original logout it shows the bye bye message
        $this -> loginView -> JustLoggedOut();
    }
    
}