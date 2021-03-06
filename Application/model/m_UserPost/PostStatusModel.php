<?php

class PostStatusModel{
    //Note: this class feels useless, move it's responsibilities to PostDAL instead?
    
    private $pDAL; //PostsDAL
    private $ImageModifier;
    
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
        $result = $this -> pDAL -> getAllPosts($this);
        if(!$result){
            throw new PostStoryError("Error occured when trying to get current posts.");
        }
        return $result;
    }
    
    //Validates the post data.
    public function ValidatePostData($message, $title, $img){
        
        if($message != strip_tags($message) || $title != strip_tags($title)){
            throw new PostStoryError(EnumStatus::$InvalidCharactersError);
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
        //if the filesize is larger than 0 then a file has been uploaded, otherwise 
        var_dump($img);
        if($img['size'] > 0){
                
            //if getimagesize returns 0 then it's not an image, 0 == false
            if(getimagesize($img['tmp_name'])){
                
                $temp = explode('.', $img['name']);
                $extension = array_pop($temp);
                //generates a random name for an image.
                $img['name'] = md5(time()).".".$extension;
                
                //After being verified as an image and having a unique name a filePath to it is created, later used to save the image after a successful DB entry of the post data*/
                $this -> filePath = Settings::$uploadDir.basename($img['name']);
            }
            else{
                //so if the file could be harmful an error is thrown.
                throw new PostStoryError("The file you wanted to upload was not validated as an image.");
            }
        }
        return true;
    }
    
    
    //TODO: reqrite this more effectivly
    //removes all images that dont have a path in the Posts array
    public function deleteOldPictures($Posts){
        foreach (scandir(Settings::$uploadDir) as $ExistingFileName){
            //this if avoids the removal of the hidden directories "." and ".."
            if (is_dir($ExistingFileName)){
                continue;
            }
                
            $pathDoesExsist = false;
            foreach ($Posts as $post){
                if(Settings::$uploadDir.$ExistingFileName == $post -> getImgPath()){
                    $pathDoesExsist = true;
                    break;
                }
            }
            
            if(!$pathDoesExsist){
                unlink(Settings::$uploadDir.$ExistingFileName);
            }
        }
    }
    
}

class PostStoryError extends Exception{}
