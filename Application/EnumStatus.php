<?php

//script to remove string dependecies
class EnumStatus{
    static $failVerification = "EnumStatus::FailedVerification";
    static $successfulVerification = "EnumStatus::SuccessVerification";
    static $alreadyActiveVerification = "EnumStatus::AlreadyActiveVerification";
    //if wanna change the verification URL this can be easily done by changing these values.
    static $EmailURLText = 'email';
    static $HashURLText = 'hash';
    
    
    static $InvalidCharactersError = "Input contained illegal characters.";
}