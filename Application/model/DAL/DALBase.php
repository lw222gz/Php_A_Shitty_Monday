<?php

class DALBase{
    private $SQLconn;
    
    //returns new SQL Connection
    public function getSQLConn(){
        $this -> SQLconn = mysqli_connect(Settings::$severN, Settings::$UserN, Settings::$passW, Settings::$dbName, Settings::$port);
        
        if($this -> SQLconn -> connect_error){
            die("Connection failed". $this -> SQLconn -> connect_error);
        }
        return $this -> SQLconn;
    }
}