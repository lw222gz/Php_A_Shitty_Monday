<?php

class PostStatusModel{
    //Note: this class feels useless, move it's responsibilities to PostDAL instead?
    
    private $pDAL; //PostsDAL
    
    public function __construct($pDAL){
        $this -> pDAL = $pDAL;
    }
    
    //Adds a new post, if something goes wrong an error is thrown.
    public function addNewPost($user, $title, $message){
        if(self::ValidatePostData($message, $title)){
            
            //TODO: NEED TO CHECK IF A REPOST, annars så blir det många meddelanden
            if(!$this -> pDAL -> AddNewPostToDB($user, $title, $message)){
                throw new PostStoryError("An error occured when trying to post your story.");
            }
        }
        
    }
    
    //returns all posts, if something goes wrong an error is thrown
    public function getAllPosts(){
        //returns an array with all posts sorted by date
        $result = $this -> pDAL -> getAllPosts();
        if(!$result){
            throw new PostStoryError("Error occured when trying to get current posts.");
        }
        return $result;
    }
    
    //Validates the post data.
    public function ValidatePostData($message, $title){
        if($message != strip_tags($message) || $title != strip_tags($title)){
            throw new PostStoryError("The text may not contain HTML-tags.");
        }
        if(strlen($message) < 5){
            if(strlen($title) < 3){
                throw new PostStoryError("A title has to be atleast 3 characters long. <br/>A story has to be atleast 5 characters long.");
            }
            throw new PostStoryError("A story has to be atleast 5 characters long.");
        }
        if(strlen($title) < 3){
            throw new PostStoryError("A title has to be atleast 3 characters long.");
        }
        return true;
    }
    
}

class PostStoryError extends Exception{}