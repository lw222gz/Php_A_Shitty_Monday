<?php

class PostStatusModel{
    //Note: this class feels useless, move it's responsibilities to PostDAL instead?
    
    private $pDAL; //PostsDAL
    
    private $filePath;
    
    public function __construct($pDAL){
        $this -> pDAL = $pDAL;
    }
    
    //Adds a new post, if something goes wrong an error is thrown.
    public function addNewPost($user, $title, $message, $img){
        if(self::ValidatePostData($message, $title, $img)){
            //TODO: Save img url in DB
            if(!$this -> pDAL -> AddNewPostToDB($user, $title, $message, $this -> filePath)){
                throw new PostStoryError("An error occured when trying to post your story.");
            }
            else if($this -> filePath != null){
                //AddNewPostToDB returned true so image is saved in directory
                move_uploaded_file($img['tmp_name'], $this -> filePath);
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
    public function ValidatePostData($message, $title, $img){
        
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
        
        //if the file size is 0 then the user did not upload a file, so filePath gets value null to put in the DB as filePath
        $this -> filePath = null;
        //if the filesize is lager than 0 then a file has been uploaded, otherwise 
        if($img['size'] != 0){
            
            //These are all possible file types for images that mime_content_type can return
            //source: http://php.net/manual/en/function.mime-content-type.php
            //if file is not ONE of these, the file could be harmful, exampel a script file.
            if(mime_content_type($img['tmp_name']) == "image/jpeg" ||
                mime_content_type($img['tmp_name']) == "image/png" ||
                mime_content_type($img['tmp_name']) == "image/gif" ||
                mime_content_type($img['tmp_name']) == "image/bmp" ||
                mime_content_type($img['tmp_name']) == "image/vnd.microsoft.icon" ||
                mime_content_type($img['tmp_name']) == "image/tiff" ||
                mime_content_type($img['tmp_name']) == "image/svg+xml"){
                //TODO: Check for smiliar file names, otherwise the files will overwrite eachother.
                //if it's a valid image then a file path is created. Later used to save the file after a successful DB entry of the status
                $this -> filePath = Settings::$uploadDir.basename($img['name']);
            }
            else{
                //so if the file could be harmful an error is thrown.
                throw new PostStoryError("The file you wanted to upload was not validated as an image.");
            }
        }
        return true;
    }
    
}

class PostStoryError extends Exception{}


/*
//TESTING
        var_dump($this -> psV -> getImg());
        $img = $this -> psV -> getImg();
        
        if($img['size'] == 0){
            echo "no image uplaoded";
        }
        else{
            $uploaddir = '../Images/';
            $uploadfile = $uploaddir . basename($img['name']);
            
            if(mime_content_type($img['tmp_name']) == "image/jpeg"){
                echo "file is valid and was uploaded";
                move_uploaded_file($img["tmp_name"],
                $uploaddir.$img["name"]);
            }
            else{
                echo "File contents could be harmful, it was not uploaded.";
            }
            
        }
        
        
        /*if (move_uploaded_file($img['tmp_name'], $uploadfile)) {
            echo "File is valid, and was successfully uploaded.\n";
        } else {
            echo "Possible file upload attack!\n";
        }*/
        
          
        
          
        //TESTING*/