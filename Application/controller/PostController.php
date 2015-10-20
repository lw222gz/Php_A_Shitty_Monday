<?php

class PostController {
    private $psV; //PostStatusView
    private $psM; //PostStatusModel
    private $sm; //sessionmanipulator
    
    public function __construct($psV, $psM, $sm){
        $this -> psV = $psV;
        $this -> psM = $psM;
        $this -> sm = $sm;
    }
    
    public function AddPost(){
        //TESTING
        var_dump($this -> psV -> getImg());
        $img = $this -> psV -> getImg();
        
        $uploaddir = '../Data/';
        $uploadfile = $uploaddir . basename($img['name']);
        
        if (move_uploaded_file($img['tmp_name'], $uploadfile)) {
            echo "File is valid, and was successfully uploaded.\n";
        } else {
            echo "Possible file upload attack!\n";
        }
        move_uploaded_file($img["tmp_name"],
          $uploaddir.$img["name"]);
          
        //TESTING
        
        $this -> psM -> addNewPost($this -> sm -> getUserNameSession(), $this -> psV -> getTitle(), $this -> psV -> getStory());
        
        //header("Location: ?");
    }
    
    public function getAllPosts(){
        
        return $this -> psM -> getAllPosts();
    }
}