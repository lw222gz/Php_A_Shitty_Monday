<?php

//talks to the Users table in the DB
class UserDAL {

    private $DALb;
    private $Users;
    private $sessionManager;
    
    public function __construct($DALb, $sessionManager){
        $this -> DALb = $DALb;
        $this -> sessionManager = $sessionManager;
    }
    
    //adds a user to the DB and returns the result of it, true is successful otherwise it returns false.
    public function AddUser($userName, $displayName, $hasedPassword, $HasedEmail, $RegisterHash){
        $conn = $this -> DALb -> getSQLConn();
        
        //when an account is created it's active status is set to false, to set this to true an email verification must be made.
        //a string representation is used because my sql DB does not suppor booleans.
        $AccountStatus = "false";
        
        $query = "INSERT INTO `Users`(`LoginName`, `DisplayName`, `HashedPassword`, `HashedEmail`, `AccountActive`, `RegisterHash`) VALUES ('".$conn -> real_escape_string($userName)."',
                                                                                                                          '".$conn -> real_escape_string($displayName)."',
                                                                                                                          '".$conn -> real_escape_string($hasedPassword)."',
                                                                                                                          '".$conn -> real_escape_string($HasedEmail)."', 
                                                                                                                          '".$conn -> real_escape_string($AccountStatus)."',
                                                                                                                          '".$conn -> real_escape_string($RegisterHash)."')";
        
        $result = $conn -> query($query);
        
        $conn -> close();
        
        return $result;
    }
    
    //returns false if something went wrong when executing the query, otherwise an array with User objects are returned.
    public function getUserData(){
        $this -> Users = array();
        
        $conn = $this -> DALb -> getSQLConn();
        
        $query = "SELECT `LoginName`, `DisplayName`, `HashedPassword`, `HashedEmail`, `AccountActive`, `RegisterHash` FROM `Users`";
        
        $result = $conn -> query($query);
        
        if(!$result){
            $conn -> close;
            return $result;
        }
        if ($result->num_rows > 0) {
            //creates a new User for each line in the Users table
            while($row = $result->fetch_assoc()) {
                array_push($this -> Users, new User($row["LoginName"], $row["DisplayName"], $row["HashedPassword"], $row["HashedEmail"], $row["AccountActive"], $row["RegisterHash"]));
            }
        } 
        
        
        $conn->close();
        
        return $this -> Users;
    }
    
    
    public function activateAccount($email, $hash){
        $conn = $this -> DALb -> getSQLConn();
        
        $query = "SELECT `HashedEmail`, `AccountActive`, `RegisterHash`  FROM `Users` WHERE `HashedEmail`='".sha1(Settings::$Salt.$conn->real_escape_string($email))."' AND `RegisterHash`='".$conn -> real_escape_string($hash)."'";
        
        $result = $conn -> query($query);
        
        
        //if $result is false then the query failed thus returning false;
        if(!$result){
            mysqli_close($conn);
            return false;
        }
        $row = $result->fetch_assoc();
        //makes sure a match is found, otherwise it could be a random occurance
        if($result -> num_rows > 0){
            if($row ["AccountActive"] == "false"){
                //activates the account
                $query = "UPDATE `Users` SET `AccountActive`='true' WHERE `HashedEmail`='".sha1(Settings::$Salt.$conn->real_escape_string($email))."' AND `RegisterHash`='".$conn -> real_escape_string($hash)."' AND `AccountActive`='false'";
                
                $reuslt = $conn -> query($query);
                //sets session to display that the account has been successfully activated.
                $this -> sessionManager -> setVerifySessionsuccessful();
            }
            else{
                //set session to display that account is allready active.
                $this -> sessionManager -> setVerifySessionAlreadyActive();
            }
        }
        else{
            //set session to display that no match was found..
            $this -> sessionManager -> setVerifySessionFail();
        }
       
        
        mysqli_close($conn);
        
        return $result;
    }

}