<?php

class PostStatusView {
    
    private static $Story = "PostStatusView::StoryID";
    private static $Title = "PostStatusView::Title";
    private static $Post = "PostStatusView::Post";
    private static $FileID = "PostStatusView::FileID";
    private static $ErrorMessID = "PostStatusView::ErrorMessage";
    
    
    private $errorMessage;
    private $saveStory;
    private $saveTitle;
    
    //returns html for a form to write a story.
    public function getHTML(){
        //TODO: add upload image option
        return '<form method="post" enctype="multipart/form-data"> 
				<fieldset>
                    <p id="' . self::$ErrorMessID . '">' . $this -> errorMessage . '</p>
                    
                    <p><label for="' . self::$Title . '">Title of story(max 50 characters): </label>
                    <input type="text" id="' . self::$Title . '" name="' . self::$Title . '" value="'. $this -> saveTitle .'" maxlength="50"/></p>
                    
					<label for="' . self::$Story . '">Write your story here(max 1550 characters):</label> 
					<textarea cols="40" rows ="5" type="text" class="PostTextBlock" id="' . self::$Story . '" name="' . self::$Story . '" maxlength="1550">' . $this -> saveStory . '</textarea> 
					
					<label>Select image to upload(optional): </label>
                    <input type="file" accept="image/*" name="' . self::$FileID . '" id="fileToUpload">
                    
					<input type="submit" name="' . self::$Post . '" value="Upload" />
				</fieldset>
			</form>';
    }
    
    //sets any possible error messages
    public function setErrorMessage($e){
        $mess = $e -> getMessage();
        if($mess == EnumStatus::$InvalidCharactersError){
            $this -> saveStory = strip_tags($this -> saveStory);
            $this -> saveTitle = strip_tags($this -> saveTitle);
        }
        $this -> errorMessage = $mess;
    }
    
    //returns true if user has pressed "Upload"
    public function hasPressedUpload(){
        if(isset($_POST[self::$Post])){
            return true;
        }
        return false;
    }
    
    //this function dont need if(isset...)) because $_FILES allways returns an array
    public function getImg(){
        return $_FILES[self::$FileID];
    }
    
    //returns the story written in the textarea
    public function getStory(){
        if(isset($_POST[self::$Story])){
            //$this -> saveStory = preg_replace('/\+/', ' ' ,$_POST[self::$Story]);
            $this -> saveStory = $_POST[self::$Story];
            return $this -> saveStory;
        }
        return null;
    }
    
    //returns the title in the input field.
    public function getTitle(){
        if(isset($_POST[self::$Title])){
            $this -> saveTitle = trim($_POST[self::$Title]);
            return $this -> saveTitle;
        }
        return null;
    }
    
    //sets a message if an unhandeld exception was thrown.
    public function UnhandeldException(){
		$this -> errorMessage = "An unhandeld exception was thrown. Please infrom...";
	}
}