<?php
ob_start();
$action = $_GET['action'];

include 'classes.php';
$auth = new Auth();
$admin = new Admin();

if($action == 'register'){
	$register = $auth->register();
	if($register)
		echo $register;
}
if($action == 'login'){
	$login = $auth->login();
	if($login)
		echo $login;
}
if($action == 'logout'){
	$logout = $auth->logout();
	if($logout)
		echo $logout;
}

if($action == 'add_election'){
	$save = $admin->add_election();
	if($save)
		echo $save;
}
// if($action == 'show_election'){
// 	$get = $admin->show_election();
// 	if($get)
// 		echo $get;
// }
if (isset($_GET['election_id'])) {
    $electionId = $_GET['election_id'];
		$get = $admin->show_election();
		if($get)
			echo $get;
}