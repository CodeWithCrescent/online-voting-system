<?php
session_start();
Class Auth {
	private $db;

	public function __construct() {
		ob_start();
		include '../config/dbconnection.php';
		
		$this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

    function register() {
        // Getting User inputs
        extract($_POST);
        $nameField = addslashes($name);
        $usernameField = addslashes($username);
        $user_type = 1;
    
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

	    // Validation of user inputs
		if (empty($username)) {
			echo 'Please fill in all fields.';
			exit();
		}
	
		// Check if the password and confirmation match
		if ($password != $confirmPassword) {
			echo json_encode(array('status' => 'password', 'message' => 'Password and confirmation do not match.'));
			exit();
		}
		// Prepare the statement to check if the username already exists in the database
		$stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
		$stmt->bind_param("s", $usernameField);
		$stmt->execute();
		$stmt->bind_result($count);
		$stmt->fetch();
		$stmt->close();

	    // Check if the username exists in the database
		if ($count > 0) {
			echo json_encode(array('status' => 'error', 'message' => 'Username already exist!.'));
		} else {
			// Prepare the statement to insert data into the database
			$stmt = $this->db->prepare("INSERT INTO users (name, username, password, type) VALUES (?, ?, ?, ?)");
			$stmt->bind_param("sssi", $nameField, $usernameField, $hashed_password, $user_type);
		
			// Execute the prepared statement
			if ($stmt->execute()) {
				$row = $this->db->prepare("SELECT * FROM users WHERE username = ?");
				$row->bind_param("s", $usernameField);
				$row->execute();
				$result = $row->get_result();
			
				if($result->num_rows > 0){
					$user = $result->fetch_assoc();
					if(password_verify($password, $user['password'])){ 
						// Session Variables
						foreach ($user as $key => $value) {
							if ($key != 'password' && !is_numeric($key)) {
								$_SESSION['login_' . $key] = $value;
							}
						}
					echo json_encode(array('status' => 'success', 'redirect_url' => 'index.php?page=vote'));

					}
				}
			} else {
				echo json_encode(array('status' => 'error', 'message' => 'Registration failed!'));
			}
		$stmt->close();
		}
    }
    

	function login(){
		if(!isset($_POST['username']) || empty($_POST['password'])) {
			echo json_encode(array('status' => 'error', 'message' => 'Please enter both username and password.'));
			return;
		}
	
		$username = addslashes($_POST['username']);
		$password = $_POST['password'];

		$stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$result = $stmt->get_result();
	
		if($result->num_rows > 0){
			$user = $result->fetch_assoc();
			if(password_verify($password, $user['password'])){ 
				// Session Variables
				foreach ($user as $key => $value) {
					if ($key != 'password' && !is_numeric($key)) {
						$_SESSION['login_' . $key] = $value;
					}
				}
	
				if ($_SESSION['login_type'] == 0) {
					// Return success and redirect URL for admin users
					$redirect_url = isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : 'index.php?page=dashboard';
    				unset($_SESSION['redirect_url']);
					// echo json_encode(array('status' => 'success', 'redirect_url' => 'index.php?page=dashboard'));
					echo json_encode(array('status' => 'success', 'redirect_url' => $redirect_url));
				} else {
					// Return success and redirect URL for non-admin users
					$redirect_url = isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : 'index.php?page=vote';
    				unset($_SESSION['redirect_url']);
					// echo json_encode(array('status' => 'success', 'redirect_url' => 'index.php?page=home'));
					echo json_encode(array('status' => 'success', 'redirect_url' => $redirect_url));
				}
			} else {
				// Incorrect password
				echo json_encode(array('status' => 'password', 'message' => 'Incorrect password.'));
			}
		} else {
			// User not found
			echo json_encode(array('status' => 'username', 'message' => 'User not found.'));
		}
	}
	
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location: ../login.php");
	}

}

class Admin {
	private $db;

	public function __construct() {
		ob_start();
        include '../config/dbconnection.php';
        
        $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function add_election(){
		extract($_POST);
		if(empty($id)){
			try {
				$save = $this->db->prepare("INSERT INTO election (title, year, voters, starttime, endtime, description) VALUES (?, ?, ?, ?, ?, ?)");
				if (!$save) {
					throw new Exception("Failed to prepare the query.");
				}
			
				$save->bind_param("ssssss", $title, $year, $voters, $starttime, $endtime, $description);
				if (!$save->execute()) {
					throw new Exception("Failed to execute the query.");
				}
			
				echo json_encode(array('status' => 'success', 'redirect_url' => 'index.php?page=election_config'));
			} catch (Exception $e) { // $e->getMessage()
				echo json_encode(array('status' => 'error', 'message' => 'Failed to add election! Try again later.'));
			}
			
		}else{
			// $save = $this->db->query("UPDATE election set ".$data." where id =".$id);
			// if($save)
				return 2;
		}
	}

	function show_election() {
		extract($_POST);
		$stmt = $mysqli->prepare("SELECT title, year, voters, starttime, endtime, description FROM election WHERE id = ?");
		$stmt->bind_param("i", $electionId);
		$stmt->execute();
		$result = $stmt->get_result();
	
		if ($result->num_rows > 0) {
			$data = $result->fetch_assoc();
			echo json_encode($data);
		} else {
			echo json_encode(array('error' => 'Item not found'));
		}
	
		$stmt->close();
		$mysqli->close();
	}
}