<?php

class PostController {
    private $postStatusView; 
    private $postStatusModel; 
    private $sessionManager; 
    
    public function __construct($postStatusView, $postStatusModel, $sessionManager){
        $this -> postStatusView = $postStatusView;
        $this -> postStatusModel = $postStatusModel;
        $this -> sessionManager = $sessionManager;
    }
    
    public function AddPost(){
        $this -> postStatusModel -> addNewPost($this -> sessionManager -> getUserNameSession(), 
                                                $this -> postStatusView -> getTitle(), 
                                                $this -> postStatusView -> getStory(), 
                                                $this -> postStatusView -> getImg());
        
        header("Location: ".Settings::$url."");
    }
    
    public function getAllPosts(){
        return $this -> postStatusModel -> getAllPosts();
    }
}