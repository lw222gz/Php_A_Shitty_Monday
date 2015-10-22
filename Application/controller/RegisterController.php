<?php

class RegisterController{
    private $registerView;
    private $registerModel;
    private $sessionManager;
    
    public function __construct($registerView, $registerModel, $sessionManager){
        $this -> registerView = $registerView;
        $this -> registerModel = $registerModel;
        $this -> sessionManager = $sessionManager;
    }
    
    public function RegisterNewUser(){
        //validates input data and registers a user.
        $RegisterUserName = $this -> registerView -> getRequestUserName();
        $this -> registerModel -> Register($RegisterUserName, 
                                            $this -> registerView -> getRequestDisplayName(), 
                                            $this -> registerView -> getRequestPassword(), 
                                            $this -> registerView -> getRequestPasswordCheck());
        
        $this -> sessionManager -> setNewUserSession($RegisterUserName);
        
        //redirects user to index page
        header("Location: ?");
    }
}