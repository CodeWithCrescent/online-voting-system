<?php
ob_start();
$action = $_GET['action'];

include 'classes.php';
$auth = new Auth();
$admin = new Admin();

// ***** AUTHENTICATIONS OF USERS ******* //
if ($action == 'register') {
	$register = $auth->register();
	if ($register)
		echo $register;
}

if ($action == 'login') {
	$login = $auth->login();
	if ($login)
		echo $login;
}

if ($action == 'logout') {
	$logout = $auth->logout();
	if ($logout)
		echo $logout;
}

if ($action == 'change_password') {
	$changePwd = $auth->change_password();
	if ($changePwd)
		echo $changePwd;
}

if ($action == 'reset_password') {
	$changePwd = $auth->reset_password();
	if ($changePwd)
		echo $changePwd;
}

// ******* ADMIN ACTIONS ********* //
if ($action == 'add_election') {
	$save = $admin->add_election();
	if ($save)
		echo $save;
}

if ($action == 'update_election') {
	$save = $admin->update_election();
	if ($save)
		echo $save;
}

if ($action == 'delete_election') {
	$save = $admin->delete_election();
	if ($save)
		echo $save;
}

if ($action == 'election_status') {
	$save = $admin->election_status();
	if ($save)
		echo $save;
}

if ($action == 'vote_status') {
	$save = $admin->vote_status();
	if ($save)
		echo $save;
}

if ($action == 'add_category') {
	$save = $admin->add_category();
	if ($save)
		echo $save;
}

if ($action == 'update_category') {
	$save = $admin->update_category();
	if ($save)
		echo $save;
}

if ($action == 'delete_category') {
	$save = $admin->delete_category();
	if ($save)
		echo $save;
}

if ($action == 'add_candidate') {
	$save = $admin->add_candidate();
	if ($save)
		echo $save;
}

if ($action == 'update_candidate') {
	$save = $admin->update_candidate();
	if ($save)
		echo $save;
}

if ($action == 'delete_candidate') {
	$save = $admin->delete_candidate();
	if ($save)
		echo $save;
}

if ($action == 'user_type') {
	$save = $admin->user_type();
	if ($save)
		echo $save;
}

// ****** ELECTION REPORT ****** //

if ($action == 'download_report') {
	$save = $admin->download_report();
	if ($save)
		echo $save;
}

if ($action == 'delete_report') {
	$save = $admin->delete_report();
	if ($save)
		echo $save;
}

// ******* VOTERS ************* //
if ($action == 'vote') {
	$save = $admin->vote();
	if ($save)
		echo $save;
}

if ($action == 'update_profile') {
	$save = $admin->update_profile();
	if ($save)
		echo $save;
}
