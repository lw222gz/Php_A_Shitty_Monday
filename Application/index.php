<?php

//I use session start here because I am using a script named SessionManager which is setting default values to some
//sessions in it's constructor and the instance of the class is created here in index
session_start();

//INCLUDE THE FILES NEEDED...
require_once('Settings.php');
require_once('view/LoginView.php');
require_once('view/LayoutView.php');
require_once('view/RegisterView.php');
require_once('view/ApplicationView.php');
require_once('view/PostStatusView.php');

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

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//CREATE OBJECTS OF THE MODELS/ VIEWS/ Controllers
$DALb = new DALBase();
$UDAL = new UserDAL($DALb);
$pDAL = new PostsDAL($DALb);


$psM = new PostStatusModel($pDAL);
$sm = new SessionManager();
$lm = new LoginModel($UDAL, $sm);
$rm = new RegisterModel($UDAL);

$PostView = new PostStatusView();
$AppV = new ApplicationView($PostView, $sm);
$v = new LoginView($lm, $AppV, $sm);
$rv = new RegisterView();
$lv = new LayoutView();

$registerCont = new RegisterController($rv, $rm, $sm);
$loginCont = new LoginController($v, $lm);
$PostCont = new PostController($PostView, $psM, $sm);
$MC = new MasterController($PostCont, $loginCont, $registerCont, $v, $rv, $PostView, $sm, $AppV);

$MC -> init();

$lv->render($sm -> getLoggedInSession(), $v, $rv);

$temp = explode('.', "Emu1.jpg");
$extension = array_pop($temp);
$name = implode('.', $temp);

echo substr($name, 0, -1);
