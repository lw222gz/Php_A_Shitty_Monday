<?php

class MasterController{
    
    private $PostCont;
    private $LoginCont;
    private $registerCont;
    private $loginView; 
    private $registerView; 
    private $postStatusView; 
    private $sessionManager; 
    private $appV;
    private $verifyView;
    private $layoutView;
    
    public function __construct($PostCont, $LoginCont, $registerCont, $loginView, $registerView, $postStatusView, $sessionManager, $appV, $verifyView, $layoutView){
        $this -> PostCont = $PostCont;
        $this -> LoginCont = $LoginCont;
        $this -> registerCont = $registerCont;
        $this -> loginView = $loginView;
        $this -> registerView = $registerView;
        $this -> postStatusView = $postStatusView;
        $this -> sessionManager = $sessionManager;
        $this -> appV = $appV;
        $this -> verifyView = $verifyView;
        $this -> layoutView = $layoutView;
    }
    
    
    public function init(){
        
        try 
        {
            if($this -> registerView -> hasPressedRegister()){
                $this -> registerCont -> RegisterNewUser();
            }
            
            else if ($this -> loginView -> hasPressedLogin())
            {
                $this -> LoginCont-> LogIn();
            }
            
            else if($this -> loginView -> hasPressedLogOut()){
                $this -> LoginCont -> LogOut();
            }
            else if($this -> postStatusView -> hasPressedUpload()){
                $this -> PostCont -> AddPost();
            }
            
            //BUGG REPORT: If you press F5, no images are loaded.
            //if the user is logged in, all the posts are set and displayed
            if($this -> sessionManager -> getLoggedInSession()){
                $this -> appV -> setAllPosts($this -> PostCont -> getAllPosts());
            }
        }
        catch (LoginModelException $e){
            $this -> loginView -> setStatusMessage($e);
        }
        catch (RegisterModelException $e){
            $this -> registerView -> setErrorMessage($e);
        }
        catch (PostStoryError $e){
            $this -> postStatusView -> setErrorMessage($e);
        }
        catch (Exception $e){
            //if an unhandeld exception was thrown then a special error message is shown.
            $this -> postStatusView -> UnHandeldException();
            $this -> registerView -> UnHandeldException();
            $this -> loginView -> UnHandeldException();
           
        }
        
        //displays html
        self::DisplayView();
    }
    
    
    private function DisplayView(){
        $isVerificationAttempt = $this -> verifyView -> isVerificationAttempt();
        if($isVerificationAttempt){
            $this -> registerCont -> VerifyAccount($this -> verifyView -> getUrlRequestEmail(), 
                                                   $this -> verifyView -> getUrlRequestHash());
        }
        //renders out the html
        $this -> layoutView -> render($this -> sessionManager -> getLoggedInSession(), 
                                      $this -> loginView, 
                                      $this -> registerView, 
                                      $isVerificationAttempt, 
                                      $this -> sessionManager -> getNewlyRegisterdUserStatus(),
                                      $this -> verifyView);
    }
}