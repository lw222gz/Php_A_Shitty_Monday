<?php

class Post{
    private $Creator;
    private $Title;
    private $Story;
    private $DateCreated;
    private $imgPath;
    
    public function __construct($Creator, $Title, $Story, $DateCreated, $imgPath){
        $this -> Creator = $Creator;
        $this -> Title = $Title;
        $this -> Story = $Story;
        $this -> DateCreated = $DateCreated;
        $this -> imgPath = $imgPath;
    }
    
    public function getCreator(){
        return $this -> Creator;
    }
    
    public function getStory(){
        return $this -> Story;
    }
    
    public function getDateCreated(){
        return $this -> DateCreated;
    }
    
    public function getTitle(){
        return $this -> Title;
    }
    
    public function getImgPath(){
        return $this -> imgPath;
    }
}