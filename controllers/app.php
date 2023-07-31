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

if ($action == 'show_election') {
    $data = $admin->show_election();
    echo json_encode($data);
}

if($action == 'add_category'){
	$save = $admin->add_category();
	if($save)
		echo $save;
}

if($action == 'update_category'){
	$save = $admin->update_category();
	if($save)
		echo $save;
}

if($action == 'delete_category'){
	$save = $admin->delete_category();
	if($save)
		echo $save;
}
