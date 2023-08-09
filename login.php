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

include 'includes/head.php';
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
                    <h5 class="card-title text-center pb-0 fs-4">Login</h5>
                    <p class="text-center small">Enter your username & password to login</p>
                  </div>

                  <form class="row g-3" novalidate id="login-form">

                    <div class="col-12">
                      <label for="username" class="form-label">Username</label>
                      <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend"><i class="ri-user-3-fill"></i></span>
                        <input type="text" name="username" class="form-control" id="username" required>
                        <div id="username-error-msg" class="invalid-feedback"></div>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="password" class="form-label">Password</label>
                      <div class="input-group">
                        <span class="input-group-text" id="inputGroupPrepend"><i class="ri-lock-2-fill"></i></span>
                        <input type="password" name="password" class="form-control" id="password" required>
                        <div id="password-error-msg" class="invalid-feedback"></div>
                      </div>
                    </div>

                    <div class="col-12">
                      <button class="btn btn-primary w-100">Login</button>
                    </div>
                    <div class="col-12">
                      <p class="small mb-0">Don't have account? <a href="register.php">Create an account</a></p>
                    </div>
                  </form>

                </div>
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

</html>