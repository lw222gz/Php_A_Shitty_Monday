<?php

class MasterController{
    
    private $PostCont;
    private $LoginCont;
    private $lv; //login view
    private $rv; //register view
    private $psV; //poststatus view
    private $lm; //loginmodel
    private $sm; //sessionmanipulator
    private $appV;
    
    public function __construct($PostCont, $LoginCont, $lv, $rv, $psV, $sm, $appV){
        $this -> PostCont = $PostCont;
        $this -> LoginCont = $LoginCont;
        $this -> lv = $lv;
        $this -> rv = $rv;
        $this -> psV = $psV;
        $this -> sm = $sm;
        $this -> appV = $appV;
    }
    
    
    public function init(){
        try 
        {
            if($this -> rv -> hasPressedRegister()){
                $this -> LoginCont -> RegisterNewUser();
            }
            
            else if ($this -> lv -> hasPressedLogin())
            {
                $this -> LoginCont-> LogIn();
            }
            
            else if($this -> lv -> hasPressedLogOut()){
                $this -> LoginCont -> LogOut();
            }
            else if($this -> psV -> hasPressedUpload()){
                $this -> PostCont -> AddPost();
            }
            
            //TODO: could benefit from only running when on the index page.
            //if the user is logged in, all the posts are set and displayed
            if($this -> sm -> getLoggedInSession()){
                $this -> appV -> setAllPosts($this -> PostCont -> getAllPosts());
            }
        }
        catch (LoginModelException $e){
            $this -> lv -> setStatusMessage($e);
        }
        catch (RegisterModelException $e){
            $this -> rv -> setErrorMessage($e);
        }
        catch (PostStoryError $e){
            $this -> psV -> setErrorMessage($e);
        }
        catch (Exception $e){
            //if an unhandeld exception was thrown then a special error message is shown.
            $this -> psV -> UnHandeldException();
            $this -> rv -> UnHandeldException();
            $this -> lv -> UnHandeldException();
           
        }
    }
}