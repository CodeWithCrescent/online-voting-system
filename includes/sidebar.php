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
          <a class="nav-link<?php echo isset($_GET['page']) && ($_GET['page'] === 'election_config' || $_GET['page'] === 'candidates' || $_GET['page'] === 'categories') ? '' : ' collapsed'; ?>" data-bs-target="#config-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-gear"></i>
            <span>Configuration</span>
            <i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="config-nav" class="nav-content collapse<?php echo isset($_GET['page']) && ($_GET['page'] === 'election_config' || $_GET['page'] === 'candidates' || $_GET['page'] === 'categories') ? ' show' : ''; ?>" data-bs-parent="#sidebar-nav">
            <li>
              <a href="index.php?page=election_config" class="<?php echo isset($_GET['page']) && ($_GET['page'] === 'election_config' || $_GET['page'] === 'candidates') ? ' active' : ''; ?>">
                <i class="bi bi-circle"></i><span>Elections</span>
              </a>
            </li>
            <li>
              <a href="index.php?page=categories" class="<?php echo isset($_GET['page']) && $_GET['page'] === 'categories' ? ' active' : ''; ?>">
                <i class="bi bi-circle"></i><span>Categories</span>
              </a>
            </li>
          </ul>
        </li><!-- End Configuration Page Nav -->
      <?php } ?>
      <li class="nav-item">
        <a class="nav-link<?php echo isset($_GET['page']) && $_GET['page'] === 'results' ? '' : ' collapsed'; ?>" href="index.php?page=results">
          <i class="bi bi-receipt"></i>
          <span>Results</span>
        </a>
      </li><!-- End Results Page Nav -->

      <?php if ($_SESSION['login_type'] == 0) { ?>
        <li class="nav-item">
          <!-- <a class="nav-link<?php //echo isset($_GET['page']) && $_GET['page'] === 'reports' ? '' : ' collapsed'; ?>" href="index.php?page=reports">
            <i class="bi bi-bookmark"></i>
            <span>Reports</span>
          </a> -->
        </li><!-- End Reports Page Nav -->
      <?php } ?>

      <li class="nav-item">
        <a class="nav-link<?php echo isset($_GET['page']) && $_GET['page'] === 'profile' ? '' : ' collapsed'; ?>" href="index.php?page=profile">
          <i class="bi bi-person"></i>
          <span>Profile</span>
        </a>
      </li><!-- End Profile Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="controllers/app.php?action=logout">
          <i class="bi bi-box-arrow-in-right"></i>
          <span>Logout</span>
        </a>
      </li><!-- End Logout Page Nav -->

    </ul>

  </aside><!-- End Sidebar-->