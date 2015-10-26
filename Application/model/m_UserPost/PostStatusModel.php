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
        //if the filesize is larger than 0 then a file has been uploaded, otherwise 
        var_dump($img);
        if($img['size'] != 0){
            
            //These are all possible file types for images that mime_content_type can return
            //source: http://php.net/manual/en/function.mime-content-type.php
            //if file is not ONE of these, the file could be harmful, exampel a script file.
            /*if(mime_content_type($img['tmp_name']) == "image/jpeg" ||
                mime_content_type($img['tmp_name']) == "image/png" ||
                mime_content_type($img['tmp_name']) == "image/gif" ||
                mime_content_type($img['tmp_name']) == "image/bmp" ||
                mime_content_type($img['tmp_name']) == "image/vnd.microsoft.icon" ||
                mime_content_type($img['tmp_name']) == "image/tiff" ||
                mime_content_type($img['tmp_name']) == "image/svg+xml"){*/
                
            //if getimagesize returns 0 then it's not an image, and 0 == false
            if(getimagesize($img['tmp_name'])){
                
                
                $temp = explode('.', $img['name']);
                $extension = array_pop($temp);
                $OrgLength = strlen(implode('.', $temp));
                
                $this -> ImageModifier = 0;
                //returns the same name if the name does not exist. Otherwise it returns a name with a unieuq modifier
                $img['name'] = self::ImageNameModifier($img['name'], $OrgLength);
                
                //After being verified as an image and having a unique name a filePath to it is created, later used to save the image after a successful DB entry of the post data
                $this -> filePath = Settings::$uploadDir.basename($img['name']);
            }
            else{
                //so if the file could be harmful an error is thrown.
                throw new PostStoryError("The file you wanted to upload was not validated as an image.");
            }
        }
        return true;
    }
    
    //checks if a file name exists and if it does it changes it to a new unique one and returns it.
    public function ImageNameModifier($imgName, $OrgLength){
        foreach (scandir(Settings::$uploadDir) as $ExistingFileName){
            if($imgName == $ExistingFileName){
                
                $this -> ImageModifier++;
                
                //splits the name up with the extension to later reassemble all parts with a modifier
                $temp = explode('.', $imgName);
                $extension = array_pop($temp);
                $name = implode('.', $temp);
                    
                //removes any current modifier to add a new one.
                $name = substr_replace($name, "", $OrgLength);
                
                //sets up a new name to test agian.
                $newName = $name.$this->ImageModifier.".".$extension;
                
                $imgName = self::ImageNameModifier($newName, $OrgLength);
                break;
            }
        }
        return $imgName;
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
