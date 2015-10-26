<?php

//I use session start here because I am using a script named SessionManager which is setting default values to some
//sessions in it's constructor and the instance of the class is created here in index
session_start();

//INCLUDE THE FILES NEEDED...
require_once('Settings.php');
require_once('EnumStatus.php');
require_once('view/LoginView.php');
require_once('view/LayoutView.php');
require_once('view/RegisterView.php');
require_once('view/ApplicationView.php');
require_once('view/PostStatusView.php');
require_once('view/VerifyView.php');

require_once('controller/MasterController.php');
require_once('controller/LoginController.php');
require_once('controller/RegisterController.php');
require_once('controller/PostController.php');

require_once('model/SessionManager.php');
require_once('model/m_Login/LoginModel.php');
require_once('model/m_Login/User.php');
require_once('model/m_Register/RegisterModel.php');
require_once('model/m_UserPost/PostStatusModel.php');
require_once('model/m_UserPost/Post.php');
require_once('model/DAL/DALBase.php');
require_once('model/DAL/PostsDAL.php');
require_once('model/DAL/UserDAL.php');


//TODO on live version:
//-remove error reporting.
//-change url in Settings script.
//-change header location in Logincontroller and Postcontroller.
//-change the url in the email sent
//-DB settings

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//CREATE OBJECTS OF THE MODELS/ VIEWS/ Controllers
$sessionManager = new SessionManager();

$DALb = new DALBase();
$UDAL = new UserDAL($DALb, $sessionManager);
$pDAL = new PostsDAL($DALb);

$postStatusModel = new PostStatusModel($pDAL);
$loginModel = new LoginModel($UDAL, $sessionManager);
$registerModel = new RegisterModel($UDAL);

$PostView = new PostStatusView();
$AppV = new ApplicationView($PostView, $sessionManager);
$loginView = new LoginView($AppV, $sessionManager);
$registerView = new RegisterView();
$layoutView = new LayoutView();
$verifyView = new VerifyView($sessionManager);

$registerCont = new RegisterController($registerView, $registerModel, $sessionManager);
$loginCont = new LoginController($loginView, $loginModel);
$PostCont = new PostController($PostView, $postStatusModel, $sessionManager);
$MC = new MasterController($PostCont, $loginCont, $registerCont, $loginView, $registerView, $PostView, $sessionManager, $AppV, $verifyView, $layoutView);

//runs logic then renders out the HTML by calling on model scripts / view scripts
$MC -> init();