<?php
session_start();
if (isset($_SESSION['login_id'])) {
  if ($_SESSION['login_type'] == 0) {
    header("location:index.php?page=dashboard");
  } else {
    header("location:index.php?page=vote");
  }
  exit();
}

echo '<!DOCTYPE html>
<html lang="en">';

include 'includes/head.php'
?>

<body>

  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="index.php" class="logo d-flex align-items-center w-auto">
                  <img src="assets/img/logo.jpg" alt="">
                  <span class="d-none d-lg-block">Online Voting System</span>
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
                    <p class="text-center small">Enter your personal details to create account</p>
                  </div>

                  <form class="row g-3" novalidate id="register-form">
                    <div class="col-12">
                      <label for="name" class="form-label">Your Name</label>
                      <input type="text" name="name" class="form-control" id="name" required>
                      <div id="name-error-msg" class="invalid-feedback">Please, enter your name!</div>
                    </div>

                    <div class="col-12">
                      <label for="username" class="form-label">Username</label>
                      <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend"><i class="ri-user-3-fill"></i></span>
                        <input type="text" name="username" class="form-control" id="username" required>
                        <div id="username-error-msg" class="invalid-feedback">Please choose a username.</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="password" class="form-label">Password</label>
                      <input type="password" name="password" class="form-control" id="password" required>
                      <div id="password-error-msg" class="invalid-feedback">Please enter your password!</div>
                    </div>

                    <div class="col-12">
                      <label for="comfirmPassword" class="form-label">Repeat Password</label>
                      <input type="Password" name="confirmPassword" class="form-control" id="comfirmPassword" required>
                      <div id="comfirmPassword-error-msg" class="invalid-feedback">Please repeat your password!</div>
                    </div>

                    <div class="col-12">
                      <button class="btn btn-primary w-100">Create Account</button>
                    </div>
                    <div class="col-12">
                      <p class="small mb-0">Already have an account? <a href="login.php">Log in</a></p>
                    </div>
                  </form>

                </div>
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->

  <?php
  include 'includes/scripts.php';
  ?>

</body>
<!-- Custom register Form JS -->
<script>
  var registerForm = document.getElementById('register-form');
  var nameInput = document.getElementById('name');
  var usernameInput = document.getElementById('username');
  var passwordInput = document.getElementById('password');
  var comfirmPasswordInput = document.getElementById('comfirmPassword');
  var nameErrorMsg = document.getElementById('name-error-msg');
  var usernameErrorMsg = document.getElementById('username-error-msg');
  var passwordErrorMsg = document.getElementById('password-error-msg');
  var comfirmPasswordErrorMsg = document.getElementById('comfirmPassword-error-msg');

  registerForm.addEventListener('submit', function(event) {
    event.preventDefault();
    event.stopPropagation();

    // Reset error messages
    nameErrorMsg.textContent = '';
    usernameInput.textContent = '';
    passwordErrorMsg.textContent = '';
    comfirmPasswordErrorMsg.textContent = '';

    if (!registerForm.checkValidity()) {
      // Show custom error messages for unvalidated fields
      if (!usernameInput.checkValidity()) {
        if (usernameInput.validity.valueMissing) {
          usernameErrorMsg.textContent = 'Please enter a username.';
        }
      }

      if (!nameInput.checkValidity()) {
        if (nameInput.validity.valueMissing) {
          nameErrorMsg.textContent = 'Please enter a name.';
        }
      }

      if (!passwordInput.checkValidity()) {
        if (passwordInput.validity.valueMissing) {
          passwordErrorMsg.textContent = 'Please provide a password.';
        }
      }

      if (!comfirmPasswordInput.checkValidity()) {
        if (comfirmPasswordInput.validity.valueMissing) {
          comfirmPasswordErrorMsg.textContent = 'Please repeat your password.';
        }
      }
    } else {
      // If form is valid, proceed with AJAX call to the server
      var formData = new FormData(registerForm);
      $.ajax({
        url: 'controllers/app.php?action=register',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        error: function(err) {
          console.log(err);
        },
        success: function(resp) {
          var response = JSON.parse(resp);
          if (response.status === 'success') {
            location.href = response.redirect_url;
          } else {
            // Show the error message received from the server
            if (response.status === 'username') {
              $('#register-form').prepend('<div class="alert alert-danger">' + response.message + '</div>');
              $('#register-form button[type="button"]').removeAttr('disabled').html('register');
              usernameInput.classList.add('is-invalid');
            } else {
              $('#register-form').prepend('<div class="alert alert-danger">' + response.message + '</div>');
              $('#register-form button[type="button"]').removeAttr('disabled').html('register');
              passwordInput.classList.add('is-invalid');
            }
          }
        }
      });
    }

    registerForm.classList.add('was-validated');
  }, false);
</script>


</html>