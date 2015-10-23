<?php

class UserDAL {

    private $DALb;
    private $Users;
    
    public function __construct($DALb){
        $this -> DALb = $DALb;
    }
    
    //adds a user to the DB and returns the result of it, true is successful otherwise it returns false.
    public function AddUser($userName, $displayName, $hasedPassword){
        $conn = $this -> DALb -> getSQLConn();
        
        $query = "INSERT INTO `Users`(`LoginName`, `DisplayName`, `HashedPassword`) VALUES ('$userName','$displayName','$hasedPassword')";
        
        $result = mysqli_query($conn, $query);
        
        $conn -> close();
        
        return $result;
    }
    
    //returns false if something went wrong when executing the query, otherwise an array with User objects are returned.
    public function getUserData(){
        $this -> Users = array();
        
        $conn = $this -> DALb -> getSQLConn();
        
        $query = "SELECT `LoginName`, `DisplayName`, `HashedPassword` FROM `Users`";
        
        $result = mysqli_query($conn, $query);
        
        if(!$result){
            $conn -> close;
            return $result;
        }
        if ($result->num_rows > 0) {
            //creates a new User for each line in the Users table
            while($row = $result->fetch_assoc()) {
                array_push($this -> Users, new User($row["LoginName"], $row["DisplayName"], $row["HashedPassword"]));
            }
        } 
        
        
        $conn->close();
        
        return $this -> Users;
    }

}