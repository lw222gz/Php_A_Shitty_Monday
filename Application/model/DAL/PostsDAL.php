<?php

class PostsDAL {
    private $DALb; //DALBase
    private $Posts;

    
    //sets the timezone for the AddNewPostMethod
    public function __construct($DALb){
        $this -> DALb = $DALb;
        date_default_timezone_set('Europe/Stockholm');
    }
        
    //Adds a new post to the DB, returns true if successful, if it fails it returns false.
    public function AddNewPostToDB($user, $title, $story, $imgPath){
        //get todays date
        $date = date('Y-m-d H:i:s');
        
        $conn = $this -> DALb -> getSQLConn();
        
        //$conn -> real_escape_string() allows the ' sign commonly used in english, otherwise the DB will complain
        $query = "INSERT INTO `Posts`(`Creator`, `Title`, `Message`, `DatePosted`, `ImagePath`) VALUES ('".$conn -> real_escape_string($user)."',
                                                                                                        '" .$conn-> real_escape_string($title). "',
                                                                                                        '".$conn->real_escape_string($story)."',
                                                                                                        '$date',
                                                                                                        '".$conn -> real_escape_string($imgPath)."')"; 
        $result = $conn -> query($query);

        mysqli_close($conn);
        
        return $result;
    }
    
    
    //Calls the removeOldPosts function, then it gets all current posts and returns it as an array with Post objects. If eighter of the query's fails
    //it returns false
    public function getAllPosts($postStatusModel){
        
        //TODO: anyway to call this function daily?
        if(!self::removeOldPosts()){
            return false;
        }
        
        $this -> Posts = array();
        $conn = $this -> DALb -> getSQLConn();
        
        $query = "SELECT `Creator`, `Title`, `Message`, `DatePosted`, `ImagePath` FROM `Posts` ORDER BY `DatePosted` DESC";
        
        $result = $conn -> query($query);
        
        //if result is false then the query failed.
        if(!$result){
            $conn -> close();
            return $result;
        }
        if ($result->num_rows > 0) {
            //creates a new Post for each line in the Posts table
            while($row = $result->fetch_assoc()) {
                array_push($this -> Posts, new Post($row["Creator"], $row["Title"], $row["Message"], $row["DatePosted"], $row["ImagePath"]));
            }
            
            //removes any pictures that dont have a path linked to them.
            $postStatusModel -> deleteOldPictures($this -> Posts);
        } 
        
        $conn->close();
        
        return $this -> Posts;
    }
    
    //removes week old posts, returns the status of the query, true if it was successful, otherwise false.
    //TODO: a more efficient place to run this code less frequently?
    public function removeOldPosts(){
        //TODO: remove images that dont have a url in the DB
        $conn = $this -> DALb -> getSQLConn();
        
        //removes week old posts
        $query = "DELETE FROM `Posts` WHERE `DatePosted` < NOW() - INTERVAL 7 DAY";
        
        $result = $conn -> query($query);
        
        $conn -> close();
        
        
        return $result;
    }
}