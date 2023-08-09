  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link nav-dashboard<?php echo (isset($_GET['page']) && ($_SESSION['login_type'] == 0 ? $_GET['page'] === 'dashboard' : $_GET['page'] === 'vote')) || ($_SERVER['SCRIPT_NAME'] === 'index.php') ? '' : ' collapsed'; ?>" href="index.php<?php echo $_SESSION['login_type'] == 0 ? '?page=dashboard' : '?page=vote'; ?>">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <?php if ($_SESSION['login_type'] == 0) { ?>
        <li class="nav-item">
          <a class="nav-link<?php echo isset($_GET['page']) && ($_GET['page'] === 'election_config' || $_GET['page'] === 'candidates') ? '' : ' collapsed'; ?>" href="index.php?page=election_config">
            <i class="bi bi-gear"></i>
            <span>Configuration</span>
          </a>
        </li><!-- End Configuration Page Nav -->
        <li class="nav-item">
          <a class="nav-link<?php echo isset($_GET['page']) && $_GET['page'] === 'categories' ? '' : ' collapsed'; ?>" href="index.php?page=categories">
            <i class="bi bi-bookmark"></i>
            <span>Categories</span>
          </a>
        </li><!-- End Categories Page Nav -->
        
        <li class="nav-item">
          <a class="nav-link<?php echo isset($_GET['page']) && $_GET['page'] === 'users' ? '' : ' collapsed'; ?>" href="index.php?page=users">
            <i class="bi bi-people"></i>
            <span>Users</span>
          </a>
        </li><!-- End Users Page Nav -->

        <li class="nav-item">
          <a class="nav-link<?php echo isset($_GET['page']) && $_GET['page'] === 'vote' ? '' : ' collapsed'; ?>" href="index.php?page=vote">
            <i class="bi bi-box2"></i>
            <span>Vote</span>
          </a>
        </li><!-- End Vote Page Nav -->

        <li class="nav-item">
          <a class="nav-link<?php echo isset($_GET['page']) && $_GET['page'] === 'results' ? '' : ' collapsed'; ?>" href="index.php?page=results">
            <i class="bi bi-receipt"></i>
            <span>Results</span>
          </a>
        </li><!-- End Results Page Nav -->

        <li class="nav-heading">User Pages</li>

      <?php } ?>

      <li class="nav-item">
        <a class="nav-link<?php echo isset($_GET['page']) && $_GET['page'] === 'profile' ? '' : ' collapsed'; ?>" href="index.php?page=profile">
          <i class="bi bi-person"></i>
          <span>Profile</span>
        </a>
      </li><!-- End Profile Page Nav -->

      <li class="nav-item">
        <a class="nav-link<?php echo isset($_GET['page']) && $_GET['page'] === 'contact' ? '' : ' collapsed'; ?>" href="index.php?page=contact">
          <i class="bi bi-envelope"></i>
          <span>Contact</span>
        </a>
      </li><!-- End Contact Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="controllers/app.php?action=logout">
          <i class="bi bi-box-arrow-in-right"></i>
          <span>Logout</span>
        </a>
      </li><!-- End Logout Page Nav -->

    </ul>

  </aside><!-- End Sidebar-->