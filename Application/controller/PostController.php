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
        
        $this -> psM -> addNewPost($this -> sm -> getUserNameSession(), $this -> psV -> getTitle(), $this -> psV -> getStory(), $this -> psV -> getImg());
        
        header("Location: ?");
    }
    
    public function getAllPosts(){
        
        return $this -> psM -> getAllPosts();
    }
}