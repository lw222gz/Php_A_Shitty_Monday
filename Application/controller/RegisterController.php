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
        
        $this -> registerModel -> Register($this -> registerView -> getRequestUserName(), 
                                           $this -> registerView -> getRequestDisplayName(), 
                                           $this -> registerView -> getRequestPassword(), 
                                           $this -> registerView -> getRequestPasswordCheck(),
                                           $this -> registerView -> getRequestEmail());
        
        //sets the newlyregisterduser session to true to display a message in the view after the redirect
        $this -> sessionManager -> setNewlyRegisterdUserTrue();
        
        //redirects user to index page
        header("Location: ?");
    }
    
    public function VerifyAccount($email, $hash){
        $this -> registerModel -> VerifyAccount($email, $hash);
    }
}