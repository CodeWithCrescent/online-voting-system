  <!-- ======= Header ======= -->
  <?php
  include 'config/dbconnection.php';
  include 'config/session.php';

  $userId = $_SESSION['login_id'];
  $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
  $stmt->bind_param('i', $userId);
  $stmt->execute();
  $row = $stmt->get_result()->fetch_assoc();
  ?>
  <header id="header hide" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center">
        <img src="assets/img/logo.jpg" class="d-md-nones" alt="">
        <span class="d-none d-lg-block ps-3">Online Voting</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <?php
            if ($row['profile_picture']) {
              $profile_picture = $row['profile_picture'];
              $profile_picture_path = 'assets/img/profile/users/' . $profile_picture;

              if (file_exists($profile_picture_path)) {
                echo '<img src="' . $profile_picture_path . '" alt="Profile Picture" class="rounded-circle">';
              }
            } else { ?>
              <img src="assets/img/user.png" alt="Profile" class="rounded-circle">
            <?php } ?>
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $row['name'] ?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo $_SESSION['login_name'] ?></h6>
              <span>Computer Eng.</span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="index.php?page=profile">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="controllers/app.php?action=logout">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->