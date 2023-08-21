<?php
session_start();
class Auth
{
	private $db;

	public function __construct()
	{
		ob_start();
		include '../config/dbconnection.php';

		$this->db = $conn;
	}
	function __destruct()
	{
		$this->db->close();
		ob_end_flush();
	}

	function register()
	{
		// Getting User inputs
		extract($_POST);
		$nameField = addslashes(ucwords($name));
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

				if ($result->num_rows > 0) {
					$user = $result->fetch_assoc();
					if (password_verify($password, $user['password'])) {
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


	function login()
	{
		if (!isset($_POST['username']) || empty($_POST['password'])) {
			echo json_encode(array('status' => 'error', 'message' => 'Please enter both username and password.'));
			return;
		}

		$username = addslashes($_POST['username']);
		$password = $_POST['password'];

		$stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$result = $stmt->get_result();

		if ($result->num_rows > 0) {
			$user = $result->fetch_assoc();
			if (password_verify($password, $user['password'])) {
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

	function logout()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location: ../login.php");
	}

	function change_password()
	{
		extract($_POST);
		$userId = $_SESSION['login_id'];

		// Validation of user inputs
		if (empty($currentPassword) || empty($newPassword) || empty($confirmNewPassword)) {
			echo json_encode(array('status' => 'error', 'message' => 'Please fill in all fields.'));
			exit();
		}

		// Prepare the statement to retrieve user data from the database
		$stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
		$stmt->bind_param("i", $userId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();

		if ($result->num_rows > 0) {
			$user = $result->fetch_assoc();
			if (password_verify($currentPassword, $user['password'])) {
				if ($newPassword == $confirmNewPassword) {

					$hashed_new_password = password_hash($newPassword, PASSWORD_DEFAULT);

					$updateStmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
					$updateStmt->bind_param("si", $hashed_new_password, $userId);

					if ($updateStmt->execute()) {
						echo json_encode(array('status' => 'success', 'message' => 'Password changed successfully.'));
					} else {
						echo json_encode(array('status' => 'error', 'message' => 'Password change failed.'));
					}
					$updateStmt->close();
				} else {
					echo json_encode(array('status' => 'error', 'message' => 'New password and confirmation do not match.'));
				}
			} else {
				echo json_encode(array('status' => 'error', 'message' => 'Incorrect old password.'));
			}
		} else {
			echo json_encode(array('status' => 'error', 'message' => 'User not found.'));
		}
	}

	function reset_password()
	{
		extract($_POST);
		$new_password = password_hash("123456", PASSWORD_DEFAULT);

		try {
			$resetPassword = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
			if (!$resetPassword) {
				throw new Exception("Failed to prepare the update query.");
			}

			$resetPassword->bind_param("si", $new_password, $user_id);

			if (!$resetPassword->execute()) {
				throw new Exception("Failed to execute the update query.");
			}

			echo json_encode(array('status' => 'success', 'message' => 'Password reset successful! New password: 123456'));
		} catch (Exception $e) {
			echo json_encode(array('status' => 'error', 'message' => 'Failed to reset password! Try again later.'));
		}
	}
}

class Admin
{
	private $db;

	public function __construct()
	{
		ob_start();
		include '../config/dbconnection.php';

		$this->db = $conn;
	}
	function __destruct()
	{
		$this->db->close();
		ob_end_flush();
	}

	function add_election()
	{
		extract($_POST);
		$added_by = $_SESSION['login_id'];

		if (empty($id)) {
			try {
				$save = $this->db->prepare("INSERT INTO election (title, year, voters, starttime, endtime, description, added_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
				if (!$save) {
					throw new Exception("Failed to prepare the query.");
				}

				$save->bind_param("sssssss", $title, $year, $voters, $starttime, $endtime, $description, $added_by);
				if (!$save->execute()) {
					throw new Exception("Failed to execute the query.");
				}

				echo json_encode(array('status' => 'success', 'redirect_url' => 'index.php?page=election_config'));
			} catch (Exception $e) { // $e->getMessage()
				echo json_encode(array('status' => 'error', 'message' => 'Failed to add election! Try again later.'));
			}
		} else {
			// $save = $this->db->query("UPDATE election set ".$data." where id =".$id);
			// if($save)
			return 2;
		}
	}

	function update_election()
	{
		extract($_POST);
		$updated_by = $_SESSION['login_id'];

		if (!empty($election_id)) {
			try {
				$save = $this->db->prepare("UPDATE election SET title = ?, year = ?, voters = ?, starttime = ?, endtime = ?, description = ?, updated_by = ? WHERE id = ?");
				if (!$save) {
					throw new Exception("Failed to prepare the query.");
				}

				$save->bind_param("ssssssii", $title, $year, $voters, $starttime, $endtime, $description, $updated_by, $election_id);

				if (!$save->execute()) {
					throw new Exception("Failed to execute the query.");
				}

				echo json_encode(array('status' => 'success', 'redirect_url' => 'index.php?page=election_config'));
			} catch (Exception $e) {
				echo json_encode(array('status' => 'error', 'message' => 'Failed to update election! Try again later.'));
			}
		} else {
			// ID does not exist
			echo json_encode(array('status' => 'error', 'message' => 'Failed to update election! Contact administrator for help.'));
		}
	}

	function delete_election()
	{
		extract($_POST);

		if (!empty($election_id)) {
			try {
				$delete = $this->db->prepare("DELETE FROM election WHERE id = ?");
				if (!$delete) {
					throw new Exception("Failed to prepare the query.");
				}

				$delete->bind_param("i", $election_id);

				if (!$delete->execute()) {
					throw new Exception("Failed to execute the query.");
				}

				if ($delete->affected_rows > 0) {
					echo json_encode(array('status' => 'success', 'message' => 'Successfully election deleted!'));
				} else {
					echo json_encode(array('status' => 'error', 'message' => 'election not found or already deleted.'));
				}
			} catch (Exception $e) {
				echo json_encode(array('status' => 'error', 'message' => 'Failed to delete election! Try again later.'));
			}
		} else {
			// Invalid or empty election_id
			echo json_encode(array('status' => 'error', 'message' => 'Invalid election ID.'));
		}
	}

	function election_status()
	{
		extract($_POST);
		$updated_by = $_SESSION['login_id'];

		if (!empty($election_id)) {
			try {
				$this->db->autocommit(false); // Start transaction

				// Set all rows' status to 0
				$resetStatus = $this->db->prepare("UPDATE election SET status = 0, can_vote = 0");
				if (!$resetStatus) {
					throw new Exception("Failed to prepare the reset query.");
				}

				if (!$resetStatus->execute()) {
					throw new Exception("Failed to execute the reset query.");
				}

				// Set the specified row's status to 1
				$updateStatus = $this->db->prepare("UPDATE election SET status = $status, can_vote = $status, updated_by = ? WHERE id = ?");
				if (!$updateStatus) {
					throw new Exception("Failed to prepare the update query.");
				}

				$updateStatus->bind_param("ii", $updated_by, $election_id);

				if (!$updateStatus->execute()) {
					throw new Exception("Failed to execute the update query.");
				}

				$this->db->commit(); // Commit transaction

				echo json_encode(array('status' => 'success', 'message' => 'Status change!'));
			} catch (Exception $e) {
				$this->db->rollback(); // Rollback transaction
				echo json_encode(array('status' => 'error', 'message' => 'Failed to update status of election! Try again later.'));
			} finally {
				$this->db->autocommit(true); // Restore autocommit
			}
		} else {
			// ID does not exist
			echo json_encode(array('status' => 'error', 'message' => 'Failed to update status of election! Contact administrator for help.'));
		}
	}

	function vote_status()
	{
		extract($_POST);
		$status = 0;

		if (!empty($election_id)) {
			try {
				$this->db->autocommit(false); // Start transaction

				$updateStatus = $this->db->prepare("UPDATE election SET can_vote = ?, updated_by = ? WHERE id = ?");
				if (!$updateStatus) {
					throw new Exception("Failed to prepare the update query.");
				}

				$updateStatus->bind_param("iii", $status, $updated_by, $election_id);

				if (!$updateStatus->execute()) {
					throw new Exception("Failed to execute the update query.");
				}

				$this->db->commit(); // Commit transaction

				echo json_encode(array('status' => 'success', 'message' => 'Status change!'));
			} catch (Exception $e) {
				$this->db->rollback(); // Rollback transaction
				echo json_encode(array('status' => 'error', 'message' => 'Failed to update status of election! Try again later.'));
			} finally {
				$this->db->autocommit(true); // Restore autocommit
			}
		} else {
			// ID does not exist
			echo json_encode(array('status' => 'error', 'message' => 'Failed to update status of election! Contact administrator for help.'));
		}
	}


	function add_category()
	{
		extract($_POST);
		$added_by = $_SESSION['login_id'];

		if (empty($id)) {
			try {
				$save = $this->db->prepare("INSERT INTO categories (election_id, name, added_by) VALUES (?, ?, ?)");
				if (!$save) {
					throw new Exception("Failed to prepare the query.");
				}

				$save->bind_param("isi", $election, $category, $added_by);

				if (!$save->execute()) {
					throw new Exception("Failed to execute the query.");
				}

				echo json_encode(array('status' => 'success', 'redirect_url' => 'index.php?page=categories'));
			} catch (Exception $e) {
				echo json_encode(array('status' => 'error', 'message' => 'Failed to add category! Try again later.'));
			}
		} else {
			// ID Exists
			echo json_encode(array('status' => 'errors', 'message' => 'Failed to add category! Contact administator for help.'));
		}
	}

	function update_category()
	{
		extract($_POST);
		$updated_by = $_SESSION['login_id'];

		if (!empty($category_id)) {
			try {
				$save = $this->db->prepare("UPDATE categories SET name = ?, updated_by = ? WHERE id = ?");
				if (!$save) {
					throw new Exception("Failed to prepare the query.");
				}

				$save->bind_param("sii", $category, $updated_by, $category_id);

				if (!$save->execute()) {
					throw new Exception("Failed to execute the query.");
				}

				echo json_encode(array('status' => 'success', 'redirect_url' => 'index.php?page=categories'));
			} catch (Exception $e) {
				echo json_encode(array('status' => 'error', 'message' => 'Failed to update category! Try again later.'));
			}
		} else {
			// ID does not exist
			echo json_encode(array('status' => 'error', 'message' => 'Failed to update category! Contact administrator for help.'));
		}
	}

	function delete_category()
	{
		extract($_POST);

		if (!empty($category_id)) {
			try {
				$delete = $this->db->prepare("DELETE FROM categories WHERE id = ?");
				if (!$delete) {
					throw new Exception("Failed to prepare the query.");
				}

				$delete->bind_param("i", $category_id);

				if (!$delete->execute()) {
					throw new Exception("Failed to execute the query.");
				}

				if ($delete->affected_rows > 0) {
					echo json_encode(array('status' => 'success', 'message' => 'Successfully Deleted'));
				} else {
					echo json_encode(array('status' => 'error', 'message' => 'Category not found or already deleted.'));
				}
			} catch (Exception $e) {
				echo json_encode(array('status' => 'error', 'message' => 'Failed to delete category! Try again later.'));
			}
		} else {
			// Invalid or empty category_id
			echo json_encode(array('status' => 'error', 'message' => 'Invalid category.'));
		}
	}

	function add_candidate()
	{
		extract($_POST);
		$added_by = $_SESSION['login_id'];

		if (empty($category) || empty($candidate) || empty($candidate_year)) {
			echo json_encode(array('status' => 'error', 'message' => 'Please fill in all the required fields.'));
			return;
		}

		try {
			// Check if Category_id exists in the categories table
			$check_category = $this->db->prepare("SELECT id FROM categories WHERE id = ?");
			if (!$check_category) {
				throw new Exception("Failed to prepare the query.");
			}

			$check_category->bind_param("i", $category);

			if (!$check_category->execute()) {
				throw new Exception("Failed to execute the query.");
			}

			$result = $check_category->get_result();

			if ($result->num_rows === 0) {
				echo json_encode(array('status' => 'error', 'message' => 'Invalid category. Please select a valid category.'));
				return;
			}

			$candidate_photo_name = '';
			$fellow_candidate_photo_name = '';

			if (!empty($_FILES['candidate_photo']['name'])) {
				$candidate_photo = $_FILES['candidate_photo']['name'];
				$candidate_photo_tmp = $_FILES['candidate_photo']['tmp_name'];
				$candidate_photo_ext = pathinfo($candidate_photo, PATHINFO_EXTENSION);
				$allowed_extensions = array('jpg', 'jpeg', 'png');

				if (!in_array(strtolower($candidate_photo_ext), $allowed_extensions)) {
					echo json_encode(array('status' => 'error', 'message' => 'Invalid Candidate Profile Picture format. Please use JPG, JPEG, or PNG.'));
					return;
				}

				$candidate_photo_name = $election . '' . $category . time() . uniqid() . '.' . $candidate_photo_ext;
				$candidate_photo_path = '../assets/img/profile/candidate/' . $candidate_photo_name;

				if (!move_uploaded_file($candidate_photo_tmp, $candidate_photo_path)) {
					echo json_encode(array('status' => 'error', 'message' => 'Failed to upload Candidate Profile Picture.'));
					return;
				}
			}

			if (!empty($_FILES['fellow_candidate_photo']['name'])) {
				$fellow_candidate_photo = $_FILES['fellow_candidate_photo']['name'];
				$fellow_candidate_photo_tmp = $_FILES['fellow_candidate_photo']['tmp_name'];
				$fellow_candidate_photo_ext = pathinfo($fellow_candidate_photo, PATHINFO_EXTENSION);
				$allowed_extensions = array('jpg', 'jpeg', 'png');

				if (!in_array(strtolower($fellow_candidate_photo_ext), $allowed_extensions)) {
					echo json_encode(array('status' => 'error', 'message' => 'Invalid Fellow Candidate Profile Picture format. Please use JPG, JPEG, or PNG.'));
					return;
				}

				$fellow_candidate_photo_name = $election . '' . $category . time() . uniqid() . '.' . $fellow_candidate_photo_ext;
				$fellow_candidate_photo_path = '../assets/img/profile/candidate/' . $fellow_candidate_photo_name;

				if (!move_uploaded_file($fellow_candidate_photo_tmp, $fellow_candidate_photo_path)) {
					echo json_encode(array('status' => 'error', 'message' => 'Failed to Upload Fellow Candidate Profile Picture.'));
					return;
				}
			}

			$save_candidate = $this->db->prepare("INSERT INTO candidates (election_id, category_id, name, candidate_year, candidate_photo, fellow_candidate_name, fellow_candidate_year, fellow_candidate_photo, added_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
			if (!$save_candidate) {
				throw new Exception("Failed to prepare the query.");
			}

			$save_candidate->bind_param("iisissisi", $election, $category, $candidate, $candidate_year, $candidate_photo_name, $fellow_candidate, $fellow_candidate_year, $fellow_candidate_photo_name, $added_by);

			if (!$save_candidate->execute()) {
				throw new Exception("Failed to execute the query.");
			}

			echo json_encode(array('status' => 'success', 'redirect_url' => 'index.php?page=candidates'));
		} catch (Exception $e) {
			echo json_encode(array('status' => 'error', 'message' => 'Failed to add candidate! Try again later.'));
		}
	}

	function update_candidate()
	{
		extract($_POST);
		$updated_by = $_SESSION['login_id'];

		if (empty($category) || empty($candidate) || empty($candidate_year)) {
			echo json_encode(array('status' => 'error', 'message' => 'Please fill in all the required fields.'));
			return;
		}

		if (!empty($candidate_id)) {
			try {
				// Check if Category_id exists in the categories table
				$check_category = $this->db->prepare("SELECT id FROM categories WHERE id = ?");
				if (!$check_category) {
					throw new Exception("Failed to prepare the query.");
				}

				$check_category->bind_param("i", $category);

				if (!$check_category->execute()) {
					throw new Exception("Failed to execute the query.");
				}

				$result = $check_category->get_result();

				if ($result->num_rows === 0) {
					echo json_encode(array('status' => 'error', 'message' => 'Invalid category. Please select a valid category.'));
					return;
				}

				$fetchPhotoQuery = $this->db->prepare("SELECT candidate_photo, fellow_candidate_photo FROM candidates WHERE id = ?");
				if (!$fetchPhotoQuery) {
					throw new Exception("Failed to prepare the query.");
				}

				$fetchPhotoQuery->bind_param("i", $candidate_id);
				if (!$fetchPhotoQuery->execute()) {
					throw new Exception("Failed to execute the query.");
				}

				$fetchPhotoQuery->bind_result($candidatePhoto, $fellowCandidatePhoto);
				$fetchPhotoQuery->fetch();
				$fetchPhotoQuery->close();

				if (empty($_FILES['candidate_photo']['name'])) {
					$candidate_photo_name = $candidatePhoto;
				} else {
					$candidate_photo = $_FILES['candidate_photo']['name'];
					$candidate_photo_tmp = $_FILES['candidate_photo']['tmp_name'];
					$candidate_photo_ext = pathinfo($candidate_photo, PATHINFO_EXTENSION);
					$allowed_extensions = array('jpg', 'jpeg', 'png');

					if (!in_array(strtolower($candidate_photo_ext), $allowed_extensions)) {
						echo json_encode(array('status' => 'error', 'message' => 'Invalid Candidate Profile Picture format. Please use JPG, JPEG, or PNG.'));
						return;
					}

					if (!empty($candidatePhoto)) {
						unlink('../assets/img/profile/candidate/' . $candidatePhoto);
					}

					$candidate_photo_name = $election . '' . $category . time() . uniqid() . '.' . $candidate_photo_ext;
					$candidate_photo_path = '../assets/img/profile/candidate/' . $candidate_photo_name;

					if (!move_uploaded_file($candidate_photo_tmp, $candidate_photo_path)) {
						echo json_encode(array('status' => 'error', 'message' => 'Failed to upload Candidate Profile Picture.'));
						return;
					}
				}

				if (empty($_FILES['fellow_candidate_photo']['name'])) {
					$fellow_candidate_photo_name = $fellowCandidatePhoto;
				} else {
					$fellow_candidate_photo = $_FILES['fellow_candidate_photo']['name'];
					$fellow_candidate_photo_tmp = $_FILES['fellow_candidate_photo']['tmp_name'];
					$fellow_candidate_photo_ext = pathinfo($fellow_candidate_photo, PATHINFO_EXTENSION);
					$allowed_extensions = array('jpg', 'jpeg', 'png');

					if (!in_array(strtolower($fellow_candidate_photo_ext), $allowed_extensions)) {
						echo json_encode(array('status' => 'error', 'message' => 'Invalid Fellow Candidate Profile Picture format. Please use JPG, JPEG, or PNG.'));
						return;
					}

					if (!empty($fellowCandidatePhoto)) {
						unlink('../assets/img/profile/' . $fellowCandidatePhoto);
					}

					$fellow_candidate_photo_name = $election . '' . $category . time() . uniqid() . '.' . $fellow_candidate_photo_ext;
					$fellow_candidate_photo_path = '../assets/img/profile/candidate/' . $fellow_candidate_photo_name;

					if (!move_uploaded_file($fellow_candidate_photo_tmp, $fellow_candidate_photo_path)) {
						echo json_encode(array('status' => 'error', 'message' => 'Failed to Upload Fellow Candidate Profile Picture.'));
						return;
					}
				}

				$save_candidate = $this->db->prepare("UPDATE candidates SET category_id = ?, name = ?, candidate_year = ?, candidate_photo = ?, fellow_candidate_name = ?, fellow_candidate_year = ?, fellow_candidate_photo = ?, updated_by = ? WHERE id = ?");
				if (!$save_candidate) {
					throw new Exception("Failed to prepare the query.");
				}

				$save_candidate->bind_param("isississi", $category, $candidate, $candidate_year, $candidate_photo_name, $fellow_candidate, $fellow_candidate_year, $fellow_candidate_photo_name, $updated_by, $candidate_id);


				if (!$save_candidate->execute()) {
					throw new Exception("Failed to execute the query.");
				}

				echo json_encode(array('status' => 'success', 'redirect_url' => 'index.php?page=candidates'));
			} catch (Exception $e) {
				echo json_encode(array('status' => 'error', 'message' => 'Failed to add candidate! Try again later.'));
			}
		}
	}

	function delete_candidate()
	{
		extract($_POST);

		if (!empty($candidate_id)) {
			try {

				$fetchPhotoQuery = $this->db->prepare("SELECT candidate_photo, fellow_candidate_photo FROM candidates WHERE id = ?");
				if (!$fetchPhotoQuery) {
					throw new Exception("Failed to prepare the query.");
				}

				$fetchPhotoQuery->bind_param("i", $candidate_id);
				if (!$fetchPhotoQuery->execute()) {
					throw new Exception("Failed to execute the query.");
				}

				$fetchPhotoQuery->bind_result($candidatePhoto, $fellowCandidatePhoto);
				$fetchPhotoQuery->fetch();
				$fetchPhotoQuery->close();

				$deleteQuery = $this->db->prepare("DELETE FROM candidates WHERE id = ?");
				if (!$deleteQuery) {
					throw new Exception("Failed to prepare the query.");
				}

				$deleteQuery->bind_param("i", $candidate_id);

				if (!$deleteQuery->execute()) {
					throw new Exception("Failed to execute the query.");
				}

				if ($deleteQuery->affected_rows > 0) {
					// Delete candidate photos from directory
					if (!empty($candidatePhoto)) {
						unlink("../assets/img/profile/candidate/" . $candidatePhoto);
					}
					if (!empty($fellowCandidatePhoto)) {
						unlink("../assets/img/profile/candidate/" . $fellowCandidatePhoto);
					}

					echo json_encode(array('status' => 'success', 'message' => 'Successful Deleted'));
				} else {
					echo json_encode(array('status' => 'error', 'message' => 'Candidate not found or already deleted.'));
				}
			} catch (Exception $e) {
				echo json_encode(array('status' => 'error', 'message' => 'Failed to delete Candidate! Try again later.'));
			}
		} else {
			// Invalid or empty candidate_id
			echo json_encode(array('status' => 'error', 'message' => 'Invalid Candidate.'));
		}
	}


	function vote()
	{
		extract($_POST);
		$voter_id = $_SESSION['login_id'];

		if (!empty($selectedCandidates)) {
			try {
				// Check if User ID exists in the users table
				$check_voter_id = $this->db->prepare("SELECT voter_id FROM votes WHERE voter_id = ? AND election_id = ?");
				if (!$check_voter_id) {
					throw new Exception("Failed to prepare the query.");
				}

				$check_voter_id->bind_param("ii", $voter_id, $election_id);

				if (!$check_voter_id->execute()) {
					throw new Exception("Failed to execute the query.");
				}

				$result = $check_voter_id->get_result();

				if ($result->num_rows > 0) {
					echo json_encode(array('status' => 'error', 'message' => 'Failed, You can only vote once per election!'));
					return;
				}

				$save = $this->db->prepare("INSERT INTO votes (election_id, category_id, candidate_id, voter_id) VALUES (?, ?, ?, ?)");
				if (!$save) {
					throw new Exception("Failed to prepare the query.");
				}

				foreach ($selectedCandidates as $candidate) {
					$election_id = $candidate['election_id'];
					$category_id = $candidate['category_id'];
					$candidate_id = $candidate['candidate_id'];

					$save->bind_param("iiii", $election_id, $category_id, $candidate_id, $voter_id);

					if (!$save->execute()) {
						throw new Exception("Failed to execute the query.");
					}
				}

				echo json_encode(array('status' => 'success', 'message' => 'Your vote submitted!'));
			} catch (Exception $e) {
				echo json_encode(array('status' => 'error', 'message' => 'Failed to Vote! Try again later.'));
			}
		} else {
			// No Candidates Selected
			echo json_encode(array('status' => 'errors', 'message' => 'Failed to Vote! No Candidate Selected.'));
		}
	}

	function update_profile()
	{
		extract($_POST);
		$updated_by = $_SESSION['login_id'];

		if (empty($fullName) || empty($username)) {
			echo json_encode(array('status' => 'error', 'message' => 'Please fill in all the required fields.'));
			return;
		}

		if (!empty($user)) {
			try {
				$current_user_id = $_SESSION['login_id'];

				$check_username = $this->db->prepare("SELECT username FROM users WHERE username = ? AND id <> ?");
				if (!$check_username) {
					throw new Exception("Failed to prepare the query.");
				}

				$check_username->bind_param("si", $username, $current_user_id);

				if (!$check_username->execute()) {
					throw new Exception("Failed to execute the query.");
				}

				$result = $check_username->get_result();

				if ($result->num_rows > 0) {
					echo json_encode(array('status' => 'error', 'message' => 'Username already exists. Please use different.'));
					return;
				}

				// Check if Email exists in the users table for other users
				$check_email = $this->db->prepare("SELECT email FROM users WHERE email = ? AND id <> ?");
				if (!$check_email) {
					throw new Exception("Failed to prepare the query.");
				}

				$check_email->bind_param("si", $email, $current_user_id);

				if (!$check_email->execute()) {
					throw new Exception("Failed to execute the query.");
				}

				$result = $check_email->get_result();

				if ($result->num_rows > 0) {
					echo json_encode(array('status' => 'error', 'message' => 'Email already exists. Please use different.'));
					return;
				}

				// Fetch the user profile name from db
				$user_profile_check = $this->db->prepare("SELECT * FROM users WHERE id = ?");
				if (!$user_profile_check) {
					throw new Exception("Failed to prepare the query.");
				}
				$user_profile_check->bind_param("i", $user);

				if (!$user_profile_check->execute()) {
					throw new Exception("Failed to execute the query.");
				}
				$profile_result = $user_profile_check->get_result()->fetch_assoc();

				if (!empty($_FILES['profileImage']['name'])) {
					$profile_image = $_FILES['profileImage']['name'];
					$profile_image_tmp = $_FILES['profileImage']['tmp_name'];
					$profile_image_ext = pathinfo($profile_image, PATHINFO_EXTENSION);
					$allowed_extensions = array('jpg', 'jpeg', 'png');

					if (!in_array(strtolower($profile_image_ext), $allowed_extensions)) {
						echo json_encode(array('status' => 'error', 'message' => 'Invalid Profile Picture format. Please use JPG, JPEG, or PNG.'));
						return;
					}


					if ($profile_result['profile_picture']) {
						$picture_name = $profile_result['profile_picture'];
						unlink('../assets/img/profile/users/' . $picture_name);
					}

					$profile_image_name = $username . '-' . time() . uniqid() . '.' . $profile_image_ext;
					$profile_image_path = '../assets/img/profile/users/' . $profile_image_name;

					if (!move_uploaded_file($profile_image_tmp, $profile_image_path)) {
						echo json_encode(array('status' => 'error', 'message' => 'Failed to upload Profile Picture.'));
						return;
					}
				} else {
					$profile_image_name = $profile_result['profile_picture'];
				}

				$save_user = $this->db->prepare("UPDATE users SET name = ?, username = ?, email = ?, phone = ?, profile_picture = ? WHERE id = ?");
				if (!$save_user) {
					throw new Exception("Failed to prepare the query.");
				}

				$save_user->bind_param("sssssi", $fullName, $username, $email, $phone, $profile_image_name, $user);


				if (!$save_user->execute()) {
					throw new Exception("Failed to execute the query.");
				}

				echo json_encode(array('status' => 'success', 'message' => 'Successful Profile Updated!'));
			} catch (Exception $e) {
				echo json_encode(array('status' => 'error', 'message' => 'Failed to update profile! Try again later.'));
			}
		}
	}

	function user_type()
	{
		extract($_POST);
		$updated_by = $_SESSION['login_id'];

		if (!empty($user_id)) {
			try {
				$changeUserType = $this->db->prepare("UPDATE users SET type = ?, updated_by = ? WHERE id = ?");
				if (!$changeUserType) {
					throw new Exception("Failed to prepare the update query.");
				}

				$changeUserType->bind_param("iii", $type, $updated_by, $user_id);

				if (!$changeUserType->execute()) {
					throw new Exception("Failed to execute the update query.");
				}

				echo json_encode(array('status' => 'success', 'message' => 'User type changed!'));
			} catch (Exception $e) {
				echo json_encode(array('status' => 'error', 'message' => 'Failed to update user type! Try again later.'));
			}
		} else {
			// ID does not exist
			echo json_encode(array('status' => 'error', 'message' => 'Failed to update user type! Contact administrator for help.'));
		}
	}
}
