<?php



class ApplicationView{
    
    private $PostView;
    private $sessionManager; 
    
    private $Posts = array();
    
    public function __construct($PostView, $sessionManager){
        $this -> PostView = $PostView;
        $this -> sessionManager = $sessionManager;
    }
    
    //generates the main html for the application when logged in.
    public function GetAppView(){
        $ret = '<h2>WELCOME ' . $this -> sessionManager -> getUserNameSession() . ' TO A SHITTY MONDAY!</h2> 
                <h4>Here you can post your painful monday stories to see if you actually got the worst one.</h4>
                
                ' . self::getHTML() . '';
        return $ret;
    }
    
    //$actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    
    //returns html of all posts in the $Posts array
    private function getAllPosts(){
        $ret = "";
        //Loopar igenom $Posts och skapar HTML för varje post. 
        foreach ($this -> Posts as $post){
            $ret .= "<fieldset><p><b class='StoryTitle'>" . strtoupper($post -> getTitle()) . "</b><br/>Written by: ". $post -> getCreator(). " At date: " . $post -> getDateCreated() . "
            <br/><h2>Story:</h2> " . str_replace("\n", "<br/>", $post -> getStory()) . "<br/>";
            if($post -> getImgPath() != null){
                //TODO: change on live verson.
                
                //$ret .= "<img src=https://php-a-shitty-monday-lw222gz-1-1.c9.io/Application/". $post->getImgPath() . ">";
                $ret .= "<img src=" . Settings::$url.$post->getImgPath() ." >";
            }
            
            $ret .= "</p></fieldset>";
        }
        return $ret;
    }
    
    //sets the value of the $Posts array with Post objects
    public function setAllPosts($Array){
        $this -> Posts = $Array;
    }
    
    //returns HTML for the main "application"
    private function getHTML(){
        if(isset($_GET['PostStatus'])){
            return '<a href=?>Home</a><br/>' . $this -> PostView -> getHTML();
        }
        return '<a href=?PostStatus>Upload your own story!</a><br/>' . self::getAllPosts();
    }
}